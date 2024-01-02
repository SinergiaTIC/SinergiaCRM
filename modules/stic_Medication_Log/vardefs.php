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

$dictionary['stic_Medication_Log'] = array(
    'table' => 'stic_medication_log',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array (
  'intake_date' => 
  array (
    'required' => true,
    'name' => 'intake_date',
    'vname' => 'LBL_INTAKE_DATE',
    'type' => 'date',
    'massupdate' => '1',
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
  'medication' => 
  array (
    'required' => false,
    'name' => 'medication',
    'vname' => 'LBL_MEDICATION',
    'type' => 'varchar',
    'massupdate' => 1,
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
  'schedule' => 
  array (
    'required' => true,
    'name' => 'schedule',
    'vname' => 'LBL_SCHEDULE',
    'type' => 'enum',
    'massupdate' => '1',
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
    'options' => 'stic_medication_schedule_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'dosage' => 
  array (
    'required' => true,
    'name' => 'dosage',
    'vname' => 'LBL_DOSAGE',
    'type' => 'varchar',
    'massupdate' => '1',
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
  'administered' => 
  array (
    'required' => true,
    'name' => 'administered',
    'vname' => 'LBL_ADMINISTERED',
    'type' => 'enum',
    'massupdate' => '1',
    'default' => 'pending',
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
    'options' => 'stic_medication_administered_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'time' => 
  array (
    'required' => false,
    'name' => 'time',
    'vname' => 'LBL_TIME',
    'type' => 'time',
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
  'stock_depletion' => 
  array (
    'required' => false,
    'name' => 'stock_depletion',
    'vname' => 'LBL_STOCK_DEPLETION',
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
  'stic_medication_log_contacts' => array(
    'name' => 'stic_medication_log_contacts',
    'type' => 'link',
    'relationship' => 'stic_medication_log_contacts',
    'source' => 'non-db',
    'module' => 'Contacts',
    'bean_name' => 'Contact',
    'vname' => 'LBL_STIC_MEDICATION_LOG_CONTACTS_FROM_CONTACTS_TITLE',
    'id_name' => 'stic_medication_log_contactscontacts_ida',    
  ),
  'stic_medication_log_contacts_name' =>
  array(
    'name' => 'stic_medication_log_contacts_name',
    'type' => 'relate',
    'source' => 'non-db',
    'vname' => 'LBL_STIC_MEDICATION_LOG_CONTACTS_FROM_CONTACTS_TITLE',
    'save' => true,
    'id_name' => 'stic_medication_log_contactscontacts_ida',
    'link' => 'stic_medication_log_contacts',
    'table' => 'contacts',
    'module' => 'Contacts',
    'massupdate' => 0,
    'inline_edit' => false,
    'rname' => 'name',
    'db_concat_fields' => 
    array (
      0 => 'first_name',
      1 => 'last_name',
    ),    
  ),
  'stic_medication_log_contactscontacts_ida' =>
  array(
    'name' => 'stic_medication_log_contactscontacts_ida',
    'type' => 'link',
    'relationship' => 'stic_medication_log_contacts',
    'source' => 'non-db',
    'reportable' => false,
    'side' => 'right',
    'vname' => 'LBL_STIC_MEDICATION_LOG_CONTACTS_FROM_STIC_MEDICATION_LOG_TITLE',    
  ),
  'stic_medication_log_stic_prescription' =>
   array (
    'name' => 'stic_medication_log_stic_prescription',
    'type' => 'link',
    'relationship' => 'stic_medication_log_stic_prescription',
    'source' => 'non-db',
    'module' => 'stic_Prescription',
    'bean_name' => 'stic_Prescription',
    'vname' => 'LBL_STIC_MEDICATION_LOG_STIC_PRESCRIPTION_FROM_STIC_PRESCRIPTION_TITLE',
    'id_name' => 'stic_medication_log_stic_prescriptionstic_prescription_ida',
   ),
  'stic_medication_log_stic_prescription_name' =>
   array (
    'name' => 'stic_medication_log_stic_prescription_name',
    'type' => 'relate',
    'source' => 'non-db',
    'vname' => 'LBL_STIC_MEDICATION_LOG_STIC_PRESCRIPTION_FROM_STIC_PRESCRIPTION_TITLE',
    'save' => true,
    'id_name' => 'stic_medication_log_stic_prescriptionstic_prescription_ida',
    'link' => 'stic_medication_log_stic_prescription',
    'table' => 'stic_prescription',
    'module' => 'stic_Prescription',
    'massupdate' => 0,
    'required' => true,
    'rname' => 'name',
   ),
  'stic_medication_log_stic_prescriptionstic_prescription_ida' =>
   array (
    'name' => 'stic_medication_log_stic_prescriptionstic_prescription_ida',
    'type' => 'link',
    'relationship' => 'stic_medication_log_stic_prescription',
    'source' => 'non-db',
    'reportable' => false,
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
VardefManager::createVardef('stic_Medication_Log', 'stic_Medication_Log', array('basic','assignable','security_groups'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Medication_Log']['fields']['name']['required'] = '0'; // Name is not required in this module
$dictionary['stic_Medication_Log']['fields']['name']['importable'] = true; // Name is importable but not required in this module
$dictionary['stic_Medication_Log']['fields']['assigned_user_name']['required'] = true; // Assigned user is required in this module
$dictionary['stic_Medication_Log']['fields']['description']['rows'] = '2'; // Make textarea fields shorter