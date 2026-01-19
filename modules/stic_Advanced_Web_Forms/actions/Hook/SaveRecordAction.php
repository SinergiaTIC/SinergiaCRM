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
 * SaveRecordAction
 *
 * Acción que gestiona el guardado de un Bloque de Datos en la Base de Datos.
 */
class SaveRecordAction extends HookDataBlockActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->isUserSelectable = false; // El usuario no puede seleccionar esta acción manualmente
        $this->isAutomatic = true;       // La acción se genera automáticamente por el sistema
        $this->category = 'data';
        $this->baseLabel = 'LBL_SAVE_RECORD_ACTION';
    }

    /**
     * Ejecuta la acción, recibe el bloque de datos principal resuelto y validado.
     *
     * @param ExecutionContext $context El contexto global.
     * @param FormAction $actionConfig La configuración de la acción.
     * @param DataBlockResolved $block El bloque de datos principal, listo para ser usado.
     * @return ActionResult
     */
    public function executeWithBlock(ExecutionContext $context, FormAction $actionConfig, DataBlockResolved $block): ActionResult
    {
        $module = $block->dataBlock->module;
        $bean = null;
        $onDuplicateAction = null;

        // Lógica de Detección de Duplicados
        $duplicateRules = $block->dataBlock->duplicate_detections ?? [];
        foreach ($duplicateRules as $rule) {
            $queryFields = [];
            $canSearch = true;

            // Construimos los campos de búsqueda para esta regla
            foreach ($rule->fields as $fieldName) {
                $fieldValue = $block->getFieldValue($fieldName)?->value;

                // Si un campo de la regla de duplicados está vacío, no aplicamos la regla
                // (Dos personas no pueden ser la misma si las dos tienen el campo email vacío)
                if ($fieldValue === null || $fieldValue === '') {
                    $canSearch = false;
                    break; // Pasamos a la siguiente regla
                }
                $queryFields[$fieldName] = $fieldValue;
            }

            // Si la regla es válida y tiene campos, buscamos el bean
            if ($canSearch && !empty($queryFields)) {
                $tempBean = BeanFactory::newBean($module);
                $foundBean = $tempBean->retrieve_by_string_fields($queryFields);

                if ($foundBean !== null) {
                    $bean = $foundBean; // Duplicado encontrado
                    $onDuplicateAction = $rule->on_duplicate;
                    break; // Dejamos de buscar, ya hemos encontrado uno
                }
            }
        }

        // Lógica de Acción (Crear o Gestionar Duplicado)
        $modificationType = null;
        if ($bean === null) {
            // No hay duplicado, creamos uno nuevo
            $bean = BeanFactory::newBean($module);
            // Asignar usuario si se ha definido uno por defecto
            if (!empty($context->defaultAssignedUserId)) {
                $bean->assigned_user_id = $context->defaultAssignedUserId;
            }
            // Llenar todos los campos del bean
            $this->populateBean($bean, $block); 
            $bean->save();
            $modificationType = BeanModificationType::CREATED;

        } else {
            // Duplicado encontrado, aplicamos la regla
            switch ($onDuplicateAction) {
                case OnDuplicateAction::ERROR:
                    // Generar un error y detener el flujo
                    return new ActionResult(ResultStatus::ERROR, $actionConfig, "Duplicate record found for module {$module}.");

                case OnDuplicateAction::UPDATE:
                    // Sobrescribir todos los campos del bean existente
                    $this->populateBean($bean, $block);
                    $bean->save();
                    $modificationType = BeanModificationType::UPDATED;
                    break;

                case OnDuplicateAction::ENRICH:
                    // Rellenar solo los campos vacíos del bean existente
                    $this->enrichBean($bean, $block); 
                    $bean->save();
                    $modificationType = BeanModificationType::ENRICHED;
                    break;
                
                case OnDuplicateAction::SKIP:
                default:
                    // No hacemos nada, el bean se queda como estaba
                    $modificationType = BeanModificationType::SKIPPED;
                    break;
            }
        }

        // Registro y Retorno
        $actionResult = new ActionResult(ResultStatus::OK, $actionConfig);
        
        // Registramos la modificación (o la no-modificación)
        $actionResult->registerBeanModificationFromBlock($bean, $block, $modificationType);

        return $actionResult;
    }

    /**
     * Rellena un bean con todos los datos del formulario (sobrescribe).
     */
    private function populateBean(SugarBean $bean, DataBlockResolved $block): void
    {
        foreach ($block->formData as $fieldName => $field) {
            $bean->{$fieldName} = $field->value;
        }
    }

    /**
     * Rellena un bean solo con los campos que están vacíos (enriquece).
     */
    private function enrichBean(SugarBean $bean, DataBlockResolved $block): void
    {
        foreach ($block->formData as $fieldName => $field) {
            // Comprobamos si el campo en el bean está vacío o nulo
            if ($bean->{$fieldName} === null || $bean->{$fieldName} === '') {
                $bean->{$fieldName} = $field->value;
            }
        }
    }
}
