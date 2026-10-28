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

        $prefs = self::clearLockoutPreferences($user->id);

        // Clear session cache for this user's preferences
        $prefKey = $user->user_name . '_PREFERENCES';
        if (isset($_SESSION[$prefKey]['global'])) {
            $_SESSION[$prefKey]['global'] = $prefs;
        }

        $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ') ### User ' . $user->user_name . ' unlocked manually ###');

        return;
    }

    /**
     * Removes the lockout related preferences of a user directly in the database,
     * preserving the rest of their preferences.
     *
     * The bean preference API must not be used for a user other than the current one:
     * their preferences are not loaded into the session, so saving would overwrite the
     * whole preferences set with only the modified keys. This would wipe preferences
     * such as 'ut' and make the user wizard appear again on the next login.
     *
     * @param string $userId
     * @return array The updated preferences
     */
    public static function clearLockoutPreferences($userId)
    {
        $db = DBManagerFactory::getInstance();
        $userId = $db->quote($userId);

        // Load existing preferences to preserve non-lockout settings
        $result = $db->query("SELECT contents FROM user_preferences WHERE assigned_user_id = '{$userId}' AND category = 'global' AND deleted = 0");
        $row = $db->fetchByAssoc($result);
        $prefs = ($row && !empty($row['contents'])) ? unserialize(base64_decode($row['contents'])) : array();
        if (!is_array($prefs)) {
            $prefs = array();
        }

        // Clear lockout-related keys
        unset($prefs['user_locked_out'], $prefs['user_locked_out_time'], $prefs['lockout'], $prefs['loginfailed']);

        // Persist updated preferences, keeping the rest of them untouched
        $encoded = $db->quote(base64_encode(serialize($prefs)));
        if ($row) {
            $db->query("UPDATE user_preferences SET contents = '{$encoded}' WHERE assigned_user_id = '{$userId}' AND category = 'global' AND deleted = 0");
        } else {
            $id = $db->quote(create_guid());
            $db->query("INSERT INTO user_preferences (id, assigned_user_id, category, contents, deleted) VALUES ('{$id}', '{$userId}', 'global', '{$encoded}', 0)");
        }

        return $prefs;
    }
}