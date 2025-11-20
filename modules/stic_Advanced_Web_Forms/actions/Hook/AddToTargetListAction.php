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
 * AddToTargetListAction
 *
 * Acción que añade el registro procesado (Persona, Interesado, Usuario o Organización) a una Lista de Público Objetivo (ProspectList) existente.
 */
class AddToTargetListAction extends HookBeanActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->isUserSelectable = true;
        $this->isCommon = true;
        $this->category = 'data';
        $this->baseLabel = 'LBL_ADD_TO_TARGET_LIST_ACTION';
    }

    /**
     * Módulos soportados por la acción
     */
    protected function getSupportedModules(): array {
        return ['Contacts', 'Users', 'Prospects', 'Leads', 'Accounts'];
    }

    /**
     * Nombre del parámetro que contiene el bloque de datos.
     * @return string
     */
    protected function getDataBlockParameterText(): string {
        return $this->translate('CONTACT_TO_ADD_TEXT');
    }

    /**
     * La descripción (help text) del parámetro de bloque de datos.
     * @return string
     */
    protected function getDataBlockParameterDescription(): string {
        return $this->translate('CONTACT_TO_ADD_DESC');
    }

    /**
     * getCustomParameters()
     * Definición de los parámetroes ADICIONALES que son necesarios para la acción
     * El parámtreo del Bloque de Datos principal lo pide la clase padre.
     */
    protected function getCustomParameters(): array
    {
        // La LPO a la que añadir el contacto / entidad
        $paramLPO = new ActionParameterDefinition();
        $paramLPO->name = 'target_list_record';
        $paramLPO->text = $this->translate('TARGET_LIST_RECORD_TEXT');
        $paramLPO->description = $this->translate('TARGET_LIST_RECORD_DESC');
        $paramLPO->type = ActionParameterType::CRM_RECORD;
        $paramLPO->supportedModules = ['ProspectLists'];
        $paramLPO->required = true;

        return [$paramLPO];
    }


    public function executeWithBean(ExecutionContext $context, FormAction $actionConfig, SugarBean $bean, DataBlockResolved $block): ActionResult
    {
        // Obtención de los parámetros adicionales (ParameterResolver asegura que no sean nulos porque son obligatorios)
        
        /** @var ?BeanReference $lpoRef */
        $lpoRef = $actionConfig->getResolvedParameter('target_list_record');
        

        // Cargamos la Relación de LPOs del Bean (Contacto, Entidad, etc.)
        $linkName = 'prospect_lists';
        if (!$bean->load_relationship($linkName)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Module '{$bean->module_name}' doesn't have a relation '{$linkName}' or can't be loaded.");
        }

        // Añadimos el contacto a la LPO (add gestiona si ya existe o no)
        try {
            $bean->$linkName->add($lpoRef->beanId);
        } catch (\Exception $e) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Error adding to ProspectList: " . $e->getMessage());
        }

        // Notificación del resultado
        $actionResult = new ActionResult(ResultStatus::OK, $actionConfig, "Added to ProspectList: {$lpoRef->beanId}");
        $dataToLog = ['added_to_prospect_list_id' => $lpoRef->beanId];
        $actionResult->registerBeanModificationFromBlock($bean, $block, BeanModificationType::UPDATED, $dataToLog);

        return $actionResult;
    }
}