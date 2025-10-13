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
}