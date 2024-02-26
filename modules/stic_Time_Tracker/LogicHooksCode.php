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
class stic_Time_TrackerLogicHooks
{
    public function manage_relationships(&$bean, $event, $arguments)
    {
        // Update the valor of the config variable stic_time_tracker_today_registration_started
        if ($arguments['related_module'] == 'Users') {
            switch ($event) {
                case 'after_relationship_delete':
                case 'after_relationship_add':
                    if ($arguments['related_id']) {
                        // Update the configuration of the stic_time_tracker_register_start
                        stic_Time_Tracker::updateTodayRegisterStatusPreference($arguments['related_id']);                        
                    }
                    else {
                        $GLOBALS['log']->error('Line '.__LINE__.': '.__METHOD__.': ' . 'The related Contact Id is empty');
                    }
                    break;
                default:
                    break;
            }
        }
    }

    public function after_ui_frame($event, $arguments) 
    {
        global $current_user, $sugar_config;
        
        // Check if the user has started any time registration today
        $GLOBALS['log']->debug('Line '.__LINE__.': '.__METHOD__.':  Checking if time registration is active or not.');
       
        // Config JS variables 
        $html =
        "<script type=\"text/javascript\" language=\"JavaScript\">" .
           "var todayRegistrationStarted = " . $current_user->getPreference('stic_time_tracker_today_registration_started') . "; " .
           "var siteURL = '" . $sugar_config['site_url'] . "';" .
        "</script>";

        echo $html;
        return "";
    }

}