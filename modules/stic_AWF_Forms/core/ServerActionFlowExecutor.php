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
                    $reqResult = $this->context->getActionResultById($reqActionId);
                    if ($reqResult === null) {
                        $GLOBALS['log']->warn('Line '.__LINE__.': '.__METHOD__.': '."Advanced Web Forms: Action '{$actionConfig->name}' (id: {$actionConfig->id}) requires action with id '{$reqActionId}' but it was not executed. Continuing anyway for backward compatibility.");
                        continue;
                    }
                    if ($reqResult->isError()) {
                        $GLOBALS['log']->warning('Line '.__LINE__.': '.__METHOD__.': '."Advanced Web Forms: Action '{$actionConfig->name}' skipped because requisite action '{$reqActionId}' failed.");
                        $skippedResult = new ActionResult(ResultStatus::SKIPPED, $actionConfig, "Requisite action failed.");
                        $this->context->addActionResult($skippedResult);
                        continue 2;
                    }
                }

                // Check the Conditions (if any)
                if(!stic_AWFUtils::evaluateConditions($actionConfig->conditions, $this->context->formData)) {
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

                // STIC-Custom OC - 20250803 - Repeatable data blocks support
                $paramDefinitions  = $actionExecutor->getParameters();
                $paramConfigurations = $actionConfig->parameters;

                // Detect whether the action operates on a block that belongs to a repeatable group
                // (either the repeatable root itself or a child of it).
                $targetBlockId = null;
                if (!empty($paramDefinitions)) {
                    $paramConfigMap = [];
                    foreach ($paramConfigurations as $paramConfig) {
                        $paramConfigMap[$paramConfig->name] = $paramConfig;
                    }
                    foreach ($paramDefinitions as $paramDef) {
                        if ($paramDef->type !== ActionParameterType::DATA_BLOCK) continue;
                        $paramConfig = $paramConfigMap[$paramDef->name] ?? null;
                        $targetBlockId = $paramConfig->value ?? $paramDef->defaultValue;
                        break;
                    }
                }

                $repeatRoot = null;
                if ($targetBlockId !== null) {
                    $targetBlock = $this->context->formConfig->data_blocks[$targetBlockId] ?? null;
                    if ($targetBlock !== null) {
                        if ($targetBlock->is_repeatable) {
                            $repeatRoot = $targetBlock;
                        } elseif (!empty($targetBlock->group_root)) {
                            // STIC-Custom OC - 20260807 - Resolve the TOP-LEVEL branch root, not just the
                            // immediate parent: with multi-level adoption a level-2+ child's group_root
                            // points to its (non-repeatable) immediate parent.
                            $repeatRoot = $this->context->formConfig->getGroupRootBlock($targetBlock);
                            // END STIC-Custom OC
                        }
                    }
                }

                // Only record-saving and relationship-creation actions are expanded once per instance.
                // Other actions keep the legacy behavior: executed exactly once without an instance index.
                $actionClassName = get_class($actionExecutor);
                $isExpandableAction = ($actionClassName === 'SaveRecordAction') || ($actionClassName === 'RelateRecordsAction');

                $instanceIndexes = [null];
                if ($repeatRoot !== null && $isExpandableAction) {
                    $instances = DataBlockResolved::resolveInstances($repeatRoot, $this->context->formData, $this->context);
                    if (empty($instances)) {
                        // Optional repeatable group (min_instances = 0) with zero instances: skip the action.
                        $skippedResult = new ActionResult(ResultStatus::SKIPPED, $actionConfig, "Repeatable group '{$repeatRoot->name}' has no instances.");
                        $this->context->addActionResult($skippedResult);
                        $lastResult = $skippedResult;
                        continue;
                    }
                    $instanceIndexes = array_map(fn($instance) => $instance->instanceIndex, $instances);
                }
                // END STIC-Custom OC

                foreach ($instanceIndexes as $instanceIndex) {
                    // STIC-Custom OC - 20250803 - The context instance index MUST be set before any
                    // parameter resolution so that per-instance form fields and bean references are read.
                    $this->context->setCurrentInstanceIndex($instanceIndex);
                    // END STIC-Custom OC

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

                // STIC-Custom OC - 20250803 - Reset the instance index after the action execution
                $this->context->setCurrentInstanceIndex(null);
                // END STIC-Custom OC
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
                if (!stic_AWFUtils::evaluateConditions($actionConfig->conditions, $this->context->formData)) {
                    $GLOBALS['log']->info('Line '.__LINE__.': '.__METHOD__.': '. "Advanced Web Forms: Skipping terminal action '{$actionConfig->text}' because conditions failed.");
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
}