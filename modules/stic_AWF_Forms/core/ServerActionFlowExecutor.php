<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */
// Prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class ServerActionFlowExecutor {
    private ExecutionContext $context; 
    private ServerActionFactory $factory;
    private ParameterResolverService $resolver;

    public function __construct(ExecutionContext $context) {
        $this->context = $context;
        $this->factory = new ServerActionFactory();
        $this->resolver = new ParameterResolverService();
    }

    /**
     * Executes the main flow and manages errors by switching to the error flow if needed.
     * @param FormFlow $flowConfig The flow definition to execute.
     * @param ?FormFlow $errorFlowConfig The error flow definition (null if none).
     * @return ActionResult Returns last ActionResult
     */
    public function executeFlow(FormFlow $flowConfig, ?FormFlow $errorFlowConfig = null): ActionResult {
        $lastResult = new ActionResult(ResultStatus::OK, null);
        $lastActionConfig = null;
        try {
            $actions = $flowConfig->actions ?? [];

            // Preprocess formData to fill in missing boolean/checkbox fields
            // (browsers don't send unchecked checkboxes, so without this the condition would
            // compare null vs '0' and fail)
            stic_AWFUtils::fillMissingBooleanFields($this->context->formConfig, $this->context->formData);

            foreach ($actions as $actionConfig) {
                $lastActionConfig = $actionConfig;

                // Check that all requisite_actions have been executed successfully
                // Backward compatibility: if a requisite action hasn't been executed (null),
                // log a warning but let the action proceed. This preserves the pre-update
                // behavior where no topological sort was performed server-side.
                foreach ($actionConfig->requisite_actions as $reqActionId) {
                    // Pre-pass: only report requisites that never ran. The
                    // authoritative gate is per instance (see
                    // requisiteAllowsInstance() inside the unroll loop): gating
                    // the whole action here would let instance 0's result decide
                    // the fate of every other instance.
                    if ($this->context->getResultForAction($reqActionId) === null) {
                        $GLOBALS['log']->warn('Line '.__LINE__.': '.__METHOD__.': '."Advanced Web Forms: Action '{$actionConfig->name}' (id: {$actionConfig->id}) requires action with id '{$reqActionId}' but it was not executed. Continuing anyway for backward compatibility.");
                    }
                }

                // Check the Conditions (if any)
                // Only SCALAR-field conditions may gate the whole action here:
                // instance-bound conditions (a field inside a group) are posted
                // as Block[i][field] and must be resolved against the instance
                // matrix, otherwise the flat-key lookup yields null and the
                // action is silently skipped BEFORE the per-instance unroll.
                if(!stic_AWFUtils::evaluateScalarConditions($actionConfig->conditions, $this->context->formConfig, $this->context->formData)) {
                    $GLOBALS['log']->info('Line '.__LINE__.': '.__METHOD__.': '. "Advanced Web Forms: Skipping action '{$actionConfig->text}' because condition failed.");
                    
                    // Record the action as skipped
                    $skippedResult = new ActionResult(ResultStatus::SKIPPED, $actionConfig, "Condition not met.");
                    $this->context->addActionResult($skippedResult);
                    continue; 
                }

                // Find the action executor (throws if not found)
                $actionExecutor = $this->factory->createAction($actionConfig);

                // Check form type compatibility
                if (!empty($this->context->formType) && !empty($actionExecutor->supportedFormTypes)) {
                    if (!in_array($this->context->formType, $actionExecutor->supportedFormTypes)) {
                        $GLOBALS['log']->info('Line '.__LINE__.': '.__METHOD__.': '. "Advanced Web Forms: Skipping action '{$actionConfig->text}' because it does not support form type '{$this->context->formType}'.");
                        $skippedResult = new ActionResult(ResultStatus::SKIPPED, $actionConfig, "Form type '{$this->context->formType}' not supported.");
                        $this->context->addActionResult($skippedResult);
                        continue;
                    }
                }

                $paramDefinitions  = $actionExecutor->getParameters();
                $paramConfigurations = $actionConfig->parameters;

                // B-4 (generic unrolling): detect the MOTOR block among the action's
                // DATA_BLOCK parameters — the deepest block (highest loop depth) that
                // belongs to a repeatable/optional group. Its instance matrix drives
                // the per-instance unrolling of ANY action (not only Save/Relate).
                $motorBlock = null;
                if (!empty($paramDefinitions)) {
                    $paramConfigMap = [];
                    foreach ($paramConfigurations as $paramConfig) {
                        $paramConfigMap[$paramConfig->name] = $paramConfig;
                    }
                    foreach ($paramDefinitions as $paramDef) {
                        if ($paramDef->type !== ActionParameterType::DATA_BLOCK) continue;
                        $paramConfig = $paramConfigMap[$paramDef->name] ?? null;
                        $targetBlockId = $paramConfig->value ?? $paramDef->defaultValue;
                        if ($targetBlockId === null || $targetBlockId === '') continue;
                        $targetBlock = $this->context->formConfig->data_blocks[$targetBlockId] ?? null;
                        if ($targetBlock === null) continue;
                        if ($targetBlock->getLoopDepth() === 0) continue;
                        if ($motorBlock === null || $targetBlock->getLoopDepth() > $motorBlock->getLoopDepth()) {
                            $motorBlock = $targetBlock;
                        }
                    }
                }

                // Terminal actions are never unrolled: they act on the whole
                // submission (one HTTP redirect per submission). TERMINAL
                // deferred actions (e.g. the payment gateway redirect) are
                // always global too: exactly one ticket and one redirect per
                // submission, so they must be configured at the parent level or
                // in the main flow (the wizard prevents binding them to a
                // repeatable loop). NON-TERMINAL deferred actions (async:
                // confirmation emails, ticket generation) DO unroll per
                // instance: N independent tickets, one per created record.
                $isTerminal = $actionExecutor instanceof ITerminalAction;

                // Instance descriptors to execute: null = no motor block (single
                // scalar execution, legacy behavior); empty = repeatable/optional
                // group with zero instances (skip the action once). Each
                // descriptor is the FULL loop-index vector of one instance.
                $instanceDescriptors = null;
                if ($motorBlock !== null && !$isTerminal) {
                    $resolvedInstances = DataBlockResolved::resolveInstances($motorBlock, $this->context->formData, $this->context);
                    if (empty($resolvedInstances)) {
                        // Repeatable group with zero instances: skip the action.
                        $skippedResult = new ActionResult(ResultStatus::SKIPPED, $actionConfig, "Repeatable group '{$motorBlock->name}' has no instances.");
                        $this->context->addActionResult($skippedResult);
                        $lastResult = $skippedResult;
                        continue;
                    }
                    // Convert the resolved instances to their loop-index vectors
                    // (one per instance: [i1], [i1, i2], ...) — the vectors drive
                    // the per-instance unrolling below
                    $instanceDescriptors = array_map(fn ($instance) => $instance->loopIndexes, $resolvedInstances);
                }

                // B-5: conditions are split by scope. Scalar-field conditions gate
                // the whole action before unrolling; instance-bound conditions are
                // evaluated per instance inside the loop (SKIPPED per instance).
                if ($instanceDescriptors !== null) {
                    if (!stic_AWFUtils::evaluateScalarConditions($actionConfig->conditions, $this->context->formConfig, $this->context->formData)) {
                        $GLOBALS['log']->info('Line '.__LINE__.': '.__METHOD__.': '. "Advanced Web Forms: Skipping action '{$actionConfig->text}' because condition failed.");
                        $skippedResult = new ActionResult(ResultStatus::SKIPPED, $actionConfig, "Condition not met.");
                        $this->context->addActionResult($skippedResult);
                        continue;
                    }
                } elseif (!stic_AWFUtils::evaluateConditionsForInstance($actionConfig->conditions, $this->context->formConfig, $this->context->formData, [])) {
                    $GLOBALS['log']->info('Line '.__LINE__.': '.__METHOD__.': '. "Advanced Web Forms: Skipping action '{$actionConfig->text}' because condition failed.");
                    // Record the action as skipped
                    $skippedResult = new ActionResult(ResultStatus::SKIPPED, $actionConfig, "Condition not met.");
                    $this->context->addActionResult($skippedResult);
                    continue;
                }

                if ($instanceDescriptors === null) {
                    $instanceDescriptors = [[]];
                }

                foreach ($instanceDescriptors as $descriptor) {
                    // The context instance indexes MUST be set before any
                    // parameter resolution so that per-instance form fields and
                    // bean references are read. $descriptor is the FULL
                    // loop-index vector of this instance ([i1, i2, ...]).
                    $this->context->setInstanceIndexes($descriptor);

                    // Authoritative per-instance requisite gate. The result of an
                    // unrolled requisite is stored per instance ({id}_{i} /
                    // {id}_{i}_{j}); looking up the plain id would always return
                    // instance 0's outcome and mask a failure on this instance.
                    if (!$this->requisiteAllowsInstance($actionConfig, $descriptor)) {
                        $skippedResult = new ActionResult(ResultStatus::SKIPPED, $actionConfig, "Requisite action failed.");
                        $this->context->addActionResult($skippedResult);
                        $lastResult = $skippedResult;
                        continue;
                    }

                    // B-5: per-instance condition evaluation (conditions referencing
                    // group fields are resolved against the instance matrix)
                    if (!stic_AWFUtils::evaluateConditionsForInstance($actionConfig->conditions, $this->context->formConfig, $this->context->formData, $this->context->getInstanceIndexes())) {
                        $GLOBALS['log']->info('Line '.__LINE__.': '.__METHOD__.': '. "Advanced Web Forms: Skipping instance of action '{$actionConfig->text}' (instance " . implode(':', $descriptor) . ") because condition failed.");
                        $skippedResult = new ActionResult(ResultStatus::SKIPPED, $actionConfig, "Condition not met for instance " . implode(':', $descriptor) . ".");
                        $this->context->addActionResult($skippedResult);
                        $lastResult = $skippedResult;
                        continue;
                    }

                    // Parameter resolution
                    $resolvedParameters = $this->resolver->resolveAll($actionConfig, $paramDefinitions, $paramConfigurations, $this->context);
                    $actionConfig->setResolvedParameters($resolvedParameters);

                    // Execute the action
                    $lastResult = $actionExecutor->execute($this->context, $actionConfig);
                    $lastResult->setAction($actionExecutor);

                    // Context update
                    $this->context->addActionResult($lastResult);

                    if ($lastResult->isWait()) {
                        // Mark the response as waiting
                        if ($this->context->responseBean) {
                            $this->context->responseBean->status = 'awaiting_action';
                            $this->context->responseBean->save();
                        }

                        $GLOBALS['log']->info('Line '.__LINE__.': '.__METHOD__.': '. "Advanced Web Forms: Flow paused by action '{$actionConfig->name}'. Reason: " . $lastResult->message);

                        // Return $lastResult to finish: the engine will be put on hold
                        return $lastResult;
                    }

                    // Error detection
                    if ($lastResult->isError()) {
                        // If the action is marked to continue on error, we log the error but we continue with the next actions of the flow.
                        if ($actionConfig->continue_on_error) {
                            $lastResult->status = ResultStatus::SKIPPED;
                            $lastResult->message = "Ignored Error: " . $lastResult->message;
                            $GLOBALS['log']->warn('Line '.__LINE__.': '.__METHOD__.': '. "Advanced Web Forms: Action '{$actionConfig->name}' failed but is marked to continue. Error: " . $lastResult->message);
                            continue 2;
                        }

                        // If there's an error flow: immediately switch to the error flow
                        if ($errorFlowConfig !== null) {
                            return $this->executeFlow($errorFlowConfig);
                        }
                        // If there is no error flow, finish
                        return $lastResult;
                    }
                }

                // Reset the instance indexes after the action execution
                $this->context->setInstanceIndexes([]);
            }
        } catch (\Throwable $t) {
            // Catch any Exception or PHP Fatal Error and convert it into a context error
            $GLOBALS['log']->fatal('Line '.__LINE__.': '.__METHOD__.': '."CRITICAL ERROR in ServerActionFlowExecutor: " . $t->getMessage());
            $lastResult = $this->context->addError($t, $lastActionConfig);
            
            // If there's an error flow: immediately switch to it
            if ($errorFlowConfig !== null) {
                try {
                    return $this->executeFlow($errorFlowConfig);
                } catch (\Throwable $t2) {
                    $lastResult = $this->context->addError($t2, $lastActionConfig);
                    $GLOBALS['log']->fatal('Line '.__LINE__.': '.__METHOD__.': '."Double Fault: Error flow failed too: " . $t2->getMessage());
                }
            }

            // If there is no error flow, we finish
            return $lastResult; 
        }
        
        return $lastResult;
    }

    /**
     * Iterates through all actions in a flow, skips non-terminal ones,
     * and executes the first terminal action that satisfies its execution conditions.
     *
     * @param FormFlow $flowConfig The flow definition to evaluate.
     */
    public function executeTerminalActionOnly(FormFlow $flowConfig): void {
        if (empty($flowConfig->actions)) {
            return;
        }

        foreach ($flowConfig->actions as $actionConfig) {
            try {
                // Instantiate the action executor to check its type and interface
                $actionExecutor = $this->factory->createAction($actionConfig);

                // Skip non-terminal actions
                if (!($actionExecutor instanceof ITerminalAction)) {
                    continue;
                }

                // Check the Conditions (if any)
                if (!stic_AWFUtils::evaluateConditionsForInstance($actionConfig->conditions, $this->context->formConfig, $this->context->formData, $this->context->getInstanceIndexes())) {
                    $GLOBALS['log']->info('Line '.__LINE__.': '.__METHOD__.': '."Advanced Web Forms: Skipping terminal action '{$actionConfig->text}' because conditions failed.");
                    continue;
                }

                $GLOBALS['log']->info('Line '.__LINE__.': '.__METHOD__.': '. "Advanced Web Forms: Executing terminal action '{$actionConfig->name}'.");
                
                // Parameter resolution
                $paramDefinitions  = $actionExecutor->getParameters();
                $paramConfigurations = $actionConfig->parameters;
                $resolvedParameters = $this->resolver->resolveAll($actionConfig, $paramDefinitions, $paramConfigurations, $this->context);
                $actionConfig->setResolvedParameters($resolvedParameters);

                // Execute the action
                $executionResult = $actionExecutor->execute($this->context, $actionConfig);
                $actionExecutor->performTerminal($this->context, $executionResult);
                break;

            } catch (\Throwable $t) {
                $GLOBALS['log']->error('Line '.__LINE__.': '.__METHOD__.': '. "Advanced Web Forms: Failed to evaluate or execute terminal action '{$actionConfig->name}': " . $t->getMessage());
            }
        }
    }

    /**
     * Per-instance requisite gate. The result of an unrolled action is stored
     * under a per-instance key ({id}_{i} / {id}_{i}_{j}), so the lookup must
     * use the current descriptor; the plain-id lookup (kept as a fallback for
     * non-unrolled requisites) always returns instance 0's outcome.
     */
    private function requisiteAllowsInstance($actionConfig, array $descriptor): bool {
        $instanceKey = $this->context->getInstanceIndexKey();
        foreach ($actionConfig->requisite_actions as $reqActionId) {
            $reqResult = $this->context->getResultForAction($reqActionId, $instanceKey);
            if ($reqResult === null) {
                // Not executed at all: keep the legacy backward-compatible behaviour.
                $reqResult = $this->context->getResultForAction($reqActionId);
            }
            if ($reqResult !== null && $reqResult->isError()) {
                $GLOBALS['log']->warning('Line '.__LINE__.': '.__METHOD__.': '."Advanced Web Forms: Action '{$actionConfig->name}' skipped for instance " . (empty($descriptor) ? 'global' : implode(':', $descriptor)) . " because requisite action '{$reqActionId}' failed.");
                return false;
            }
        }
        return true;
    }
}