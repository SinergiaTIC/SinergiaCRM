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


    public function action_Save() {
        if (isset($_REQUEST['mass_ids']) && $_REQUEST['mass_ids'] !== '') {
            $idsArray = explode(';', $_REQUEST['mass_ids']);
            $phonesArray = explode(',', $_REQUEST['phone']);

            array_map(function($id, $phone) {
                $newBean = BeanFactory::newBean('stic_Messages');
                $this->bean = $newBean;
                $this->pre_save();
                $this->bean->parent_id = $id;
                $this->bean->phone = $phone;
                $this->bean->save(!empty($this->bean->notify_on_save));  
            }, $idsArray, $phonesArray);
        }
        else {
            $this->bean->save(!empty($this->bean->notify_on_save));
        }
    }


    public function action_Retry(){

        $db = DBManagerFactory::getInstance();
        // only messages not sent and with direction outbound can be retried
        $sql = "SELECT id,name,`type`,direction,phone,sender,message,status  FROM stic_messages WHERE deleted = 0 and status <> 'sent' and direction = 'outbound'";
        $where = '';
        if (isset($_REQUEST['select_entire_list']) && $_REQUEST['select_entire_list'] == '1' && isset($_REQUEST['current_query_by_page'])) {
            require_once 'include/export_utils.php';
            $retArray = generateSearchWhere($moduleTable, $_REQUEST['current_query_by_page']);
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

    public function action_ComposeView()
    {
        $this->view = 'compose';
        // For viewing the Compose as modal from other modules we need to load the stic_Messages language strings
        if (isset($_REQUEST['in_popup']) && $_REQUEST['in_popup']) {
            if (!is_file('cache/jsLanguage/stic_Messages/' . $GLOBALS['current_language'] . '.js')) {
                require_once('include/language/jsLanguage.php');
                jsLanguage::createModuleStringsCache('stic_Messages', $GLOBALS['current_language']);
            }
            echo '<script src="cache/jsLanguage/stic_Messages/'. $GLOBALS['current_language'] . '.js"></script>';
        }


        // Building and running the query that retrieves all the record that were selected in ListView
        
        $bean = BeanFactory::getBean($_REQUEST['targetModule']);
        $phoneFieldName = stic_MessagesUtils::getPhoneFieldNameForMessage($bean);
        $nameFieldName = stic_MessagesUtils::getNameFieldNameForMessage($bean);
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

        // if (isset($_REQUEST['ids']) && isset($_REQUEST['targetModule'])) {
        //     $toAddressIds = explode(',', rtrim($_REQUEST['ids'], ','));
        //     foreach ($toAddressIds as $id) {
        //         $destinataryBean = BeanFactory::getBean($_REQUEST['targetModule'], $id);
        //         if ($destinataryBean) {
        //             $idLine = '<input type="hidden" class="phone-compose-view-to-list" ';
        //             $idLine .= 'data-record-module="' . $_REQUEST['targetModule'] . '" ';
        //             $idLine .= 'data-record-id="' . $id . '" ';
        //             $idLine .= 'data-record-name="' . $destinataryBean->name . '" ';
        //             if ($_REQUEST['targetModule'] === 'Accounts') {
        //                 $idLine .= 'data-record-phone="' . $destinataryBean->phone_office . '">';
        //             }
        //             else {
        //                 $idLine .= 'data-record-phone="' . $destinataryBean->phone_mobile . '">';
        //             }
        //             echo $idLine;
        //         }
        //     }
        // }
    }

    public function action_getParentPhone() {
        $parentId = $_POST["parentId"];
        $parentType = $_POST["parentType"];
        
        $response['code'] = 'No data';
        $db = DBManagerFactory::getInstance();

        switch ($parentType) {
            case 'Contacts':
            case 'Leads':
                $sql = "SELECT phone_mobile as phone FROM contacts where id = '{$parentId}'";
                break;
            case 'Accounts':
                $sql = "SELECT phone_office as phone FROM accounts where id = '{$parentId}'";
                break;
            default:
                $sql = "";
        }

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

    /**
     * This action runs when the user wants to syncronize the data with Incorpora from the ViewList
     * of any Module.
     *
     * It runs a query that returns all the records that where selected by the user in the ViewList. Returning
     * its SugarCRM ID and Incorpora ID, if any. And get the User connection params from the user profile details.
     *
     * Then it sets the current view and the next action.
     *
     * @return void
     */
    public function action_fromMassUpdate()
    {
        global $db;
        $ids = array();

        $GLOBALS['log']->debug(__METHOD__ . ' ' . __LINE__ . ' ' . ' Sending messages from ListView/MassUpdate ');

        // Retrieving and setting user syncronization params
        $this->setIncorporaUserParams();

        // This may only happen if the URL is introduced manually
        if (!$this->returnModule = $_REQUEST['return_module']) {
            echo "There are missing some URL parameters. Expected 'return_module'.";
            die();
        }
        $bean = BeanFactory::getBean($this->returnModule);
        $moduleTable = $bean->table_name;
        switch ($this->returnModule) {
            case 'stic_Job_Offers':
                $tableQuery = 'FROM ' . $moduleTable;
                $incIdFieldSql = 'inc_id';
                $incIdField = 'inc_id';
                break;

            default:
                $tableQuery = 'FROM ' . $moduleTable . ' JOIN ' . $moduleTable . '_cstm c ON id=c.id_c';
                $incIdFieldSql = 'c.inc_id_c';
                $incIdField = 'inc_id_c';
                break;
        }

        // Building and running the query that retrieves all the record that were selected in ListView
        $sql = "SELECT id, $incIdFieldSql $tableQuery WHERE {$moduleTable}.deleted=0";
        $where = '';
        if (isset($_REQUEST['select_entire_list']) && $_REQUEST['select_entire_list'] == '1' && isset($_REQUEST['current_query_by_page'])) {
            require_once 'include/export_utils.php';
            $retArray = generateSearchWhere($moduleTable, $_REQUEST['current_query_by_page']);
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
        $resultado = $db->query($sql);
        unset($ids);
        $ids = array();

        while ($row = $db->fetchByAssoc($resultado)) {
            // Building the Summary count table
            $ids[] = $row['id'];
            $this->summary['crm_ids']++;
            $incIds[$row['id']] = $row[$incIdField];
            if ($row[$incIdField]) {
                $this->summary['inc_ids']++;
            } else {
                $this->summary['no_inc_ids']++;
            }
        }

        // Sending the params that the UI will use
        $this->view_object_map['SUMMARY'] = $this->summary;
        $this->view_object_map['IDS'] = $ids;
        $this->view_object_map['INC_IDS'] = $incIds;

        $GLOBALS['log']->debug(__METHOD__ . ' ' . __LINE__ . ' ' . ' Syncronization Incorpora action from ListView/MassUpdate finished with Summary: ', $this->summary);

        $this->view = "syncoptions"; //call for the view file in views dir
        $this->mapStepNavigation('results'); //next action to be run
    }

    protected function action_getPhoneField()
    {
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