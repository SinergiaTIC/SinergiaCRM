<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
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
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

$dictionary['stic_Signature_Logs'] = array(
    'table' => 'stic_signature_logs',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'action_type' => array(
            'required' => false,
            'name' => 'action_type',
            'vname' => 'LBL_ACTION_TYPE',
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
        'action_datetime' => array(
            'required' => false,
            'name' => 'action_datetime',
            'vname' => 'LBL_ACTION_DATETIME',
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
        'stic_signers_stic_signature_logs' => array(
            'name' => 'stic_signers_stic_signature_logs',
            'type' => 'link',
            'relationship' => 'stic_signers_stic_signature_logs',
            'source' => 'non-db',
            'module' => 'stic_Signers',
            'bean_name' => 'stic_Signers',
            'vname' => 'LBL_STIC_SIGNERS_STIC_SIGNATURE_LOGS_FROM_STIC_SIGNERS_TITLE',
            'id_name' => 'stic_signers_stic_signature_logsstic_signers_ida',
        ),
        'stic_signers_stic_signature_logs_name' => array(
            'name' => 'stic_signers_stic_signature_logs_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_SIGNERS_STIC_SIGNATURE_LOGS_FROM_STIC_SIGNERS_TITLE',
            'save' => true,
            'id_name' => 'stic_signers_stic_signature_logsstic_signers_ida',
            'link' => 'stic_signers_stic_signature_logs',
            'table' => 'stic_signers',
            'module' => 'stic_Signers',
            'rname' => 'name',
        ),
        'stic_signers_stic_signature_logsstic_signers_ida' => array(
            'name' => 'stic_signers_stic_signature_logsstic_signers_ida',
            'type' => 'link',
            'relationship' => 'stic_signers_stic_signature_logs',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_STIC_SIGNERS_STIC_SIGNATURE_LOGS_FROM_STIC_SIGNATURE_LOGS_TITLE',
        ),
        'stic_signatures_stic_signature_logs' => array(
            'name' => 'stic_signatures_stic_signature_logs',
            'type' => 'link',
            'relationship' => 'stic_signatures_stic_signature_logs',
            'source' => 'non-db',
            'module' => 'stic_Signatures',
            'bean_name' => 'stic_Signatures',
            'vname' => 'LBL_STIC_SIGNATURES_STIC_SIGNATURE_LOGS_FROM_STIC_SIGNATURES_TITLE',
            'id_name' => 'stic_signatures_stic_signature_logsstic_signatures_ida',
        ),
        'stic_signatures_stic_signature_logs_name' => array(
            'name' => 'stic_signatures_stic_signature_logs_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_SIGNATURES_STIC_SIGNATURE_LOGS_FROM_STIC_SIGNATURES_TITLE',
            'save' => true,
            'id_name' => 'stic_signatures_stic_signature_logsstic_signatures_ida',
            'link' => 'stic_signatures_stic_signature_logs',
            'table' => 'stic_signatures',
            'module' => 'stic_Signatures',
            'rname' => 'name',
        ),
        'stic_signatures_stic_signature_logsstic_signatures_ida' => array(
            'name' => 'stic_signatures_stic_signature_logsstic_signatures_ida',
            'type' => 'link',
            'relationship' => 'stic_signatures_stic_signature_logs',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_STIC_SIGNATURES_STIC_SIGNATURE_LOGS_FROM_STIC_SIGNATURE_LOGS_TITLE',
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
VardefManager::createVardef('stic_Signature_Logs', 'stic_Signature_Logs', array('basic', 'assignable', 'security_groups'));
