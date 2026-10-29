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

// Scheduled task that pushes stic_Work_Calendar records to the users' Google Calendar (primary)

$job_strings[] = 'sticWorkCalendarGooglePush';

/**
 * One-way push of stic_Work_Calendar records to Google Calendar.
 *
 * @return bool true when no per-user error blocked the pass
 */
function sticWorkCalendarGooglePush()
{
    $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ':  Running the task sticWorkCalendarGooglePush');
    require_once 'custom/include/SticGoogleSync/SticWorkCalendarSync.php';
    $sync = new SticWorkCalendarSync();
    return $sync->pushAllUsers();
}