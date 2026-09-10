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

require_once 'SticInclude/Portal/AuthUtils.php';

session_start();
if (!empty($_SESSION['portal_user_id'])) {
    $bean = null;
    if (!empty($_SESSION['portal_user_type'])) {
        $bean = BeanFactory::getBean($_SESSION['portal_user_type'] . 's', $_SESSION['portal_user_id']);
    }
    SticPortalAuthUtils::destroyPortalSession($bean && $bean->id ? $bean : null);
}
session_write_close();
if (session_status() === PHP_SESSION_ACTIVE) session_destroy();

header('Location: index.php?entryPoint=sticPortalLogin');
exit;
