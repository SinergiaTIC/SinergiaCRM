<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'modules/stic_AWF_Forms/core/includes.php';

class ResponseProcessingService
{
    /**
     * Process a single form response.
     * Called by: ResponseHandler (sync) AND AsyncResponseProcessor (async)
     *
     * @param string $responseId The response ID to process
     * @param bool $saveLinks Whether to save links (default true)
     * @return ActionResult The result of processing
     */
    public static function processResponse(string $responseId, bool $saveLinks = true): ActionResult
    {
        $GLOBALS['log']->info("Line " . __LINE__ . ": " . __METHOD__ . ": Processing response ID: {$responseId}");

        // 1. Load response bean
        $responseBean = BeanFactory::getBean('stic_AWF_Responses', $responseId);
        if (!$responseBean || empty($responseBean->id)) {
            $GLOBALS['log']->error("Line " . __LINE__ . ": " . __METHOD__ . ": Response not found: {$responseId}");
            return new ActionResult(ResultStatus::ERROR, null, "Response not found");
        }

        // 2. Load form bean
        $formBean = BeanFactory::getBean('stic_AWF_Forms', $responseBean->stic_awf_forms_id);
        if (!$formBean || empty($formBean->id)) {
            $GLOBALS['log']->error("Line " . __LINE__ . ": " . __METHOD__ . ": Form not found for response: {$responseId}");
            $responseBean->status = 'error';
            $responseBean->execution_log = "Form configuration not found";
            $responseBean->save();
            return new ActionResult(ResultStatus::ERROR, null, "Form not found");
        }

        // 3. Parse form configuration
        $configData = json_decode(html_entity_decode($formBean->configuration), true);
        if (!$configData) {
            $GLOBALS['log']->error("Line " . __LINE__ . ": " . __METHOD__ . ": Invalid form configuration for response: {$responseId}");
            $responseBean->status = 'error';
            $responseBean->execution_log = "Invalid form configuration";
            $responseBean->save();
            return new ActionResult(ResultStatus::ERROR, null, "Invalid form configuration");
        }

        $formConfig = FormConfig::fromJsonArray($configData);
        if (!$formConfig) {
            $GLOBALS['log']->error("Line " . __LINE__ . ": " . __METHOD__ . ": Could not parse form config for response: {$responseId}");
            $responseBean->status = 'error';
            $responseBean->execution_log = "Could not parse form configuration";
            $responseBean->save();
            return new ActionResult(ResultStatus::ERROR, null, "Could not parse form config");
        }

        // 4. Reconstruct execution context
        $formData = json_decode($responseBean->raw_payload ?? '{}', true) ?: [];
        $context = new ExecutionContext(
            $formBean->id, 
            $responseBean->id, 
            $formData, 
            $formConfig, 
            null, 
            $responseBean->assigned_user_id, 
            $responseBean
        );

        // 5. Get flows
        $mainFlow = $formConfig->flows['0'] ?? null;
        $errorFlow = $formConfig->flows['-1'] ?? null;

        if (!$mainFlow) {
            $GLOBALS['log']->warn("Line " . __LINE__ . ": " . __METHOD__ . ": No main flow for response: {$responseId}");
            $responseBean->status = 'processed';
            $responseBean->execution_log = "[" . date('Y-m-d H:i:s') . "]\nNo main flow configured\n";
            $responseBean->save();
            return new ActionResult(ResultStatus::OK, null, "No main flow");
        }

        // 6. Execute main flow
        $executor = new ServerActionFlowExecutor($context);
        $lastResult = $executor->executeFlow($mainFlow, $errorFlow);

        // 7. Save links (traceability)
        if ($saveLinks) {
            self::saveLinks($responseBean, $context);
        }

        // 8. Generate response details
        self::generateResponseDetails($responseBean, $formBean, $formConfig, $context->formData);

        // 9. Generate execution log
        $hasErrors = false;
        $logSummary = "[" . date('Y-m-d H:i:s') . "]\n";
        foreach ($context->actionResults as $result) {
            if ($result->isError()) {
                $hasErrors = true;
                $icon = translate('LBL_EXECUTION_ITEM_ERROR', 'stic_AWF_Responses');
            } elseif ($result->isSkipped()) {
                $icon = translate('LBL_EXECUTION_ITEM_SKIPPED', 'stic_AWF_Responses');
            } else {
                $icon = translate('LBL_EXECUTION_ITEM_OK', 'stic_AWF_Responses');
            }
            $actionName = $result->actionConfig->text ?? $result->actionConfig->name ?? 'Unknown Action';
            $logSummary .= "{$icon} {$actionName}";
            if (!empty($result->message)) {
                $logSummary .= ": " . $result->message;
            }
            $logSummary .= "\n";
        }
        $responseBean->execution_log = $logSummary;

        // 10. Update status
        if ($lastResult->isError()) {
            $responseBean->status = 'error';
        } elseif ($lastResult->isWait()) {
            $responseBean->status = 'awaiting_action';
            self::createDeferredTicket($context, $lastResult);
        } else {
            $responseBean->status = $hasErrors ? 'error' : 'processed';
        }
        $responseBean->save();

        $GLOBALS['log']->info("Line " . __LINE__ . ": " . __METHOD__ . ": Response processed: {$responseId}, status: {$responseBean->status}");

        return $lastResult;
    }

