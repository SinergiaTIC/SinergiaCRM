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

//prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class App_TrackAccess
{
    public function TrackAccess(&$bean, $event, $arguments)
    {
        global $current_user;

        if (!isset($_SESSION['TRACKER'])) {
            $_SESSION['TRACKER'] = array();
        }

        // prevents multiples entries on same action and event
        if ($_SESSION['TRACKER']['ID'] != $_SERVER['UNIQUE_ID'] ||
            $_SESSION['TRACKER']['EVENT'] != $event ||
            $_SESSION['TRACKER']['MODULE'] != $bean->module_name) {

            $_SESSION['TRACKER']['ID'] = $_SERVER['UNIQUE_ID'];
            $_SESSION['TRACKER']['EVENT'] = $event;
            $_SESSION['TRACKER']['MODULE'] = $bean->module_name;

            $tracker = new Tracker();
            $tracker->item_summary = $bean->name;
            $tracker->user_id = $current_user->id;
            $tracker->action = self::decodeEvent($bean, $event, $arguments);
            $tracker->module_name = $bean->module_name;
            $tracker->item_id = $bean->id;
            $tracker->tracker_user = $current_user->user_name;

            // Modules that are excluded to track
            $excludedModules = ['vCals', 'Reminders', 'Reminders_Invitees', 'UserPreferences', 'SugarFeed'];

            // Untrack the innecesary information
            if (in_array($bean->module_name, $excludedModules) ||
                $bean->action == 'login_ok' ||
                $bean->action == 'logout' ||
                $bean->action == 'record_deletion' &&
                $event == 'after_save') {
                return;
            } else {
                $tracker->save();
            }

        }
    }

    // decode event to action list element
    protected static function decodeEvent($bean, $event, $arguments)
    {
        switch ($event) {
            case 'after_save': //Executes after a record is saved.
            case 'before_save': //Executes before a record is saved.
                if (empty($bean->fetched_row['id'])) {
                    return 'record_creation';
                } else {
                    return 'record_update';
                }

            case 'before_delete': //Executes before a record is deleted
            case 'after_delete': //Executes after a record is deleted
                return 'record_deletion';

            default: //Other events
                return '';
        }

    }
}
