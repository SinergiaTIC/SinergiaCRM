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

require_once 'modules/Users/views/view.detail.php';
require_once 'SticInclude/Views.php';

class CustomUsersViewDetail extends UsersViewDetail
{
    public function __construct()
    {
        parent::__construct();
    }

    public function preDisplay()
    {
        parent::preDisplay();

        SticViews::preDisplay($this);

        // Write here the SinergiaCRM code that must be executed for this module and view
        include_once "modules/stic_Remittances/Utils.php";
        stic_RemittancesUtils::fillDynamicListForIssuingOrganizations(true);
    }

    public function display()
    {
        global $current_user, $sugar_config;

        echo '<script> editACL = '. ACLController::checkAccess('stic_Work_Calendar', 'edit', true) .' </script>';
        
        // Show lockout status with countdown timer or unlock message for admins
        if (is_admin($current_user) || $current_user->isAdminForModule('Users')) {
            // Check if user is locked out and calculate remaining time
            $lockoutPref = $this->bean->getPreference('user_locked_out');
            $isLockedOut = !empty($lockoutPref) && $lockoutPref !== '0' && $lockoutPref !== 0 && $lockoutPref !== false;

            $lockoutTime = (int) $this->bean->getPreference('user_locked_out_time');
            $unlockMinutes = (int) ($sugar_config['userlockout']['automaticunlocktime'] ?? 0);
            $autoUnlockEnabled = $unlockMinutes > 0;
            $timeRemaining = 0;

            if ($isLockedOut && $autoUnlockEnabled && $lockoutTime > 0) {
                $timeRemaining = (($lockoutTime + ($unlockMinutes * 60)) - time());
            }

            // Auto-unlock when unlock period expires
            if ($isLockedOut && $autoUnlockEnabled && $timeRemaining <= 5) {
                $this->bean->setPreference('user_locked_out', '0');
                $this->bean->setPreference('user_locked_out_time', '');
                $this->bean->setPreference('lockout', '');
                $this->bean->setPreference('loginfailed', '0');
                $this->bean->savePreferencesToDB();
                SugarApplication::redirect('index.php?module=Users&action=DetailView&record=' . $this->bean->id);
            }

            // Render lockout message with countdown timer
            if ($isLockedOut) {
                $showUnlockButton = (is_admin($current_user) || $current_user->isAdminForModule('Users')) ? 1 : 0;

                if ($autoUnlockEnabled && $timeRemaining > 0) {
                    $timeRemaining = (int) $timeRemaining;
                    $minutes = floor($timeRemaining / 60);
                    $seconds = $timeRemaining % 60;
                    $countdownTpl = translate('LBL_USER_LOCKOUT_COUNTDOWN', 'Users');
                    $countdownText = str_replace(
                        ['{minutes}', '{seconds}'],
                        [(string) $minutes, str_pad((string) $seconds, 2, '0', STR_PAD_LEFT)],
                        $countdownTpl
                    );
                    echo '<p class="error">' .
                        '<span id="stic-user-lockout-countdown">' . $countdownText . '</span>' .
                        '<span id="stic-user-lockout-state" data-show-unlock-button="' . $showUnlockButton . '" data-remaining-seconds="' . $timeRemaining . '" style="display:none"></span>' .
                        '</p>';
                } else {
                    echo '<p class="error">' .
                        '⛔ ' . translate('ERR_USER_IS_LOCKED_OUT_DETAILVIEW', 'Users') .
                        '<span id="stic-user-lockout-state" data-show-unlock-button="' . $showUnlockButton . '" data-remaining-seconds="0" style="display:none"></span>' .
                        '</p>';
                }
            }
        }

        parent::display();

        SticViews::display($this);

        // Write here the SinergiaCRM code that must be executed for this module and view
        echo getVersionedScript("custom/modules/Users/SticUtils.js");
    }
}
