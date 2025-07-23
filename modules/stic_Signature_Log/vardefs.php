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

$dictionary['stic_Signature_Log'] = array(
    'table' => 'stic_signature_log',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'action' => array(
            'required' => false,
            'name' => 'action',
            'vname' => 'LBL_ACTION',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => '100',
            'size' => '20',
        ),
        'date' => array(
            'required' => false,
            'name' => 'date',
            'vname' => 'LBL_DATE',
            'type' => 'datetimecombo',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'size' => '20',
            'enable_range_search' => false,
            'dbType' => 'datetime',
        ),
        'ip_address' => array(
            'required' => false,
            'name' => 'ip_address',
            'vname' => 'LBL_IP_ADDRESS',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => '255',
            'size' => '20',
        ),
        'user_agent' => array(
            'required' => false,
            'name' => 'user_agent',
            'vname' => 'LBL_USER_AGENT',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => '255',
            'size' => '20',
        ),
        'stic_signers_stic_signature_log' => array(
            'name' => 'stic_signers_stic_signature_log',
            'type' => 'link',
            'relationship' => 'stic_signers_stic_signature_log',
            'source' => 'non-db',
            'module' => 'stic_Signers',
            'bean_name' => 'stic_Signers',
            'vname' => 'LBL_STIC_SIGNERS_STIC_SIGNATURE_LOG_FROM_STIC_SIGNERS_TITLE',
            'id_name' => 'stic_signers_stic_signature_logtic_signers_ida',
        ),
        'stic_signers_stic_signature_log_name' => array(
            'name' => 'stic_signers_stic_signature_log_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_SIGNERS_STIC_SIGNATURE_LOG_FROM_STIC_SIGNERS_TITLE',
            'save' => true,
            'id_name' => 'stic_signers_stic_signature_logtic_signers_ida',
            'link' => 'stic_signers_stic_signature_log',
            'table' => 'stic_signers',
            'module' => 'stic_Signers',
            'rname' => 'name',
        ),
        'stic_signers_stic_signature_logtic_signers_ida' => array(
            'name' => 'stic_signers_stic_signature_logtic_signers_ida',
            'type' => 'link',
            'relationship' => 'stic_signers_stic_signature_log',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_STIC_SIGNERS_STIC_SIGNATURE_LOG_FROM_STIC_SIGNATURE_LOG_TITLE',
        ),
        'stic_signatures_stic_signature_log' => array(
            'name' => 'stic_signatures_stic_signature_log',
            'type' => 'link',
            'relationship' => 'stic_signatures_stic_signature_log',
            'source' => 'non-db',
            'module' => 'stic_Signatures',
            'bean_name' => 'stic_Signatures',
            'vname' => 'LBL_STIC_SIGNATURES_STIC_SIGNATURE_LOG_FROM_STIC_SIGNATURES_TITLE',
            'id_name' => 'stic_signatures_stic_signature_logtic_signatures_ida',
        ),
        'stic_signatures_stic_signature_log_name' => array(
            'name' => 'stic_signatures_stic_signature_log_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_SIGNATURES_STIC_SIGNATURE_LOG_FROM_STIC_SIGNATURES_TITLE',
            'save' => true,
            'id_name' => 'stic_signatures_stic_signature_logtic_signatures_ida',
            'link' => 'stic_signatures_stic_signature_log',
            'table' => 'stic_signatures',
            'module' => 'stic_Signatures',
            'rname' => 'name',
        ),
        'stic_signatures_stic_signature_logtic_signatures_ida' => array(
            'name' => 'stic_signatures_stic_signature_logtic_signatures_ida',
            'type' => 'link',
            'relationship' => 'stic_signatures_stic_signature_log',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_STIC_SIGNATURES_STIC_SIGNATURE_LOG_FROM_STIC_SIGNATURE_LOG_TITLE',
        ),
    ),
    'relationships' => array(
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('stic_Signature_Log', 'stic_Signature_Log', array('basic', 'assignable', 'security_groups'));
