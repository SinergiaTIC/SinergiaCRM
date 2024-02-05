<?php
/**
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
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
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo, "Supercharged by SuiteCRM" logo and “Nonprofitized by SinergiaCRM” logo. 
 * If the display of the logos is not reasonably feasible for technical reasons, 
 * the Appropriate Legal Notices must display the words "Powered by SugarCRM", 
 * "Supercharged by SuiteCRM" and “Nonprofitized by SinergiaCRM”. 
 */

$module_name = 'DHA_PlantillasDocumentos';
$OBJECT_NAME = 'DHA_PLANTILLASDOCUMENTOS';
 
// STIC-Custom - MHP - 20240201 - Override the core metadata files with the custom metadata files 
// https://github.com/SinergiaTIC/SinergiaCRM/pull/105
// $listViewDefs [$module_name] = 
// array (
//   // Calculado, ver el bean
//   'FILE_URL' => 
//   array (
//     'width' => '2%',
//     'label' => '&nbsp;',
//     'link' => true,
//     'default' => true,
//     'related_fields' => 
//     array (
//       0 => 'file_ext',
//     ),
//     'sortable' => false,
//     'studio' => false,
//   ),
  
//   'DOCUMENT_NAME' => 
//   array (
//     'width' => '25%',
//     'label' => 'LBL_NAME',
//     'link' => true,
//     'default' => true,
//   ),
  
//   // Calculado, ver el bean
//   'MODULO_URL' => 
//   array (
//     'width' => '2%',
//     'label' => '&nbsp;',
//     'link' => false, //true,
//     'default' => true,
//     'sortable' => false,
//     'studio' => false,
//   ),  
  
//   'MODULO' => 
//   array (
//     'type' => 'enum',
//     'default' => true,
//     'studio' => 'visible',
//     'label' => 'LBL_MODULO',
//     'width' => '10%',
//   ),
//   'IDIOMA' => 
//   array (
//     'type' => 'enum',
//     'default' => true,
//     'studio' => 'visible',
//     'label' => 'LBL_IDIOMA_PLANTILLA',
//     'width' => '10%',
//   ),
//   'STATUS_ID' => 
//   array (
//     'type' => 'enum',
//     'default' => true,
//     'studio' => 'visible',
//     'label' => 'LBL_DOC_STATUS',
//     'width' => '10%',
//   ),
//   'CATEGORY_ID' => 
//   array (
//     'width' => '10%',
//     'label' => 'LBL_LIST_CATEGORY',
//     'default' => true,
//   ),
//   'SUBCATEGORY_ID' => 
//   array (
//     'width' => '40%',
//     'label' => 'LBL_LIST_SUBCATEGORY',
//     'default' => false,
//   ),  
//   'ASSIGNED_USER_NAME' => 
//   array (
//     'link' => 'assigned_user_link',
//     'type' => 'relate',
//     'label' => 'LBL_ASSIGNED_TO_NAME',
//     'width' => '10%',
//     'default' => true,
//     'module' => 'Employees',
//   ),
//   'DESCRIPTION' => 
//   array (
//     'type' => 'text',
//     'label' => 'LBL_DESCRIPTION',
//     'sortable' => false,
//     'width' => '10%',
//     'default' => false,
//   ),
//   'FILE_EXT' => 
//   array (
//     'type' => 'varchar',
//     'label' => 'LBL_FILE_EXTENSION',
//     'width' => '10%',
//     'default' => false,
//   ),
//   'ACLROLES' => 
//   array (
//     'type' => 'multienum',
//     'default' => true,
//     'studio' => 'visible',
//     'label' => 'LBL_ROLES_WITH_ACCESS',
//     'width' => '10%',
//   ), 
//   'CREATED_BY_NAME' => 
//   array (
//     'width' => '2%',
//     'label' => 'LBL_LIST_LAST_REV_CREATOR',
//     'default' => false,
//     'sortable' => false,
//   ),
//   'DATE_ENTERED' => 
//   array (
//     'type' => 'datetime',
//     'label' => 'LBL_DATE_ENTERED',
//     'width' => '10%',
//     'default' => false,
//   ),
//   'MODIFIED_BY_NAME' => 
//   array (
//     'width' => '10%',
//     'label' => 'LBL_MODIFIED_USER',
//     'module' => 'Users',
//     'id' => 'USERS_ID',
//     'default' => false,
//     'sortable' => false,
//     'related_fields' => 
//     array (
//       0 => 'modified_user_id',
//     ),
//   ),
//   'DATE_MODIFIED' => 
//   array (
//     'type' => 'datetime',
//     'label' => 'LBL_DATE_MODIFIED',
//     'width' => '10%',
//     'default' => false,
//   ),
//   'UPLOADFILE' => 
//   array (
//     'type' => 'file',
//     'label' => 'LBL_FILE_UPLOAD',
//     'width' => '10%',
//     'default' => false,
//   ),
// );

$listViewDefs [$module_name] = 
array (
  'FILE_URL' => 
  array (
    'width' => '2%',
    'label' => '&nbsp;',
    'link' => true,
    'default' => true,
    'related_fields' => 
    array (
      0 => 'file_ext',
    ),
    'sortable' => false,
    'studio' => false,
  ),
  'MODULO_URL' => 
  array (
    'width' => '2%',
    'label' => '&nbsp;',
    'link' => false,
    'default' => true,
    'sortable' => false,
    'studio' => false,
  ),
  'DOCUMENT_NAME' => 
  array (
    'width' => '25%',
    'label' => 'LBL_NAME',
    'link' => true,
    'default' => true,
  ),
  'MODULO' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_MODULO',
    'width' => '10%',
  ),
  'IDIOMA' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_IDIOMA_PLANTILLA',
    'width' => '10%',
  ),
  'STATUS_ID' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_DOC_STATUS',
    'width' => '10%',
  ),
  'CATEGORY_ID' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_CATEGORY',
    'default' => false,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'link' => 'assigned_user_link',
    'type' => 'relate',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'width' => '10%',
    'default' => true,
    'module' => 'Employees',
  ),
  'ACLROLES' => 
  array (
    'type' => 'multienum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_ROLES_WITH_ACCESS',
    'width' => '10%',
  ),
  'SUBCATEGORY_ID' => 
  array (
    'width' => '40%',
    'label' => 'LBL_LIST_SUBCATEGORY',
    'default' => false,
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'FILE_EXT' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_FILE_EXTENSION',
    'width' => '10%',
    'default' => false,
  ),
  'CREATED_BY_NAME' => 
  array (
    'width' => '2%',
    'label' => 'LBL_LIST_LAST_REV_CREATOR',
    'default' => false,
    'sortable' => false,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => false,
  ),
  'MODIFIED_BY_NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_MODIFIED_USER',
    'module' => 'Users',
    'id' => 'USERS_ID',
    'default' => false,
    'sortable' => false,
    'related_fields' => 
    array (
      0 => 'modified_user_id',
    ),
  ),
  'DATE_MODIFIED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => false,
  ),
  'UPLOADFILE' => 
  array (
    'type' => 'file',
    'label' => 'LBL_FILE_UPLOAD',
    'width' => '10%',
    'default' => false,
  ),
);
// END STIC-Custom 