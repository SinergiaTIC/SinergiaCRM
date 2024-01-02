<?php
/* * *******************************************************************************
 * This file is part of KReporter. KReporter is an enhancement developed
 * by Christian Knoll. All rights are (c) 2012 by Christian Knoll
 *
 * This Version of the KReporter is licensed software and may only be used in
 * alignment with the License Agreement received with this Software.
 * This Software is copyrighted and may not be further distributed without
 * witten consent of Christian Knoll
 *
 * You can contact us at info@kreporter.org
 * ****************************************************************************** */
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('modules/KReports/KReport.php');

class pluginktargetlistexportcontroller {

    public function action_export_to_targetlist() {
       
        // STIC-Custom 20230710 AAM - These params aren't needed, because we use the sever params.
        // STIC#1010
        // ini_set('maximum_execution_time', '3600');
		// ini_set("memory_limit", "1024M");
        // END STIC-Custom
        
        $thisReport = new KReport();
        $thisReport->retrieve($_REQUEST['record']);

        // check if we have set dynamic Options
        if (isset($_REQUEST['whereConditions'])) 
            $thisReport->whereOverride = json_decode_kinamu(html_entity_decode($_REQUEST['whereConditions']));
        
        // STIC-Custom 20230710 AAM - Adding contacts/acounts/leads to the selected prospect_list.
        // STIC#1010
        // $thisReport->createTargeList($_REQUEST['targetlist_name']);
        global $db;
        $emailableModules = $this->getEmailableModules();
        $emailableModules = implode("','",$emailableModules);
        $prospectListId = $_REQUEST['prospectListId'];
        $reportQuery = $thisReport->get_report_main_sql_query();
        if (isset($_REQUEST['replacement']) && $_REQUEST['replacement'] === "true") {
            // If replacement is true, we remove firsts previous related records from the LPO
            $deleteRelationQuery = "UPDATE prospect_lists_prospects plp SET deleted = 1, date_modified = NOW()
            WHERE
                plp.prospect_list_id = '$prospectListId'
                AND plp.deleted = 0 AND 
                plp.related_id NOT IN (select reportQuery.sugarRecordId FROM ( 
                $reportQuery) reportQuery)";
            $resultQuery = $db->query($deleteRelationQuery);
            if (!$resultQuery) {
                $GLOBALS['log']->error(__LINE__.__METHOD__. " - There is an error running the query that deletes the LPO relationship");
            }
        } 
        // We insert the record from the report that are missing in the selected LPO
        $insertRelationQuery = "INSERT INTO
            prospect_lists_prospects (
            SELECT
                UUID() as id,
                '$prospectListId' as prospect_list_id,
                reportQuery.sugarRecordId as related_id,
                reportQuery.sugarRecordModule as related_type,
                UTC_TIMESTAMP() as date_modified,
                0 as deleted
            FROM
                ($reportQuery) reportQuery
            LEFT JOIN 
                prospect_lists_prospects plp
            ON
                plp.related_id = reportQuery.sugarRecordId
                AND plp.related_type = reportQuery.sugarRecordModule
                AND plp.prospect_list_id = '$prospectListId'
                AND plp.deleted = 0
            WHERE
                plp.related_id is null 
                AND reportQuery.sugarRecordModule IN ('$emailableModules')

            )";
        $resultQuery = $db->query($insertRelationQuery);
        if (!$resultQuery) {
            $GLOBALS['log']->error(__LINE__.__METHOD__. " - There is an error running the query that inserts the LPO relationship");
        }
        
        // END STIC-Custom
        return true;
    }

    protected function getEmailableModules() {
        global $beanFiles, $beanList, $app_list_strings;
        $excludedModules = array();

        if (file_exists('modules/KReports/kreportsConfig.php'))
            include('modules/KReports/kreportsConfig.php');

        $returnArray = array();
        foreach ($app_list_strings['moduleList'] as $module => $description) {
            if (!in_array($module, $excludedModules))
                $returnArray[] = $module;
        }
        $emailableModules = array();
        foreach ($returnArray as $bean_name) {
            if (isset($beanList[$bean_name]) && isset($beanFiles[$beanList[$bean_name]])) {
                require_once($beanFiles[$beanList[$bean_name]]);
                $obj = new $beanList[$bean_name];
                if ($obj instanceof Person || $obj instanceof Company) {
                    $emailableModules[] = $bean_name;
                }
            }
        }
        asort($emailableModules);
        return $emailableModules;
    }
}
