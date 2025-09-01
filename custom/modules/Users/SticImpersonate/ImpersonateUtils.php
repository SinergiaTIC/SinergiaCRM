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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class ImpersonateUtils
{
    /**
     * Check if current user can impersonate other users
     * 
     * @return bool
     */
    public static function canImpersonate()
    {
        global $current_user;
        
        // Must be logged in
        if (empty($current_user) || empty($current_user->id)) {
            return false;
        }
        
        // Must be admin
        if (!$current_user->is_admin) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Check if a target user can be impersonated
     * 
     * @param string $target_user_id
     * @return bool
     */
    public static function canImpersonateUser($target_user_id)
    {
        global $current_user;
        
        // Basic permission check
        if (!self::canImpersonate()) {
            return false;
        }
        
        // Cannot impersonate self
        if ($target_user_id === $current_user->id) {
            return false;
        }
        
        // Load target user
        $target_user = BeanFactory::getBean('Users', $target_user_id);
        if (!$target_user || empty($target_user->id)) {
            return false;
        }
        
        // Target user must be active
        if ($target_user->status !== 'Active') {
            return false;
        }
        
        // Target user must not be deleted
        if ($target_user->deleted == '1') {
            return false;
        }
        
        return true;
    }
    
    /**
     * Log impersonation activity
     * 
     * @param string $action ('start' or 'stop')
     * @param string $target_user_id
     * @param string $original_user_id
     */
    public static function logActivity($action, $target_user_id = null, $original_user_id = null)
    {
        global $current_user;
        
        $log_message = "User Impersonation - Action: {$action}";
        
        if ($target_user_id) {
            $target_user = BeanFactory::getBean('Users', $target_user_id);
            $log_message .= ", Target User: {$target_user->user_name} ({$target_user_id})";
        }
        
        if ($original_user_id) {
            $original_user = BeanFactory::getBean('Users', $original_user_id);
            $log_message .= ", Original User: {$original_user->user_name} ({$original_user_id})";
        } else {
            $log_message .= ", Current User: {$current_user->user_name} ({$current_user->id})";
        }
        
        $GLOBALS['log']->info($log_message);
        
        // Also log to database for audit trail
        self::logToDatabase($action, $target_user_id, $original_user_id);
    }
    
    /**
     * Log to Tracker for audit trail
     * 
     * @param string $action
     * @param string $target_user_id
     * @param string $original_user_id
     */
    private static function logToDatabase($action, $target_user_id = null, $original_user_id = null)
    {
        global $current_user, $app_list_strings;

        $trackerManager = TrackerManager::getInstance();

        $monitor = $trackerManager->getMonitor('tracker');

        if ($monitor) {
            $target_user = BeanFactory::getBean('Users', $target_user_id);

            $monitor->setValue('date_modified', $GLOBALS['timedate']->nowDb());

            $monitor->setValue('user_id', $original_user_id ?: $current_user->id);
            $monitor->setValue('assigned_user_id', $original_user_id ?: $current_user->id);
            $monitor->setValue('module_name', 'Users');
            $monitor->setValue('action', $action);
            $monitor->setValue('item_id', $target_user_id);
            $item_summary = 'Impersonation of user '.$target_user->user_name.' with ID '.$target_user_id. ' '.$app_list_strings['trackers_actions_list'][$action];
            $monitor->setValue('item_summary', $item_summary);
            $monitor->setValue('visible', true);
            $monitor->setValue('session_id', $monitor->getSessionId());

            $trackerManager->saveMonitor($monitor, true, true);
        }
    }

    /**
     * Validate session integrity
     * 
     * @return bool
     */
    public static function validateSession()
    {
        if (isset($_SESSION['stic_impersonate_original_user'])) {
            $original_user_id = $_SESSION['stic_impersonate_original_user'];
            $original_user = BeanFactory::getBean('Users', $original_user_id);
            
            if (!$original_user || empty($original_user->id) || !$original_user->is_admin) {
                return false;
            }
        }
        
        return true;
    }
}

