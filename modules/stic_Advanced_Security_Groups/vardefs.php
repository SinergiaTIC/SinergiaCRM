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

$dictionary['stic_Advanced_Security_Groups'] = array(
    'table' => 'stic_advanced_security_groups',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'enum',
            'options' => 'dynamic_filtered_module_list',
            'link' => false,
            'dbType' => 'varchar',
            'len' => '255',
            'unified_search' => false,
            'full_text_search' => array(
                'boost' => 3,
            ),
            'required' => true,
            'importable' => 'required',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'selected',
            'massupdate' => 0,
            'default' => '',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'duplicate_merge_dom_value' => '3',
            'audited' => true,
            'inline_edit' => false,
            'reportable' => true,
            'size' => '20',
        ),
        'name_lbl' => array(
            'name' => 'name_lbl',
            'vname' => 'LBL_NAME_LBL',
            'type' => 'varchar',
            'link' => true,
            'dbType' => 'varchar',
            'len' => '255',
            'unified_search' => false,
            'required' => false,
            'importable' => false,
            'duplicate_merge' => 'disabled',
            'merge_filter' => 'disabled',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => false,
            'reportable' => false,
            'size' => '20',
        ),
        'comments' => array(
            'name' => 'comments',
            'vname' => 'LBL_COMMENTS',
            'type' => 'varchar',
            'link' => false,
            'dbType' => 'varchar',
            'len' => '255',
            'unified_search' => false,
            'required' => false,
            'importable' => false,
            'duplicate_merge' => 'disabled',
            'merge_filter' => 'disabled',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => false,
            'size' => '20',
        ),
        'active' => array(
            'required' => false,
            'name' => 'active',
            'vname' => 'LBL_ACTIVE',
            'type' => 'bool',
            'no_default' => false,
            'massupdate' => 1,
            'inline_edit' => 1,
            'comments' => '',
            'help' => '',
            'importable' => 'false',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'popupHelp' => 'LBL_ACTIVE_INFO',
            'studio' => array(
                'quickcreate' => false,
                'editview' => false,
            ),
        ),
        'inherit_from_modules' => array(
            'audited' => false,
            'comments' => '',
            'duplicate_merge_dom_value' => '0',
            'duplicate_merge' => 'disabled',
            'help' => '',
            'importable' => 'true',
            'inline_edit' => false,
            'isMultiSelect' => true,
            'default' => '^^',
            'massupdate' => 1,
            'merge_filter' => 'disabled',
            'name' => 'inherit_from_modules',
            'no_default' => 0,
            'options' => 'dynamic_related_module_list',
            'reportable' => true,
            'required' => false,
            'studio' => 'visible',
            'type' => 'multienum',
            'unified_search' => false,
            'vname' => 'LBL_INHERIT_FROM_MODULES',
            'popupHelp' => 'LBL_INHERIT_FROM_MODULES_INFO',
        ),

        'non_inherit_from_security_groups' => array(
            'required' => false,
            'name' => 'non_inherit_from_security_groups',
            'vname' => 'LBL_NON_INHERIT_FROM_SECURITY_GROUPS',
            'type' => 'multienum',
            'massupdate' => 1,
            'isMultiSelect' => true,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => false,
            'reportable' => true,
            'unified_search' => false,
            'options' => 'dynamic_security_group_list',
            'merge_filter' => 'disabled',
            'studio' => 'visible',
            'dependency' => false,
            'popupHelp' => 'LBL_NON_INHERIT_FROM_SECURITY_GROUPS_INFO',
        ),

        'inherit_assigned' => array(
            'required' => true,
            'name' => 'inherit_assigned',
            'vname' => 'LBL_INHERIT_ASSIGNED',
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
            'len' => 100,
            'size' => '20',
            'studio' => 'visible',
            'dependency' => false,
            'popupHelp' => 'LBL_INHERIT_ASSIGNED_CUSTOM_INFO',
        ),
        'inherit_creator' => array(
            'required' => true,
            'name' => 'inherit_creator',
            'vname' => 'LBL_INHERIT_CREATOR',
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
            'len' => 100,
            'size' => '20',
            'studio' => 'visible',
            'dependency' => false,
            'popupHelp' => 'LBL_INHERIT_CREATOR_CUSTOM_INFO',
        ),
        'inherit_parent' => array(
            'required' => true,
            'name' => 'inherit_parent',
            'vname' => 'LBL_INHERIT_PARENT',
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
            'len' => 100,
            'size' => '20',
            'studio' => 'visible',
            'dependency' => false,
            'popupHelp' => 'LBL_INHERIT_PARENT_CUSTOM_INFO',
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
VardefManager::createVardef('stic_Advanced_Security_Groups', 'stic_Advanced_Security_Groups', array('basic', 'assignable', 'security_groups'));
