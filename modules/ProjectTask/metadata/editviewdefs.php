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
// $viewdefs ['ProjectTask'] =
// array(
//   'EditView' =>
//   array(
//     'templateMeta' =>
//     array(
//       'maxColumns' => '2',
//       'widths' =>
//       array(
//         0 =>
//         array(
//           'label' => '10',
//           'field' => '30',
//         ),
//         1 =>
//         array(
//           'label' => '10',
//           'field' => '30',
//         ),
//       ),
//       'includes' =>
//       array(
//         0 =>
//         array(
//           'file' => 'modules/ProjectTask/ProjectTask.js',
//         ),
//       ),
//       'useTabs' => false,
//       'tabDefs' =>
//       array(
//         'DEFAULT' =>
//         array(
//           'newTab' => false,
//           'panelDefault' => 'expanded',
//         ),
//         'LBL_PANEL_TIMELINE' =>
//         array(
//           'newTab' => false,
//           'panelDefault' => 'expanded',
//         ),
//       ),
//       'syncDetailEditViews' => false,
//     ),
//     'panels' =>
//     array(
//       'default' =>
//       array(
//         0 =>
//         array(
//           0 =>
//           array(
//             'name' => 'name',
//             'label' => 'LBL_NAME',
//           ),
//           1 =>
//           array(
//             'name' => 'status',
//             'customCode' => '<select name="{$fields.status.name}" id="{$fields.status.name}" title="" tabindex="s" onchange="update_percent_complete(this.value);">{if isset($fields.status.value) && $fields.status.value != ""}{html_options options=$fields.status.options selected=$fields.status.value}{else}{html_options options=$fields.status.options selected=$fields.status.default}{/if}</select>',
//           ),
//         ),
//         1 =>
//         array(
//           0 =>
//           array(
//             'name' => 'date_start',
//           ),
//           1 =>
//           array(
//             'name' => 'date_finish',
//           ),
//         ),
//         2 =>
//         array(
//           0 => 'priority',
//           1 =>
//           array(
//             'name' => 'percent_complete',
//             'customCode' => '<input type="text" name="{$fields.percent_complete.name}" id="{$fields.percent_complete.name}" size="30" value="{$fields.percent_complete.value}" title="" tabindex="0" onChange="update_status(this.value);" /></tr>',
//           ),
//         ),
//         3 =>
//         array(
//           0 =>
//           array(
//             'name' => 'project_name',
//             'label' => 'LBL_PROJECT_NAME',
//           ),
//           1 => 'task_number',
//         ),
//         4 =>
//         array(
//           0 => 'assigned_user_name',
//         ),
//         5 =>
//         array(
//           0 =>
//           array(
//             'name' => 'description',
//           ),
//         ),
//       ),
//       'LBL_PANEL_TIMELINE' =>
//       array(
//         0 =>
//         array(
//           0 => 'estimated_effort',
//           1 =>
//           array(
//             'name' => 'actual_effort',
//             'label' => 'LBL_ACTUAL_EFFORT',
//           ),
//         ),
//         1 =>
//         array(
//           0 =>
//           array(
//             'name' => 'relationship_type',
//             'studio' => 'visible',
//             'label' => 'LBL_RELATIONSHIP_TYPE',
//           ),
//           1 => 'utilization',
//         ),
//         2 =>
//         array(
//           0 => 'order_number',
//           1 => 'milestone_flag',
//         ),
//       ),
//     ),
//   ),
// );

$viewdefs['ProjectTask'] =
array(
    'EditView' => array(
        'templateMeta' => array(
            'maxColumns' => '2',
            'widths' => array(
                0 => array(
                    'label' => '10',
                    'field' => '30',
                ),
                1 => array(
                    'label' => '10',
                    'field' => '30',
                ),
            ),
            'includes' => array(
                0 => array(
                    'file' => 'modules/ProjectTask/ProjectTask.js',
                ),
            ),
            'useTabs' => true,
            'tabDefs' => array(
                'LBL_DEFAULT_PANEL' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
                'LBL_PANEL_TIMELINE' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
            ),
            'syncDetailEditViews' => false,
        ),
        'panels' => array(
            'lbl_default_panel' => array(
                0 => array(
                    0 => array(
                        'name' => 'name',
                        'label' => 'LBL_NAME',
                    ),
                    1 => 'assigned_user_name',
                ),
                1 => array(
                    0 => array(
                        'name' => 'status',
                        'customCode' => '<select name="{$fields.status.name}" id="{$fields.status.name}" title="" tabindex="s" onchange="update_percent_complete(this.value);">{if isset($fields.status.value) && $fields.status.value != ""}{html_options options=$fields.status.options selected=$fields.status.value}{else}{html_options options=$fields.status.options selected=$fields.status.default}{/if}</select>',
                    ),
                    1 => '',
                ),
                2 => array(
                    0 => array(
                        'name' => 'date_start',
                    ),
                    1 => array(
                        'name' => 'date_finish',
                    ),
                ),
                3 => array(
                    0 => 'priority',
                    1 => array(
                        'name' => 'percent_complete',
                        'customCode' => '<input type="text" name="{$fields.percent_complete.name}" id="{$fields.percent_complete.name}" size="30" value="{$fields.percent_complete.value}" title="" tabindex="0" onChange="update_status(this.value);" /></tr>',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'project_name',
                        'label' => 'LBL_PROJECT_NAME',
                    ),
                    1 => 'task_number',
                ),
                5 => array(
                    0 => array(
                        'name' => 'description',
                    ),
                ),
            ),
            'LBL_PANEL_TIMELINE' => array(
                0 => array(
                    0 => 'estimated_effort',
                    1 => array(
                        'name' => 'actual_effort',
                        'label' => 'LBL_ACTUAL_EFFORT',
                    ),
                ),
                1 => array(
                    0 => array(
                        'name' => 'relationship_type',
                        'studio' => 'visible',
                        'label' => 'LBL_RELATIONSHIP_TYPE',
                    ),
                    1 => 'utilization',
                ),
                2 => array(
                    0 => 'order_number',
                    1 => 'milestone_flag',
                ),
            ),
        ),
    ),
);
// END STIC-Custom