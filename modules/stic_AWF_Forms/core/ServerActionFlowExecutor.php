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

            // Preprocess formData to fill in missing boolean/checkbox fields (browsers don't send unchecked checkboxes)
            stic_AWFUtils::fillMissingBooleanFields($this->context->formConfig, $this->context->formData);

            foreach ($actions as $actionConfig) {
                $lastActionConfig = $actionConfig;

                // Requisite actions verification
                foreach ($actionConfig->requisite_actions as $reqActionId) {
                    if ($this->context->getResultForAction($reqActionId) === null) {
                        $GLOBALS['log']->warn('Line '.__LINE__.': '.__METHOD__.': '."Advanced Web Forms: Action '{$actionConfig->name}' (id: {$actionConfig->id}) requires action with id '{$reqActionId}' but it was not executed. Continuing anyway for backward compatibility.");
                    }
                }

                // Action executor creation
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

                $paramDefinitions = $actionExecutor->getParameters();
                $paramConfigurations = $actionConfig->parameters;

                // Detect motor block for repeatable/optional groups
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
                        if ($targetBlock === null || $targetBlock->getLoopDepth() === 0) continue;

                        if ($motorBlock === null || $targetBlock->getLoopDepth() > $motorBlock->getLoopDepth()) {
                            $motorBlock = $targetBlock;
                        }
                    }
                }

                $isTerminal = $actionExecutor instanceof ITerminalAction;
                $conditionLogic = $actionConfig->condition_logic ?? 'AND';

                // Build instance descriptors
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
                    // Convert the resolved instances to their loop-index vectors (one per instance: [i1], [i1, i2], ...)
                    $instanceDescriptors = array_map(fn($instance) => $instance->loopIndexes, $resolvedInstances);
                }
                
                // Condition gating by scope (Scalar vs Instance)
                if ($instanceDescriptors !== null) {
                    // Repeatable action: scalar conditions gate the whole action before entering the loop
                    if (!stic_AWFUtils::evaluateScalarConditions($actionConfig->conditions, $this->context->formConfig, $this->context->formData, $conditionLogic)) {
                        $GLOBALS['log']->info('Line '.__LINE__.': '.__METHOD__.': '."Advanced Web Forms: Skipping repeatable action '{$actionConfig->text}' because scalar condition failed.");
                        $skippedResult = new ActionResult(ResultStatus::SKIPPED, $actionConfig, "Condition not met.");
                        $this->context->addActionResult($skippedResult);
                        continue;
                    }
                } else {
                    // Scalar action: evaluate all conditions at global scope once
                    if (!stic_AWFUtils::evaluateConditionsForInstance($actionConfig->conditions, $this->context->formConfig, $this->context->formData, [], $conditionLogic)) {
                        $GLOBALS['log']->info('Line '.__LINE__.': '.__METHOD__.': '."Advanced Web Forms: Skipping scalar action '{$actionConfig->text}' because condition failed.");
                        $skippedResult = new ActionResult(ResultStatus::SKIPPED, $actionConfig, "Condition not met.");
                        $this->context->addActionResult($skippedResult);
                        continue;
                    }
                    $instanceDescriptors = [[]];
                }

                
                // 7. Execution loop
                foreach ($instanceDescriptors as $descriptor) {
                    $this->context->setInstanceIndexes($descriptor);

                    // Requisite check per instance
                    if (!$this->requisiteAllowsInstance($actionConfig, $descriptor)) {
                        $skippedResult = new ActionResult(ResultStatus::SKIPPED, $actionConfig, "Requisite action failed.");
                        $this->context->addActionResult($skippedResult);
                        $lastResult = $skippedResult;
                        continue;
                    }

                    // Per-instance conditions evaluation (inside loop)
                    if ($motorBlock !== null && !stic_AWFUtils::evaluateConditionsForInstance($actionConfig->conditions, $this->context->formConfig, $this->context->formData, $this->context->getInstanceIndexes(), $conditionLogic)) {
                        $GLOBALS['log']->info('Line '.__LINE__.': '.__METHOD__.': '."Advanced Web Forms: Skipping instance of action '{$actionConfig->text}' (" . implode(':', $descriptor) . ") because condition failed.");
                        $skippedResult = new ActionResult(ResultStatus::SKIPPED, $actionConfig, "Condition not met for instance " . implode(':', $descriptor) . ".");
                        $this->context->addActionResult($skippedResult);
                        $lastResult = $skippedResult;
                        continue;
                    }

                    // Parameter resolution
                    try {
                        $resolvedParameters = $this->resolver->resolveAll($actionConfig, $paramDefinitions, $paramConfigurations, $this->context);
                        $actionConfig->setResolvedParameters($resolvedParameters);
                    } catch (UnactivatedOptionalBlockException $e) {
                        // Skip the action if a required parameter references an unactivated optional DataBlock
                        $GLOBALS['log']->info("Line " . __LINE__ . ": " . __METHOD__ . ": Action '{$actionConfig->name}' skipped: " . $e->getMessage());
                        $skippedResult = new ActionResult(ResultStatus::SKIPPED, $actionConfig, "Action skipped because required parameter '{$e->parameterName}' references unactivated optional DataBlock '{$e->blockName}'.");
                        $this->context->addActionResult($skippedResult);
                        continue;
                    }

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

                try {
                    $resolvedParameters = $this->resolver->resolveAll($actionConfig, $paramDefinitions, $paramConfigurations, $this->context);
                    $actionConfig->setResolvedParameters($resolvedParameters);
                } catch (UnactivatedOptionalBlockException $e) {
                    // Skip the action if a required parameter references an unactivated optional DataBlock
                    $GLOBALS['log']->info("Line " . __LINE__ . ": " . __METHOD__ . ": Action '{$actionConfig->name}' skipped: " . $e->getMessage());
                    $skippedResult = new ActionResult(ResultStatus::SKIPPED, $actionConfig, "Action skipped because required parameter '{$e->parameterName}' references unactivated optional DataBlock '{$e->blockName}'.");
                    $this->context->addActionResult($skippedResult);
                    continue;
                }

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