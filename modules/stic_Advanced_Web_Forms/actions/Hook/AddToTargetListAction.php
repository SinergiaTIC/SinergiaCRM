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
 * Action that adds the processed record (Contact, Lead, User or Organization) to an existing Target List (ProspectList).
 */
class AddToTargetListAction extends HookBeanActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->isUserSelectable = true;
        $this->category = 'data';
        $this->baseLabel = 'LBL_ADD_TO_TARGET_LIST_ACTION';
    }

    /**
     * Modules supported by the action
     */
    protected function getSupportedModules(): array {
        return ['Contacts', 'Users', 'Prospects', 'Leads', 'Accounts'];
    }

    /**
     * Name of the parameter that contains the data block.
     * @return string
     */
    protected function getDataBlockParameterText(): string {
        return $this->translate('CONTACT_TO_ADD_TEXT');
    }

    /**
     * The description (help text) of the data block parameter.
     * @return string
     */
    protected function getDataBlockParameterDescription(): string {
        return $this->translate('CONTACT_TO_ADD_DESC');
    }

    /**
    * getCustomParameters()
    * Definition of the ADDITIONAL parameters required for the action
    * The main Data Block parameters are requested by the parent class.
    */
    protected function getCustomParameters(): array
    {
        // The ProsectList to which to add the contact / entity
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
        // Obtaining the additional parameters (ParameterResolver ensures they are not null because they are mandatory)
        
        /** @var BeanReference $lpoRef */
        $lpoRef = $actionConfig->getResolvedParameter('target_list_record');
        

        // We load the Bean's TargerLists Relationship (Contact, Entity, etc.)
        $linkName = 'prospect_lists';
        if (!$bean->load_relationship($linkName)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Module '{$bean->module_name}' doesn't have a relation '{$linkName}' or can't be loaded.");
        }

        // We add the contact to the TargetList (add checks if it already exists or not)
        try {
            $bean->$linkName->add($lpoRef->beanId);
        } catch (\Exception $e) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Error adding to ProspectList: " . $e->getMessage());
        }

        // Notification of the result
        $actionResult = new ActionResult(ResultStatus::OK, $actionConfig, "Added to ProspectList: {$lpoRef->beanId}");
        $dataToLog = ['added_to_prospect_list_id' => $lpoRef->beanId];
        $actionResult->registerBeanModificationFromBlock($bean, $block, BeanModificationType::UPDATED, $dataToLog);

        return $actionResult;
    }
}