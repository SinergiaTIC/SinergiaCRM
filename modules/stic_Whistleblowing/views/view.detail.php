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

require_once('include/MVC/View/views/view.detail.php');

class stic_WhistleblowingViewDetail extends ViewDetail
{
    /**
     * The preDisplay method is executed before Smarty direct functions
     */
    public function preDisplay()
    {
        global $app_strings, $current_user;

        require_once 'modules/stic_Settings/Utils.php';
        $allowedUsersStr = stic_SettingsUtils::getSetting('WHISTLEBLOWING_ALLOWED_USERS');
        $allowedUsers = array_map('trim', explode(',', $allowedUsersStr));

        $isImpersonating = (isset($_SESSION['stic_impersonate_original_user']) && $_SESSION['stic_impersonate_original_user'] != $current_user->id);

        if (!in_array($current_user->id, $allowedUsers) || $isImpersonating) {
            $noPermissionLabel = $app_strings['LBL_WHISTLEBLOWING_NO_PERMISSION'] ?? translate('LBL_WHISTLEBLOWING_NO_PERMISSION', 'stic_Whistleblowing');
            SugarApplication::appendErrorMessage($noPermissionLabel);
            SugarApplication::redirect('index.php?module=stic_Whistleblowing&action=index');
            return;
        }

        parent::preDisplay();
    }
}