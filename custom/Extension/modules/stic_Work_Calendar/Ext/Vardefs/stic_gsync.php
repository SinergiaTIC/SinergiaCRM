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

// Push matching columns (one-way sync to Google Calendar primary)
$dictionary['stic_Work_Calendar']['fields']['stic_gsync_id'] = array(
    'name' => 'stic_gsync_id',
    'vname' => 'LBL_STIC_GSYNC_ID',
    'type' => 'varchar',
    'len' => 1024,
    'importable' => false,
    'reportable' => false,
    'audited' => false,
    'massupdate' => 0,
    'studio' => 'visible',
    'source' => 'db',
    'unified_search' => false,
);

$dictionary['stic_Work_Calendar']['fields']['stic_gsync_lastsync'] = array(
    'name' => 'stic_gsync_lastsync',
    'vname' => 'LBL_STIC_GSYNC_LASTSYNC',
    'type' => 'int',
    'importable' => false,
    'reportable' => false,
    'audited' => false,
    'massupdate' => 0,
    'studio' => 'visible',
    'source' => 'db',
    'unified_search' => false,
);