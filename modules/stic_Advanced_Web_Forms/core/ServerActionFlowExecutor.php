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
     * @return ?FormAction The Terminal action configuration pending execution, or null if finished.
     */
    public function executeFlow(FormFlow $flowConfig, ?FormFlow $errorFlowConfig = null): ?FormAction {
        $lastActionConfig = null;
        try {
            $actions = $flowConfig->actions ?? [];
            foreach ($actions as $actionConfig) {
                $lastActionConfig = $actionConfig;

                // Check the Condition (if any)
                if (!empty($actionConfig->condition_field)) {
                    if (!$this->checkCondition($actionConfig, $this->context)) {
                        $GLOBALS['log']->info('Line '.__LINE__.': '.__METHOD__.': '. "Skipping action '{$actionConfig->text}' because condition failed.");
                        
                        // Record the action as skipped
                        $skippedResult = new ActionResult(ResultStatus::SKIPPED, $actionConfig, "Condition not met.");
                        $this->context->addActionResult($skippedResult);
                        continue; 
                    }
                }

                // Find the action executor (throws if not found)
                $actionExecutor = $this->factory->createAction($actionConfig);
                
                // If it's Terminal we don't execute it: stop loop and return it to be executed later
                if ($actionExecutor instanceof ITerminalAction) {
                    return $actionConfig;
                }

                // Parameter resolution
                $paramDefinitions  = $actionExecutor->getParameters();
                $paramConfigurations = $actionConfig->parameters;
                $resolvedParameters = $this->resolver->resolveAll($actionConfig, $paramDefinitions, $paramConfigurations, $this->context);
                $actionConfig->setResolvedParameters($resolvedParameters);

                // Execute the action
                $actionResult = $actionExecutor->execute($this->context, $actionConfig); 

                // Context update
                $this->context->addActionResult($actionResult);


                if ($actionResult->isWait()) {
                    // Mark the response as waiting
                    if ($this->context->responseBean) {
                        $this->context->responseBean->status = 'awaiting_action';
                        $this->context->responseBean->save();
                    }
                    
                    $GLOBALS['log']->info("Advanced Web Forms: Flow paused by action '{$actionConfig->name}'. Reason: " . $actionResult->message);

                    // Return null to finish: the engine will be put on hold
                    return null; 
                }

                // Error detection
                if ($actionResult->isError()) {
                    // If there's an error flow: immediately switch to the error flow
                    if ($errorFlowConfig !== null) {
                        return $this->executeFlow($errorFlowConfig);
                    }
                    // If there is no error flow, finish
                    return null; 
                }
            }
        } catch (\Throwable $t) {
            // Catch any Exception or PHP Fatal Error and convert it into a context error
            $GLOBALS['log']->fatal('Line '.__LINE__.': '.__METHOD__.': '."CRITICAL ERROR in ServerActionFlowExecutor: " . $t->getMessage());
            $this->context->addError($t, $lastActionConfig);
            
            // If there's an error flow: immediately switch to it
            if ($errorFlowConfig !== null) {
                try {
                    return $this->executeFlow($errorFlowConfig);
                } catch (\Throwable $t2) {
                    $GLOBALS['log']->fatal('Line '.__LINE__.': '.__METHOD__.': '."Double Fault: Error flow failed too: " . $t2->getMessage());
                }
            }
            // If there is no error flow, we finish
            return null; 
        }
        
        return null;
    }

    /**
     * Checks if the condition is met to execute an action.
     * @param FormAction $action The action to check
     * @param ExecutionContext $context The execution context
     * @return bool True if the condition is met or there is no condition, false otherwise
     */
    private function checkCondition(FormAction $action, ExecutionContext $context): bool {
        $fieldKey = $action->condition_field; // Ex: "Contact0.newsletter"
        $expectedValue = $action->condition_value;

        // Convert the logical key to PHP key (replacing '.' with '_')
        $phpKey = str_replace('.', '_', $fieldKey);

        // Look for the submitted value in the form
        if (!isset($context->formData[$phpKey])) {
            $submittedValue = $context->formData[$phpKey];
        } else {
            $submittedValue = '0'; // We assume false if it has not arrived from the form
        }

        // Check if the submitted value matches the expected one
        if (is_array($submittedValue)) {
            return in_array($expectedValue, $submittedValue);
        }
        return $submittedValue == $expectedValue;
    }
}