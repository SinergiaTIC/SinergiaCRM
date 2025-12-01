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

use SuiteCRM\Utility\SuiteValidator;

include_once 'include/Exceptions/SugarControllerException.php';
require_once 'modules/stic_Messages/Utils.php';
require_once("modules/AOW_WorkFlow/aow_utils.php");

class stic_MessagesController extends SugarController
{
    // We remap EditView action when no id is present (new record) to use the ComposeView
    protected function remapAction()
    {
        if (!empty($this->action_remap[$this->do_action])) {
            $this->action = $this->action_remap[$this->do_action];
            $this->do_action = $this->action;
        }

        if ($this->do_action == 'EditView' && empty($this->bean->id)) {
            $this->action = 'ComposeView';
            $this->do_action = 'ComposeView';
        }
    }

    public function action_Save() {
        if (isset($_REQUEST['mass_ids']) && $_REQUEST['mass_ids'] !== '') {
            $idsArray = explode(';', $_REQUEST['mass_ids']);
            $phonesArray = explode(',', $_REQUEST['phone']);

            array_map(function($id, $phone) {
                $newBean = BeanFactory::newBean('stic_Messages');
                $this->bean = $newBean;
                $this->pre_save();
                $this->bean->parent_id = $id;
                $this->bean->parent_type = $_REQUEST['return_module'];
                $this->bean->phone = $phone;
                $this->bean->save(!empty($this->bean->notify_on_save));  
            }, $idsArray, $phonesArray);
        }
        else {
            $this->bean->save(!empty($this->bean->notify_on_save));
            // header('Content-Type: application/json');
            // $this->bean->response
            // echo "{'status': 200, 'message': 'ok'}";
            echo json_encode(array('success' => true, 'number_found' => true));
        }
    }

    public function pre_savePopUp(){
        parent::pre_save();
    }

    public function action_SavePopUp() {
        global $app_strings, $current_language;
        $mod_strings = return_module_language($current_language, $this->module);

        if (isset($_REQUEST['mass_ids']) && $_REQUEST['mass_ids'] !== '') {
            $idsArray = explode(';', $_REQUEST['mass_ids']);
            $phonesArray = explode(',', $_REQUEST['phone']);

            array_map(function($id, $phone) {
                $newBean = BeanFactory::newBean('stic_Messages');
                $this->bean = $newBean;
                $this->pre_save();
                $this->bean->parent_id = $id;
                $this->bean->parent_type = $_REQUEST['return_module'];
                $this->bean->phone = $phone;
                $this->bean->save(!empty($this->bean->notify_on_save));  
            }, $idsArray, $phonesArray);
            header('Content-Type: application/json');
            echo json_encode(array('success' =>  true, 'title' => $app_strings['LBL_EMAIL_SUCCESS'], 'detail' => $mod_strings['LBL_CHECK_STATUS']));
            exit;
        }
        else {
            $oldStatus = $this->bean->fetched_row['status']??'';
            $id = $this->bean->save(!empty($this->bean->notify_on_save));
            header('Content-Type: application/json');
            switch ($this->bean->status) {
                case 'sent':
                    if ($this->bean->status !== $oldStatus) {
                        $title = $app_strings['LBL_EMAIL_SUCCESS'];
                        $detail = $mod_strings['LBL_MESSAGE_SENT'];
                    }
                    else {
                        $title = $app_strings['LBL_EMAIL_SUCCESS'];
                        $detail = $mod_strings['LBL_MESSAGE_SAVED'];
                    }
                    break;
                case 'error':
                    $title = $mod_strings['LBL_ERROR'];
                    $detail = $mod_strings['LBL_MESSAGE_NOT_SENT'];
                    break;
                case 'draft':
                    $title = $app_strings['LBL_EMAIL_SUCCESS'];
                    $detail = $mod_strings['LBL_MESSAGE_SAVED'];
                    break;
                default:
                    $title = $mod_strings['LBL_EMAIL_SUCCESS'];
                    $detail = $mod_strings['LBL_MESSAGE_SAVED'];
            }
            echo json_encode(array('success' => $this->bean->status === 'error' ? false : true, 'title' => $title, 'detail' => $detail, 'id' => $id));
            exit;
        }
    }


    public function action_RetryOne() {
        global $app_strings, $mod_strings;

        $id = $_REQUEST['recordId'];
        $bean = BeanFactory::getBean('stic_Messages', $id);
        $bean->status = 'sent';
        $bean->save();

        $title = $bean->status !== 'error' ? $app_strings['LBL_EMAIL_SUCCESS'] : $mod_strings['LBL_ERROR'];
        $detail = $bean->status !== 'error' ? $mod_strings['LBL_MESSAGE_SENT'] : $mod_strings['LBL_MESSAGE_NOT_SENT'];
        echo json_encode(array('success' => $bean->status === 'error' ? false : true, 'title' => $title, 'detail' => $detail, 'id' => $id));
        exit;
    }
    public function action_Retry(){

        $db = DBManagerFactory::getInstance();
        // only messages not sent and with direction outbound can be retried
        $sql = "SELECT id,name,`type`,direction,phone,sender,message,status  FROM stic_messages WHERE deleted = 0 and status <> 'sent' and direction = 'outbound'";
        $where = '';
        if (isset($_REQUEST['select_entire_list']) && $_REQUEST['select_entire_list'] == '1' && isset($_REQUEST['current_query_by_page'])) {
            require_once 'include/export_utils.php';
            $retArray = generateSearchWhere('stic_Messages', $_REQUEST['current_query_by_page']);
            $where = '';
            if (!empty($retArray['where'])) {
                $where = " AND " . $retArray['where'];
            }
        } else {
            $ids = explode(',', $_REQUEST['uid']);
            $idList = implode("','", $ids);
            $where = " AND id in ('{$idList}')";
        }
        $sql .= $where;
        $result = $db->query($sql);

        while ($row = $db->fetchByAssoc($result)) {
            $bean = BeanFactory::getBean('stic_Messages', $row['id']);
            $bean->status = 'sent';
            $bean->save();
        }

        SugarApplication::redirect("index.php?module=stic_Messages&action=index");
    }

