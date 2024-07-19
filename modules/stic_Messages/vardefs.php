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

$dictionary['stic_Messages'] = array(
    'table' => 'stic_messages',
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
        'type' => array(
            'required' => true,
            'name' => 'type',
            'vname' => 'LBL_TYPE',
            'type' => 'enum',
            'massupdate' => '1',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'required',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'stic_messages_type_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'direction' => array(
            'required' => true,
            'name' => 'direction',
            'vname' => 'LBL_DIRECTION',
            'type' => 'enum',
            'massupdate' => '1',
            'default' => 'outbound',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'required',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'stic_messages_direction_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'phone' => array(
            'name' => 'phone',
            'vname' => 'LBL_PHONE',
            'type' => 'phone',
            'dbType' => 'varchar',
            'len' => 100,
            'required' => true,
            'unified_search' => true,
            'full_text_search' => array('boost' => 1),
            'comment' => 'Phone number',
            'merge_filter' => 'enabled',
            'inline_edit' => false,
        ),
        'sender' => array(
            'name' => 'sender',
            'vname' => 'LBL_SENDER',
            'type' => 'varchar',
            'len' => '255',
            'comment' => 'The name used as sender in the message',
            'merge_filter' => 'enabled',
            'inline_edit' => false,
        ),
        'message' => array(
            'name' => 'message',
            'vname' => 'LBL_MESSAGE',
            'type' => 'text',
            'comment' => 'Full text of the message',
            'rows' => 6,
            'cols' => 80,
            'inline_edit' => false,
        ),
        'parent_type' => array(
            'name' => 'parent_type',
            'vname' => 'LBL_PARENT_TYPE',
            'type' => 'parent_type',
            'dbType' => 'varchar',
            'required' => false,
            'group' => 'parent_name',
            'options' => 'parent_type_display',
            'len' => 255,
            'comment' => 'The Sugar object to which the call is related'
        ),
        'parent_name' => array(
            'name' => 'parent_name',
            'parent_type' => 'record_type_display',
            'type_name' => 'parent_type',
            'id_name' => 'parent_id',
            'vname' => 'LBL_LIST_RELATED_TO',
            'type' => 'parent',
            'group' => 'parent_name',
            'source' => 'non-db',
            'options' => 'parent_type_display',
        ),
        'parent_id' => array(
            'name' => 'parent_id',
            'vname' => 'LBL_LIST_RELATED_TO_ID',
            'type' => 'id',
            'group' => 'parent_name',
            'reportable' => false,
            'comment' => 'The ID of the parent Sugar object identified by parent_type'
        ),
        // template
        'template_id_c' => 
        array (
          'required' => false,
          'name' => 'template_id_c',
          'vname' => 'LBL_TEMPLATE_ID',
          'type' => 'id',
          'massupdate' => 1,
          'no_default' => false,
          'comments' => '',
          'help' => '',
          'importable' => 'true',
          'duplicate_merge' => 'disabled',
          'duplicate_merge_dom_value' => 0,
          'audited' => false,
          'inline_edit' => false,
          'reportable' => false,
          'unified_search' => false,
          'merge_filter' => 'disabled',
          'len' => 36,
          'size' => '20',
        ),
        'template' => 
        array (
          'required' => false,
          'source' => 'non-db',
          'name' => 'template',
          'vname' => 'LBL_TEMPLATE',
          'type' => 'relate',
          'massupdate' => 1,
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
          'merge_filter' => 'disabled',
          'len' => '255',
          'size' => '20',
          'id_name' => 'template_id_c',
          'ext2' => 'EmailTemplates',
          'module' => 'EmailTemplates',
          'rname' => 'name',
          'quicksearch' => 'enabled',
          'studio' => 'visible',
        ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_messages_date',
            'type' => 'index',
            'fields' => array('date_entered'),
        ),
        array(
            'name' => 'idx_messages_par_del',
            'type' => 'index',
            'fields' => array('parent_id', 'parent_type', 'deleted')
        ),
        array(
            'name' => 'idx_messages_phone',
            'type' => 'index',
            'fields' => array('phone')),
    ),
    'relationships' => array(
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('stic_Messages', 'stic_Messages', array('basic', 'assignable', 'security_groups'));
