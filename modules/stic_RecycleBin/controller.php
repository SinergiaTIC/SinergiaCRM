<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along
 * with this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'modules/stic_RecycleBin/Utils.php';

class stic_RecycleBinController extends SugarController
{
    /**
     * Single record recovery from the detail view.
     *
     * @return void
     */
    public function action_restore()
    {
        global $log;

        if (!ACLController::checkAccess('stic_RecycleBin', 'edit', true)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ACL denied for restore action');
            SugarApplication::appendErrorMessage(translate('LBL_NO_ACCESS', 'stic_RecycleBin'));
            SugarApplication::redirect('index.php?module=stic_RecycleBin&action=index');
            return;
        }

        $recordId = $_REQUEST['record'] ?? '';

        if (empty($recordId) || !self::isValidId($recordId)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': missing or invalid record id');
            SugarApplication::appendErrorMessage(translate('LBL_NO_RECORDS_SELECTED', 'stic_RecycleBin'));
            SugarApplication::redirect('index.php?module=stic_RecycleBin&action=index');
            return;
        }

        $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': restoring record: ' . $recordId);

        try {
            $result = stic_RecycleBinUtils::restoreRecord($recordId);
        } catch (Throwable $e) {
            $log->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': exception: ' . $e->getMessage());
            SugarApplication::appendErrorMessage(translate('LBL_RESTORE_FAIL', 'stic_RecycleBin'));
            SugarApplication::redirect('index.php?module=stic_RecycleBin&action=index');
            return;
        }

        if ($result['success']) {
            $relationsRestored = $result['relations_restored'] ?? 0;
            $relationsSkipped = $result['relations_skipped'] ?? 0;
            SugarApplication::appendSuccessMessage(
                translate('LBL_RESTORE_SUCCESS', 'stic_RecycleBin') . ' ' .
                sprintf(translate('LBL_RESTORE_RELATIONS_RESTORED', 'stic_RecycleBin'), $relationsRestored) . ' ' .
                sprintf(translate('LBL_RESTORE_RELATIONS_SKIPPED', 'stic_RecycleBin'), $relationsSkipped)
            );
        } else {
            SugarApplication::appendErrorMessage($result['message'] ?? translate('LBL_RESTORE_FAIL', 'stic_RecycleBin'));
        }

        SugarApplication::redirect('index.php?module=stic_RecycleBin&action=index');
    }

    /**
     * Mass recovery from list view (bulk action).
     *
     * @return void
     */
    public function action_mass_restore()
    {
        global $log;

        if (!ACLController::checkAccess('stic_RecycleBin', 'edit', true)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ACL denied for mass restore action');
            SugarApplication::appendErrorMessage(translate('LBL_NO_ACCESS', 'stic_RecycleBin'));
            SugarApplication::redirect('index.php?module=stic_RecycleBin&action=index');
            return;
        }

        $rawIds = $_REQUEST['uid'] ?? '';
        $ids = array();
        if (is_string($rawIds) && $rawIds !== '') {
            $ids = array_filter(array_map('trim', explode(',', $rawIds)), 'strlen');
        } elseif (is_array($rawIds)) {
            $ids = array_filter($rawIds, 'strlen');
        }

        $ids = array_values(array_filter($ids, function ($id) {
            return self::isValidId($id);
        }));

        if (empty($ids)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': no valid ids in mass restore request');
            SugarApplication::appendErrorMessage(translate('LBL_NO_RECORDS_SELECTED', 'stic_RecycleBin'));
            SugarApplication::redirect('index.php?module=stic_RecycleBin&action=index');
            return;
        }

        $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': mass restore for ' . count($ids) . ' records');

        try {
            $result = stic_RecycleBinUtils::massRestoreRecords($ids);
        } catch (Throwable $e) {
            $log->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': exception: ' . $e->getMessage());
            SugarApplication::appendErrorMessage(translate('LBL_RESTORE_FAIL', 'stic_RecycleBin'));
            SugarApplication::redirect('index.php?module=stic_RecycleBin&action=index');
            return;
        }

        if ($result['success'] > 0 && $result['failed'] === 0) {
            SugarApplication::appendSuccessMessage(
                sprintf(translate('LBL_MASS_RESTORE_SUCCESS', 'stic_RecycleBin'), $result['success'])
            );
        } elseif ($result['success'] > 0 && $result['failed'] > 0) {
            SugarApplication::appendErrorMessage(
                sprintf(translate('LBL_MASS_RESTORE_PARTIAL', 'stic_RecycleBin'), $result['success'], $result['failed'])
            );
        } else {
            SugarApplication::appendErrorMessage(
                sprintf(translate('LBL_MASS_RESTORE_ALL_ALREADY', 'stic_RecycleBin'), $result['failed'])
            );
        }

        SugarApplication::redirect('index.php?module=stic_RecycleBin&action=index');
    }

    /**
     * Validates a SugarCRM-style UUID (36-char with hyphens).
     *
     * @param string $id ID to validate
     * @return bool true if the value matches the UUID pattern
     */
    private static function isValidId($id)
    {
        return is_string($id) && preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $id) === 1;
    }
}