    /**
     * Save links between response and created/modified records.
     */
    private static function saveLinks(SugarBean $responseBean, ExecutionContext $context): void
    {
        if (!$responseBean->load_relationship('stic_1c31forms_links')) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Could not load relationship responses-links for Response ID {$responseBean->id}");
            return;
        }

        global $app_list_strings;

        $consolidatedBeans = [];
        foreach ($context->actionResults as $result) {
            foreach ($result->modifiedBeans as $modBean) {
                $key = $modBean->moduleName . ':' . $modBean->beanId;

                if (!isset($consolidatedBeans[$key])) {
                    $consolidatedBeans[$key] = [
                        'module' => $modBean->moduleName,
                        'id' => $modBean->beanId,
                        'type' => $modBean->modificationType,
                        'data' => $modBean->submittedData
                    ];
                } else {
                    $currentEntry = &$consolidatedBeans[$key];
                    $oldType = $currentEntry['type'];
                    $newType = $modBean->modificationType;

                    if ($newType === BeanModificationType::CREATED) {
                        $currentEntry['type'] = BeanModificationType::CREATED;
                    } elseif ($newType === BeanModificationType::UPDATED && $oldType !== BeanModificationType::CREATED) {
                        $currentEntry['type'] = BeanModificationType::UPDATED;
                    } elseif ($newType === BeanModificationType::ENRICHED && !in_array($oldType, [BeanModificationType::CREATED, BeanModificationType::UPDATED])) {
                        $currentEntry['type'] = BeanModificationType::ENRICHED;
                    } elseif ($oldType === BeanModificationType::METADATA && $newType !== BeanModificationType::METADATA) {
                        $currentEntry['type'] = $newType;
                    }

                    if ($newType !== BeanModificationType::SKIPPED && !empty($modBean->submittedData)) {
                        foreach ($modBean->submittedData as $fieldName => $fieldMod) {
                            if (!isset($currentEntry['data'][$fieldName])) {
                                $currentEntry['data'][$fieldName] = $fieldMod;
                            } else {
                                $existingMod = $currentEntry['data'][$fieldName];
                                if ($fieldMod->status === FieldModificationStatus::APPLIED) {
                                    $currentEntry['data'][$fieldName] = $fieldMod;
                                } elseif ($fieldMod->status === FieldModificationStatus::IGNORED_ENRICH && $existingMod->status === FieldModificationStatus::UNCHANGED) {
                                    $currentEntry['data'][$fieldName] = $fieldMod;
                                }
                            }
                        }
                    }
                }
            }
        }

