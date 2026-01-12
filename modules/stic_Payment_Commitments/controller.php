<?php

require_once 'modules/stic_Payment_Commitments/Utils.php';
class stic_Payment_CommitmentsController extends SugarController
{

    public function action_copyPCProposals() {
        require_once 'modules/stic_Payment_Commitments/Utils.php';
        $result = stic_Payment_CommitmentsUtils::copyPCProposals($this->bean, $_REQUEST['originPC']);

        SugarApplication::redirect('index.php?module=stic_Payment_Commitments&action=DetailView&record=' . $_REQUEST['record']);

        return $result;
    }

    public function action_massCopyPCProposals() {
        // require_once 'modules/stic_Payment_Commitments/Utils.php';

        // $recordIds = explode(',', $_REQUEST['record']);
        // foreach ($recordIds as $targetPCId) {
        //     $targetPCBean = BeanFactory::getBean('stic_Payment_Commitments', $targetPCId);
        //     stic_Payment_CommitmentsUtils::copyPCProposals($targetPCBean, $_REQUEST['originPC']);
        // }


        //get proposals from originBean
        $originBean = BeanFactory::getBean('stic_Payment_Commitments', $_REQUEST['originPC']);
        $linkName = 'stic_allocation_proposals';
        $originBean->load_relationship($linkName);
        $proposalsIds = $originBean->$linkName->get(); 



        $db = DBManagerFactory::getInstance();
        $moduleTable = $this->bean->getTableName();
        $sql = "SELECT id FROM {$moduleTable} WHERE deleted = 0 ";

        if (isset($_REQUEST['select_entire_list']) && $_REQUEST['select_entire_list'] == '1' && isset($_REQUEST['current_query_by_page'])) {
            require_once 'include/export_utils.php';
            $retArray = generateSearchWhere('stic_Payment_Commitments', $_REQUEST['current_query_by_page']);
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
            $targetPCBean = BeanFactory::getBean('stic_Payment_Commitments', $row['id']);
            stic_Payment_CommitmentsUtils::copyProposals($targetPCBean, $proposalsIds);
        }

        SugarApplication::redirect("index.php?module=stic_Payment_Commitments&action=index");
        return true;
    }

    /**
     * Execute stic_Payment_CommitmentsUtils::recalculateAllFuturePaymentsViaSQL() method.
     * It is possible to force the execution of this action by executing this command from the browser console:
     * location.href='index.php?module=stic_Payment_Commitments&action=recalculateAllFuturePaymentsViaSQL'
     *
     * @return bool True if the operation was successful.
     */
    public function action_recalculateAllFuturePaymentsViaSQL()
    {

        $startTime = microtime(true);
        $startMemoryUsage = memory_get_usage();

        require_once 'modules/stic_Payment_Commitments/Utils.php';
        stic_Payment_CommitmentsUtils::recalculateAllFuturePaymentsViaSQL();

        $endTime = microtime(true);
        $endMemoryUsage = memory_get_usage();

        $executionTime = $endTime - $startTime;
        $memoryUsage = $endMemoryUsage - $startMemoryUsage;
        $memoryUsageInMb = round($memoryUsage / 1024 / 1024, 2);

        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": The recalculateAllFuturePaymentsViaSQL method has been executed in {$executionTime} seconds, using {$memoryUsageInMb} MB of memory");

        SugarApplication::redirect('index.php?module=stic_Payment_Commitments&action=index');

        return true;
    }

    /**
     * Execute stic_Payment_CommitmentsUtils::recalculateCurrentYearTotalPaidViaSQL() method.
     * It is possible to force the execution of this action by executing this command from the browser console:
     * location.href='index.php?module=stic_Payment_Commitments&action=recalculateCurrentYearTotalPaidViaSQL'
     *
     * @return bool True if the operation was successful.
     */
    public function action_recalculateCurrentYearTotalPaidViaSQL()
    {

        $startTime = microtime(true);
        $startMemoryUsage = memory_get_usage();

        require_once 'modules/stic_Payment_Commitments/Utils.php';
        stic_Payment_CommitmentsUtils::recalculateCurrentYearTotalPaidViaSQL();

        $endTime = microtime(true);
        $endMemoryUsage = memory_get_usage();

        $executionTime = $endTime - $startTime;
        $memoryUsage = $endMemoryUsage - $startMemoryUsage;
        $memoryUsageInMb = round($memoryUsage / 1024 / 1024, 2);

        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": The recalculateCurrentYearTotalPaidViaSQL method has been executed in {$executionTime} seconds, using {$memoryUsageInMb} MB of memory");

        SugarApplication::redirect('index.php?module=stic_Payment_Commitments&action=index');

        return true;
    }
}
