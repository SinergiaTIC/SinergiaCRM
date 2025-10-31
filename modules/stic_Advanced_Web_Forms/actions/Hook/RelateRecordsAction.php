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

include_once "modules/stic_Advanced_Web_Forms/actions/CoreActions.php";
class RelateRecordsAction extends HookActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->baseLabel = 'LBL_RELATE_RECORDS_ACTION';
    }

    /**
     * Retorna los parámetros definidos para la acción
     * @return ActionParameterDefinition[] Los parámetros de la acción
     */
    public function getParameters(): array {
        return [
            // Plantilla de email
            new ActionParameterDefinition(
                name: 'templateId',
                text: $this->translate('PARAM_TEMPLATEID_TEXT'),
                description: $this->translate('PARAM_TEMPLATEID_DESCRIPTION'),
                type: ActionParameterType::CRM_RECORD,
                supportedModules: ['EmailTemplates'],
                required: true,
            ),
            // Bloque de datos base para las variables de la plantilla
            new ActionParameterDefinition(
                name: 'baseDataBlock',
                text: $this->translate('PARAM_BASEDATABLOCK_TEXT'),
                description: $this->translate('PARAM_BASEDATABLOCK_DESCRIPTION'),
                type: ActionParameterType::DATA_BLOCK,
                required: false,
            ),
            // Destinatario del email
            new ActionParameterDefinition(
                name: 'recipientSource',
                text: $this->translate('PARAM_RECIPIENTSOURCE_TEXT'),
                description: $this->translate('PARAM_RECIPIENTSOURCE_DESCRIPTION'),
                type: ActionParameterType::OBJECT_SELECTOR,
                required: true,
                selectorOptions: [
                    // Opción 1: Campo definido en el formulario
                    new ActionSelectorOptionDefinition(
                        name: 'field',
                        text: $this->translate('PARAM_RECIPIENTSOURCE_OPT_FIELD_TEXT'),
                        resolvedType: ActionParameterType::FIELD,
                        supportedDataTypes: [ActionDataType::EMAIL, ActionDataType::TEXT]
                    ),
                    // Opción 2: El email de un bloque de datos (Accounts/Contacts/Leads/Users)
                    new ActionSelectorOptionDefinition(
                        name: 'dataBlock',
                        text: $this->translate('PARAM_RECIPIENTSOURCE_OPT_DATABLOCK_TEXT'),
                        resolvedType: ActionParameterType::DATA_BLOCK,
                        supportedModules: ['Accounts', 'Contacts', 'Leads', 'Users'],
                    ),
                    // Opción 3: Email fijo
                    new ActionSelectorOptionDefinition(
                        name: 'fixed',
                        text: $this->translate('PARAM_RECIPIENTSOURCE_OPT_FIXED_TEXT'),
                        resolvedType: ActionParameterType::VALUE,
                        supportedDataTypes: [ActionDataType::EMAIL],
                    ),
                    // Opción 4: Email de un registro fijo del CRM
                    new ActionSelectorOptionDefinition(
                        name: 'beanId',
                        text: $this->translate('PARAM_RECIPIENTSOURCE_OPT_BEANID_TEXT'),
                        resolvedType: ActionParameterType::CRM_RECORD,
                        supportedModules: ['Accounts', 'Contacts', 'Leads', 'Users'],
                    ),
                    // Opción 5: Campo relacionado con un módulo válido (Accounts/Contacts/Leads/Users)
                    new ActionSelectorOptionDefinition(
                        name: 'relatedField',
                        text: $this->translate('PARAM_RECIPIENTSOURCE_OPT_RELATEDFIELD_TEXT'),
                        resolvedType: ActionParameterType::FIELD,
                        supportedDataTypes: [ActionDataType::RELATE],
                        supportedModules: ['Accounts', 'Contacts', 'Leads', 'Users'],
                    ),
                ],
            ),
        ];
    }

    /**
     * Ejecuta la acción definida por esta definición.
     *
     * @param ExecutionContext $context Contexto de ejecución de la acción
     * @param FormAction $actionConfig Configuración de la acción del formulario
     * @return ActionResult Resultado de la ejecución de la acción
     */
    public function execute(ExecutionContext $context, FormAction $actionConfig): ActionResult {
        // Lógica para enviar el correo electrónico
        
        $params = $actionConfig->parameters;
        $templateId = $params['templateId']?->value;
        $baseDataBlock = $params['baseDataBlock']?->value;
        $recipientSource = $params['recipientSource'] ?? null;
        // IEPA!!
        // Json: modificar per saber quina opció és i a què correspon el valor reals


        // Validar y procesar los parámetros para enviar el correo electrónico
        // Aquí se implementaría la lógica para enviar el correo utilizando los parámetros obtenidos.

        return new ActionResult(ResultStatus::OK, $actionConfig, "Email sent successfully.");
    }
}