    public function action_ComposeView() {
        $this->view = 'edit';
        // For viewing the Compose as modal from other modules we need to load the stic_Messages language strings
        if (isset($_REQUEST['in_popup']) && $_REQUEST['in_popup']) {
            if (!is_file('cache/jsLanguage/stic_Messages/' . $GLOBALS['current_language'] . '.js')) {
                require_once('include/language/jsLanguage.php');
                jsLanguage::createModuleStringsCache('stic_Messages', $GLOBALS['current_language']);
            }
            echo '<script src="cache/jsLanguage/stic_Messages/'. $GLOBALS['current_language'] . '.js"></script>';
        }


        // Building and running the query that retrieves all the record that were selected in ListView
        if(!empty($_REQUEST['targetModule'])){
            $bean = BeanFactory::getBean($_REQUEST['targetModule']);
            $phoneFieldName = stic_MessagesUtils::getPhoneFieldNameForMessage($bean->module_name);
            $nameFieldName = stic_MessagesUtils::getNameFieldNameForMessage($bean->module_name);
            $moduleTable = $bean->table_name;
            $moduleName = $bean->module_name;
            $sql = "SELECT id, $phoneFieldName as phone, $nameFieldName as name FROM {$moduleTable} WHERE {$moduleTable}.deleted=0";
            $where = '';
            if (isset($_REQUEST['select_entire_list']) && $_REQUEST['select_entire_list'] == '1' && isset($_REQUEST['current_query_by_page'])) {
                require_once 'include/export_utils.php';
                $retArray = generateSearchWhere($moduleName, $_REQUEST['current_query_by_page']);
                $where = '';
                if (!empty($retArray['where'])) {
                    $where = " AND " . $retArray['where'];
                }
            } else {
                $ids = explode(',', rtrim($_REQUEST['ids'], ','));
                $idList = implode("','", $ids);
                $where = " AND id in ('{$idList}')";
            }
            $sql .= $where;
            $db = DBManagerFactory::getInstance();
            $resultado = $db->query($sql);
            unset($ids);
            $ids = array();
    
            while ($row = $db->fetchByAssoc($resultado)) {
                // Building the Summary count table
                $idLine = '<input type="hidden" class="phone-compose-view-to-list" ';
                $idLine .= 'data-record-module="' . $_REQUEST['targetModule'] . '" ';
                $idLine .= 'data-record-id="' . $row['id'] . '" ';
                $idLine .= 'data-record-name="' . $row['name'] . '" ';
                $idLine .= 'data-record-phone="' . $row['phone'] . '">';
                echo $idLine;
            }
        }
    }

    public function action_getParentPhone() {
        $parentId = $_POST["parentId"];
        $parentType = $_POST["parentType"];
        
        require_once('modules/stic_Messages/Utils.php');
        $phoneFieldName = stic_MessagesUtils::getPhoneFieldNameForMessage($parentType);
        $tableName = stic_MessagesUtils::gettableNameForMessage($parentType);

        $response['code'] = 'No data';
        $db = DBManagerFactory::getInstance();

        $sql = "SELECT {$phoneFieldName} as phone FROM {$tableName} WHERE id = '{$parentId}'";

        $result = $db->query($sql);
        if($row = $result->fetch_assoc()) {
            $response['code'] = 'OK';
            $response['data']['phone'] = $row['phone'];
        }
        else {
            $response['data']['phone'] = '';
        }

        echo json_encode($response);
        exit;
    }

    protected function action_getPhoneField() {
        $module = $_REQUEST['aow_module'];
        $aow_field = $_REQUEST['aow_newfieldname'];

        if (isset($_REQUEST['view'])) {
            $view = $_REQUEST['view'];
        } else {
            $view= 'EditView';
        }

        if (isset($_REQUEST['aow_value'])) {
            $value = $_REQUEST['aow_value'];
        } else {
            $value = '';
        }

        switch ($_REQUEST['aow_type']) {
            case 'Record Phone':
                echo '';
                break;
            case 'Related Field':
                $rel_field_list = stic_MessagesUtils::getRelatedMessageableFields($module);
                if ($view == 'EditView') {
                    echo "<select type='text'  name='$aow_field' id='$aow_field' title='' tabindex='116'>". get_select_options_with_id($rel_field_list, $value) ."</select>";
                } else {
                    echo $rel_field_list[$value];
                }
                break;
            case 'Specify User':
                echo getModuleField('Accounts', 'assigned_user_name', $aow_field, $view, $value);
                break;
            case 'Users':
                echo getAssignField($aow_field, $view, $value);
                break;
            case 'Phone':
            default:
                if ($view == 'EditView') {
                    echo "<input type='text' name='$aow_field' id='$aow_field' size='25' title='' tabindex='116' value='$value'>";
                } else {
                    echo $value;
                }
                break;
        }
        die;
    }


}