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

/**
 * This class is the controller of the stic_Incorpora module. This module gathers all the necessary
 * functions and views to manage the data synchronization with Incorpora remote database, through their
 * Web Services.
 *
 * In order to build the views with the user info and parameteres, this controller uses the variable
 * view_object_map. This variable is redirected to the tpl smarty template, with the name of $MAP,
 * and it can be manipulated as needed
 */
class stic_Message_MarketingController extends SugarController {

    public function action_selectMessageMarketing() {
        global $current_language;

        $db = DBManagerFactory::getInstance();

        $current_module_strings = return_module_language($current_language, 'EmailMarketing');
        echo getClassicModuleTitle('Campaigns', array($current_module_strings['LBL_MODULE_SEND_EMAILS']), false);
        $campaign_id = isset($_REQUEST['record']) ? $_REQUEST['record'] : false;

        if (!empty($campaign_id)) {
            $campaign = BeanFactory::newBean('Campaigns');
            $campaign->retrieve($campaign_id);
        }

        $query = "
            SELECT smm.id, smm.name, smm.status, smm.select_all, smm.prospect_lists 
            FROM stic_message_marketing smm 
            join campaigns_stic_message_marketing_c csmmc on smm.id = csmmc.campaigns_stic_message_marketingmessage_idb 
            where smm.deleted = 0
            and csmmc.deleted = 0
            and campaigns_stic_message_marketingcampaign_ida = '{$campaign_id}'
        ";

        $result = $db->query($query);

        $mmlist = array();
        while ($row = $db->fetchByAssoc($result)) {
            $prospectLists = $this->getProspectLists($campaign_id, $row['select_all'], $row['prospect_lists']);
            $prospectListsString = implode(', ', $prospectLists);
            $mmlist[$row['id']] = array(
                'name' => $row['name'], 
                'status' => $row['status'],
                'lists' => $prospectListsString,
            );
        }

        $this->view = "selectMessageMarketing"; //call for the view file in views dir
        $this->view_object_map['MMLIST'] = $mmlist;
        $this->view_object_map['RETURN_MODULE'] = 'Campaigns';
        $this->view_object_map['RETURN_ID'] = $campaign_id;
        $this->view_object_map['TEST'] = $_REQUEST['test'] ?? 'false';
        // $this->mapStepNavigation('results'); //next action to be run
    }

    protected function getProspectLists($campaignId, $selectAll, $prospectListsString) {
        if ($selectAll) {
            return $this->getCampaignLists($campaignId);
        }
        else {
            // $prospectLists= trim($prospectListsString, '^');
            // $prospectLists = explode('^,^', $prospectListsString);
            $prospectListsString = str_replace('^', '\'', $prospectListsString);
            return $this->getProspectListsNames($prospectListsString);
        }
    }

    protected function getCampaignLists($campaignId) {
        $db = DBManagerFactory::getInstance();

        $query = "
            SELECT pl.name
            FROM prospect_list_campaigns plc
            join prospect_lists pl on pl.id = plc.prospect_list_id
            where plc.campaign_id = '$campaignId'
            and pl.list_type in ('default', 'test')
            and plc.deleted = 0
            and pl.deleted = 0";

        $result = $db->query($query);

        $prospectListsArray = array();
        while ($row = $db->fetchByAssoc($result)) {
            $prospectListsArray[] = $row['name'];
        }  

        return $prospectListsArray;
    }

    protected function getProspectListsNames($prospectListsString) {
        $db = DBManagerFactory::getInstance();

        $query = "
            SELECT pl.name
            FROM prospect_lists pl 
            WHERE id in ($prospectListsString)
            AND deleted = 0
            ";

        $result = $db->query($query);

        $prospectListsArray = array();
        while ($row = $db->fetchByAssoc($result)) {
            $prospectListsArray[] = $row['name'];
        }  

        return $prospectListsArray;
    }

    public function action_sendMessages() {
        require_once 'modules/stic_Message_Marketing/Utils.php';
        require_once 'modules/stic_MessagesMan/Utils.php';
        
        $ids = $_REQUEST['mass'] ?? array();
        $test = $_REQUEST['test'] ?? 'false';

        $test = ($test === 'false') ? false : true;

        if ($test) {
            foreach($ids as $mmid) {
                stic_Message_MarketingUtils::queueMessages($mmid, true);
                stic_MessagesManUtils::sendQueuedMessages(true);
            }
        }
        else {
            foreach($ids as $mmid) {
                stic_Message_MarketingUtils::queueMessages($mmid);
            }
        }

        header("Location: index.php?module=Campaigns&action=DetailView&record={$_REQUEST['return_id']}");
        $this->redirect_url = "index.php?module=Campaigns&action=DetailView&record={$_REQUEST['return_id']}";
    }

    public function action_getDefaultSender() {
        require_once 'modules/stic_Settings/Utils.php';

        $defaultMessageSender = stic_SettingsUtils::getSetting('MESSAGES_SENDER');

        $response = array();
        $response['code'] = 'OK';
        $response['data']['defaultSender'] = $defaultMessageSender;

        echo json_encode($response);
        exit;

    }
}