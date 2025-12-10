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
    public function run()
    {
        // Obtención de datos y saneamiento
        $formId = $_POST['id'] ?? null;
        $rawPostData = $_POST;

        unset($rawPostData['module']);
        unset($rawPostData['action']);
        unset($rawPostData['entryPoint']);
        unset($rawPostData['id']);

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
        $formUrl = $_SERVER['HTTP_REFERER'] ?? '';
        $payloadJson = json_encode($rawPostData);

        $fingerprintString = $payloadJson . $formId . $remoteIp . $userAgent . $formUrl;
        $responseHash = md5($fingerprintString);

        if ($this->checkDuplicateSubmission($formId, $responseHash)) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": ResponseHandler: Duplicated response detected");
            $this->handleDuplicateError($formBean);
            return;
        }

        // Buscamos el estado de la respuesta
        $isPublic = ($formBean->status === 'public');
        if ($isPublic) {
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
        $responseBean->name = translate('LBL_RESPONSE_PREFIX_NAME', 'stic_Advanced_Web_Forms_Responses'). " ". date('Y-m-d H:i:s');
        $responseBean->form_id = $formBean->id;
        $responseBean->status = $responseStatus;
        $responseBean->raw_payload = $payloadJson;
        $responseBean->response_hash = $responseHash;
        $responseBean->remote_ip = $remoteIp;
        $responseBean->form_url = $formUrl;
        $responseBean->user_agent = $userAgent;
        $responseBean->description = $responseDescription;
        $responseBean->save();

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

        // Solo los formularios 'public' procesan las respuestas
        if (!$isPublic) {
            $title = $formConfig->layout->closed_form_title ?? translate('LBL_THEME_CLOSED_FORM_TITLE_VALUE', 'stic_Advanced_Web_Forms');
            $msg = $formConfig->layout->closed_form_text ?? translate('LBL_THEME_CLOSED_FORM_TEXT_VALUE', 'stic_Advanced_Web_Forms');
            $this->renderGenericResponse($formConfig, $title, $msg);
            return;
        }

        // Contexto de ejecución
        $context = new ExecutionContext($formBean->id, $responseBean->id, $rawPostData, $formConfig);
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
                    $this->executeTerminalAction($pendingTerminalAction, $context);
                } else {
                    $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Terminal action not found in Receipt flow in form. ID: $formId");
                    $this->renderGenericResponse($formConfig, 
                                                 translate('LBL_RECEIPT_GENERIC_TITLE', 'stic_Advanced_Web_Forms_Responses'), 
                                                 translate('LBL_RECEIPT_GENERIC_MSG', 'stic_Advanced_Web_Forms_Responses'));
                }
            } else {
                // Si no hay flujo de recibido, mostramos mensaje genérico
                $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Receipt flow not found in form. ID: $formId");
                $this->renderGenericResponse($formConfig, 
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
                
                // Guardamos los vínculos de trazabilidad (registros creados/modificados)                
                $this->saveLinks($responseBean, $context);

                // Actualizamos el estado
                $hasErrors = false;
                foreach ($context->actionResults as $result) {
                    if ($result->isError()) $hasErrors = true;
                }
                $responseBean->status = $hasErrors ? 'error' : 'processed';
                $responseBean->save();

                // Ejecutamos la acción terminal
                if ($pendingTerminalAction) {
                    $this->executeTerminalAction($pendingTerminalAction, $context);
                } else {
                    $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Terminal action not found in Main flow in form. ID: $formId");
                    if ($hasErrors) {
                        $this->renderGenericResponse($formConfig, "Error", "An error occurred while processing your request.");
                    } else {
                        $this->renderGenericResponse($formConfig, 
                            translate('LBL_MAIN_GENERIC_TITLE', 'stic_Advanced_Web_Forms_Responses'),
                            translate('LBL_MAIN_GENERIC_MSG', 'stic_Advanced_Web_Forms_Responses')
                        );
                    }
                }
            } else {
                // Si no hay flujo principal, mostramos mensaje genérico
                $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Main flow not found in form. ID: $formId");
                $this->renderGenericResponse($formConfig, "Error", "Configuration Error: Main flow missing.");
            }
        }
    }

    // IEPA!!!
    /**
     * Executa l'acció terminal pendent.
     * Aquí es resolen els paràmetres (ja que no s'han resolt al executeFlow) i s'executa.
     * L'acció terminal pot fer 'echo' i 'exit' lliurement.
     */
    private function executeTerminalAction(FormAction $actionConfig, ExecutionContext $context) {
        $factory = new ServerActionFactory();
        $resolver = new ParameterResolverService();
        
        try {
            $actionExecutor = $factory->createAction($actionConfig);
            
            // Resolem paràmetres ara
            $paramDefinitions = $actionExecutor->getParameters();
            $resolvedParameters = $resolver->resolveAll($actionConfig, $paramDefinitions, $actionConfig->parameters, $context);
            $actionConfig->setResolvedParameters($resolvedParameters);
            
            // Executem. Aquest mètode probablement no retornarà perquè farà exit/redirect.
            $actionExecutor->execute($context, $actionConfig);
            
        } catch (\Exception $e) {
            $GLOBALS['log']->fatal("Terminal Action Failed: " . $e->getMessage());
            // Si falla l'acció terminal (ex: error PHP al generar el resum), intentem mostrar un error genèric
            if (!headers_sent()) {
                $this->renderGenericResponse($context->formConfig, "Error", "Error generating response page.");
            }
        }
    }

    /**
     * Renderitza una pàgina HTML senzilla utilitzant l'estil definit al Layout.
     */
    private function renderGenericResponse(FormConfig $config, string $title, string $message)
    {
        if (ob_get_length()) ob_clean();
        $theme = $config->layout->theme;
        
        echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<title>" . htmlspecialchars($title) . "</title>";
        echo "<style>
            body { font-family: {$theme->font_family}; background-color: {$theme->page_bg_color}; color: {$theme->text_color}; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
            .message-card { background-color: {$theme->form_bg_color}; padding: 40px; border-radius: {$theme->border_radius_container}px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); max-width: 600px; width: 90%; text-align: center; border: {$theme->border_width}px solid {$theme->border_color}; }
            h1 { color: {$theme->primary_color}; margin-bottom: 20px; }
            " . $config->layout->custom_css . "
        </style>";
        echo "</head><body>";
        echo "<div class='message-card'><h1>" . htmlspecialchars($title) . "</h1><div>" . nl2br(htmlspecialchars($message)) . "</div></div>";
        if (!empty($config->layout->custom_js)) { echo "<script>" . $config->layout->custom_js . "</script>"; }
        echo "</body></html>";
        
        sugar_cleanup(true);
    }

    private function terminateRawError($msg) {
        http_response_code(400);
        die("System Error: " . htmlspecialchars($msg));
    }

    private function checkDuplicateSubmission($formId, $hash) {
        $sq = new SugarQuery();
        $sq->select(['id']);
        $sq->from(BeanFactory::newBean('stic_Advanced_Web_Forms_Responses'));
        $sq->where()->equals('form_id', $formId)->equals('response_hash', $hash);
        return !empty($sq->execute());
    }

    private function handleDuplicateError($formBean) {
        $configData = json_decode(html_entity_decode($formBean->configuration), true);
        $formConfig = $configData ? FormConfig::fromJsonArray($configData) : null;
        if ($formConfig) {
            $this->renderGenericResponse($formConfig, "Error", "This response has already been submitted.");
        } else {
            $this->terminateRawError("This response has already been submitted.");
        }
    }

    private function saveLinks(SugarBean $responseBean, ExecutionContext $context) {
        $sequence = 1;
        foreach ($context->actionResults as $result) {
            foreach ($result->modifiedBeans as $modifiedBean) {
                $linkBean = BeanFactory::newBean('stic_Advanced_Web_Forms_Links');
                $linkBean->response_id = $responseBean->id;
                $linkBean->sequence_number = $sequence++;
                $linkBean->related_module = $modifiedBean->moduleName;
                $linkBean->related_id = $modifiedBean->beanId; 
                $linkBean->parent_type = $modifiedBean->moduleName; 
                $linkBean->record_action = $modifiedBean->modificationType->value;
                if (!empty($modifiedBean->submittedData)) {
                    $linkBean->submitted_data = json_encode($modifiedBean->submittedData, JSON_UNESCAPED_UNICODE);
                }
                $linkBean->save();
            }
        }
    }
}

// Ejecución del Handler
$handler = new AWF_ResponseHandler();
$handler->run();