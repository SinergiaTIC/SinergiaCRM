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
}
