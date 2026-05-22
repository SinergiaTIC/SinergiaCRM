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

class SticPasswordManager
{
    /**
     * Handle password logic before save - independent of portal enable/disable
     *
     * @param SugarBean $bean
     * @return void
     */
    public static function handlePasswordBeforeSave($bean)
    {
        $submittedPassword = (string)($_REQUEST['stic_pa_password_c'] ?? '');
        $hasSubmittedPassword = (array_key_exists('stic_pa_password_c', $_REQUEST) && $submittedPassword !== '');
        $storedPassword = self::getStoredPassword($bean);
        $fetchedPassword = (string)($bean->fetched_row['stic_pa_password_c'] ?? '');

        if (!$hasSubmittedPassword) {
            $bean->_stic_plain_pa_password = '';
            if ($fetchedPassword !== '') {
                $bean->stic_pa_password_c = $fetchedPassword;
            } elseif ($storedPassword !== '') {
                $bean->stic_pa_password_c = $storedPassword;
            }
        } else {
            if ($fetchedPassword !== '' && $submittedPassword === $fetchedPassword && $storedPassword !== '') {
                $bean->stic_pa_password_c = $storedPassword;
                $bean->_stic_plain_pa_password = '';
            } else {
                $bean->_stic_plain_pa_password = $submittedPassword;
                $bean->stic_pa_password_c = $submittedPassword;
            }
        }
    }

    /**
     * Get stored password from database
     *
     * @param SugarBean $bean
     * @return string
     */
    public static function getStoredPassword($bean)
    {
        $module = $bean->module_dir ?? '';
        $id = $bean->id ?? '';

        if (empty($module) || empty($id)) {
            return '';
        }

        $storedBean = BeanFactory::getBean($module, $id, ['disable_row_level_security' => true]);
        if (empty($storedBean) || empty($storedBean->id)) {
            return '';
        }

        $password = $storedBean->stic_pa_password_c ?? '';
        return is_scalar($password) ? (string)$password : '';
    }

    /**
     * Check if bean has a stored password
     *
     * @param SugarBean $bean
     * @return bool
     */
    public static function hasStoredPassword($bean)
    {
        return self::getStoredPassword($bean) !== '';
    }

    /**
     * Generate random password for new portal users
     *
     * @return string
     */
    public static function generateRandomPassword()
    {
        try {
            return bin2hex(random_bytes(6));
        } catch (\Exception $e) {
            return substr(md5(uniqid((string)mt_rand(), true)), 0, 12);
        }
    }

    /**
     * Check if portal is enabled for the bean
     *
     * @param SugarBean $bean
     * @return bool
     */
    public static function isPortalEnabled($bean)
    {
        return (bool)($bean->stic_pa_enable_c ?? false);
    }
}
