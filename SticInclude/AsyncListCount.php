<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2025 SinergiaTIC Association
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

// STIC-Custom OC - 20260309 - Async list count for ListView

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
sleep(5);
global $current_user;

$module = $_GET['module'] ?? '';
$where = $_GET['where'] ?? '';

// Validate module
if (empty($module)) {
    @ob_end_clean();
        ob_start();
        ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Module not specified']);
    return;
}

$bean = BeanFactory::getBean($module);
if (!$bean) {
    @ob_end_clean();
        ob_start();
        ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid module']);
    return;
}

// Create count query
$ret_array = $bean->create_new_list_query('', $where, [], [], 0, '', true, $bean, false);
$countQuery = $bean->create_list_count_query($ret_array['select'] . $ret_array['from'] . $ret_array['where']);

// Execute count
$db = DBManagerFactory::getInstance();
$result = $db->query($countQuery);
$row = $db->fetchByAssoc($result);
$total = intval($row['c'] ?? $row['count'] ?? 0);
@ob_end_clean();
        ob_start();
        ob_clean();
header('Content-Type: application/json');
echo json_encode(['success' => true, 'total' => $total]);
// END STIC-Custom OC
