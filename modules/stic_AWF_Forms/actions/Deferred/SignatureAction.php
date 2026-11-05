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

require_once 'modules/stic_Signatures/SignatureSignersManager.php';

/**
 * SignatureAction
 * Deferred action that adds the user as a signer to a signature process
 * and redirects to the signature portal.
 * Follows the same pattern as EmailConfirmationAction (PR #1143).
 */
class SignatureAction extends DeferredBeanActionDefinition implements ITerminalAction
{
    public function __construct() {
        $this->isActive = true;
        $this->isUserSelectable = true;
        $this->category = 'integration';
        $this->baseLabel = 'LBL_SIGNATURE_ACTION';
        $this->defaultExpirationDays = '7';
    }

    /**
     * Declares who will resume this deferred process and how.
     */
    public function getResumptionContext(): DeferredResumptionContext
    {
        return DeferredResumptionContext::ORIGINAL_USER;
    }

    /**
     * Modules supported by the action
     */
    protected function getSupportedModules(): array {
        return []; // All modules: the action works with any module that has signature processes
    }

    /**
     * Name of the parameter that contains the data block.
     * @return string
     */
    protected function getDataBlockParameterText(): string {
        return $this->translate('RECIPIENT_BLOCK_TEXT');
    }

    /**
     * The description (help text) of the data block parameter.
     * @return string
     */
    protected function getDataBlockParameterDescription(): string {
        return $this->translate('RECIPIENT_BLOCK_DESC');
    }

    /**
     * Definition of the ADDITIONAL parameters needed for the deferred action
     */
    protected function getDeferredCustomParameters(): array
    {
        $paramSignature = new ActionParameterDefinition();
        $paramSignature->name = 'signature_process';
        $paramSignature->text = $this->translate('SIGNATURE_PROCESS_TEXT');
        $paramSignature->description = $this->translate('SIGNATURE_PROCESS_DESC');
        $paramSignature->type = ActionParameterType::CRM_RECORD;
        $paramSignature->supportedModules = ['stic_Signatures'];
        $paramSignature->required = true;

        // Delivery mode: redirect to portal or send the signing email to the signer
        $paramDelivery = new ActionParameterDefinition();
        $paramDelivery->name = 'signature_delivery';
        $paramDelivery->text = $this->translate('DELIVERY_TEXT');
        $paramDelivery->description = $this->translate('DELIVERY_DESC');
        $paramDelivery->type = ActionParameterType::VALUE;
        $paramDelivery->dataType = ActionDataType::SELECT;
        $paramDelivery->required = true;
        $paramDelivery->defaultValue = 'portal';
        $paramDelivery->options = [
            new ActionParameterOption('portal', $this->translate('DELIVERY_PORTAL_TEXT')),
            new ActionParameterOption('email', $this->translate('DELIVERY_EMAIL_TEXT')),
        ];

        return [$paramSignature, $paramDelivery];
    }

