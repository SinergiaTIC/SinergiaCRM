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
            SELECT smm.id, smm.name, smm.status 
            FROM stic_message_marketing smm 
            join campaigns_stic_message_marketing_c csmmc on smm.id = csmmc.campaigns_stic_message_marketingmessage_idb 
            where smm.deleted = 0
            and csmmc.deleted = 0
            and campaigns_stic_message_marketingcampaign_ida = '{$campaign_id}'
        ";

        $result = $db->query($query);

        $mmlist = array();
        while ($row = $db->fetchByAssoc($result)) {
            $mmlist[$row['id']] = array(
                'name' => $row['name'], 
                'status' => $row['status'],
                'lists' => array(),
            );
        }

        $this->view = "selectMessageMarketing"; //call for the view file in views dir
        $this->view_object_map['MMLIST'] = $mmlist;
        $this->view_object_map['RETURN_MODULE'] = 'Campaigns';
        $this->view_object_map['RETURN_ID'] = $campaign_id;
        // $this->mapStepNavigation('results'); //next action to be run
    }

    public function action_sendMessages() {
        require_once 'modules/stic_Message_Marketing/Utils.php';
        
        $ids = $_REQUEST['mass'];

        foreach($ids as $mmid) {
            stic_Message_MarketingUtils::queueMessages($mmid);
        }

        header("Location: index.php?module=Campaigns&action=DetailView&record={$_REQUEST['return_id']}");
    }
}