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

include_once "modules/stic_Advanced_Web_Forms/actions/coreActions.php";

/**
 * SendEmailToAssignedAction
 *
 * Envia un correo al USUARIO ASIGNADO de un registro.
 * El registro origen puede ser el formulario, un Bloque de Datos (recién creado) o un Registro Fijo (ej: el Evento).
 */
class SendEmailToAssignedAction extends HookActionDefinition {

    public function __construct() {
        $this->isActive = true;
        $this->isUserSelectable = true;
        $this->isCommon = true;
        $this->category = 'communication';
        $this->baseLabel = 'LBL_SEND_EMAIL_TO_ASSIGNED_ACTION';
    }

    /**
     * Retorna los parámetros definidos para la acción
     * @return ActionParameterDefinition[] Los parámetros de la acción
     */
    public function getParameters(): array {
        // --- El Origen del Responsable (SELECTOR) ---
        $paramSource = new ActionParameterDefinition();
        $paramSource->name = 'record_source_selector';
        $paramSource->text = $this->translate('SOURCE_TEXT');
        $paramSource->description = $this->translate('SOURCE_DESC');
        $paramSource->type = ActionParameterType::OPTION_SELECTOR;
        $paramSource->required = true;

        // Opción A: El responsable del Formulario
        $optFormOwner = new ActionSelectorOptionDefinition();
        $optFormOwner->name = 'opt_form_owner';
        $optFormOwner->text = $this->translate('OPT_OWNER_TEXT');
        $optFormOwner->resolvedType = ActionParameterType::EMPTY; // No requiere valor adicional
        
        // Opción B: Un Bloque de datos (ej: El asignado del Contacto creado)
        $optBlock = new ActionSelectorOptionDefinition();
        $optBlock->name = 'opt_datablock';
        $optBlock->text = $this->translate('OPT_DATABLOCK_TEXT');
        $optBlock->resolvedType = ActionParameterType::DATA_BLOCK;

        // Opción C: Un Registro Fijo (ej: El asignado del Evento X)
        $optRecord = new ActionSelectorOptionDefinition();
        $optRecord->name = 'opt_record';
        $optRecord->text = $this->translate('OPT_RECORD_TEXT');
        $optRecord->resolvedType = ActionParameterType::CRM_RECORD;
        
        // Opción D: Un Campo Relacionado (ej: El asignado del registro seleccionado en un campo Relate)
        $optField = new ActionSelectorOptionDefinition();
        $optField->name = 'opt_field';
        $optField->text = $this->translate('OPT_RELATE_TEXT');
        $optField->resolvedType = ActionParameterType::FIELD;
        $optField->supportedDataTypes = [ActionDataType::RELATE];

        $paramSource->selectorOptions = [$optFormOwner, $optBlock, $optRecord, $optField];

        // --- La Plantilla de Email ---
        $paramTemplate = new ActionParameterDefinition();
        $paramTemplate->name = 'email_template';
        $paramTemplate->text = $this->translate('TEMPLATE_TEXT');
        $paramTemplate->description = $this->translate('TEMPLATE_DESC'); 
        $paramTemplate->type = ActionParameterType::CRM_RECORD;
        $paramTemplate->supportedModules = ['EmailTemplates'];
        $paramTemplate->required = true;

        return [$paramSource, $paramTemplate];
    }

    /**
     * Ejecuta la acción definida por esta definición.
     *
     * @param ExecutionContext $context Contexto de ejecución de la acción
     * @param FormAction $actionConfig Configuración de la acción del formulario
     * @return ActionResult Resultado de la ejecución de la acción
     */
    public function execute(ExecutionContext $context, FormAction $actionConfig): ActionResult {
        /** @var ?BeanReference $templateRef */
        $templateRef = $actionConfig->getResolvedParameter('email_template');
        if (!$templateRef) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Email template parameter is missing.");
        }

        // Usuario asignado a notificar
        $assignedUserId = null;
        $sourceBean = null;

        /** @var ?OptionSelectorResolved $selector */
        $selector = $actionConfig->getResolvedParameter('record_source_selector');
        if ($selector) {
            if ($selector->selectedOptionName === 'opt_form_owner') {
                // Opción A: El responsable del Formulario
                $sourceBean = BeanFactory::getBean('stic_Advanced_Web_Forms', $context->formId);

            } else if ($selector->selectedOptionName === 'opt_datablock') {
                // Opción B: Un Bloque de datos (ej: El asignado del Contacto creado)
                /** @var DataBlockResolved $block */
                $block = $selector->resolvedValue;
                $sourceBean = $block->dataBlock->getBeanReference()?->getBean();
               
            } else if ($selector->selectedOptionName === 'opt_record') {
                // Opción C: Un Registro Fijo (ej: El asignado del Evento X)
                /** @var BeanReference $recordRef */
                $recordRef = $selector->resolvedValue;
                $sourceBean = $recordRef?->getBean();
                
            } else if ($selector->selectedOptionName === 'opt_field') {
                // Opción D: Un Campo Relacionado (ej: El asignado del registro seleccionado en un campo Relate)
                /** @var DataBlockFieldResolved $fieldResolved */
                $fieldResolved = $selector->resolvedValue;
                /** @var BeanReference $recordRef */
                $recordRef = new BeanReference($fieldResolved->dataBlockField?->related_module, $fieldResolved->value);
                $sourceBean = $recordRef?->getBean();
            }
        }
        if ($sourceBean) {
            $assignedUserId = $sourceBean->assigned_user_id ?? null;
        }

        if (empty($assignedUserId) || !$sourceBean) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Can not determine assigned user or source record.");
        }

        // Obtenemos el email del Bean.
        $user = BeanFactory::retrieveBean('Users', $assignedUserId);
        if (!$user) {
             return new ActionResult(ResultStatus::ERROR, $actionConfig, "Assigned user not found (ID: {$assignedUserId}).");
        }
        $emailAddress = $user->email1; 
        if (empty($emailAddress)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Assigned user '{$user->user_name}' does not have a valid 'email1' field.");
        }

        // Enviamos el email
        try {
            AWF_Utils::sendTemplateEmail($emailAddress, $templateRef->beanId, $user, $context, $sourceBean);
        } catch (\Exception $e) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, $e->getMessage());
        }

        return new ActionResult(ResultStatus::OK, $actionConfig, "Email sent to: {$user->user_name} ({$emailAddress})");
    }
}