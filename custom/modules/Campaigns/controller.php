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

require_once('modules/Campaigns/controller.php');
class CustomCampaignsController extends CampaignsController
{
    // STIC - 20210624  - We override the process function to recover the classic view to create or edit a campaign
    public function process()
    {
        SugarController::process();
    }

    /**
     * Get the tracker URLs of a campaign
     * @return void 
     */
    public function action_getCampaignTrackerURLs()
    {
        $trackerURLs = array();
        $campaignId = $_POST['campaignID'] ?? null;

        if (!empty($campaignId)) 
        {
            $GLOBALS['log']->debug('Line '.__LINE__.': '.__METHOD__.':  Getting the tracker URLs of the campaign: ');
            global $db;
            $query = "SELECT id,tracker_name, tracker_url FROM campaign_trkrs WHERE campaign_id = '$campaignId' AND deleted = 0";
            $result = $db->query($query);
            while (($row = $db->fetchByAssoc($result)) != null) {
                $trackerURLs[] = array('url' => $row['tracker_url']);
            }
        }

        // return the json result
        ob_clean();
        $json = json_encode($trackerURLs);
        header('Content-Type: application/json');
        echo $json;
        ob_flush();
        die();
    }
}
