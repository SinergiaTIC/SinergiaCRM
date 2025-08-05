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

$dictionary["TemplateSectionLine"]['fields']['assigned_user_id'] = array (
    'name' => 'assigned_user_id',
    'rname' => 'user_name',
    'id_name' => 'assigned_user_id',
    'vname' => 'LBL_ASSIGNED_TO_ID',
    'group' => 'assigned_user_name',
    'type' => 'relate',
    'table' => 'users',
    'module' => 'Users',
    'reportable' => true,
    'isnull' => 'false',
    'dbType' => 'id',
    'audited' => true,
    'comment' => 'User ID assigned to record',
    'duplicate_merge' => 'disabled',
    'massupdate' => true,
);

$dictionary["TemplateSectionLine"]['fields']['assigned_user_name'] = array (
    'name' => 'assigned_user_name',
    'link' => 'assigned_user_link',
    'vname' => 'LBL_ASSIGNED_TO_NAME',
    'rname' => 'user_name',
    'type' => 'relate',
    'reportable' => false,
    'source' => 'non-db',
    'table' => 'users',
    'id_name' => 'assigned_user_id',
    'module' => 'Users',
    'duplicate_merge' => 'disabled',
);

$dictionary["TemplateSectionLine"]['fields']['assigned_user_link'] = array (
    'name' => 'assigned_user_link',
    'type' => 'link',
    'relationship' => 'templateSectionLine_assigned_user',
    'vname' => 'LBL_ASSIGNED_TO',
    'link_type' => 'one',
    'module' => 'Users',
    'bean_name' => 'User',
    'source' => 'non-db',
    'duplicate_merge' => 'enabled',
    'rname' => 'user_name',
    'id_name' => 'assigned_user_id',
    'table' => 'users',
);

$dictionary["TemplateSectionLine"]['fields']['thumbnail_name_c'] = array (
  'required' => true,
  'name' => 'thumbnail_name_c',
  'vname' => 'LBL_THUMBNAIL_NAME',
  'type' => 'varchar',
  'source' => 'custom_fields',
  'massupdate' => 0,
  'no_default' => false,
  'comments' => '',
  'help' => '',
  'popupHelp' => 'LBL_THUMBNAIL_NAME_HELP',
  'importable' => 'true',
  'duplicate_merge' => 'disabled',
  'duplicate_merge_dom_value' => '0',
  'audited' => false,
  'inline_edit' => true,
  'reportable' => true,
  'unified_search' => false,
  'merge_filter' => 'disabled',
  'len' => '40',
  'size' => '40',
);

$dictionary["TemplateSectionLine"]['fields']['thumbnail_image_c'] = array (
  'required' => false,
  'source' => 'custom_fields',
  'name' => 'thumbnail_image_c',
  'vname' => 'LBL_THUMBNAIL_IMAGE',
  'type' => 'image',
  'massupdate' => '0',
  'default' => NULL,
  'no_default' => false,
  'comments' => '',
  'help' => '',
  'importable' => 'true',
  'duplicate_merge' => 'disabled',
  'duplicate_merge_dom_value' => '0',
  'audited' => false,
  'inline_edit' => '1',
  'reportable' => true,
  'unified_search' => false,
  'merge_filter' => 'disabled',
  'len' => 255,
  'size' => '20',
  'studio' => 'visible',
  'dbType' => 'varchar',
  'border' => '',
  'width' => '120',
  'height' => '',
  'id' => 'TemplateSectionLinethumbnail_image_c',
  'custom_module' => 'TemplateSectionLine',
);

$dictionary["TemplateSectionLine"]['relationships']['templateSectionLine_assigned_user'] = array (
  'lhs_module' => 'Users',
  'lhs_table' => 'users',
  'lhs_key' => 'id',
  'rhs_module' => 'TemplateSectionLine',
  'rhs_table' => 'templatesectionline',
  'rhs_key' => 'assigned_user_id',
  'relationship_type' => 'one-to-many',
);

$dictionary["TemplateSectionLine"]['fields']['description']['editor'] = 'html';
$dictionary["TemplateSectionLine"]['fields']['thumbnail']['default'] = 'upload/';

