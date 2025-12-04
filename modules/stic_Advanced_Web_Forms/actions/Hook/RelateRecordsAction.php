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
 * RelateRecordsAction
 *
 * Acción que establece una relación entre dos Bloques de Datos en la Base de Datos.
 */
class RelateRecordsAction extends HookBeanActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->isUserSelectable = false;
        $this->isCommon = true;
        $this->category = 'data';
        $this->baseLabel = 'LBL_RELATE_RECORDS_ACTION';
    }

    /**
     * getCustomParameters()
     * Definición de los parámetroes ADICIONALES que son necesarios para la acción
     * El parámtreo del Bloque de Datos principal lo pide la clase padre.
     */
    protected function getCustomParameters(): array
    {
        // El Bloque de Datos Destino (Obligatorio)
        $paramTargetBlock = new ActionParameterDefinition();
        $paramTargetBlock->name = 'target_data_block';
        $paramTargetBlock->text = $this->translate('TARGET_BLOCK_TEXT');
        $paramTargetBlock->description = $this->translate('TARGET_BLOCK_DESC');
        $paramTargetBlock->type = ActionParameterType::DATA_BLOCK; 
        $paramTargetBlock->required = true;

        // El Campo a Actualizar (que apunta al Bloque de datos destino)
        $paramFieldName = new ActionParameterDefinition();
        $paramFieldName->name = 'field_to_update';
        $paramFieldName->text = $this->translate('FIELD_TO_UPDATE_TEXT');
        $paramFieldName->description = $this->translate('FIELD_TO_UPDATE_DESC');
        $paramFieldName->type = ActionParameterType::FIELD; 
        $paramFieldName->dataType = ActionDataType::TEXT; 
        $paramFieldName->required = true;

        return [$paramTargetBlock, $paramFieldName];
    }


    public function executeWithBean(ExecutionContext $context, FormAction $actionConfig, SugarBean $bean, DataBlockResolved $block): ActionResult
    {
        // Obtención de los parámetros adicionales (ParameterResolver asegura que no sean nulos porque son obligatorios)

        /** @var DataBlockFieldResolved $fieldResolved */
        $fieldResolved = $actionConfig->getResolvedParameter('field_to_update');

        /** @var DataBlockResolved $targetBlock */
        $targetBlock = $actionConfig->getResolvedParameter('target_data_block');

        // Validación del campo a actualizar
        $fieldName = $fieldResolved->fieldName;
        if (empty($fieldName)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Field to update is not specified.");
        }

        // Obtención de la referencia al Bean destino
        $targetBeanRef = $targetBlock->dataBlock->getBeanReference();
        if ($targetBeanRef === null) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Destination data block '{$targetBlock->dataBlock->name}' not saved in database.");
        }

        // Asignación (lógica principal)
        $targetBeanId = $targetBeanRef->beanId;
        if ($bean->{$fieldName} !== $targetBeanId) {
            $bean->{$fieldName} = $targetBeanId;
            $bean->save();
            $modificationType = BeanModificationType::UPDATED;
        } else {
            $modificationType = BeanModificationType::SKIPPED;
        }

        // Notificación del resultado
        $actionResult = new ActionResult(ResultStatus::OK, $actionConfig, "Relationship saved: {$bean->module_name}.{$fieldName} = {$targetBeanId}");
        $dataToLog = [$fieldName => $targetBeanId];
        $actionResult->registerBeanModificationFromBlock($bean, $block, $modificationType, $dataToLog);

        return $actionResult;
    }
}