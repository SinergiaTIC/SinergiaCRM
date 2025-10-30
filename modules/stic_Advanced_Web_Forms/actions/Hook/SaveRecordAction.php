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

class SaveRecordAction extends HookActionDefinition {
    public function __construct() {
        $this->baseLabel = 'LBL_SAVE_RECORD_ACTION';
    }

    /**
     * Retorna los parámetros definidos para la acción
     * @return ActionParameterDefinition[] Los parámetros de la acción
     */
    public function getParameters(): array {
        return [
            // Bloque de datos
            new ActionParameterDefinition(
                name: 'dataBlock',
                text: $this->translate('PARAM_DATABLOCK_TEXT'),
                description: $this->translate('PARAM_DATABLOCK_DESCRIPTION'),
                type: ActionParameterType::DATA_BLOCK,
                required: true,
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
