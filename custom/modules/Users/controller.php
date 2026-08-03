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

require_once 'modules/Users/controller.php';
class CustomUsersController extends UsersController
{
    /**
     * Start impersonation of a target user
     */
    public function action_startImpersonation() {
        global $current_user;

        if (!$current_user->is_admin) {
            $GLOBALS['log']->fatal(__METHOD__.__LINE__.'Access denied. Only administrators can use impersonation.');
            die('Access denied. Only administrators can use this feature.');
        }

        $target_user_id = $_REQUEST['userId'] ?? '';
        
        if (empty($target_user_id)) {
            $GLOBALS['log']->fatal(__METHOD__.__LINE__.'Target user ID is missing.');
            die('Target user ID is missing.');
        }
        require_once 'custom/modules/Users/SticImpersonate/Impersonate.php';
        $impersonate = new Impersonate();
        
        if ($impersonate->startImpersonation($target_user_id)) {
            // Redirect to home page
            SugarApplication::redirect('index.php?module=Home&action=index');
        } else {
            $GLOBALS['log']->fatal(__METHOD__.__LINE__.'Failed to start impersonation for user ID: ' . $target_user_id);
            die('Failed to start impersonation for user ID: ' . $target_user_id);
        }
        
        SugarApplication::redirect('index.php?module=Home&action=index');
    }
    /**
     * Stop impersonation and revert to original user
     */
    public function action_stopImpersonation()
    {
        require_once 'custom/modules/Users/SticImpersonate/Impersonate.php';
        $impersonate = new Impersonate();
        
        if ($impersonate->stopImpersonation()) {
            // Redirect to home page
            SugarApplication::redirect('index.php');
        } else {
            $GLOBALS['log']->fatal(__METHOD__.__LINE__.'Failed to stop impersonation.');
            die('Failed to stop impersonation.');
        }
    }

    /**
    * Action to manually unlock a user account by an admin user
    * This is used when the automatic unlock period has not yet expired but an admin wants to unlock the user immediately
    */
    public function action_unlockuser()
    {
        global $current_user;
        $record = $_REQUEST['record'] ?? '';

        if (!is_admin($current_user) || empty($record)) {
            SugarApplication::redirect('index.php?module=Users&record=' . $record . '&action=DetailView');
            return;
        }

        $user = BeanFactory::newBean('Users');
        $user->retrieve($record);
        if (empty($user->id)) {
            SugarApplication::redirect('index.php?module=Users&action=index');
            return;
        }

        $db = DBManagerFactory::getInstance();
        $userId = $db->quote($user->id);

        // Load existing preferences to preserve non-lockout settings
        $result = $db->query("SELECT contents FROM user_preferences WHERE assigned_user_id = '{$userId}' AND category = 'global' AND deleted = 0");
        $row = $db->fetchByAssoc($result);
        $prefs = $row ? unserialize(base64_decode($row['contents'])) : [];

        // Clear lockout-related keys
        unset($prefs['user_locked_out'], $prefs['user_locked_out_time'], $prefs['lockout'], $prefs['loginfailed']);

        // Persist updated preferences
        $encoded = base64_encode(serialize($prefs));
        $db->query("DELETE FROM user_preferences WHERE assigned_user_id = '{$userId}' AND category = 'global'");
        $db->query("INSERT INTO user_preferences (id, assigned_user_id, category, contents, deleted) VALUES ('{$userId}-global', '{$userId}', 'global', '{$encoded}', 0)");

        // Clear session cache for this user's preferences
        $prefKey = $user->user_name . '_PREFERENCES';
        if (isset($_SESSION[$prefKey]['global'])) {
            $_SESSION[$prefKey]['global'] = $prefs;
        }

        $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ') ### User ' . $user->user_name . ' unlocked manually ###');

        return;
    }
}