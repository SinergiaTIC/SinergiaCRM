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

// global $messageableModules;

// This array can be extended in custom folder to add new modules or change default fields


class stic_MessagesManUtils {
    public static function sendQueuedMessages($all = true) {
        $admin = BeanFactory::newBean('Administration');
        $admin->retrieveSettings();
        // TODOEPS: Max messages per run? If implemented, do it as a STIC config value?
        if (isset($admin->settings['massemailer_campaign_messages_per_run'])) {
            $max_messages_per_run = $admin->settings['massemailer_campaign_messages_per_run'];
        }
        if (empty($max_messages_per_run)) {
            $max_messages_per_run = 500; //default
        }

        // TODOEPS: test mode?
        // Preparation in case we decide to implement a test mode for sending messages
        // $test = false;
        // if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'test') {
        //     $test = true;
        // }
        // if ($test) {
        // }
        // else {}

        $db = DBManagerFactory::getInstance();
        $timedate = TimeDate::getInstance();
        $messagesMan = BeanFactory::newBean('stic_MessagesMan');

        // TODOEPS: retries en enviament de missatges?
        $select_query = " 
            SELECT stic_messagesman.*, stic_message_marketing.template_id_c, stic_message_marketing.type 
             FROM stic_messagesman 
             join stic_message_marketing on stic_message_marketing.id = stic_messagesman.marketing_id  ";
        $select_query .= " WHERE send_date_time <= " . $db->now();
        $select_query .= " AND stic_messagesman.deleted = 0 AND stic_message_marketing.deleted = 0";
        $select_query .= " AND (in_queue ='0' OR in_queue IS NULL OR ( in_queue ='1' AND in_queue_date <= " . $db->convert($db->quoted($timedate->fromString("-1 day")->asDb()), "datetime") . ")) ";
    
        // TODOEPS: Vaciar sólo una campaña?
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
            self::sendMessage($row);
        }
        return true; // Everything OK
    }

    protected static function sendMessage($row) {
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
}
