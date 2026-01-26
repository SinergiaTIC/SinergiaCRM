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
 * Se encarga de recibir, validar, persistir y procesar las respuestas de los formularios web.
 */
class AWF_ResponseHandler
{
    public function run(): void {
        global $current_user;

        // Usuario real (antes de cambiar a admin)
        $realUserId = null;
        if (!empty($current_user) && !empty($current_user->id)) {
            $realUserId = $current_user->id;
        }

        // Usuario administrador
        $current_user = BeanFactory::newBean('Users');
        $current_user->getSystemUser();

        // Obtención de datos
        $formId = $_REQUEST['id'] ?? null;
        $rawPostData = $_POST;
        $cleanData = $this->sanitizeInput($rawPostData);

        // Detección Anti-Spam (Honeypot): Campo oculto que los bots suelen rellenar
        $isSpam = !empty($cleanData['awf_honey_pot']);

        // Detección Anti-Spam (TimeTrap): Normalmente los bots envían el formulario inmediatamente y/o sin ejecutar JS
        $submissionTs = (int)($_POST['awf_submission_ts'] ?? 0);
        $currentTs = time();
        $duration = $currentTs - $submissionTs;
        if ($submissionTs === 0 || $duration < 3) {
            $isSpam = true;
        }

        // URL del formulario
        $formUrl = $_POST['awf_form_url'] ?? $_SERVER['HTTP_REFERER'] ?? '';
        $formUrl = substr(strip_tags($formUrl), 0, 255);

        // Saneamiento de datos
        unset($cleanData['module']);
        unset($cleanData['action']);
        unset($cleanData['entryPoint']);
        unset($cleanData['id']);
        unset($cleanData['awf_honey_pot']);
        unset($cleanData['awf_submission_ts']);
        unset($cleanData['awf_form_url']);


        // Validaciones iniciales
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

        // Detección de duplicados: Fingerprint
        $remoteIp = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $payloadJson = json_encode($cleanData);

        $fingerprintString = $payloadJson . $formId . $remoteIp . $userAgent . $formUrl;
        $responseHash = md5($fingerprintString);

        if ($this->checkDuplicateSubmission($formId, $responseHash)) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": ResponseHandler: Duplicated response detected");
            $this->handleDuplicateError($formBean);
            return;
        }

        // Buscamos el estado de la respuesta
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

        // Guardamos la respuesta
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

        // Vinculamos la respuesta con el formulario
        if ($formBean->load_relationship('stic_69c1s_responses')) {
            $formBean->stic_69c1s_responses->add($responseBean->id);
        } else {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": ResponseHandler: Could not load relationship form-responses for Form ID {$formId}");
            $this->terminateRawError("Form relationship not found.");
        }

        // Cargamos la configuración (con los flujos de acciones)
        $configData = json_decode(html_entity_decode($formBean->configuration), true);
        if (!$configData) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": ResponseHandler: Form Configuration not found. ID: $formId");

            // Actualizamos la respuesta
            if ($isPublic) {
                $responseBean->status = 'error';
                $responseBean->description = translate('LBL_ERROR_FORM_CONFIG', 'stic_Advanced_Web_Forms_Responses');
                $responseBean->save();
            }
            $this->terminateRawError("Invalid Form Configuration.");
        }
        $formConfig = FormConfig::fromJsonArray($configData);

        // Paramos si es SPAM (Fake success)
        if ($isSpam) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": ResponseHandler: Spam detected and saved for Form ID: $formId");

            // Actualizamos contador de spam
            $db = DBManagerFactory::getInstance();
            $safeId = $db->quote($formId);
            $db->query("UPDATE stic_advanced_web_forms SET analytics_spam = analytics_spam + 1 WHERE id = '$safeId'");
            
            // Mostramos éxito genérico para engañar al bot
            AWF_Utils::renderGenericResponse($formConfig, 
                                             translate('LBL_RECEIPT_GENERIC_TITLE', 'stic_Advanced_Web_Forms_Responses'),
                                             translate('LBL_RECEIPT_GENERIC_MSG', 'stic_Advanced_Web_Forms_Responses'));
            return; // Paramos: no procesamos más
        }

        // Solo los formularios 'public' procesan las respuestas
        if (!$isPublic) {
            $title = $formConfig->layout->closed_form_title ?? translate('LBL_THEME_CLOSED_FORM_TITLE_VALUE', 'stic_Advanced_Web_Forms');
            $msg = $formConfig->layout->closed_form_text ?? translate('LBL_THEME_CLOSED_FORM_TEXT_VALUE', 'stic_Advanced_Web_Forms');
            AWF_Utils::renderGenericResponse($formConfig, $title, $msg);
            return;
        }

        // Validación de datos
        $validationErrors = $this->validateSubmission($formConfig, $cleanData);
        if ($validationErrors) {
            $errorString = implode(", ", $validationErrors);
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": ResponseHandler: Data Validation errors for Form ID {$formId}:" . $errorString);

            $responseBean->status = 'error';
            $responseBean->description = translate('LBL_ERROR_FORM_VALIDATION', 'stic_Advanced_Web_Forms_Responses') . ": " . $errorString;
            $responseBean->save();

            $title = translate('LBL_ERROR_GENERIC_TITLE', 'stic_Advanced_Web_Forms_Responses');
            $htmlErrors = "<ul><li>" . implode("</li><li>", $validationErrors) . "</li></ul>";
            $msg = translate('LBL_ERROR_FORM_VALIDATION_MSG', 'stic_Advanced_Web_Forms_Responses') . ":<br>" .$htmlErrors;
            AWF_Utils::renderGenericResponse($formConfig, $title, $msg);
            return;
        }

        // Actualizamos contador de respuestas válidas
        $db = DBManagerFactory::getInstance();
        $safeId = $db->quote($formId);
        $db->query("UPDATE stic_advanced_web_forms SET analytics_submissions = analytics_submissions + 1 WHERE id = '$safeId'");

        // Contexto de ejecución
        $defaultAssignedUserId = $realUserId ?? $formBean->assigned_user_id;
        if (empty($defaultAssignedUserId)) {
            $defaultAssignedUserId = $current_user->id;
        }
        $context = new ExecutionContext($formBean->id, $responseBean->id, $cleanData, $formConfig, null, $defaultAssignedUserId, $responseBean);

        // Generamos el HTML resumen y lo guardamos en la respuesta
        try {
            $snapshotHtml = AWF_Utils::generateSummaryHtml($context, ['showTitle' => false, 'useFlex' => true, 'includeCss' => false]);
            $responseBean->html_summary = $snapshotHtml;
        } catch (Exception $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Error generating HTML snapshot for response {$responseBean->id}: " . $e->getMessage());
            $responseBean->html_summary = "<div class='alert alert-danger'>Error generating HTML snapshot for response.</div>";
        }

        $executor = new ServerActionFlowExecutor($context);
        
        // Preparación de los Flujos de acciones
        //    0: Main    (Principal)
        //    1: Receipt (Recibido/Confirmación para 'async')
        //   -1: OnError (Error)
        $mainFlow = $formConfig->flows['0'] ?? null;
        $receiptFlow = $formConfig->flows['1'] ?? null;
        $errorFlow = $formConfig->flows['-1'] ?? null;

        $isAsync = ($formBean->processing_mode === 'async');
        if ($isAsync) {
            // Modo async - Ejecutamos Flow 1: 'receiptFlow' para data feedback inmediato al usuario
            if ($receiptFlow) {
                // Ejecutamos las acciones no terminales
                $pendingTerminalAction = $executor->executeFlow($receiptFlow, $errorFlow);
                // No actualizamos el estado: seguirá siendo 'pending'
                
                // Ejecutamos la acción terminal
                if ($pendingTerminalAction) {
                    $this->executeTerminalAction($pendingTerminalAction, $context, $errorFlow);
                } else {
                    $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Terminal action not found in Receipt flow in form. ID: $formId");
                    AWF_Utils::renderGenericResponse($formConfig, 
                                                     translate('LBL_RECEIPT_GENERIC_TITLE', 'stic_Advanced_Web_Forms_Responses'), 
                                                     translate('LBL_RECEIPT_GENERIC_MSG', 'stic_Advanced_Web_Forms_Responses'));
                }
            } else {
                // Si no hay flujo de recibido, mostramos mensaje genérico
                $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Receipt flow not found in form. ID: $formId");
                AWF_Utils::renderGenericResponse($formConfig, 
                                                 translate('LBL_RECEIPT_GENERIC_TITLE', 'stic_Advanced_Web_Forms_Responses'), 
                                                 translate('LBL_RECEIPT_GENERIC_MSG', 'stic_Advanced_Web_Forms_Responses'));
            }
        } else {
            // Modo sync - Ejecutamos Flow 0: 'mainFlow' inmediatamente
            $responseBean->status = 'processing';
            $responseBean->save();

            if ($mainFlow) {
                // Ejecutamos las acciones no terminales
                $pendingTerminalAction = $executor->executeFlow($mainFlow, $errorFlow);

                // Si el flujo se ha pausado, salimos
                if ($responseBean->status === 'awaiting_action') {
                    return;
                }
                
                // Guardamos los vínculos de trazabilidad (registros creados/modificados)                
                $this->saveLinks($responseBean, $context);

                // Generamos las respuestas analíticas
                $this->generateAnalyticsAnswers($responseBean, $formConfig, $cleanData);

                // Actualizamos el estado y generamos el log de ejecución
                $hasErrors = false;
                $logSummary = ">> " . date('Y-m-d H:i:s') . "\n";
                foreach ($context->actionResults as $result) {
                    if ($result->isError()) {
                        $hasErrors = true;
                        $icon = "❌";
                    }elseif ($result->isSkipped()) {
                        $icon = "⏭️";
                    } else {
                        $icon = "✅";
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

                // Ejecutamos la acción terminal
                if ($pendingTerminalAction) {
                    $this->executeTerminalAction($pendingTerminalAction, $context, $errorFlow);
                } else {
                    $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Terminal action not found in Main flow in form. ID: $formId");
                    if ($hasErrors) {
                        AWF_Utils::renderGenericResponse($formConfig, 
                                                         translate('LBL_ERROR_GENERIC_TITLE', 'stic_Advanced_Web_Forms_Responses'),
                                                         translate('LBL_ERROR_GENERIC_MSG', 'stic_Advanced_Web_Forms_Responses'));
                    } else {
                        AWF_Utils::renderGenericResponse($formConfig, 
                                                         translate('LBL_MAIN_GENERIC_TITLE', 'stic_Advanced_Web_Forms_Responses'),
                                                         translate('LBL_MAIN_GENERIC_MSG', 'stic_Advanced_Web_Forms_Responses'));
                    }
                }
            } else {
                // Si no hay flujo principal, mostramos mensaje genérico
                $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Main flow not found in form. ID: $formId");
                AWF_Utils::renderGenericResponse($formConfig, "Error", "Configuration Error: Main flow missing.");
            }
        }
    }

    /**
     * Ejecuta la acción terminal pendiente
     * @param FormAction $actionConfig Configuración de la acción a ejecutar
     * @param ExecutionContext $context Contexto de ejecución
     * @param ?FormFlow $errorFlow Flujo de error que se ejecutará si falla la acción
     */
    private function executeTerminalAction(FormAction $actionConfig, ExecutionContext $context, ?FormFlow $errorFlow = null): void {
        $factory = new ServerActionFactory();
        $resolver = new ParameterResolverService();
        
        try {
            $actionExecutor = $factory->createAction($actionConfig);
            
            // Resolución de parámetros
            $paramDefinitions = $actionExecutor->getParameters();
            $resolvedParameters = $resolver->resolveAll($actionConfig, $paramDefinitions, $actionConfig->parameters, $context);
            $actionConfig->setResolvedParameters($resolvedParameters);
            
            // Ejecutamos. Seguramente no retornará porque tendrá un exit/redirect
            $actionExecutor->execute($context, $actionConfig);
        } catch (\Exception $e) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Terminal Action Failed: " . $e->getMessage());

            // Si ha fallado y tenemos un flujo de error definido lo ejecutamos si no se han enviado las cabeceras aún
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
            // Si todo falla mostramos error genérico
            if (!headers_sent()) {
                AWF_Utils::renderGenericResponse($context->formConfig, "Error", "Error processing terminal action.");
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
            AWF_Utils::renderGenericResponse($formConfig, 
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

        // Consolidamos los links: Un único link por cada bean afectado
        $consolidatedBeans = []; 
        foreach ($context->actionResults as $result) {
            foreach ($result->modifiedBeans as $modBean) {
                $key = $modBean->moduleName . ':' . $modBean->beanId;

                if (!isset($consolidatedBeans[$key])) {
                    // Primera vez que lo tocamos
                    $consolidatedBeans[$key] = [
                        'module' => $modBean->moduleName,
                        'id' => $modBean->beanId,
                        'type' => $modBean->modificationType, // (CREATED, UPDATED, ENRICHED, SKIPPED)
                        'data' => $modBean->submittedData
                    ];
                } else {
                    // Ya existe, hacemos fusión
                    $currentEntry = &$consolidatedBeans[$key];

                    // Fusión del tipo de acción realizada. Prioridad: CREATED > UPDATED > ENRICHED
                    if ($modBean->modificationType === BeanModificationType::CREATED) {
                        $currentEntry['type'] = BeanModificationType::CREATED;
                    } elseif ($currentEntry['type'] !== BeanModificationType::CREATED && $modBean->modificationType === BeanModificationType::UPDATED) {
                        $currentEntry['type'] = BeanModificationType::UPDATED;
                    } elseif ($currentEntry['type'] === BeanModificationType::SKIPPED && $modBean->modificationType !== BeanModificationType::SKIPPED) {
                        // Si estaba como SKIPPED y ahora es otra acción, lo actualizamos
                        $currentEntry['type'] = $modBean->modificationType;
                    }

                    // Fusión de los datos tocados: Acumulamos los campos (no SKIPPED)
                    if ($modBean->modificationType !== BeanModificationType::SKIPPED && !empty($modBean->submittedData)) {
                        $currentEntry['data'] = array_merge($currentEntry['data'], $modBean->submittedData);
                    }
                }
            }
        }

        // Guardamos los links
        $sequence = 1;
        foreach ($consolidatedBeans as $item) {
            $linkBean = BeanFactory::newBean('stic_Advanced_Web_Forms_Links');

            $targetBean = BeanFactory::getBean($item['module'], $item['id']);
            $targetBeanName = $targetBean ? $targetBean->get_summary_text() : $item['id'];
            
            $actionValue = $item['type']->value;
            $recordActionName = $app_list_strings['stic_advanced_web_forms_links_record_action_list'][$actionValue] ?? $actionValue;
            $moduleSingular = $app_list_strings['moduleListSingular'][$item['module']] ?? $item['module'];

            $linkBean->is_automated_save = true;
            // Formato del nombre del link: "NombreFormulario - Módulo: NombreRegistro (Creado)"
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

            // Vinculamos el Link a la Response
            $responseBean->stic_1c31forms_links->add($linkBean->id);
        }
    }

    /**
     * Valida los datos de la sumisión contra la configuración del formulario.
     * @param FormConfig $config Configuración del formulario
     * @param array $data Datos recibidos en la sumisión
     * @return ?array Lista de errores o null si no hay errores
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

                // Validación de campo obligado (Required)
                if ($field->required_in_form) {
                    if ($value === null || $value === '' || (is_array($value) && empty($value))) {
                        $errors[] = "Field '{$field->label}' is required.";
                        continue;
                    }
                }

                // Validación de tipo de datos (Sanity Check)
                if ($value !== null && $value !== '') {
                    if ($field->type_in_form === 'number') {
                        if (!is_numeric($value)) {
                            $errors[] = "Field value '{$field->label}' is not a valid number.";
                        }
                    }
                    if ($field->type_in_form === 'date') {
                        if (!strtotime($value)) {
                            $errors[] = "Field value '{$field->label}' is not a valid date.";
                        }
                    }
                    if ($field->subtype_in_form === 'text_email') {
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[] = "Field value '{$field->label}' is not a valid email.";
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
                                $errors[] = "The value '{$subVal}' is not valid for field '{$field->label}'.";
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
     * Genera las respuestas analíticas para su almacenamiento y análisis posterior.
     * @param SugarBean $responseBean Bean de la respuesta
     * @param FormConfig $formConfig Configuración del formulario
     * @param array $submittedData Datos enviados en la sumisión
     */
    private function generateAnalyticsAnswers(SugarBean $responseBean, FormConfig $formConfig, array $submittedData): void {
        global $app_strings;

        foreach ($formConfig->data_blocks as $block) {
            foreach ($block->fields as $field) {
                // Saltamos los campos fijos
                if ($field->type_field === DataBlockFieldType::FIXED) continue;

                // Clave del input
                $prefix = ($field->type_field === DataBlockFieldType::UNLINKED) ? '_detached_' : '';
                $inputKey = $prefix . $block->name . '_' . $field->name;
                
                $rawValue = $submittedData[$inputKey] ?? null;
                
                // Calculamos el texto legible y el valor a almacenar
                $readableText = $rawValue;
                $storedValue = $rawValue;
                
                // Campos de tipo lista (select, multiselect, radio)
                if (!empty($field->value_options)) {
                    if (is_array($rawValue)) {
                        // Multiselección
                        $labels = [];
                        foreach ($rawValue as $valItem) {
                            $opt = $this->findOption($field->value_options, $valItem);
                            $labels[] = $opt ? $opt->text : $valItem;
                        }
                        $storedValue = json_encode($rawValue, JSON_UNESCAPED_UNICODE); // Guardamos JSON ["A","B"]
                        $readableText = implode(', ', $labels); // Text: "Opción A, Opción B"
                    } else {
                        // Selección única
                        $opt = $this->findOption($field->value_options, $rawValue);
                        if ($opt) {
                            $readableText = $opt->text;
                        }
                    }
                } 
                // Campos booleanos (checkbox)
                elseif ($field->type === 'bool' || $field->type === 'checkbox') {
                    $isTrue = ($rawValue === '1' || $rawValue === 'on' || $rawValue === 'true' || $rawValue === true);
                    $readableText = $isTrue ? $app_strings['LBL_YES'] : $app_strings['LBL_NO'];
                    $storedValue = $isTrue ? '1' : '0';
                }
                // Arrays genéricos que no son listas
                elseif (is_array($rawValue)) {
                    $storedValue = json_encode($rawValue, JSON_UNESCAPED_UNICODE);
                    $readableText = 'Array'; 
                }

                // Creamos el bean de la respuesta analítica
                $answerBean = BeanFactory::newBean('stic_Advanced_Web_Forms_Answers');
                $answerBean->response_id = $responseBean->id;
                $answerBean->form_id = $formConfig->id ?? ''; 
                
                $answerBean->question_key = $block->name . '.' . $field->name;
                $answerBean->question_label = $field->label ?? $field->text_original ?? $field->name;
                $answerBean->question_section = $block->text; 
                
                $answerBean->answer_value = (string)$storedValue;
                $answerBean->answer_text = (string)$readableText;
                $answerBean->answer_type = $field->type_in_form;
                
                // Para facilitar análisis, guardamos el valor numérico si aplica
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
     * Función que limpia recursivamente el input para evitar XSS
     * Elimina tags HTML peligrosos y espacios sobrantes
     */
    private function sanitizeInput($input) {
        if (is_array($input)) {
            // Si es un array, limpiamos cada uno de los elementos, también la clave
            $clean = [];
            foreach ($input as $key => $value) {
                $clean[strip_tags($key)] = $this->sanitizeInput($value);
            }
            return $clean;
        }
        
        // Si es un bool, null o numérico puro lo dejamos pasar
        if (is_bool($input) || is_null($input) || is_numeric($input)) {
            return $input;
        }

        // Limpiamos el input
        return strip_tags(trim((string)$input));
    }    
}

// Ejecución del Handler
$handler = new AWF_ResponseHandler();
$handler->run();