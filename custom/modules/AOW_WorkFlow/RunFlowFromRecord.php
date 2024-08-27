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

global $db, $current_language;

if (!isset($_REQUEST['workflowID']) && !isset($_REQUEST['module']) && !isset($_REQUEST['uid']) && !$this->returnModule = $_REQUEST['module']) {
    die("Bad data passed in; <a href=\"{$sugar_config['site_url']}\">Return to Home</a>");
}

$workflowBean = BeanFactory::getBean('AOW_WorkFlow', $_REQUEST['workflowID']);
if (!$workflowBean->id && $workflowBean->stic_run_on_record != true ) {
    die("Bad data passed in; <a href=\"{$sugar_config['site_url']}\">Return to Home</a>");
}
// if ($workflowBean->stic_run_on_record != true ) {
//     $url = 'index.php?module='.$_REQUEST['module'].'&action='.$_REQUEST['return_action'];
//     if ($_REQUEST['return_action'] == 'DetailView') {
//         $url .= '&record=' . $_REQUEST['uid'];
//     }
//     SugarApplication::appendErrorMessage('<div class="msg-fatal-lock">' . $mod_strings['LBL_RUN_FLOW_FROM_RECORD_DEACTIVATED'] . '</div>');
//     SugarApplication::redirect($url);
// }
$workflowBean->flow_run_on = false;
// $workflowBean->multiple_runs = true;

$recordIds = array();

// Building and running the query that retrieves all the record that were selected in ListView

if (isset($_REQUEST['select_entire_list']) && $_REQUEST['select_entire_list'] == '1' && isset($_REQUEST['current_query_by_page'])) {
    require_once 'include/export_utils.php';
    $retArray = generateSearchWhere($moduleTable, $_REQUEST['current_query_by_page']);
    $bean = BeanFactory::getBean($this->returnModule);
    $moduleTable = $bean->table_name;
    $sql = "SELECT id FROM $moduleTable WHERE $moduleTable.deleted = 0";
    $where = '';
    if (!empty($retArray['where'])) {
        $where = " AND " . $retArray['where'];
    }
    $sql .= $where;
    $resultado = $db->query($sql);
    while ($row = $db->fetchByAssoc($resultado)) {
        $recordIds[] = $row['id'];
    }
} else {
    $recordIds = explode(',', $_REQUEST['uid']);
}

$recordsNotMetConditions = 0;
$recordsFailedActions = 0;
$recordsSuccess = 0;

foreach($recordIds as $recordId) {
    $recordBean = BeanFactory::getBean($_REQUEST['module'], $recordId);

    if ($workflowBean->check_valid_bean($recordBean)) {
        if ($workflowBean->run_actions($recordBean)) {
            $recordsSuccess++;
        } else {
            $recordsFailedActions++;
        }
    } else {
        $recordsNotMetConditions++;
    }
}

$url = 'index.php?module='.$_REQUEST['module'].'&action='.$_REQUEST['return_action'];
if ($_REQUEST['return_action'] == 'DetailView') {
    $url .= '&record=' . $_REQUEST['uid'];
}

$workflowModStrings = return_module_language($current_language, 'AOW_WorkFlow');

$summaryMessage .= $workflowModStrings['LBL_RUN_FLOW_FROM_RECORD_SUMMARY'];
$summaryMessage .= '<br>'.$workflowModStrings['LBL_RUN_FLOW_FROM_RECORD_SUMMARY_SUCCESS'] . ': ' .$recordsSuccess;
$summaryMessage .= '<br>'.$workflowModStrings['LBL_RUN_FLOW_FROM_RECORD_SUMMARY_NOT_MET_CONDTIONS'] . ': ' .$recordsNotMetConditions;
$summaryMessage .= '<br>'.$workflowModStrings['LBL_RUN_FLOW_FROM_RECORD_SUMMARY_FAILED_ACTIONS'] . ': ' .$recordsFailedActions;
SugarApplication::appendSuccessMessage('<div class="alert alert-success">' . $summaryMessage . '</div>');
SugarApplication::redirect($url);
