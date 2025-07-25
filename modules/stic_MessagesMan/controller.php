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

    // TODOEPS: Eliminar codi no emprat
    public function action_forceXXXX()
    {
        $admin = BeanFactory::newBean('Administration');
        $admin->retrieveSettings();
        // TODOEPS: Max messages per run? If implemented, do it as a STIC config value?
        if (isset($admin->settings['massemailer_campaign_messages_per_run'])) {
            $max_messages_per_run = $admin->settings['massemailer_campaign_messages_per_run'];
        }
        if (empty($max_messages_per_run)) {
            $max_messages_per_run = 500; //default
        }

        $db = DBManagerFactory::getInstance();
        $timedate = TimeDate::getInstance();
        $messagesMan = BeanFactory::newBean('stic_MessagesMan');

        $select_query = " 
            SELECT stic_messagesman.*, stic_message_marketing.template_id_c, stic_message_marketing.type 
             FROM stic_messagesman 
             join stic_message_marketing on stic_message_marketing.id = stic_messagesman.marketing_id  ";
        $select_query .= " WHERE send_date_time <= " . $db->now();
        $select_query .= " AND stic_messagesman.deleted = 0 AND stic_message_marketing.deleted = 0";
        $select_query .= " AND (in_queue ='0' OR in_queue IS NULL OR ( in_queue ='1' AND in_queue_date <= " . $db->convert($db->quoted($timedate->fromString("-1 day")->asDb()), "datetime") . ")) ";
    
        if (!empty($campaign_id)) {
            $select_query .= " AND campaign_id='{$campaign_id}'";
        }
        $select_query .= " ORDER BY send_date_time ASC,user_id, list_id";
    
        DBManager::setQueryLimit(0);
        $result = $db->query($select_query);

        if(!$result) {
            $GLOBALS['log']->fatal('###EPS###' . __METHOD__ . __LINE__ ,);
            return false;
        }
        while ($row = $db->fetchByAssoc($result)) {
            $GLOBALS['log']->fatal('###EPS###' . __METHOD__ . __LINE__ , $row['id']);
            $this->sendMessage($row);
        }
        header("Location: index.php?module=stic_MessagesMan&action=index");

    }

    protected function sendMessage($row) {
        require_once 'modules/stic_Settings/Utils.php';

        // TODOEPS: Check if related bean is in an except list

        $messageman = BeanFactory::newBean('stic_MessagesMan');
        foreach ($row as $name => $value) {
            $messageman->$name = $value;
        }

        // TODOEPS: Recueprar sender de la campanya
        $sender = stic_SettingsUtils::getSetting('MESSAGES_SENDER');
        $templateId = $row['template_id_c'];
        $type = $row['type'];
        $return = $messageman->sendMessage($sender, $templateId, $type);

        // TODOEPS: Process return value... must remove from list?increment send_attemps?
        if (!$return) {
            $GLOBALS['log']->fatal('###EPS###' . __METHOD__ . __LINE__ ,);
        }
    }

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
