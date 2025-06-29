<?php
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

require_once('include/EditView/EditView2.php');


#[\AllowDynamicProperties]
class CalendarViewQuickEdit extends SugarView
{
    public $ev;
    protected $editable;

    public function preDisplay()
    {
        $this->bean = $this->view_object_map['currentBean'];

        // STIC Custom 20250315 JBL - Fix Error: Call to a member function ACLAccess() on false
        // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
        // if ($this->bean->ACLAccess('Save')) {
        if ($this->bean !== false && $this->bean->ACLAccess('Save')) {
        // END STIC Custom
            $this->editable = 1;
        } else {
            $this->editable = 0;
        }
    }

    public function display()
    {
        require_once("modules/Calendar/CalendarUtils.php");

        $module = $this->view_object_map['currentModule'];

        $_REQUEST['module'] = $module;

        $base = 'modules/' . $module . '/metadata/';
        $source = 'custom/'.$base.'quickcreatedefs.php';
        if (!file_exists($source)) {
            $source = $base . 'quickcreatedefs.php';
            if (!file_exists($source)) {
                $source = 'custom/' . $base . 'editviewdefs.php';
                if (!file_exists($source)) {
                    $source = $base . 'editviewdefs.php';
                }
            }
        }

        $GLOBALS['mod_strings'] = return_module_language($GLOBALS['current_language'], $module);
        $tpl = $this->getCustomFilePathIfExists('include/EditView/EditView.tpl');

        $this->ev = new EditView();
        $this->ev->view = "QuickCreate";
        $this->ev->ss = new Sugar_Smarty();
        $this->ev->formName = "CalendarEditView";
	     //Fix #9781 Meetings and Calls quick edit via Calender does not populate correct reminders
        //Fetch Reminders Data for existing Calls or Meetings and assign to smarty template
        if (!empty($this->bean->id)) {
            $this->ev->ss->assign('remindersData', Reminder::loadRemindersData($module, $this->bean->id, false));
            $this->ev->ss->assign('remindersDataJson', Reminder::loadRemindersDataJson($module, $this->bean->id, false));
            $this->ev->ss->assign('remindersDefaultValuesDataJson', Reminder::loadRemindersDefaultValuesDataJson());
            $this->ev->ss->assign('remindersDisabled', json_encode(false));
        }
        $this->ev->setup($module, $this->bean, $source, $tpl);
        $this->ev->defs['templateMeta']['form']['headerTpl'] = "modules/Calendar/tpls/editHeader.tpl";
        $this->ev->defs['templateMeta']['form']['footerTpl'] = "modules/Calendar/tpls/empty.tpl";
        $this->ev->process(false, "CalendarEditView");

        if (!empty($this->bean->id)) {
            require_once('include/json_config.php');
            global $json;
            $json = getJSONobj();
            $json_config = new json_config();
            $GRjavascript = $json_config->getFocusData($module, $this->bean->id);
        } else {
            $GRjavascript = "";
        }

        $json_arr = array(
                'access' => 'yes',
                'module_name' => $this->bean->module_dir,
                'record' => $this->bean->id,
                'edit' => $this->editable,
                'html'=> $this->ev->display(false, true),
                'gr' => $GRjavascript,
        );

        if ($repeat_arr = CalendarUtils::get_sendback_repeat_data($this->bean)) {
            $json_arr = array_merge($json_arr, array("repeat" => $repeat_arr));
        }

        ob_clean();
        echo json_encode($json_arr);
    }
}
