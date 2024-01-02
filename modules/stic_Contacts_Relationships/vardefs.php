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

$dictionary['stic_Contacts_Relationships'] = array(
    'table' => 'stic_contacts_relationships',
    'audited' => 1,
    'inline_edit' => 1,
    'duplicate_merge' => 1,
    'fields' => array(
        'start_date' => array(
            'required' => 0,
            'name' => 'start_date',
            'vname' => 'LBL_START_DATE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'date',
            'massupdate' => '1',
            'no_default' => 0,
            'validation' => array('type' => 'isbefore', 'compareto' => 'end_date', 'blank' => 1),
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'inline_edit' => 1,
            'unified_search' => 0,
            'size' => '20',
            'options' => 'date_range_search_dom',
            'enable_range_search' => 1,
            'reportable' => 1,
            'display_default' => 'today',
        ),
        'end_date' => array(
            'required' => 0,
            'name' => 'end_date',
            'vname' => 'LBL_END_DATE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'date',
            'massupdate' => 1,
            'no_default' => 0,
            'validation' => array('type' => 'isafter', 'compareto' => 'start_date', 'blank' => 1),
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'inline_edit' => 1,
            'unified_search' => 0,
            'size' => '20',
            'options' => 'date_range_search_dom',
            'enable_range_search' => 1,
            'reportable' => 1,
            'display_default' => '',
        ),
        'relationship_type' => array(
            'required' => 1,
            'name' => 'relationship_type',
            'vname' => 'LBL_RELATIONSHIP_TYPE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'enum',
            'massupdate' => '1',
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'required',
            'audited' => 0,
            'unified_search' => 0,
            'len' => 100,
            'size' => '20',
            'options' => 'stic_contacts_relationships_types_list',
            'studio' => 'visible',
            'dependency' => 0,
            'inline_edit' => 1,
            'reportable' => 1,
            'default' => '',
        ),

        'end_reason' => array(
            'required' => 0,
            'name' => 'end_reason',
            'vname' => 'LBL_END_REASON',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'enum',
            'massupdate' => '1',
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'inline_edit' => 1,
            'unified_search' => 0,
            'len' => 100,
            'size' => '20',
            'options' => 'stic_contacts_relationships_end_reasons_list',
            'studio' => 'visible',
            'dependency' => 0,
            'reportable' => 1,
        ),
        'other_end_reasons' => array(
            'required' => 0,
            'name' => 'other_end_reasons',
            'vname' => 'LBL_OTHER_END_REASONS',
            'type' => 'varchar',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => '255',
            'size' => '20',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'inline_edit' => 1,
        ),
        'role' => array(
            'required' => 0,
            'name' => 'role',
            'vname' => 'LBL_ROLE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'enum',
            'massupdate' => '1',
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'inline_edit' => 1,
            'unified_search' => 0,
            'len' => 100,
            'size' => '20',
            'options' => 'stic_contacts_relationships_project_roles_list',
            'studio' => 'visible',
            'dependency' => 0,
            'reportable' => 1,
        ),
        'active' => array(
            'required' => false,
            'name' => 'active',
            'vname' => 'LBL_ACTIVE',
            'type' => 'bool',
            'no_default' => false,
            'massupdate' => 0,
            'inline_edit' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'false',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'popupHelp' => 'LBL_ACTIVE_HELP',
            'studio' => array(
                'quickcreate' => false,
                'editview' => false),
        ),
        'stic_contacts_relationships_contacts' => array(
            'name' => 'stic_contacts_relationships_contacts',
            'type' => 'link',
            'relationship' => 'stic_contacts_relationships_contacts',
            'source' => 'non-db',
            'module' => 'Contacts',
            'bean_name' => 'Contact',
            'vname' => 'LBL_STIC_CONTACTS_RELATIONSHIPS_CONTACTS_FROM_CONTACTS_TITLE',
            'id_name' => 'stic_contacts_relationships_contactscontacts_ida',
        ),
        'stic_contacts_relationships_contacts_name' => array(
            'name' => 'stic_contacts_relationships_contacts_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_CONTACTS_RELATIONSHIPS_CONTACTS_FROM_CONTACTS_TITLE',
            'save' => 1,
            'id_name' => 'stic_contacts_relationships_contactscontacts_ida',
            'link' => 'stic_contacts_relationships_contacts',
            'table' => 'contacts',
            'module' => 'Contacts',
            'massupdate' => 1,
            'rname' => 'name',
            'required' => 1,
            'db_concat_fields' => array(
                0 => 'first_name',
                1 => 'last_name',
            ),
        ),
        'stic_contacts_relationships_contactscontacts_ida' => array(
            'name' => 'stic_contacts_relationships_contactscontacts_ida',
            'type' => 'link',
            'relationship' => 'stic_contacts_relationships_contacts',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_STIC_CONTACTS_RELATIONSHIPS_CONTACTS_FROM_STIC_CONTACTS_RELATIONSHIPS_TITLE',
        ),
        'stic_contacts_relationships_project' => array(
            'name' => 'stic_contacts_relationships_project',
            'type' => 'link',
            'relationship' => 'stic_contacts_relationships_project',
            'source' => 'non-db',
            'module' => 'Project',
            'bean_name' => 'Project',
            'vname' => 'LBL_STIC_CONTACTS_RELATIONSHIPS_PROJECT_FROM_PROJECT_TITLE',
            'id_name' => 'stic_contacts_relationships_projectproject_ida',
        ),
        'stic_contacts_relationships_project_name' => array(
            'name' => 'stic_contacts_relationships_project_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_CONTACTS_RELATIONSHIPS_PROJECT_FROM_PROJECT_TITLE',
            'save' => 1,
            'id_name' => 'stic_contacts_relationships_projectproject_ida',
            'link' => 'stic_contacts_relationships_project',
            'table' => 'project',
            'module' => 'Project',
            'rname' => 'name',
            'massupdate' => 1,
        ),
        'stic_contacts_relationships_projectproject_ida' => array(
            'name' => 'stic_contacts_relationships_projectproject_ida',
            'type' => 'link',
            'relationship' => 'stic_contacts_relationships_project',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_STIC_CONTACTS_RELATIONSHIPS_PROJECT_FROM_STIC_CONTACTS_RELATIONSHIPS_TITLE',
        ),
    ),
    'relationships' => array(
    ),
    'optimistic_locking' => 1,
    'unified_search' => 1,
    'unified_search_default_enabled' => true,
);
if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('stic_Contacts_Relationships', 'stic_Contacts_Relationships', array('basic', 'assignable', 'security_groups'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Contacts_Relationships']['fields']['name']['required'] = '0'; // Name is not required in this module
$dictionary['stic_Contacts_Relationships']['fields']['name']['importable'] = true; // Name is importable but not required in this module
$dictionary['stic_Contacts_Relationships']['fields']['description']['rows'] = '2'; // Make textarea fields shorter
