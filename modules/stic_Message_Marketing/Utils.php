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
class stic_Message_MarketingUtils
{


    protected static function getMessageMarketingInfo($mmid) {
        $db = DBManagerFactory::getInstance();

        $query = "
            SELECT select_all, prospect_lists, start_date_time, csmmc.campaigns_stic_message_marketingcampaign_ida as campaignId 
            FROM stic_message_marketing smm
            JOIN campaigns_stic_message_marketing_c csmmc ON csmmc.campaigns_stic_message_marketingmessage_idb = smm.id
            WHERE id = '{$mmid}'
        ";

        $result = $db->query($query);
        if(!$result) {
            //TODOEPS
            $GLOBALS['log']->fatal('###EPS###' . __METHOD__ . __LINE__ ,);
            return false;
        }
        $marketingRow = $db->fetchByAssoc($result);
        if(!$marketingRow) {
            //TODOEPS
            $GLOBALS['log']->fatal('###EPS###' . __METHOD__ . __LINE__ ,);
            return false;
        }
        return $marketingRow;

    }

    protected static function getProspectsQuery($mmid, $test, $marketingRow) {

        $selectAll = $marketingRow['select_all'];

        if ($selectAll) {
            // Get prospects from all default list from Campaign
            $query = "
            SELECT pl.id, plp.related_type, plp.related_id 
            FROM campaigns_stic_message_marketing_c csmmc 
            JOIN prospect_list_campaigns plc on plc.campaign_id = csmmc.campaigns_stic_message_marketingcampaign_ida 
            JOIN prospect_lists pl on pl.id = plc.prospect_list_id 
            JOIN prospect_lists_prospects plp on plp.prospect_list_id = pl.id
            WHERE csmmc.campaigns_stic_message_marketingmessage_idb = '{$mmid}'
            AND csmmc.deleted = 0
            AND plc.deleted = 0
            AND pl.deleted = 0
            AND plp.deleted = 0
            AND pl.list_type IN ('default', 'test')
            ";
        }
        else {
            // Get prospects from mm selected lists
            $rawLists = $marketingRow['prospect_lists'];
            $rawLists = str_replace('^,^', "','", $rawLists);
            $rawLists = str_replace('^', "'", $rawLists);

            $query = "
            SELECT pl.id, plp.related_type, plp.related_id 
            FROM prospect_lists pl 
            JOIN prospect_lists_prospects plp on plp.prospect_list_id = pl.id
            WHERE pl.id IN ({$rawLists})
            AND pl.deleted = 0
            AND plp.deleted = 0
            AND pl.list_type IN ('default', 'test')
            ";
        }
        return $query;
    }

    protected static function queueProspects($query, $mmid, $marketingRow) {
        $db = DBManagerFactory::getInstance();
        
        $result = $db->query($query);

        while ($row = $db->fetchByAssoc($result)) {
            // TODOEPS: Add prospect toMessageMan
            $GLOBALS['log']->fatal('###EPS###' . __METHOD__ . __LINE__ ,$row);
            $messageMan = BeanFactory::newBean('stic_MessagesMan');
            $messageMan->marketing_id = $mmid;
            $messageMan->list_id = $row['id'];
            $messageMan->send_date_time = $marketingRow['start_date_time'];
            $messageMan->in_queue = 0;
            $messageMan->send_attempts = 0;
            $messageMan->related_id = $row['related_id'];
            $messageMan->related_type = $row['related_type'];
            $messageMan->save();
        }
    }

    protected static function removeExempts($mmid, $marketingRow) {
        $db = DBManagerFactory::getInstance();

        $query = "
            DELETE mm
            from stic_messagesman mm
            JOIN prospect_lists_prospects plp ON plp.related_id = mm.related_id and plp.related_type = mm.related_type 
            JOIN prospect_lists pl ON pl.id = plp.prospect_list_id 
            JOIN prospect_list_campaigns plc ON plc.prospect_list_id = pl.id
            WHERE pl.list_type = 'exempt'
            AND plc.campaign_id like '%'
            AND plc.deleted = 0
            AND pl.deleted = 0
            and plp.deleted = 0
        ";

        $result = $db->query($query);

        if (!$result) {
            // TODOEPS
            $GLOBALS['log']->fatal('###EPS###' . __METHOD__ . __LINE__ ,);
        }
    }

    public static function queueMessages($mmid) {
        

        // TODOEPS: $test
        $test = false;

        $marketingRow = self::getMessageMarketingInfo($mmid);

        $query = self::getProspectsQuery($mmid, $test, $marketingRow);

        self::queueProspects($query, $mmid, $marketingRow);

        if(!$test) {
            self::removeExempts($mmid, $marketingRow);
        }
        
    }

}
