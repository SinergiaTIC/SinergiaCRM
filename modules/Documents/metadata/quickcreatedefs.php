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

// STIC-Custom - MHP - 20240201 - Override the core metadata files with the custom metadata files 
// https://github.com/SinergiaTIC/SinergiaCRM/pull/105 
// $viewdefs['Documents']['QuickCreate'] = array(
//     'templateMeta' => array('form' => array('enctype'=>'multipart/form-data',
//                                             'hidden'=>array('<input type="hidden" name="old_id" value="{$fields.document_revision_id.value}">',
//                                                             '<input type="hidden" name="parent_id" value="{$smarty.request.parent_id}">',
//                                                             '<input type="hidden" name="parent_type" value="{$smarty.request.parent_type}">',)),                                    
//                             'maxColumns' => '2',
//                             'widths' => array(
//                                             array('label' => '10', 'field' => '30'),
//                                             array('label' => '10', 'field' => '30')
//                                             ),
//                             'includes' =>
//                               array(
//                                 array('file' => 'include/javascript/popup_parent_helper.js'),
//                                 array('file' => 'cache/include/javascript/sugar_grp_jsolait.js'),
//                                 array('file' => 'modules/Documents/documents.js'),
//                               ),
// ),
//  'panels' =>array(
//   'default' =>
//   array(
//     array(
//       'status_id',
//     ),
//     array(
//       array('name'=>'filename',
//             'displayParams'=>array('required'=>true, 'onchangeSetFileNameTo' => 'document_name'),
//             ),
//     ),
//     array(
//       'document_name',
//        array('name'=>'revision',
//             'customCode' => '<input name="revision" type="text" value="{$fields.revision.value}" {$DISABLED}>'
//            ),
//     ),
//     array(
//        array('name'=>'active_date','displayParams'=>array('required'=>true)),
//        'category_id',
//     ),
//     array(
//       array('name'=>'description', 'displayParams'=>array('rows'=>10, 'cols'=>120)),
//     ),
//   ),
// )
// );

$viewdefs['Documents']['QuickCreate'] = array (
  'templateMeta' => 
  array (
    'form' => 
    array (
      'enctype' => 'multipart/form-data',
      'hidden' => 
      array (
        0 => '<input type="hidden" name="old_id" value="{$fields.document_revision_id.value}">',
        1 => '<input type="hidden" name="parent_id" value="{$smarty.request.parent_id}">',
        2 => '<input type="hidden" name="parent_type" value="{$smarty.request.parent_type}">',
      ),
    ),
    'maxColumns' => '2',
    'widths' => 
    array (
      0 => 
      array (
        'label' => '10',
        'field' => '30',
      ),
      1 => 
      array (
        'label' => '10',
        'field' => '30',
      ),
    ),
    'includes' => 
    array (
      0 => 
      array (
        'file' => 'include/javascript/popup_parent_helper.js',
      ),
      1 => 
      array (
        'file' => 'cache/include/javascript/sugar_grp_jsolait.js',
      ),
      2 => 
      array (
        'file' => 'modules/Documents/documents.js',
      ),
    ),
    'useTabs' => true,
    'tabDefs' => 
    array (
      'LBL_OVERVIEW_PANEL' => 
      array (
        'newTab' => true,
        'panelDefault' => 'expanded',
      ),
    ),
  ),
  'panels' => 
  array (
    'lbl_overview_panel' => 
    array (
      0 => 
      array (
        0 => 'document_name',
        1 => 
        array (
          'name' => 'assigned_user_name',
          'label' => 'LBL_ASSIGNED_TO_NAME',
        ),
      ),
      1 => 
      array (
        0 => 
        array (
          'name' => 'filename',
          'displayParams' => 
          array (
            'onchangeSetFileNameTo' => 'document_name',
          ),
        ),
        1 => 'status_id',
      ),
      2 => 
      array (
        0 => 
        array (
          'name' => 'stic_shared_document_link_c',
          'label' => 'LBL_STIC_SHARED_DOCUMENT_LINK',
        ),
        1 => '',
      ),
      3 => 
      array (
        0 => 
        array (
          'name' => 'active_date',
          'displayParams' => 
          array (
            'required' => true,
          ),
        ),
        1 => 
        array (
          'name' => 'exp_date',
          'label' => 'LBL_DOC_EXP_DATE',
        ),
      ),
      4 => 
      array (
        0 => 
        array (
          'name' => 'description',
          'displayParams' => 
          array (
            'rows' => 2,
            'cols' => 120,
          ),
        ),
      ),
    ),
  ),
);
// END STIC-Custom