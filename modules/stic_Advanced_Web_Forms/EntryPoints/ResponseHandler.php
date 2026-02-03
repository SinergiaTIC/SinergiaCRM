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
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once "modules/stic_Advanced_Web_Forms/core/includes.php";

/**
 * EntryPoint: ResponseHandler
 * It is responsible for receiving, validating, persisting and processing form responses.
 */
class ResponseHandler
{
    public function run(): void {
        global $current_user;

        // Real user (before changing to admin)
        $realUserId = null;
        if (!empty($current_user) && !empty($current_user->id)) {
            $realUserId = $current_user->id;
        }

        // Admin user
        $current_user = BeanFactory::newBean('Users');
        $current_user->getSystemUser();

        // Data retrieval
        $formId = $_REQUEST['id'] ?? null;
        $rawPostData = $_POST;
        $cleanData = $this->sanitizeInput($rawPostData);

        // Anti-Spam Detection (Honeypot): Hidden field that bots usually fill in
        $isSpam = !empty($cleanData['awf_honey_pot']);

        // Anti-Spam Detection (TimeTrap): Normally bots submit the form immediately and/or without executing JS
        $submissionTs = (int)($_POST['awf_submission_ts'] ?? 0);
        $currentTs = time();
        $duration = $currentTs - $submissionTs;
        if ($submissionTs === 0 || $duration < 3) {
            $isSpam = true;
        }

        // Form URL
        $formUrl = $_POST['awf_form_url'] ?? $_SERVER['HTTP_REFERER'] ?? '';
        $formUrl = substr(strip_tags($formUrl), 0, 255);

        // Data sanitization
        unset($cleanData['module']);
        unset($cleanData['action']);
        unset($cleanData['entryPoint']);
        unset($cleanData['id']);
        unset($cleanData['awf_honey_pot']);
        unset($cleanData['awf_submission_ts']);
        unset($cleanData['awf_form_url']);


        // Initial validations
        if (empty($formId)) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": ResponseHandler. EntryPoint called without ID");
            $this->terminateRawError("No Form ID provided.");
        }

        /** @var stic_Advanced_Web_Forms $formBean */
        $formBean = BeanFactory::getBean('stic_Advanced_Web_Forms', $formId);
        if (!$formBean || empty($formBean->id)) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": ResponseHandler: Form not found. ID: $formId");
            $this->terminateRawError("Form not found.");
        }

        // Duplicate detection: Fingerprint
        $timeWindow = 300; // 300s = 5 min
        $timeSlot = floor(time() / $timeWindow); // $timeSlot will change every 5 minutes: we avoid accidental F5

        $remoteIp = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $payloadJson = json_encode($cleanData);

        $fingerprintString = $payloadJson . $formId . $remoteIp . $userAgent . $formUrl . $timeSlot;
        $responseHash = md5($fingerprintString);

        if ($this->checkDuplicateSubmission($formId, $responseHash)) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": ResponseHandler: Duplicated response detected");
            $this->handleDuplicateError($formBean);
            return;
        }

        // We look for the response status
        $isPublic = ($formBean->status === 'public');
        if ($isSpam) {
            $responseStatus = 'spam';
            $responseDescription = translate('LBL_RESPONSE_HONEYPOT_SPAM', 'stic_Advanced_Web_Forms_Responses');
        } elseif ($isPublic) {
            $responseStatus = 'pending';
            $responseDescription = '';
        } else {
            global $app_list_strings;

            $responseStatus = 'rejected';
            $responseDescription = translate('LBL_RESPONSE_NO_PUBLIC_STATUS', 'stic_Advanced_Web_Forms_Responses')." ".
                                   translate('LBL_STATUS', 'stic_Advanced_Web_Forms_Responses').": ". 
                                   "'{$app_list_strings['stic_advanced_web_forms_response_status_list'][$formBean->status]}'";
        }

        // We save the response
        $responseBean = BeanFactory::newBean('stic_Advanced_Web_Forms_Responses');
        $responseBean->is_automated_save = true;
        $responseBean->name = $formBean->name ." - ". date('Y-m-d H:i:s');
        $responseBean->status = $responseStatus;
        $responseBean->raw_payload = $payloadJson;
        $responseBean->response_hash = $responseHash;
        $responseBean->remote_ip = $remoteIp;
        $responseBean->form_url = $formUrl;
        $responseBean->user_agent = $userAgent;
        $responseBean->description = $responseDescription;
        $responseBean->assigned_user_id = $formBean->assigned_user_id;
        $responseBean->save();

        // Link the response with the form
        if ($formBean->load_relationship('stic_69c1s_responses')) {
            $formBean->stic_69c1s_responses->add($responseBean->id);
        } else {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": ResponseHandler: Could not load relationship form-responses for Form ID {$formId}");
            $this->terminateRawError("Form relationship not found.");
        }

        // Load the configuration (with action flows)
        $configData = json_decode(html_entity_decode($formBean->configuration), true);
        if (!$configData) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": ResponseHandler: Form Configuration not found. ID: $formId");

            // Update the response
            if ($isPublic) {
                $responseBean->status = 'error';
                $responseBean->description = translate('LBL_ERROR_FORM_CONFIG', 'stic_Advanced_Web_Forms_Responses');
                $responseBean->save();
            }
            $this->terminateRawError("Invalid Form Configuration.");
        }
        $formConfig = FormConfig::fromJsonArray($configData);

        // Stop if it's SPAM (Fake success)
        if ($isSpam) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": ResponseHandler: Spam detected and saved for Form ID: $formId");

            // Update spam counter
            $db = DBManagerFactory::getInstance();
            $safeId = $db->quote($formId);
            $db->query("UPDATE stic_advanced_web_forms SET analytics_spam = analytics_spam + 1 WHERE id = '$safeId'");
            
            // Show generic success to fool the bot
            $title = $formConfig->layout->receipt_form_title ?? translate('LBL_THEME_RECEIPT_FORM_TITLE_VALUE', 'stic_Advanced_Web_Forms');
            $msg = $formConfig->layout->receipt_form_text ?? translate('LBL_THEME_RECEIPT_FORM_TEXT_VALUE', 'stic_Advanced_Web_Forms');
            stic_AWFUtils::renderGenericResponse($formConfig, $title, $msg);
            return; // Stop: we don't process further
        }

        // Only 'public' forms process responses
        if (!$isPublic) {
            $title = $formConfig->layout->closed_form_title ?? translate('LBL_THEME_CLOSED_FORM_TITLE_VALUE', 'stic_Advanced_Web_Forms');
            $msg = $formConfig->layout->closed_form_text ?? translate('LBL_THEME_CLOSED_FORM_TEXT_VALUE', 'stic_Advanced_Web_Forms');
            stic_AWFUtils::renderGenericResponse($formConfig, $title, $msg);
            return;
        }

        // Data validation
        $validationErrors = $this->validateSubmission($formConfig, $cleanData);
        if ($validationErrors) {
            $errorString = implode(", ", $validationErrors);
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": ResponseHandler: Data Validation errors for Form ID {$formId}:" . $errorString);

            $responseBean->status = 'error';
            $responseBean->description = translate('LBL_ERROR_FORM_VALIDATION', 'stic_Advanced_Web_Forms_Responses') . ": " . $errorString;
            $responseBean->save();

            $title = translate('LBL_ERROR_GENERIC_TITLE', 'stic_Advanced_Web_Forms_Responses');
            $errors = "\n- " . implode("\n- ", $validationErrors);
            $msg = translate('LBL_ERROR_FORM_VALIDATION_MSG', 'stic_Advanced_Web_Forms_Responses') . ":" .$errors;
            stic_AWFUtils::renderGenericResponse($formConfig, $title, $msg);
            return;
        }

        // Update valid responses counter
        $db = DBManagerFactory::getInstance();
        $safeId = $db->quote($formId);
        $db->query("UPDATE stic_advanced_web_forms SET analytics_submissions = analytics_submissions + 1 WHERE id = '$safeId'");

        // Execution context
        $defaultAssignedUserId = $realUserId ?? $formBean->assigned_user_id;
        if (empty($defaultAssignedUserId)) {
            $defaultAssignedUserId = $current_user->id;
        }
        $context = new ExecutionContext($formBean->id, $responseBean->id, $cleanData, $formConfig, null, $defaultAssignedUserId, $responseBean);

        // Generate summary HTML and save it in the response
        try {
            $snapshotHtml = stic_AWFUtils::generateSummaryHtml($context, ['showTitle' => false, 'useFlex' => true, 'includeCss' => false]);
            $responseBean->html_summary = $snapshotHtml;
        } catch (Exception $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Error generating HTML snapshot for response {$responseBean->id}: " . $e->getMessage());
            $responseBean->html_summary = "<div class='alert alert-danger'>Error generating HTML snapshot for response.</div>";
        }

        $executor = new ServerActionFlowExecutor($context);
        
        // Preparation of the Action Flows
        //    0: Main    (Main)
        //    1: Receipt (Received/Confirmation for 'async')
        //   -1: OnError (Error)
        $mainFlow = $formConfig->flows['0'] ?? null;
        $receiptFlow = $formConfig->flows['1'] ?? null;
        $errorFlow = $formConfig->flows['-1'] ?? null;

        $isAsync = ($formBean->processing_mode === 'async');
        if ($isAsync) {
            // Async mode - Execute Flow 1: 'receiptFlow' for immediate data feedback to user
            if ($receiptFlow) {
                // Execute non-terminal actions
                $pendingTerminalAction = $executor->executeFlow($receiptFlow, $errorFlow);
                // We don't update the status: it will continue to be 'pending'
                
                // Execute terminal action
                if ($pendingTerminalAction) {
                    $this->executeTerminalAction($pendingTerminalAction, $context, $errorFlow);
                } else {
                    $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": Terminal action not found in Receipt flow in form. ID: $formId");
                    $title = $formConfig->layout->receipt_form_title ?? translate('LBL_THEME_RECEIPT_FORM_TITLE_VALUE', 'stic_Advanced_Web_Forms');
                    $msg = $formConfig->layout->receipt_form_text ?? translate('LBL_THEME_RECEIPT_FORM_TEXT_VALUE', 'stic_Advanced_Web_Forms');
                    stic_AWFUtils::renderGenericResponse($formConfig, $title, $msg);
                }
            } else {
                // If there is no receipt flow, we show generic message
                $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Receipt flow not found in form. ID: $formId");
                $title = $formConfig->layout->receipt_form_title ?? translate('LBL_THEME_RECEIPT_FORM_TITLE_VALUE', 'stic_Advanced_Web_Forms');
                $msg = $formConfig->layout->receipt_form_text ?? translate('LBL_THEME_RECEIPT_FORM_TEXT_VALUE', 'stic_Advanced_Web_Forms');
                stic_AWFUtils::renderGenericResponse($formConfig, $title, $msg);                
            }
        } else {
            // Sync mode - Execute Flow 0: 'mainFlow' immediately
            $responseBean->status = 'processing';
            $responseBean->save();

            if ($mainFlow) {
                // Execute non-terminal actions
                $pendingTerminalAction = $executor->executeFlow($mainFlow, $errorFlow);

                // If the flow has paused, exit
                if ($responseBean->status === 'awaiting_action') {
                    return;
                }
                
                // Save traceability links (created/modified records)                
                $this->saveLinks($responseBean, $context);

                // Generate analytical answers
                $this->generateAnalyticsAnswers($responseBean, $formConfig, $cleanData);

                // Update status and generate execution log
                $hasErrors = false;
                $logSummary = "[" . date('Y-m-d H:i:s') . "]\n";
                foreach ($context->actionResults as $result) {
                    if ($result->isError()) {
                        $hasErrors = true;
                        $icon = translate('LBL_EXECUTION_ITEM_ERROR', 'stic_Advanced_Web_Forms_Responses');
                    }elseif ($result->isSkipped()) {
                        $icon = translate('LBL_EXECUTION_ITEM_SKIPPED', 'stic_Advanced_Web_Forms_Responses');
                    } else {
                        $icon = translate('LBL_EXECUTION_ITEM_OK', 'stic_Advanced_Web_Forms_Responses');
                    }
                    $actionName = $result->actionConfig->text ?? $result->actionConfig->name ?? 'Unknown Action';
                    $logSummary .= "{$icon} {$actionName}";
                    if (!empty($result->message)) {
                        $logSummary .= ": " . $result->message;
                    }
                    $logSummary .= "\n";
                }
                $responseBean->execution_log = $logSummary;
                $responseBean->status = $hasErrors ? 'error' : 'processed';
                $responseBean->save();

                // Execute terminal action
                if ($pendingTerminalAction) {
                    $this->executeTerminalAction($pendingTerminalAction, $context, $errorFlow);
                } else {
                    $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Terminal action not found in Main flow in form. ID: $formId");
                    if ($hasErrors) {
                        stic_AWFUtils::renderGenericResponse($formConfig, 
                                                         translate('LBL_ERROR_GENERIC_TITLE', 'stic_Advanced_Web_Forms_Responses'),
                                                         translate('LBL_ERROR_GENERIC_MSG', 'stic_Advanced_Web_Forms_Responses'));
                    } else {
                        $title = $formConfig->layout->processed_form_title ?? translate('LBL_THEME_PROCESSED_FORM_TITLE_VALUE', 'stic_Advanced_Web_Forms');
                        $msg = $formConfig->layout->processed_form_text ?? translate('LBL_THEME_PROCESSED_FORM_TEXT_VALUE', 'stic_Advanced_Web_Forms');
                        stic_AWFUtils::renderGenericResponse($formConfig, $title, $msg); 
                    }
                }
            } else {
                // If there is no main flow, we show generic message
                $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Main flow not found in form. ID: $formId");
                stic_AWFUtils::renderGenericResponse($formConfig, "Error", "Configuration Error: Main flow missing.");
            }
        }
    }

    /**
     * Executes the pending terminal action
     * @param FormAction $actionConfig Configuration of the action to execute
     * @param ExecutionContext $context Execution context
     * @param ?FormFlow $errorFlow Error flow that will be executed if the action fails
     */
    private function executeTerminalAction(FormAction $actionConfig, ExecutionContext $context, ?FormFlow $errorFlow = null): void {
        $factory = new ServerActionFactory();
        $resolver = new ParameterResolverService();
        
        try {
            $actionExecutor = $factory->createAction($actionConfig);
            
            // Parameter resolution
            $paramDefinitions = $actionExecutor->getParameters();
            $resolvedParameters = $resolver->resolveAll($actionConfig, $paramDefinitions, $actionConfig->parameters, $context);
            $actionConfig->setResolvedParameters($resolvedParameters);
            
            // Execute. It probably won't return because it will have an exit/redirect
            $actionExecutor->execute($context, $actionConfig);
        } catch (\Exception $e) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Terminal Action Failed: " . $e->getMessage());

            // If it has failed and we have a defined error flow, execute it if headers have not been sent yet
            if ($errorFlow && !headers_sent()) {
                $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": Attempting to execute Error Flow after Terminal Action failure.");
                
                try {
                    $executor = new ServerActionFlowExecutor($context);
                    $errorTerminalAction = $executor->executeFlow($errorFlow, null);
                    if ($errorTerminalAction) {
                        $this->executeTerminalAction($errorTerminalAction, $context, null);
                        return;
                    }
                } catch (\Exception $ex) {
                    $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Error Flow execution failed also: " . $e->getMessage());
                }
            }
            // If everything fails we show generic error
            if (!headers_sent()) {
                stic_AWFUtils::renderGenericResponse($context->formConfig, "Error", "Error processing terminal action.");
            }
        }
    }

    private function terminateRawError($msg): void {
        http_response_code(400);
        die("System Error: " . htmlspecialchars($msg));
    }

    private function checkDuplicateSubmission($formId, $hash): bool {
        global $db;
        $query = "SELECT count(response.id) as count FROM stic_advanced_web_forms_responses response
                    INNER JOIN stic_f193responses_c form_response
                        ON form_response.stic_21b0sponses_idb = response.id
                  WHERE
                    form_response.stic_aa0eb_forms_ida = '{$formId}'
                    AND form_response.deleted = 0
                    AND response.response_hash = '{$hash}'
                    AND response.deleted = 0";

        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": " . $query);
        $result = $db->query($query);
        $data = $db->fetchByAssoc($result);
        return $data['count'] > 0;
    }

    private function handleDuplicateError($formBean): void {
        $configData = json_decode(html_entity_decode($formBean->configuration), true);
        $formConfig = $configData ? FormConfig::fromJsonArray($configData) : null;
        if ($formConfig) {
            stic_AWFUtils::renderGenericResponse($formConfig, 
                                             translate('LBL_DUPLICATE_RESPONSE_TITLE', 'stic_Advanced_Web_Forms_Responses'),
                                             translate('LBL_DUPLICATE_RESPONSE_MSG', 'stic_Advanced_Web_Forms_Responses'));
        } else {
            $this->terminateRawError("This response has already been submitted.");
        }
    }

    private function saveLinks(SugarBean $responseBean, ExecutionContext $context): void {
        if (!$responseBean->load_relationship('stic_1c31forms_links')) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": ResponseHandler: Could not load relationship responses-links for Response ID {$responseBean->id}");
            return;
        }

        global $app_list_strings;

        // Consolidate links: A single link for each affected bean
        $consolidatedBeans = []; 
        foreach ($context->actionResults as $result) {
            foreach ($result->modifiedBeans as $modBean) {
                $key = $modBean->moduleName . ':' . $modBean->beanId;

                if (!isset($consolidatedBeans[$key])) {
                    // First time we touch it
                    $consolidatedBeans[$key] = [
                        'module' => $modBean->moduleName,
                        'id' => $modBean->beanId,
                        'type' => $modBean->modificationType, // (CREATED, UPDATED, ENRICHED, SKIPPED)
                        'data' => $modBean->submittedData
                    ];
                } else {
                    // It already exists, we merge
                    $currentEntry = &$consolidatedBeans[$key];

                    // Merge of action type performed. Priority: CREATED > UPDATED > ENRICHED
                    if ($modBean->modificationType === BeanModificationType::CREATED) {
                        $currentEntry['type'] = BeanModificationType::CREATED;
                    } elseif ($currentEntry['type'] !== BeanModificationType::CREATED && $modBean->modificationType === BeanModificationType::UPDATED) {
                        $currentEntry['type'] = BeanModificationType::UPDATED;
                    } elseif ($currentEntry['type'] === BeanModificationType::SKIPPED && $modBean->modificationType !== BeanModificationType::SKIPPED) {
                        // If it was SKIPPED and now it is another action, we update it
                        $currentEntry['type'] = $modBean->modificationType;
                    }

                    // Merge of touched data: We accumulate the fields (not SKIPPED)
                    if ($modBean->modificationType !== BeanModificationType::SKIPPED && !empty($modBean->submittedData)) {
                        $currentEntry['data'] = array_merge($currentEntry['data'], $modBean->submittedData);
                    }
                }
            }
        }

        // Save the links
        $sequence = 1;
        foreach ($consolidatedBeans as $item) {
            $linkBean = BeanFactory::newBean('stic_Advanced_Web_Forms_Links');

            $targetBean = BeanFactory::getBean($item['module'], $item['id']);
            $targetBeanName = $targetBean ? $targetBean->get_summary_text() : $item['id'];
            
            $actionValue = $item['type']->value;
            $recordActionName = $app_list_strings['stic_advanced_web_forms_links_record_action_list'][$actionValue] ?? $actionValue;
            $moduleSingular = $app_list_strings['moduleListSingular'][$item['module']] ?? $item['module'];

            $linkBean->is_automated_save = true;
            // Link name format: "FormName - Module: RecordName (Created)"
            $linkBean->name = $responseBean->name ." - ". $moduleSingular .": ". $targetBeanName ." (". $recordActionName .")";
            $linkBean->sequence_number = $sequence++;
            $linkBean->parent_id = $item['id']; 
            $linkBean->parent_type = $item['module']; 
            $linkBean->record_action = $actionValue;
            $linkBean->assigned_user_id = $responseBean->assigned_user_id;

            if (!empty($item['data'])) {
                $linkBean->submitted_data = json_encode($item['data'], JSON_UNESCAPED_UNICODE);
            }

            $linkBean->save();

            // Link the Link to the Response
            $responseBean->stic_1c31forms_links->add($linkBean->id);
        }
    }

    /**
     * Validates submission data against form configuration.
     * @param FormConfig $config Form configuration
     * @param array $data Received data in the submission
     * @return ?array List of errors or null if no errors
     */
    private function validateSubmission(FormConfig $config, array $data): ?array {
        $errors = [];

        foreach ($config->data_blocks as $block) {
            foreach ($block->fields as $field) {
                if ($field->type_field === DataBlockFieldType::FIXED) {
                    continue;
                }

                $prefix = ($field->type_field === DataBlockFieldType::UNLINKED) ? '_detached_' : '';
                $inputKey = $prefix . $block->name . '_' . $field->name;
                $value = $data[$inputKey] ?? null;

                // Validation of required field (Required)
                if ($field->required_in_form) {
                    if ($value === null || $value === '' || (is_array($value) && empty($value))) {
                        $errors[] = translate('LBL_FIELD', 'stic_Advanced_Web_Forms_Responses') ." '{$field->label}': ". 
                                    translate('LBL_ERROR_REQUIRED_FIELD', 'stic_Advanced_Web_Forms_Responses');
                        continue;
                    }
                }

                // Data type validation (Sanity Check)
                if ($value !== null && $value !== '') {
                    if ($field->type_in_form === 'number') {
                        if (!is_numeric($value)) {
                            $errors[] = translate('LBL_FIELD', 'stic_Advanced_Web_Forms_Responses') ." '{$field->label}': ". 
                                        translate('LBL_ERROR_NUMERIC_FIELD', 'stic_Advanced_Web_Forms_Responses');
                        }
                    }
                    if ($field->type_in_form === 'date') {
                        if (!strtotime($value)) {
                            $errors[] = translate('LBL_FIELD', 'stic_Advanced_Web_Forms_Responses') ." '{$field->label}': ". 
                                        translate('LBL_ERROR_DATE_FIELD', 'stic_Advanced_Web_Forms_Responses');
                        }
                    }
                    if ($field->subtype_in_form === 'text_email') {
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[] = translate('LBL_FIELD', 'stic_Advanced_Web_Forms_Responses') ." '{$field->label}': ". 
                                        translate('LBL_ERROR_EMAIL_FIELD', 'stic_Advanced_Web_Forms_Responses');
                        }
                    }
                    if (!empty($field->value_options) && $field->value_type === DataBlockFieldValueType::SELECTABLE) {
                        $validValues = array_map(fn($opt) => $opt->value, $field->value_options);
                        $submittedValues = is_array($value) ? $value : [$value];
                        foreach ($submittedValues as $subVal) {
                            if ($subVal === '' || $subVal === null) {
                                continue;
                            }
                            if (!in_array($subVal, $validValues)) {
                                $errors[] = translate('LBL_FIELD', 'stic_Advanced_Web_Forms_Responses') ." '{$field->label}': ". 
                                            translate('LBL_ERROR_VALUE_FIELD', 'stic_Advanced_Web_Forms_Responses') . ' ({$subVal})';
                                break; 
                            }
                        }
                    }
                }
            }
        }

        return empty($errors) ? null : $errors;
    }

    /**
     * Generates analytical responses for storage and subsequent analysis.
     * @param SugarBean $responseBean Response bean
     * @param FormConfig $formConfig Form configuration
     * @param array $submittedData Data sent in the submission
     */
    private function generateAnalyticsAnswers(SugarBean $responseBean, FormConfig $formConfig, array $submittedData): void {
        global $app_strings;

        // Global counter
        $orderCounter = 1;

        foreach ($formConfig->data_blocks as $block) {
            foreach ($block->fields as $field) {
                $currentOrder = $orderCounter;
                $orderCounter += 1;

                // Skip fixed fields
                if ($field->type_field === DataBlockFieldType::FIXED) continue;

                // Input key
                $prefix = ($field->type_field === DataBlockFieldType::UNLINKED) ? '_detached_' : '';
                $inputKey = $prefix . $block->name . '_' . $field->name;
                
                $rawValue = $submittedData[$inputKey] ?? null;
                
                // Calculate readable text and value to store
                $readableText = $rawValue;
                $storedValue = $rawValue;
                
                // List type fields (select, multiselect, radio)
                if (!empty($field->value_options)) {
                    if (is_array($rawValue)) {
                        // Multi-selection
                        $labels = [];
                        foreach ($rawValue as $valItem) {
                            $opt = $this->findOption($field->value_options, $valItem);
                            $labels[] = $opt ? $opt->text : $valItem;
                        }
                        $storedValue = json_encode($rawValue, JSON_UNESCAPED_UNICODE); // Store JSON ["A","B"]
                        $readableText = implode(', ', $labels); // Text: "Option A, Option B"
                    } else {
                        // Single selection
                        $opt = $this->findOption($field->value_options, $rawValue);
                        if ($opt) {
                            $readableText = $opt->text;
                        }
                    }
                } 
                // Boolean fields (checkbox)
                elseif ($field->type === 'bool' || $field->type === 'checkbox') {
                    $isTrue = ($rawValue === '1' || $rawValue === 'on' || $rawValue === 'true' || $rawValue === true);
                    $readableText = $isTrue ? $app_strings['LBL_YES'] : $app_strings['LBL_NO'];
                    $storedValue = $isTrue ? '1' : '0';
                }
                // Generic arrays that are not lists
                elseif (is_array($rawValue)) {
                    $storedValue = json_encode($rawValue, JSON_UNESCAPED_UNICODE);
                    $readableText = 'Array'; 
                }

                // Create analytical response bean
                $answerBean = BeanFactory::newBean('stic_Advanced_Web_Forms_Answers');
                $answerBean->response_id = $responseBean->id;
                $answerBean->form_id = $formConfig->id ?? ''; 
                
                $answerBean->question_key = $block->name . '.' . $field->name;
                $answerBean->question_label = $field->label ?? $field->text_original ?? $field->name;
                if (!empty($field->description)) {
                    $answerBean->question_help_text = stic_AWFUtils::parseAnchorMarkdown($field->description);
                }
                $answerBean->question_section = $block->text;
                
                $answerBean->question_sort_order = $currentOrder;

                $answerBean->answer_value = (string)$storedValue;
                $answerBean->answer_text = (string)$readableText;
                $answerBean->answer_type = $field->type_in_form;
                
                // To facilitate analysis, we save the numeric value if applicable
                if (!is_array($rawValue) && is_numeric($rawValue)) {
                    $answerBean->answer_integer = (float)$rawValue;
                } else {
                    $answerBean->answer_integer = 0;
                }

                $answerBean->save();
            }
        }
    }

    private function findOption(array $options, $value) {
        foreach ($options as $opt) {
            if ($opt->value == $value) return $opt;
        }
        return null;
    }

    /**
     * Function that recursively cleans the input to avoid XSS
     * Removes dangerous HTML tags and extra spaces
     */
    private function sanitizeInput($input) {
        if (is_array($input)) {
            // If it is an array, we clean each element, also the key
            $clean = [];
            foreach ($input as $key => $value) {
                $clean[strip_tags($key)] = $this->sanitizeInput($value);
            }
            return $clean;
        }
        
        // If it is a bool, null or pure numeric, let it pass
        if (is_bool($input) || is_null($input) || is_numeric($input)) {
            return $input;
        }

        // Clean the input
        return strip_tags(trim((string)$input));
    }    
}

// Handler execution
$handler = new ResponseHandler();
$handler->run();