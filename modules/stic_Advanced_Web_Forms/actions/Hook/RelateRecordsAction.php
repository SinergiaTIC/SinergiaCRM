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
        $this->isUserSelectable = false; // El usuario no puede seleccionar esta acción manualmente
        $this->isAutomatic = true;       // La acción se genera automáticamente por el sistema
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
        // Destino de la relación (Obligatorio): Puede ser un Bloque de Datos o un Id de Registro
        $paramTarget = new ActionParameterDefinition();
        $paramTarget->name = 'target_object';
        $paramTarget->text = $this->translate('TARGET_OBJECT_TEXT');
        $paramTarget->description = $this->translate('TARGET_OBJECT_DESC');
        $paramTarget->type = ActionParameterType::OPTION_SELECTOR; 
        $paramTarget->required = true;

        // El Bloque de Datos Destino
        $optTargetBlock = new ActionSelectorOptionDefinition();
        $optTargetBlock->name = 'datablock';
        $optTargetBlock->text = $this->translate('OPTION_BLOCK_TEXT');
        $optTargetBlock->resolvedType = ActionParameterType::DATA_BLOCK;

        // El Id del registro Destino
        $optValue = new ActionSelectorOptionDefinition();
        $optValue->name = 'value';
        $optValue->text = $this->translate('OPTION_VALUE_TEXT');
        $optValue->resolvedType = ActionParameterType::VALUE;
        $optValue->resolvedDataType = ActionDataType::TEXT;

        // Opciones del Destino
        $paramTarget->selectorOptions = [$optTargetBlock, $optValue];
        $paramTarget->defaultValue = 'datablock';


        // El Nombre de la relación (que apunta al Bloque de datos destino)
        $paramRelName = new ActionParameterDefinition();
        $paramRelName->name = 'relationship_name';
        $paramRelName->text = $this->translate('RELATIONSHIP_TEXT');
        $paramRelName->description = $this->translate('RELATIONSHIP_DESC');
        $paramRelName->type = ActionParameterType::VALUE; 
        $paramRelName->dataType = ActionDataType::TEXT; 
        $paramRelName->required = true;

        return [$paramTarget, $paramRelName];
    }


    public function executeWithBean(ExecutionContext $context, FormAction $actionConfig, SugarBean $bean, DataBlockResolved $block): ActionResult
    {
        // Obtención de los parámetros adicionales (ParameterResolver asegura que no sean nulos porque son obligatorios)

        $targetObject = $actionConfig->getResolvedParameter('target_object');
        $linkName = $actionConfig->getResolvedParameter('relationship_name');

        // Validación del nombre de la relación
        if (empty($linkName)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Relationship name is not specified.");
        }

        // Obtención del Id del Bean destino
        $targetBeanId = null;
        if ($targetObject instanceof DataBlockResolved) {
            $targetBeanRef = $targetObject->dataBlock->getBeanReference();
            if ($targetBeanRef === null || empty($targetBeanRef->beanId)) {
                return new ActionResult(ResultStatus::ERROR, $actionConfig, "Destination data block '{$targetObject->dataBlock->name}' has no ID. Check Action Order.");
            }
            $targetBeanId = $targetBeanRef->beanId;

        } elseif (is_string($targetObject) && !empty($targetObject)) {
            $targetBeanId = $targetObject;

        } elseif ($targetObject instanceof DataBlockFieldResolved) {
            $targetBeanId = $targetObject->value;
        }

        if (empty($targetBeanId)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Relationship '{$linkName}' failed: No target ID found.");
        }

        // Cargar la relación en el Bean origen
        if (!isset($bean->$linkName) && !$bean->load_relationship($linkName)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Could not load relationship '{$linkName}' in module '{$bean->module_name}'. Check vardefs link name.");
        }

        // Establecer la relación
        // El método add() gestiona internamente si es 1:M (foreign keys) o M:M (tablas intermedias).
        try {
            $bean->$linkName->add($targetBeanId);
        } catch (\Exception $e) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Error linking records: " . $e->getMessage());
        }

        // Recalculamos el nombre (si es necesario)
        $nameFieldInBlock = $block->getFieldValue('name');
        $nameIsUserDefined = $nameFieldInBlock && !empty($nameFieldInBlock->value);
        $beanWasCreatedHere = $this->wasBeanCreatedInThisContext($bean->id, $context);

        // El nombre no se ha indicado explícitamente, se ha creado el bean y tiene un nombre (calculado)
        if (!$nameIsUserDefined && $beanWasCreatedHere && !empty($bean->name)) {
            // Reseteamos el nombre
            $bean->name = '';
            $bean->save();
        }

        if (!isset($block->dataBlock->fields['name']) && $bean->name!='') {
            // Verificar que el bean se ha creado 
        }

        // Notificación del resultado
        $actionResult = new ActionResult(ResultStatus::OK, $actionConfig, "Linked via '{$linkName}' to ID {$targetBeanId}");
        $dataToLog = ['_link_name' => $linkName, '_related_id' => $targetBeanId];
        $actionResult->registerBeanModificationFromBlock($bean, $block, BeanModificationType::UPDATED, $dataToLog);

        return $actionResult;
    }

    private function wasBeanCreatedInThisContext(string $beanId, ExecutionContext $context): bool 
    {
        foreach ($context->actionResults as $result) {
            foreach ($result->modifiedBeans as $modBean) {
                if ($modBean->beanId === $beanId && $modBean->modificationType === BeanModificationType::CREATED) {
                    return true;
                }
            }
        }
        return false;
    }
}