<?php

class stic_MessagesManController extends SugarController
{

    /**
     * Execute stic_Payment_CommitmentsUtils::recalculateAllFuturePaymentsViaSQL() method.
     * It is possible to force the execution of this action by executing this command from the browser console:
     * location.href='index.php?module=stic_Payment_Commitments&action=recalculateAllFuturePaymentsViaSQL'
     *
     * @return bool True if the operation was successful.
     */
    public function action_force() {
        require_once('modules/stic_MessagesMan/Utils.php');
        stic_MessagesManUtils::sendQueuedMessages(false);

        header("Location: index.php?module=stic_MessagesMan&action=index");
    }

    // TODOEPS: Això ha d'estar aquí? rep una row com paràmetre
    // protected function sendMessage($row) {
    //     require_once 'modules/stic_Settings/Utils.php';

    //     $messageman = BeanFactory::newBean('stic_MessagesMan');
    //     foreach ($row as $name => $value) {
    //         $messageman->$name = $value;
    //     }

    //     if (!empty($messageman->marketing_id)) {
    //         $marketingBean = BeanFactory::getBean('stic_Message_Marketing', $messageman->marketing_id);
    //         $sender = $marketingBean->sender;
    //     }
    //     else {
    //         $sender = stic_SettingsUtils::getSetting('MESSAGES_SENDER');
    //     }
    //     $templateId = $row['template_id_c'];
    //     $type = $row['type'];
    //     $return = $messageman->sendMessage($sender, $templateId, $type);

    //     // TODOEPS: Process return value... must remove from list?increment send_attemps?
    //     if (!$return) {
    //         $GLOBALS['log']->fatal('###EPS###' . __METHOD__ . __LINE__ ,);
    //     }
    // }

    public function xxx() {
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
}
