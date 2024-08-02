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

        $parent_module = $defines['focus']->module_name;
        $button_module = $defines['module'];


        if ($bean->module_dir === 'Accounts') {
            $phone = $bean->phone_office;
        }
        else {
            $phone = $bean->phone_mobile;
        }

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
                 >";

        // $form = "<div type='hidden' 
        //          onclick='openCustomModal(this); return false;'
        //          data-phone='{$bean->phone_mobile}'
        //          data-module='{$bean->module_name}'
        //          data-record-id='{$bean->id}'
        //          >";


                //  onclick='openCustomModal(\"{$button_module}\", \"{$parent_module}\"); return false;'>";


                // $additional_params = array(
                //     'parent_id' => $defines['focus']->id,
                //     'parent_name' => $defines['focus']->name,
                //     'phone' => $defines['focus']->phone_mobile,
                //     // Add more parameters as needed
                // );
        
                // $params_json = json_encode($additional_params);
        
                // $form = "<input type='button' name='button' id='custom_modal_button' class='button' 
                //          title='{$button}' value='{$button}' accesskey='{$accesskey}'
                //          onclick='openCustomModal(this, {$params_json}); return false;'>";
                //         //  onclick='openCustomModal(\"{$button_module}\", \"{$parent_module}\", {$params_json}); return false;'>";
        




        return $form;
    }

    public function display($defines, $additionalFormFields = null, $nonbutton = false)
    {
        require_once 'modules/MySettings/TabController.php';
        $controller = new TabController();
        $currentTabs = $controller->get_system_tabs();

        if (!$currentTabs['stic_Messages']){
            return '';
        }

        $focus = $defines['focus'];
        $button = $this->_get_form($defines, $additionalFormFields);
        
        return $button;
    }



    // public $form_value = '';

    // public function getWidgetId($buttonSuffix = true)
    // {
    //     global $app_strings;
    //     $this->form_value = 'PAPPAPAPA';// $app_strings['LBL_SUBPANEL_NEW_MESSAGE_LABEL'];
    //     return parent::getWidgetId();
    // }
    // public function &_get_form($defines, $additionalFormFields = null, $nonbutton = false)
    // {
    //     $bean = $defines['focus'];
    //     $button = '<div type="hidden" onclick="currentModule=\''
    //     //   . $bean->module_name . '\';alert(\'bbbb\');$(document).openComposeViewModal(this);" data-module="'
    //       . $bean->module_name . '\';alert(\'bbbb\');$(document).openComposeMessageViewModal(this);" data-module="'
    //       . $bean->module_name . '" data-record-id="'
    //       . $bean->id . '" data-module-name="'
    //       . $bean->name .'" data-email-address="'
    //       . $bean->email1 .'">';

    //     return $button;

    // }

    // public function display($defines, $additionalFormFields = null, $nonbutton = false)
    // {
    //     $focus = new Meeting;
    //     if (!$focus->ACLAccess('EditView')) {
    //         return '';
    //     }
        
    //     $inputID = $this->getWidgetId();

    //     $button = $this->_get_form($defines, $additionalFormFields);

    //     // global $current_user;
    //     // $client = $current_user->getEmailClient();

    //     // if ($client == 'sugar') {
    //     //     $button .= "<input class='button' onclick='return false;' type='button' id='$inputID' value='$this->form_value'>";
    //     // }
    //         $button .= "<input class='button' onclick='alert('ccc');return false;' type='button' id='$inputID' value='$this->form_value'>";

    //     return $button;
    // }

}
