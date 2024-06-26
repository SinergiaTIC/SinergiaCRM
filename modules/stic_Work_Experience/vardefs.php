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

$dictionary['stic_Work_Experience'] = array(
    'table' => 'stic_work_experience',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'name',
            'link' => true,
            'dbType' => 'varchar',
            'len' => '255',
            'unified_search' => true,
            'full_text_search' => array(
                'boost' => 3,
            ),
            'required' => false,
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'selected',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'duplicate_merge_dom_value' => '3',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'size' => '20',
        ),
        'position_type' => array(
            'required' => false,
            'name' => 'position_type',
            'vname' => 'LBL_POSITION_TYPE',
            'type' => 'enum',
            'massupdate' => '1',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'stic_incorpora_cno_n1_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'contract_type' => array(
            'required' => true,
            'name' => 'contract_type',
            'vname' => 'LBL_CONTRACT_TYPE',
            'type' => 'enum',
            'massupdate' => '1',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'required',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'stic_work_experience_contract_types_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'workday_type' => array(
            'required' => false,
            'name' => 'workday_type',
            'vname' => 'LBL_WORKDAY_TYPE',
            'type' => 'enum',
            'massupdate' => '1',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'stic_work_experience_workday_types_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'schedule' => array(
            'required' => false,
            'name' => 'schedule',
            'vname' => 'LBL_SCHEDULE',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => '255',
            'size' => '20',
        ),
        'sector' => array(
            'required' => false,
            'name' => 'sector',
            'vname' => 'LBL_SECTOR',
            'type' => 'enum',
            'massupdate' => '1',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'stic_incorpora_cnae_n1_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'subsector' => array(
            'required' => false,
            'name' => 'subsector',
            'vname' => 'LBL_SUBSECTOR',
            'type' => 'dynamicenum',
            'massupdate' => '1',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'stic_incorpora_cnae_n2_list',
            'studio' => 'visible',
            'dbType' => 'enum',
            'parentenum' => 'sector',
        ),
        'achieved' => array(
            'required' => false,
            'name' => 'achieved',
            'vname' => 'LBL_ACHIEVED',
            'type' => 'bool',
            'massupdate' => '1',
            'default' => '0',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => '255',
            'size' => '20',
        ),
        'position' => array(
            'required' => true,
            'name' => 'position',
            'vname' => 'LBL_POSITION',
            'type' => 'varchar',
            'massupdate' => '1',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'required',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => '255',
            'size' => '20',
        ),
        'start_date' => array(
            'required' => true,
            'name' => 'start_date',
            'vname' => 'LBL_START_DATE',
            'type' => 'date',
            'massupdate' => '1',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'required',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'size' => '20',
            'options' => 'date_range_search_dom',
            'enable_range_search' => true,
        ),
        'end_date' => array(
            'required' => false,
            'name' => 'end_date',
            'vname' => 'LBL_END_DATE',
            'type' => 'date',
            'massupdate' => '1',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'size' => '20',
            'options' => 'date_range_search_dom',
            'enable_range_search' => false,
        ),
        'stic_work_experience_contacts' => array(
            'name' => 'stic_work_experience_contacts',
            'type' => 'link',
            'relationship' => 'stic_work_experience_contacts',
            'source' => 'non-db',
            'module' => 'Contacts',
            'bean_name' => 'Contact',
            'vname' => 'LBL_STIC_WORK_EXPERIENCE_CONTACTS_FROM_CONTACTS_TITLE',
            'id_name' => 'stic_work_experience_contactscontacts_ida',
        ),
        'stic_work_experience_contacts_name' => array(
            'name' => 'stic_work_experience_contacts_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_WORK_EXPERIENCE_CONTACTS_FROM_CONTACTS_TITLE',
            'save' => true,
            'id_name' => 'stic_work_experience_contactscontacts_ida',
            'link' => 'stic_work_experience_contacts',
            'table' => 'contacts',
            'module' => 'Contacts',
            'rname' => 'name',
            'required' => true,
            'db_concat_fields' => array(
                0 => 'first_name',
                1 => 'last_name',
            ),
        ),
        'stic_work_experience_contactscontacts_ida' => array(
            'name' => 'stic_work_experience_contactscontacts_ida',
            'type' => 'link',
            'relationship' => 'stic_work_experience_contacts',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_STIC_WORK_EXPERIENCE_CONTACTS_FROM_STIC_WORK_EXPERIENCE_TITLE',
        ),
        'stic_work_experience_accounts' => array(
            'name' => 'stic_work_experience_accounts',
            'type' => 'link',
            'relationship' => 'stic_work_experience_accounts',
            'source' => 'non-db',
            'module' => 'Accounts',
            'bean_name' => 'Account',
            'vname' => 'LBL_STIC_WORK_EXPERIENCE_ACCOUNTS_FROM_ACCOUNTS_TITLE',
            'id_name' => 'stic_work_experience_accountsaccounts_ida',
        ),
        'stic_work_experience_accounts_name' => array(
            'name' => 'stic_work_experience_accounts_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_WORK_EXPERIENCE_ACCOUNTS_FROM_ACCOUNTS_TITLE',
            'save' => true,
            'id_name' => 'stic_work_experience_accountsaccounts_ida',
            'link' => 'stic_work_experience_accounts',
            'table' => 'accounts',
            'module' => 'Accounts',
            'rname' => 'name',
            'required' => false,
        ),
        'stic_work_experience_accountsaccounts_ida' => array(
            'name' => 'stic_work_experience_accountsaccounts_ida',
            'type' => 'link',
            'relationship' => 'stic_work_experience_accounts',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_STIC_WORK_EXPERIENCE_ACCOUNTS_FROM_STIC_WORK_EXPERIENCE_TITLE',
        ),
        'stic_work_experience_stic_job_applications' => array(
            'name' => 'stic_work_experience_stic_job_applications',
            'type' => 'link',
            'relationship' => 'stic_work_experience_stic_job_applications',
            'source' => 'non-db',
            'module' => 'stic_Job_Applications',
            'bean_name' => 'stic_Job_Applications',
            'vname' => 'LBL_STIC_WORK_EXPERIENCE_STIC_JOB_APPLICATIONS_FROM_STIC_JOB_APPLICATIONS_TITLE',
            'id_name' => 'stic_work_9fefcations_idb',
        ),
        'stic_work_experience_stic_job_applications_name' => array(
            'name' => 'stic_work_experience_stic_job_applications_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_WORK_EXPERIENCE_STIC_JOB_APPLICATIONS_FROM_STIC_JOB_APPLICATIONS_TITLE',
            'save' => true,
            'id_name' => 'stic_work_9fefcations_idb',
            'link' => 'stic_work_experience_stic_job_applications',
            'table' => 'stic_job_applications',
            'module' => 'stic_Job_Applications',
            'rname' => 'name',
            'inline_edit' => 1,
            'massupdate' => 0,
        ),
        'stic_work_9fefcations_idb' => array(
            'name' => 'stic_work_9fefcations_idb',
            'type' => 'link',
            'relationship' => 'stic_work_experience_stic_job_applications',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'left',
            'vname' => 'LBL_STIC_WORK_EXPERIENCE_STIC_JOB_APPLICATIONS_FROM_STIC_JOB_APPLICATIONS_TITLE',
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
VardefManager::createVardef('stic_Work_Experience', 'stic_Work_Experience', array('basic', 'assignable', 'security_groups'));
// Set special values for SuiteCRM base fields
$dictionary['stic_Training']['fields']['description']['rows'] = '2'; // Make textarea fields shorter
