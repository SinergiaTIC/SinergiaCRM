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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * This custom class, allows you to create a button in each record of the subpanel
 * which open the QuickCreate View to edit the record.
 */

require_once('modules/stic_Messages/Utils.php');

class SugarWidgetSubPanelEditMessagesButton extends SugarWidgetSubPanelTopButtonQuickCreate
{

    public function getWidgetId($buttonSuffix = true)
    {
        global $app_strings;
        $this->form_value = $app_strings['LBL_SUBPANEL_NEW_MESSAGE_LABEL'];
        return parent::getWidgetId();
    }


    public function &_get_form($defines, $additionalFormFields = null, $asUrl = false)
    {
        global $app_strings;

        $bean = $defines['focus'];

        $button = $app_strings['LBL_SUBPANEL_NEW_MESSAGE_LABEL'];
        $accesskey = $app_strings['LBL_SUBPANEL_NEW_MESSAGE_LABEL'];

        $phone = stic_MessagesUtils::getPhoneForMessage($bean);
        $name = stic_MessagesUtils::getNameFieldNameForMessage($bean->module_name);

        $jsonData = json_encode([
            'return_action' => 'DetailView',
        ]);
        $jsonData = str_replace("'", "\\'", $jsonData);
        $form = "<input type='button' name='button' id='custom_modal_button' class='button' 
            title='{$button}' value='{$button}' accesskey='{$accesskey}'
            onclick='openMessagesModal(this); return false;'
                 data-phone='{$phone}'
                 data-module='{$bean->module_name}'
                 data-record-id='{$bean->id}'
                 data-name='{$bean->name}'
                 >";

        return $form;
    }

    public function display($defines, $additionalFormFields = null, $nonbutton = false)
    {
        if (ACLController::checkAccess('stic_Messages', 'edit', true)) {
            require_once 'modules/MySettings/TabController.php';
            $controller = new TabController();
            $currentTabs = $controller->get_system_tabs();
    
            if (!isset($currentTabs['stic_Messages']) || !$currentTabs['stic_Messages']){
                return '';
            }
    
            $button = $this->_get_form($defines, $additionalFormFields);
            
            return $button;
        }
        return '';
    }
}
