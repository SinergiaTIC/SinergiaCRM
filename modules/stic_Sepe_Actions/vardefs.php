<?php

/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
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
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
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
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/

$dictionary['stic_Sepe_Actions'] = array(
    'table' => 'stic_sepe_actions',
    'audited' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'start_date' =>
        array(
            'required' => true,
            'name' => 'start_date',
            'vname' => 'LBL_START_DATE',
            'type' => 'date',
            'massupdate' => 1,
            'no_default' => false,
            // 'validation' => array('type' => 'isbefore', 'compareto' => 'end_date', 'blank' => 1),
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'size' => '20',
            'enable_range_search' => '1',
            'options' => 'date_range_search_dom',
        ),
        'end_date' =>
        array(
            'required' => false,
            'name' => 'end_date',
            'vname' => 'LBL_END_DATE',
            'type' => 'date',
            'massupdate' => 1,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'size' => '20',
            'enable_range_search' => '1',
            'options' => 'date_range_search_dom',
        ),
        'type' =>
        array(
            'required' => true,
            'name' => 'type',
            'vname' => 'LBL_ACTION_TYPE',
            'type' => 'enum',
            'massupdate' => 1,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'len' => 100,
            'size' => '20',
            'options' => 'stic_sepe_action_types_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'agreement' =>
        array(
            'required' => false,
            'name' => 'agreement',
            'vname' => 'LBL_AGREEMENT',
            'type' => 'enum',
            'massupdate' => 1,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'inline_edit' => 0,
            'len' => 100,
            'size' => '20',
            'options' => 'stic_sepe_agreement_list',
            'studio' => 'visible',
            'dependency' => false,
            'popupHelp' => 'LBL_AGREEMENT_HELP',
        ),
        "stic_sepe_actions_contacts" =>
        array(
            'name' => 'stic_sepe_actions_contacts',
            'type' => 'link',
            'relationship' => 'stic_sepe_actions_contacts',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_SEPE_ACTIONS_CONTACTS_FROM_CONTACTS_TITLE',
            'id_name' => 'stic_sepe_actions_contactscontacts_ida',
        ),
        "stic_sepe_actions_contacts_name" =>
        array(
            'required' => true,
            'name' => 'stic_sepe_actions_contacts_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_SEPE_ACTIONS_CONTACTS_FROM_CONTACTS_TITLE',
            'save' => true,
            'id_name' => 'stic_sepe_actions_contactscontacts_ida',
            'link' => 'stic_sepe_actions_contacts',
            'table' => 'contacts',
            'module' => 'Contacts',
            'rname' => 'name',
            'db_concat_fields' =>
            array(
                0 => 'first_name',
                1 => 'last_name',
            ),
        ),
        "stic_sepe_actions_contactscontacts_ida" =>
        array(
            'name' => 'stic_sepe_actions_contactscontacts_ida',
            'type' => 'link',
            'relationship' => 'stic_sepe_actions_contacts',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_STIC_SEPE_ACTIONS_CONTACTS_FROM_STIC_SEPE_ACTIONS_TITLE',
        ),
    ),
    'relationships' => array(),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once('include/SugarObjects/VardefManager.php');
}

VardefManager::createVardef('stic_Sepe_Actions', 'stic_Sepe_Actions', array('basic', 'assignable', 'security_groups'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Sepe_Actions']['fields']['name']['required'] = '0'; // Name is not required in this module
$dictionary['stic_Sepe_Actions']['fields']['name']['importable'] = true; // Name is importable but not required in this module
$dictionary['stic_Sepe_Actions']['fields']['description']['rows'] = '2'; // Make textarea fields shorter