    /**
     * Executes the action, receives the loaded bean and the main data block with the form data
     *
     * @param ExecutionContext $context The global context.
     * @param FormAction $actionConfig The configuration of the action.
     * @param SugarBean $bean The bean loaded from the DB (saved data).
     * @param DataBlockResolved $block The data block (form data).
     * @return ActionResult
     */
    public function executeWithBean(ExecutionContext $context, FormAction $actionConfig, SugarBean $bean, DataBlockResolved $block): ActionResult
    {
        /** @var BeanReference $signatureRef */
        $signatureRef = $actionConfig->getResolvedParameter('signature_process');
        if (empty($signatureRef)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Signature process parameter is missing.");
        }

        // Resolve the correct bean based on the signature process's main module.
        // If the current data block's module does not match the signature's main_module,
        // look for another data block in the form with the matching module.
        $signatureBean = BeanFactory::getBean('stic_Signatures', $signatureRef->beanId);
        if ($signatureBean && !empty($signatureBean->id) && $signatureBean->main_module !== $bean->module_name) {
            $targetModule = $signatureBean->main_module;
            $matchingBlock = null;
            foreach ($context->formConfig->data_blocks as $db) {
                if ($db->module === $targetModule) {
                    $matchingBlock = $db;
                    break;
                }
            }
            if ($matchingBlock !== null) {
                $beanRef = $matchingBlock->getBeanReference();
                if ($beanRef !== null) {
                    $matchedBean = BeanFactory::getBean($beanRef->moduleName, $beanRef->beanId);
                    if ($matchedBean && !empty($matchedBean->id)) {
                        $bean = $matchedBean;
                    }
                }
            }
            if ($signatureBean->main_module !== $bean->module_name) {
                return new ActionResult(ResultStatus::ERROR, $actionConfig, "The selected signature process is for module '{$signatureBean->main_module}'. No data block of that module was found in the form. Please add a data block for that module or select a matching signature process.");
            }
        }

        // Guard: a signature process without a "signer_path" cannot derive the signer;
        // return a clear error instead of a cryptic one.
        if ($signatureBean && !empty($signatureBean->id) && empty(trim((string) ($signatureBean->signer_path ?? '')))) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "El proceso de firma '{$signatureBean->name}' no tiene configurada la Ruta del firmante. Edite el proceso de firma e indique cómo obtener el firmante a partir del registro (p. ej., Persona vinculada al pago).");
        }

        // Derive the signer path from the signature: "SignerModule:field_name" or just "Module"
        // (self-signer, e.g. Contacts signatures where the record itself is the signer).
        $signerPathFull = (string) ($signatureBean->signer_path ?? '');
        $signerModule = $signerPathFull;
        $signerField = $signerPathFull;
        if (str_contains($signerPathFull, ':')) {
            [$signerModule, $signerField] = explode(':', $signerPathFull, 2);
        }

        // For non-person source modules the signer is a linked Contact. If the bean has no
        // linked signer yet, link the Contact/User from a data block of the form.
        $selfSigner = ($signerModule === $signerField && $signerModule === $bean->module_name);
        $beanContactId = null;
        if (!$selfSigner && !empty($signerField) && !empty($bean->$signerField ?? null)) {
            $beanContactId = $bean->$signerField;
        }

        if (!$selfSigner && empty($beanContactId) && !empty($signerField)) {
            foreach ($context->formConfig->data_blocks as $db) {
                if ($db->module !== $signerModule) {
                    continue;
                }
                $beanRef = $db->getBeanReference();
                if ($beanRef === null) {
                    continue;
                }
                $linkedBean = BeanFactory::getBean($beanRef->moduleName, $beanRef->beanId);
                if ($linkedBean && !empty($linkedBean->id) && !empty($linkedBean->email1 ?? null)) {
                    $bean->$signerField = $linkedBean->id;
                    $bean->save();
                    $beanContactId = $linkedBean->id;
                    break;
                }
            }
        }

        // Get signer email: from the bean itself (self-signer), from the linked signer, or
        // as a last resort from a Contact/User data block in the form.
        $emailAddress = $bean->email1 ?? null;
        if (empty($emailAddress)) {
            require_once 'modules/stic_Signatures/Utils.php';
            $signers = stic_SignaturesUtils::getSignatureSigners($signatureRef->beanId, [$bean->id]);
            if (!empty($signers)) {
                $firstSigner = reset($signers);
                $emailAddress = $firstSigner['email'] ?? null;
            }
        }
        if (empty($emailAddress)) {
            foreach ($context->formConfig->data_blocks as $db) {
                if (in_array($db->module, ['Contacts', 'Users'])) {
                    $beanRef = $db->getBeanReference();
                    if ($beanRef !== null) {
                        $linkedBean = BeanFactory::getBean($beanRef->moduleName, $beanRef->beanId);
                        if ($linkedBean && !empty($linkedBean->email1)) {
                            $emailAddress = $linkedBean->email1;
                            break;
                        }
                    }
                }
            }
        }
        if (empty($emailAddress)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Bean '{$bean->name}' does not have an email address.");
        }

        // Create a deferred ticket
        $ticket = BeanFactory::newBean('stic_AWF_Deferred_Tickets');
        $ticket->id = create_guid(); // Set Id for the ContextData
        $ticket->new_with_id = true;
        $ticket->name = 'Signature: ' . $bean->name . ' - ' . date('Y-m-d H:i:s');
        $ticket->stic_awf_responses_id_c = $context->responseId;
        $ticket->token_hash = bin2hex(random_bytes(32));
        $ticket->status = 'pending';
        $ticket->handler_action_id = $actionConfig->id;

        // Set the expiration date
        $days = (int)$actionConfig->getResolvedParameter('expiration_days', 7);
        $ticket->expiration_date = date('Y-m-d H:i:s', strtotime("+{$days} days"));

        // Set the context data for the deferred flow
        $contextData = DeferredContextData::createSnapshot(
            self::class, $ticket, $actionConfig, $bean, $context,
            [
                'signature_id' => $signatureRef->beanId,
                'target_id' => $bean->id,
                'target_module' => $bean->module_name,
                'email' => $emailAddress,
            ]
        );
        $ticket->context_data = $contextData->toJson();

        // Save the ticket
        $ticket->save();

        // Add the user as a signer to the signature process
        $currentUserId = $context->currentUserId ?? $bean->assigned_user_id ?? '1';
        $signerResult = SignatureSignersManager::addSignersToSignature(
            $signatureRef->beanId,
            $bean->module_name,
            [$bean->id],
            $currentUserId
        );

        // Detect the "signer already attached" case (e.g. resubmission of the form):
        // nothing added (ko>0, ok==0) but signers already exist for this
        // signature+record → it is not an error: continue with the flow.
        $alreadyAttached = false;
        $existingSignerIds = [];
        if (empty($signerResult['ok']) && !empty($signerResult['ko'])) {
            $db = DBManagerFactory::getInstance();
            $sigIdQ = $db->quote($signatureRef->beanId);
            $recIdQ = $db->quote($bean->id);
            $resCount = $db->query("SELECT s.id FROM stic_signers s
                                    JOIN stic_signatures_stic_signers_c rel
                                      ON rel.stic_signatures_stic_signersstic_signers_idb = s.id AND rel.deleted = 0
                                    WHERE rel.stic_signatures_stic_signersstic_signatures_ida = '{$sigIdQ}'
                                      AND s.record_id = '{$recIdQ}'
                                      AND s.deleted = 0");
            while ($row = $db->fetchByAssoc($resCount)) {
                $existingSignerIds[] = $row['id'];
            }
            $alreadyAttached = !empty($existingSignerIds);
            if ($alreadyAttached) {
                $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": Signer(s) already attached to signature {$signatureRef->beanId} for record {$bean->id} (" . implode(', ', $existingSignerIds) . "). Continuing.");
            }
        }

        if ((empty($signerResult['ok']) && empty($alreadyAttached)) || ($signerResult['ok'] === 0 && !$alreadyAttached)) {
            $errorMsg = !empty($signerResult['errors']) ? implode(', ', $signerResult['errors']) : 'No signer could be added (check that the record has a linked signer and the signature process is configured correctly).';
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Failed to add signer to signature {$signatureRef->beanId}: {$errorMsg}");
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Failed to add signer: {$errorMsg}");
        }

        // Delivery mode: 'portal' (redirect, default) or 'email' (send signing email)
        $delivery = $actionConfig->getResolvedParameter('signature_delivery') ?? 'portal';
        if ($delivery === 'email') {
            require_once 'modules/stic_Signers/Utils.php';

            $signerIds = !empty($signerResult['created_signer_ids'])
                ? $signerResult['created_signer_ids']
                : $existingSignerIds;

            $sentCount = 0;
            $failCount = 0;
            foreach ($signerIds as $signerId) {
                $sent = stic_SignersUtils::sendToSign($signerId, false, $ticket->token_hash);
                if ($sent) {
                    $sentCount++;
                } else {
                    $failCount++;
                    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Failed to send signing email to signer {$signerId}.");
                }
            }

            if ($sentCount === 0) {
                return new ActionResult(ResultStatus::ERROR, $actionConfig, "La firma se ha preparado ({$sentCount} email(s) enviados, {$failCount} errores), pero no se ha podido enviar el email a ningún firmante. Comprueba la cuenta de correo saliente y que los firmantes tengan email.");
            }

            // Continue the flow: the deferred ticket waits until the signer
            // completes the signature (ticket resolved by the signing portal).
            return new ActionResult(ResultStatus::OK, $actionConfig, "Email de firma enviado a {$sentCount} firmante(s)" . ($failCount > 0 ? " ({$failCount} errores al enviar)" : '') . ". El flujo se reanudará cuando se complete la firma.");
        }

        // Generate the redirect URL to the signature portal
        global $sugar_config;
        $siteUrl = rtrim($sugar_config['site_url'] ?? '', '/');
        $redirectUrl = $siteUrl . '/index.php?entryPoint=sticSign'
            . '&signatureId=' . urlencode($signatureRef->beanId)
            . '&targetId=' . urlencode($bean->id)
            . '&returnToken=' . urlencode($ticket->token_hash);

        // Return a WAIT result to halt the flow and redirect to signature portal
        return new ActionResult(ResultStatus::WAIT, $actionConfig, "Redirecting to signature portal")
            ->setData(['redirect_url' => $redirectUrl]);
    }

    /**
     * Redirects the user to the signature portal.
     * Only called if the action returned WAIT.
     *
     * @param ExecutionContext $context Execution context of the action
     * @param ActionResult $executionResult Result of the execution of the action
     */
    public function performTerminal(ExecutionContext $context, ActionResult $executionResult): void
    {
        if (!$executionResult->isWait()) {
            return;
        }

        $data = $executionResult->getData();
        $url = $data['redirect_url'] ?? '';

        if (!empty($url)) {
            header("Location: " . $url);
            exit;
        }
    }

    /**
     * Processes an incoming request (webhook) from an external service.
     * Not applicable for SignatureAction - the flow resumes via the signature portal redirect.
     *
     * @param ExecutionContext $context The global context.
     * @param array $requestData The data of the incoming request.
     * @return ActionResult Result of the execution of the action.
     */
    public function processWebhook(ExecutionContext $context, array $requestData): ActionResult
    {
        return new ActionResult(ResultStatus::ERROR, null, "SignatureAction does not support webhooks.");
    }
}
