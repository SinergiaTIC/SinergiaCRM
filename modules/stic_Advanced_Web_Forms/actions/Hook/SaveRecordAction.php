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

class SaveRecordAction extends HookDataBlockActionDefinition {
    public function __construct() {
        $this->isActive = true;
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
        // IEPA!!
        // TODO: Aplicar la lógica de detección y gestión de duplicados

        // Lógica de negocio
        $bean = BeanFactory::newBean($block->dataBlock->module);
        
        // El $block ya contiene los datos parseados y con el casting
        foreach ($block->formData as $fieldName => $field) {
            $bean->{$fieldName} = $field->value;
        }
        $bean->save();

        // Registro
        $actionResult = new ActionResult(ResultStatus::OK, $actionConfig);
        $actionResult->registerBeanModificationFromBlock($bean, $block, BeanModificationType::CREATED);

        return $actionResult;
    }
}
