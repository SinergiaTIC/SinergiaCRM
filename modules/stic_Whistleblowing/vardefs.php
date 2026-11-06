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

$dictionary['stic_Whistleblowing'] = array(
    'table' => 'stic_whistleblowing',
    'audited' => true,
    'studio' => false,
    'reportable' => false,
    'inline_edit' => false,
    'duplicate_merge' => false,
    'fields' => array (
    'stic_category' => 
    array (
      'required' => false,
      'name' => 'stic_category',
      'vname' => 'LBL_STIC_CATEGORY',
      'type' => 'enum',
      'massupdate' => 0,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'false',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'inline_edit' => false,
      'reportable' => false,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'len' => 100,
      'size' => '20',
      'options' => 'stic_whistleblowing_type_list',
      'studio' => 'false',
      'dependency' => false,
    ),
    'stic_start_date' => 
    array (
      'required' => false,
      'name' => 'stic_start_date',
      'vname' => 'LBL_STIC_START_DATE',
      'type' => 'date',
      'massupdate' => 0,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'false',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'inline_edit' => false,
      'reportable' => false,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'size' => '20',
      'enable_range_search' => false,
    ),
    'stic_code' => 
    array (
      'required' => false,
      'name' => 'stic_code',
      'vname' => 'LBL_STIC_CODE',
      'type' => 'varchar',
      'massupdate' => 0,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'false',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'inline_edit' => false,
      'reportable' => false,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'len' => '255',
      'size' => '20',
    ),
    'stic_status' => 
    array (
      'required' => false,
      'name' => 'stic_status',
      'vname' => 'LBL_STIC_STATUS',
      'type' => 'enum',
      'massupdate' => 0,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'false',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'inline_edit' => true,
      'reportable' => false,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'len' => 100,
      'size' => '20',
      'options' => 'stic_whistleblowing_status_list',
      'studio' => 'false',
      'dependency' => false,
    ),
    'stic_status_detail' => 
    array (
      'required' => false,
      'name' => 'stic_status_detail',
      'vname' => 'LBL_STIC_STATUS_DETAIL',
      'type' => 'varchar',
      'massupdate' => 0,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'false',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'inline_edit' => true,
      'reportable' => false,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'len' => '255',
      'size' => '20',
    ),
    // Link field for the Documents relationship (join table defined in custom/metadata/SticRelationshipsMetaData.php)
    'stic_whistleblowing_documents' => array(
      'name' => 'stic_whistleblowing_documents',
      'type' => 'link',
      'relationship' => 'stic_whistleblowing_documents',
      'source' => 'non-db',
      'module' => 'Documents',
      'bean_name' => 'Document',
      'vname' => 'LBL_STIC_WHISTLEBLOWING_DOCUMENTS_FROM_DOCUMENTS_TITLE',
    ),
    // Link fields for the Contacts relationship (join table defined in custom/metadata/SticRelationshipsMetaData.php)
    'stic_whistleblowing_contacts' => array(
      'name' => 'stic_whistleblowing_contacts',
      'type' => 'link',
      'relationship' => 'stic_whistleblowing_contacts',
      'source' => 'non-db',
      'module' => 'Contacts',
      'bean_name' => 'Contact',
      'vname' => 'LBL_STIC_WHISTLEBLOWING_CONTACTS_FROM_CONTACTS_TITLE',
      'id_name' => 'stic_whistleblowing_contactscontacts_ida',
    ),
    'stic_whistleblowing_contacts_name' => array(
      'name' => 'stic_whistleblowing_contacts_name',
      'type' => 'relate',
      'source' => 'non-db',
      'vname' => 'LBL_STIC_WHISTLEBLOWING_CONTACTS_FROM_CONTACTS_TITLE',
      'save' => 1,
      'id_name' => 'stic_whistleblowing_contactscontacts_ida',
      'link' => 'stic_whistleblowing_contacts',
      'table' => 'contacts',
      'module' => 'Contacts',
      'massupdate' => 1,
      'rname' => 'name',
      'required' => 0,
      'db_concat_fields' => array(
        0 => 'first_name',
        1 => 'last_name',
      ),
    ),
    'stic_whistleblowing_contactscontacts_ida' => array(
      'name' => 'stic_whistleblowing_contactscontacts_ida',
      'type' => 'link',
      'relationship' => 'stic_whistleblowing_contacts',
      'source' => 'non-db',
      'reportable' => false,
      'side' => 'right',
      'vname' => 'LBL_STIC_WHISTLEBLOWING_CONTACTS_FROM_CONTACTS_TITLE',
    ),
  ),
    'relationships' => array (
),
    'optimistic_locking' => true,
    'unified_search' => false,
);
if (!class_exists('VardefManager')) {
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('stic_Whistleblowing', 'stic_Whistleblowing', array('basic','assignable','security_groups'));

$dictionary['stic_Whistleblowing']['fields']['name']['inline_edit'] = false;
$dictionary['stic_Whistleblowing']['fields']['name']['audited'] = false;
$dictionary['stic_Whistleblowing']['fields']['name']['reportable'] = false;
$dictionary['stic_Whistleblowing']['fields']['name']['unified_search'] = false;
$dictionary['stic_Whistleblowing']['fields']['name']['massupdate'] = 0;
$dictionary['stic_Whistleblowing']['fields']['description']['inline_edit'] = false;
$dictionary['stic_Whistleblowing']['fields']['description']['audited'] = false;
$dictionary['stic_Whistleblowing']['fields']['description']['reportable'] = false;
$dictionary['stic_Whistleblowing']['fields']['description']['unified_search'] = false;
$dictionary['stic_Whistleblowing']['fields']['description']['massupdate'] = 0;
$dictionary['stic_Whistleblowing']['fields']['assigned_user_name']['inline_edit'] = false;
$dictionary['stic_Whistleblowing']['fields']['assigned_user_name']['audited'] = false;
$dictionary['stic_Whistleblowing']['fields']['assigned_user_name']['reportable'] = false;
$dictionary['stic_Whistleblowing']['fields']['assigned_user_name']['unified_search'] = false;
$dictionary['stic_Whistleblowing']['fields']['assigned_user_name']['massupdate'] = 0;