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

/**
 * Utility class for handling operations related to stic_Signature_Log module.
 * This includes functionality for logging signature actions.
 */
class stic_SignatureLogUtils
{

    /**
     * Logs an action related to a signature or signer.
     *
     * @param string $action The action performed (e.g., 'SIGNATURE_CREATED', 'SIGNER_ADDED').
     * @param string $id The ID of the signature or signer related to the action.
     * @param string $idType The type of ID provided ('SIGNER' or 'SIGNATURE').
     * @param string $extraInfo Optional additional information to include in the log.
     *
     * @return void
     */
    public static function logSignatureAction($action, $id, $idType, $extraInfo = '')
    {

        if ($idType != 'SIGNER' && $idType != 'SIGNATURE') {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " idType must be SIGNER or SIGNATURE.");
            return false;
        }

        if (empty($action)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Action cannot be empty.");
            return false;
        }

        $logBean = BeanFactory::newBean('stic_Signature_Log');
        $logBean->name = $action . ' - ' . TimeDate::getInstance()->nowDb();
        $logBean->action = $action;
        $logBean->ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $logBean->user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $logBean->description = $extraInfo;
        $logBean->date = TimeDate::getInstance()->nowDb();
        $logBean->save();

        switch ($idType) {
            case 'SIGNER':
                $logBean->load_relationship('stic_signers_stic_signature_log');
                $logBean->stic_signers_stic_signature_log->add($id);
                $logBean->save();
                break;

            case 'SIGNATURE':
                $logBean->load_relationship('stic_signatures_stic_signature_log');
                $logBean->stic_signatures_stic_signature_log->add($id);
                $logBean->save();
                break;

            default:
                # code...
                break;
        }

    }

    /**
     * Retrieves the signature log actions related to a specific signer or signature.
     *
     * @param string $id The ID of the signature or signer.
     * @param string $idType The type of ID provided ('SIGNER' or 'SIGNATURE').
     * @param array $exclude Optional array of action types to exclude from the results.
     *
     * @return array|false An array of log entries or false on error.
     */
    public static function getSignatureLogActions($id, $idType, $exclude=[])
    {

        if ($idType != 'SIGNER' && $idType != 'SIGNATURE') {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " idType must be SIGNER or SIGNATURE.");
            return false;
        }

if (!empty($exclude) && is_array($exclude)) {
    $excludeList = "'" . implode("','", array_map('addslashes', $exclude)) . "'";
    $excludeCondition = " AND l.action NOT IN ({$excludeList}) ";
} else {
    $excludeCondition = '';
}

        global $db;
        $logs = [];
        switch ($idType) {
            case 'SIGNER':
                $query = "SELECT l.* FROM stic_signature_log l
                        INNER JOIN stic_signers_stic_signature_log_c sl ON l.id = sl.stic_signers_stic_signature_logtic_signature_log_idb
                        WHERE sl.stic_signers_stic_signature_logtic_signers_ida  = '{$id}'
                        {$excludeCondition}
                        ORDER BY l.date DESC";

                if (!$db->query($query)) {
                    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Error executing query: {$query}");
                } else {
                    $result = $db->query($query);
                    while ($row = $db->fetchByAssoc($result)) {
                        $logs[] = $row;
                    }
                }

                break;

            case 'SIGNATURE':
                $query = "SELECT l.* FROM stic_signature_log l
                        INNER JOIN stic_signatures_stic_signature_log_c sl ON l.id = sl.stic_signatures_stic_signature_logtic_signature_logs_idb
                        WHERE sl.stic_signatures_stic_signature_logtic_signatures_ida  = '{$id}'
                        {$excludeCondition}
                        ORDER BY l.date DESC";
                if (!$db->query($query)) {
                    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Error executing query: {$query}");
                } else {
                    $result = $db->query($query);
                    while ($row = $db->fetchByAssoc($result)) {
                        $logs[] = $row;
                    }
                }

                break;

            default:
                # code...
                break;
        }

        return $logs;
    }

}
