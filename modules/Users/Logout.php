<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
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
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */





// record the last theme the user used
$current_user->setPreference('lastTheme', $theme);
$GLOBALS['current_user']->call_custom_logic('before_logout');

if (method_exists($authController->authController, 'preLogout')) {
    $authController->authController->preLogout();
}

// submitted by Tim Scott from SugarCRM forums
foreach ($_SESSION as $key => $val) {
    $_SESSION[$key] = ''; // cannot just overwrite session data, causes segfaults in some versions of PHP
}
if (isset($_COOKIE[session_name()])) {
    SugarApplication::setCookie(session_name(), '', time()-42000, '/', null, isSSL(), true);
}

//Update the tracker_sessions table
// STIC-Custom 20241014 ART - Tracker Module
// https://github.com/SinergiaTIC/SinergiaCRM/pull/211
// Track the logout of the current user
if ($action === 'Logout') {
    // Get the instance of the TrackerManager
    $trackerManager = TrackerManager::getInstance();

    // Get the tracker monitor
    $monitor = $trackerManager->getMonitor('tracker');

    // If the monitor exists, set its values
    if ($monitor) {
        // Set the date and time of the logout
        $monitor->setValue('date_modified', $GLOBALS['timedate']->nowDb());

        // Set the user ID, assigned user ID, module name, action, item ID, item summary, visibility and session ID
        $monitor->setValue('user_id', $current_user->id);
        $monitor->setValue('assigned_user_id', $current_user->id);
        $monitor->setValue('module_name', 'Users');
        $monitor->setValue('action', 'logout');
        $monitor->setValue('item_id', $current_user->id);
        $monitor->setValue('item_summary', $current_user->full_name .' - Logout');
        $monitor->setValue('visible', true);
        $monitor->setValue('session_id', $monitor->getSessionId());

        // Save the monitor to the database
        $trackerManager->saveMonitor($monitor, true, true);
    }
}
// END STIC Custom
// clear out the authenticating flag
session_destroy();

LogicHook::initialize();
$GLOBALS['logic_hook']->call_custom_logic('Users', 'after_logout');

/** @var AuthenticationController $authController */
$authController->authController->logout();
