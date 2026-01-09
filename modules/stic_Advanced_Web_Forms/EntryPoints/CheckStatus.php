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

if (ob_get_level()) {
    ob_end_clean();
}
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$id = $_REQUEST['id'] ?? '';
if (empty($id)) {
    echo json_encode(['active' => false, 'message' => 'No ID provided']);
    exit;
}

$db = DBManagerFactory::getInstance();
$safeId = $db->quote($id);
$status = $db->getOne("SELECT status FROM stic_advanced_web_forms WHERE id = '$safeId' AND deleted = 0");
$isActive = ($status === 'public');

require_once 'include/utils.php';
$message = !$isActive ? translate('LBL_THEME_CLOSED_FORM_TEXT_VALUE', 'stic_Advanced_Web_Forms') : '';
echo json_encode(['active' => $isActive, 'message' => $message]);

exit;
