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
 * Functionality to handle user impersonation
 */
class Impersonate {
    /**
     * Check if current user is an administrator
     * 
     * @return bool
     */
    public function isAdmin()
    {
        require_once('custom/modules/Users/SticImpersonate/ImpersonateUtils.php');
        return ImpersonateUtils::canImpersonate();
    }

    /**
     * Check if user is currently impersonating another user
     * 
     * @return bool
     */
    public function isImpersonating()
    {
        return isset($_SESSION['stic_impersonate_original_user']);
    }

    /**
     * Get the original user ID if impersonating
     * 
     * @return string|null
     */
    public function getOriginalUserId()
    {
        return isset($_SESSION['stic_impersonate_original_user']) 
            ? $_SESSION['stic_impersonate_original_user'] 
            : null;
    }

    /**
     * Get the impersonated user ID
     * 
     * @return string|null
     */
    public function getImpersonatedUserId()
    {
        return isset($_SESSION['stic_impersonate_target_user']) 
            ? $_SESSION['stic_impersonate_target_user'] 
            : null;
    }

    /**
     * Start impersonating a user
     * 
     * @param string $target_user_id
     * @return bool
     */
    public function startImpersonation($target_user_id)
    {
        global $current_user;

        require_once('custom/modules/Users/SticImpersonate/ImpersonateUtils.php');

        // Security checks
        if (!ImpersonateUtils::canImpersonateUser($target_user_id)) {
            return false;
        }

        // Load target user
        $target_user = BeanFactory::getBean('Users', $target_user_id);
        if (!$target_user || empty($target_user->id)) {
            return false;
        }

        // Store original user information
        $_SESSION['stic_impersonate_original_user'] = $current_user->id;
        $_SESSION['stic_impersonate_target_user'] = $target_user_id;

        // Log the activity
        ImpersonateUtils::logActivity('impersonate_start', $target_user_id);

        // Switch current user
        $current_user = $target_user;
        $_SESSION['authenticated_user_id'] = $target_user_id;

        return true;
    }

    /**
     * Stop impersonation and return to original user
     * 
     * @return bool
     */
    public function stopImpersonation()
    {
        global $current_user;

        require_once('custom/modules/Users/SticImpersonate/ImpersonateUtils.php');

        if (!$this->isImpersonating()) {
            return false;
        }

        // Validate session integrity
        if (!ImpersonateUtils::validateSession()) {
            return false;
        }

        $original_user_id = $_SESSION['stic_impersonate_original_user'];
        $target_user_id = $_SESSION['stic_impersonate_target_user'];

        // Load original user
        $original_user = BeanFactory::getBean('Users', $original_user_id);
        if (!$original_user || empty($original_user->id)) {
            return false;
        }

        // Log the activity
        ImpersonateUtils::logActivity('impersonate_stop', $target_user_id, $original_user_id);

        // Restore original user
        $current_user = $original_user;
        $_SESSION['authenticated_user_id'] = $original_user_id;

        // Clear impersonation session variables
        unset($_SESSION['stic_impersonate_original_user']);
        unset($_SESSION['stic_impersonate_target_user']);

        return true;
    }

    /**
     * Get impersonation status information
     * 
     * @return array
     */
    public function getImpersonationStatus()
    {
        global $current_user;

        $status = array(
            'is_impersonating' => $this->isImpersonating(),
            'is_admin' => $this->isAdmin(),
            'target_user_id' => $current_user->id,
            'target_user_name' => $current_user->user_name,
            'target_user_full_name' => $current_user->full_name,
        );

        if ($this->isImpersonating()) {
            $original_user_id = $this->getOriginalUserId();
            $original_user = BeanFactory::getBean('Users', $original_user_id);
            
            $status['original_user_id'] = $original_user_id;
            $status['original_user_name'] = $original_user->user_name;
            $status['original_user_full_name'] = $original_user->full_name;
        }

        return $status;
    }
}