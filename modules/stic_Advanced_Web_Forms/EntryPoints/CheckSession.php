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

global $current_user;
$response = [
    'is_logged' => false,
    'user_id' => null,
    'permissions' => true,
    'failed_modules' => [],
    'error' => ''
];

// Validar sesión
if (!empty($current_user) && !empty($current_user->id)) {
    $response['is_logged'] = true;
    $response['user_id'] = $current_user->id;

    // Validar ID del formulario
    $formId = $_REQUEST['id'] ?? '';
    if (empty($formId)) {
        $response['error'] = 'No Form ID provided';
        echo json_encode($response);
        exit;
    }

    // Cargar Bean del formulario
    $bean = BeanFactory::getBean('stic_Advanced_Web_Forms', $formId);
    if (!$bean || empty($bean->id)) {
        $response['error'] = 'Form not found';
        echo json_encode($response);
        exit;
    }

    // Buscamos los módulos asociados al formulario y verificamos permisos
    $configData = json_decode(html_entity_decode($bean->configuration), true);
    if ($configData && isset($configData['data_blocks'])) {
        foreach ($configData['data_blocks'] as $block) {
            $module = $block['module'] ?? '';
            if (!empty($module)) {
                // Check Access 'edit'
                if (!ACLController::checkAccess($module, 'edit', true)) {
                    $response['permissions'] = false;
                    $moduleName = translate($module);
                    if (!in_array($moduleName, $response['failed_modules'])) {
                        $response['failed_modules'][] = $moduleName;
                    }
                }
            }
        }
    }    
}

echo json_encode($response);
exit;
