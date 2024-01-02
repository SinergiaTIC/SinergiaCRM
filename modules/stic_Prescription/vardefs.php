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

$dictionary['stic_Prescription'] = array(
    'table' => 'stic_prescription',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array (
  'start_date' => 
  array (
    'required' => true,
    'name' => 'start_date',
    'vname' => 'LBL_START_DATE',
    'type' => 'date',
    'massupdate' => 1,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'enable_range_search' => false,
  ),
  'end_date' => 
  array (
    'required' => false,
    'name' => 'end_date',
    'vname' => 'LBL_END_DATE',
    'type' => 'date',
    'massupdate' => 1,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'enable_range_search' => false,
  ),
  'active' => 
  array (
    'required' => false,
    'name' => 'active',
    'vname' => 'LBL_ACTIVE',
    'type' => 'bool',
    'massupdate' => 0,
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
  'frequency' => 
  array (
    'required' => true,
    'name' => 'frequency',
    'vname' => 'LBL_FREQUENCY',
    'type' => 'enum',
    'massupdate' => 1,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'stic_medication_frequency_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'schedule' => 
  array (
    'required' => true,
    'name' => 'schedule',
    'vname' => 'LBL_SCHEDULE',
    'type' => 'multienum',
    'massupdate' => 1,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'options' => 'stic_medication_schedule_list',
    'studio' => 'visible',
    'isMultiSelect' => true,
  ),
  'dosage' => 
  array (
    'required' => true,
    'name' => 'dosage',
    'vname' => 'LBL_DOSAGE',
    'type' => 'varchar',
    'massupdate' => 1,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
  ),
  'skip_intake' => 
  array (
    'required' => false,
    'name' => 'skip_intake',
    'vname' => 'LBL_SKIP_INTAKE',
    'type' => 'multienum',
    'massupdate' => 1,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'options' => 'stic_weekdays_list',
    'studio' => 'visible',
    'isMultiSelect' => true,
  ),
  'stock_depletion_date' => 
  array (
    'required' => false,
    'name' => 'stock_depletion_date',
    'vname' => 'LBL_STOCK_DEPLETION_DATE',
    'type' => 'date',
    'massupdate' => 1,
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
    'enable_range_search' => false,
  ),
  'prescription' => 
  array (
    'required' => false,
    'name' => 'prescription',
    'vname' => 'LBL_PRESCRIPTION',
    'type' => 'bool',
    'massupdate' => 1,
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
  'type' => 
  array (
    'required' => false,
    'name' => 'type',
    'vname' => 'LBL_TYPE',
    'type' => 'enum',
    'massupdate' => 1,
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
    'options' => 'stic_prescription_type_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'contact_id_c' => 
  array (
    'required' => false,
    'name' => 'contact_id_c',
    'vname' => 'LBL_PRESCRIBER_CONTACT_ID',
    'type' => 'id',
    'massupdate' => 1,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => false,
    'inline_edit' => true,
    'reportable' => false,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 36,
    'size' => '20',
  ),
  'prescriber' => 
  array (
    'required' => false,
    'source' => 'non-db',
    'name' => 'prescriber',
    'vname' => 'LBL_PRESCRIBER',
    'type' => 'relate',
    'massupdate' => 1,
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
    'id_name' => 'contact_id_c',
    'ext2' => 'Contacts',
    'module' => 'Contacts',
    'rname' => 'name',
    'quicksearch' => 'enabled',
    'studio' => 'visible',
  ),
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_NAME',
    'type' => 'name',
    'link' => true,
    'dbType' => 'varchar',
    'len' => '255',
    'unified_search' => false,
    'full_text_search' => 
    array (
      'boost' => 3,
    ),
    'required' => false,
    'importable' => 'required',
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
  'dont_create_logs' =>
  array(
    'required' => false,
    'name' => 'dont_create_logs',
    'vname' => 'LBL_DONT_CREATE_LOGS',
    'type' => 'bool',
    'massupdate' => 1,
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
  'stic_prescription_contacts' =>
  array(
    'name' => 'stic_prescription_contacts',
    'type' => 'link',
    'relationship' => 'stic_prescription_contacts',
    'source' => 'non-db',
    'module' => 'Contacts',
    'bean_name' => 'Contact',
    'vname' => 'LBL_STIC_PRESCRIPTION_CONTACTS_FROM_CONTACTS_TITLE',
    'id_name' => 'stic_prescription_contactscontacts_ida',  
  ),
  'stic_prescription_contacts_name' =>
  array(
    'name' => 'stic_prescription_contacts_name',
    'type' => 'relate',
    'source' => 'non-db',
    'vname' => 'LBL_STIC_PRESCRIPTION_CONTACTS_FROM_CONTACTS_TITLE',
    'save' => true,
    'id_name' => 'stic_prescription_contactscontacts_ida',
    'link' => 'stic_prescription_contacts',
    'table' => 'contacts',
    'module' => 'Contacts',
    'massupdate' => 0,
    'rname' => 'name',
    'required' => true,
    'db_concat_fields' => 
    array (
      0 => 'first_name',
      1 => 'last_name',
    ),  
  ),
  'stic_prescription_contactscontacts_ida' =>
  array(
    'name' => 'stic_prescription_contactscontacts_ida',
    'type' => 'link',
    'relationship' => 'stic_prescription_contacts',
    'source' => 'non-db',
    'reportable' => false,
    'side' => 'right',
    'vname' => 'LBL_STIC_PRESCRIPTION_CONTACTS_FROM_STIC_PRESCRIPTION_TITLE',  
  ),
  'stic_prescription_stic_medication' =>
  array(
    'name' => 'stic_prescription_stic_medication',
    'type' => 'link',
    'relationship' => 'stic_prescription_stic_medication',
    'source' => 'non-db',
    'module' => 'stic_Medication',
    'bean_name' => false,
    'vname' => 'LBL_STIC_PRESCRIPTION_STIC_MEDICATION_FROM_STIC_MEDICATION_TITLE',
    'id_name' => 'stic_prescription_stic_medicationstic_medication_ida',  
  ),
  'stic_prescription_stic_medication_name' =>
  array(
    'name' => 'stic_prescription_stic_medication_name',
    'type' => 'relate',
    'source' => 'non-db',
    'vname' => 'LBL_STIC_PRESCRIPTION_STIC_MEDICATION_FROM_STIC_MEDICATION_TITLE',
    'save' => true,
    'id_name' => 'stic_prescription_stic_medicationstic_medication_ida',
    'link' => 'stic_prescription_stic_medication',
    'table' => 'stic_medication',
    'module' => 'stic_Medication',
    'massupdate' => 1,
    'rname' => 'name',
    'required' => true,  
  ),
  'stic_prescription_stic_medicationstic_medication_ida' =>
  array(
    'name' => 'stic_prescription_stic_medicationstic_medication_ida',
    'type' => 'link',
    'relationship' => 'stic_prescription_stic_medication',
    'source' => 'non-db',
    'reportable' => false,
    'side' => 'right',
    'vname' => 'LBL_STIC_PRESCRIPTION_STIC_MEDICATION_FROM_STIC_PRESCRIPTION_TITLE',  
  ),
  'stic_medication_log_stic_prescription' =>
  array (
    'name' => 'stic_medication_log_stic_prescription',
    'type' => 'link',
    'relationship' => 'stic_medication_log_stic_prescription',
    'source' => 'non-db',
    'module' => 'stic_Medication_Log',
    'bean_name' => 'stic_Medication_Log',
    'side' => 'right',
    'vname' => 'LBL_STIC_MEDICATION_LOG_STIC_PRESCRIPTION_FROM_STIC_MEDICATION_LOG_TITLE',
  ),
),
    'relationships' => array (
),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('stic_Prescription', 'stic_Prescription', array('basic','assignable','security_groups'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Prescription']['fields']['name']['required'] = '0'; // Name is not required in this module
$dictionary['stic_Prescription']['fields']['name']['importable'] = true; // Name is importable but not required in this module
$dictionary['stic_Prescription']['fields']['description']['rows'] = '2'; // Make textarea fields shorter