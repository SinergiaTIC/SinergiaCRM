<?php

use Symfony\Component\Validator\Constraints\IsNull;

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
class stic_Time_TrackerController extends SugarController {


    /**
     * 
     * @return void
     */
    public function action_getTimeTrackerRegisterButtonStatus()
    {
        // Check if the user has started any time registration today
        $GLOBALS['log']->debug('Line '.__LINE__.': '.__METHOD__.':  Checking time tracker registration status.');
        global $current_user;
        
        include_once 'modules/MySettings/TabController.php';
        $controller = new TabController();
        $currentTabs = $controller->get_system_tabs();
        $timeTrackerModuleActive = in_array('stic_Time_Tracker', $currentTabs) ? 1 : 0;
        $data = array(
            'timeTrackerModuleActive' => $timeTrackerModuleActive,
            'timeTrackerActiveInEmployee' => $current_user->stic_clock_c ? 1:0,
            'todayRegistrationStarted' => $current_user->getPreference('stic_time_tracker_today_registration_started'),
        );
        
        // return the json result
        $json = json_encode($data);
        header('Content-Type: application/json');
        echo $json;
        die();
    }

    /**
     * 
     * @return void
     */
    public function action_createOrUpdateTodayRegister()
    {
        global $current_user, $timedate;

        // Check if the user has started any time registration today
        include_once 'modules/stic_Time_Tracker/stic_Time_Tracker.php';        
        $todayUserRegistrationData = stic_Time_Tracker::getTodayRegisterForEmployee($current_user->id);
        $todayRegistrationStarted =  $todayUserRegistrationData ? empty($todayUserRegistrationData["end_date"]) : false;

        if ($todayRegistrationStarted) {           
            // Update the end date field of today's time tracker for the current user
            $bean = BeanFactory::getBean($this->module, $todayUserRegistrationData['id']);
            $bean->end_date = $timedate->now();
            $bean->name = '';
        } else {
            // Create today's time tracker record for the current user
            $bean = BeanFactory::getBean($this->module);
            $bean->start_date = $timedate->now();
            $bean->users_stic_time_trackerusers_ida = $current_user->id;
            $bean->users_stic_time_tracker_name = $current_user->name;
            $bean->assigned_user_id = $current_user->id;
            $bean->assigned_user_name = $current_user->name;
        }
        $bean->save();   
        die();
    }
}