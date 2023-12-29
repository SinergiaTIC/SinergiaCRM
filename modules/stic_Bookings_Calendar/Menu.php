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
global $mod_strings, $app_strings, $sugar_config;

if (ACLController::checkAccess('stic_Bookings', 'edit', true)) {
    $module_menu[] = array("index.php?module=stic_Bookings&action=EditView&return_module=stic_Bookings_Calendar&return_action=index", translate('LNK_NEW_RECORD', 'stic_Bookings'), "create-stic-bookings", 'stic_Bookings');
}

if (ACLController::checkAccess('stic_Resources', 'edit', true)) {
    $module_menu[] = array("index.php?module=stic_Resources&action=EditView&return_module=stic_Bookings_Calendar&return_action=index", translate('LNK_NEW_RECORD', 'stic_Resources'), "create-stic-resources", 'stic_Resources');
}