        $sequence = 1;
        foreach ($consolidatedBeans as $item) {
            $linkBean = BeanFactory::newBean('stic_AWF_Links');

            $targetBean = BeanFactory::getBean($item['module'], $item['id']);
            $targetBeanName = $targetBean ? $targetBean->get_summary_text() : $item['id'];

            $actionValue = $item['type']->value;
            $recordActionName = $app_list_strings['stic_awf_links_record_action_list'][$actionValue] ?? $actionValue;
            $moduleSingular = $app_list_strings['moduleListSingular'][$item['module']] ?? $item['module'];

            $linkBean->is_automated_save = true;
            $linkBean->name = $responseBean->name . " - " . $moduleSingular . ": " . $targetBeanName . " (" . $recordActionName . ")";
            $linkBean->sequence_number = $sequence++;
            $linkBean->parent_id = $item['id'];
            $linkBean->parent_type = $item['module'];
            $linkBean->record_action = $actionValue;
            $linkBean->assigned_user_id = $responseBean->assigned_user_id;

            if (!empty($item['data'])) {
                $linkBean->submitted_data = json_encode($item['data'], JSON_UNESCAPED_UNICODE);
            }

            $linkBean->save();
            $responseBean->stic_1c31forms_links->add($linkBean->id);
        }
    }

    /**
     * Create a deferred ticket for async processing.
     */
    private static function createDeferredTicket(ExecutionContext $context, ActionResult $lastResult): void
    {
        $ticket = BeanFactory::newBean('stic_AWF_Deferred_Tickets');
        $ticket->name = "Deferred: " . $context->formId . " - " . date('Y-m-d H:i');
        $ticket->status = 'pending';

        $data = $lastResult->getData();
        $ticket->form_id = $context->formId;
        $ticket->response_id = $context->responseId;

        $token = bin2hex(random_bytes(16));
        $ticket->token_hash = $token;

        if (!empty($data['external_transaction_id'])) {
            $ticket->external_transaction_id = $data['external_transaction_id'];
        }

        $contextData = [
            'response_id' => $context->responseId,
            'form_id' => $context->formId,
        ];
        if (!empty($data['strategy_class'])) {
            $contextData['strategy_class'] = $data['strategy_class'];
        }
        if (!empty($data['strategy_suffix'])) {
            $contextData['strategy_suffix'] = $data['strategy_suffix'];
        }
        if (!empty($data['payment_id'])) {
            $contextData['payment_id'] = $data['payment_id'];
        }

        $ticket->context_data = json_encode($contextData);
        $ticket->expiration_date = date('Y-m-d H:i:s', strtotime('+30 days'));

        $ticket->save();

        $GLOBALS['log']->info("Line " . __LINE__ . ": " . __METHOD__ . ": Created deferred ticket: {$ticket->id} for response: {$context->responseId}");
    }

        /**
     * Generates response details for storage and subsequent analysis.
     * @param SugarBean $responseBean Response bean
     * @param SugarBean $formBean Form bean
     * @param FormConfig $formConfig Form configuration
     * @param array $submittedData Data sent in the submission
     */
    public static function generateResponseDetails(SugarBean $responseBean, SugarBean $formBean, FormConfig $formConfig, array $submittedData): void {
        global $app_strings;

        // Global counter
        $orderCounter = 1;

        // if (!$responseBean->load_relationship('details_link')) {
        //     $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": ResponseHandler: Could not load relationship 'details_link' in Responses bean.");
        // }

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
                $detailBean = BeanFactory::newBean('stic_AWF_Response_Details');
                $detailBean->stic_awf_responses_id_c = $responseBean->id;
                $detailBean->stic_awf_forms_id_c = $formBean->id ?? ''; 
                $detailBean->assigned_user_id = $responseBean->assigned_user_id;
                
                $detailBean->question_key = $block->name . '.' . $field->name;
                $detailBean->question_label = $field->label ?? $field->text_original ?? $field->name;
                $detailBean->question_label = rtrim($detailBean->question_label, ' :');
                if (!empty($field->description)) {
                    $detailBean->question_help_text = stic_AWFUtils::parseAnchorMarkdown($field->description);
                }
                $detailBean->question_section = $block->text;
                
                $detailBean->question_sort_order = $currentOrder;

                $detailBean->answer_value = (string)$storedValue;
                $detailBean->answer_text = (string)$readableText;
                $detailBean->answer_type = $field->type_in_form;
                
                // Save the value as integer to facilitate analysis
                // Special handling for rating fields to normalize them to a 0-100 scale
                if ($field->type_in_form === 'rating' && is_numeric($rawValue)) {
                    $rawNum = (float)$rawValue;
                    
                    if ($field->subtype_in_form === 'rating_nps') {
                        // NPS (0-10) -> Scale 0-100
                        $normalized = $rawNum * 10;
                    } else {
                        // Stars, emojis, lights, thumbs (1-5) -> Scale 0-100: 1=20, 2=40, 3=60, 4=80, 5=100
                        $normalized = $rawNum * 20;
                    }
                    // Make sure to store a clean integer limited between 0 and 100
                    $detailBean->answer_integer = (int)max(0, min(100, round($normalized)));
                } elseif (!is_array($rawValue) && is_numeric($rawValue)) {
                    // Save the numeric value if applicable
                    $detailBean->answer_integer = (int)round((float)$rawValue);
                } else {
                    $detailBean->answer_integer = 0;
                }

                $detailBean->save();
            }
        }
    }
}
