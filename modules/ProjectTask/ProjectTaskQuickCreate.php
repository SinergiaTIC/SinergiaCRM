<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
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
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

 
require_once('include/EditView/QuickCreate.php');



#[\AllowDynamicProperties]
class ProjectTaskQuickCreate extends QuickCreate
{
    public $javascript;
    
    // STIC Custom 20250211 JBL - Fix inherited function declaration compatibility
    // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
    // public function process()
    public function process($checkFormName = false, $formName = '')
    // End STIC Custom
    {
        global $current_user, $timedate, $app_list_strings, $current_language, $mod_strings;
        $mod_strings = return_module_language($current_language, 'ProjectTask');
        
        parent::process();
        
        if ($this->viaAJAX) { // override for ajax call
            $this->ss->assign('saveOnclick', "onclick='if(check_form(\"projectTaskQuickCreate\")) return SUGAR.subpanelUtils.inlineSave(this.form.id, \"projecttask\"); else return false;'");
            $this->ss->assign('cancelOnclick', "onclick='return SUGAR.subpanelUtils.cancelCreate(\"subpanel_projecttask\")';");
        }
        
        $this->ss->assign('viaAJAX', $this->viaAJAX);

        $this->javascript = new javascript();
        $this->javascript->setFormName('projectTaskQuickCreate');
        
        $focus = BeanFactory::newBean('ProjectTask');
        $this->javascript->setSugarBean($focus);
        $this->javascript->addAllFields('');

        $this->ss->assign('additionalScripts', $this->javascript->getScript(false));

        $json = getJSONobj();
        
        ///////////////////////////////////////
        ///
        /// SETUP PARENT POPUP

        $popup_request_data = array(
        'call_back_function' => 'set_return',
        'form_name' => 'projectTypeQuickCreate',
        'field_to_name_array' => array(
            'id' => 'parent_id',
            'name' => 'parent_name',
            ),
        );

        $encoded_parent_popup_request_data = $json->encode($popup_request_data);
        $this->ss->assign('encoded_parent_popup_request_data', $encoded_parent_popup_request_data);
        
        $popup_request_data = array(
            'call_back_function' => 'set_return',
            'form_name' => 'projectTaskQuickCreate',
            'field_to_name_array' => array(
                'id' => 'account_id',
                'name' => 'account_name',
            ),
        );
    
        $encoded_popup_request_data = $json->encode($popup_request_data);
        $this->ss->assign('encoded_popup_request_data', $encoded_popup_request_data);
    }
}
