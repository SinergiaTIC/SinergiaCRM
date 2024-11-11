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
$module_name = 'stic_Messages';
$viewdefs[$module_name] =
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
            'useTabs' => false,
            'tabDefs' => array(
                'LBL_DEFAULT_PANEL' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
            'syncDetailEditViews' => false,
        ),
        'panels' => array(
            'lbl_default_panel' => array(
                1 => array(
                    0 => array(
                        'name' => 'parent_name',
                        'label' => 'LBL_LIST_RELATED_TO',
                        'displayParams' => array(
                            'call_back_function' => '$.fn.stic_MessagesComposeView.onParentSelect',
                            'field_to_name_array' => array(
                                'id' => 'parent_id',
                                'name' => 'parent_name',
                                'phone_mobile' => 'phone_mobile',
                                'phone_office' => 'phone_office',
                            )
                        ),
                    ),

                ),
                2 => array(
                    0 => array(
                        'name' => 'sender',
                        'comment' => 'Sender',
                        'label' => 'LBL_SENDER',
                    ),
                    1 => array(
                        'name' => 'phone',
                        'studio' => 'visible',
                        'label' => 'LBL_PHONE',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'message',
                        'comment' => 'Full text of the message',
                        'label' => 'LBL_MESSAGE',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'type',
                        'studio' => 'visible',
                        'label' => 'LBL_TYPE',
                    ),
                    1 => array(
                        'name' => 'direction',
                        'studio' => 'visible',
                        'label' => 'LBL_DIRECTION',
                    ),
                ),
                5 => array(
                    0 => array(
                        'name' => 'status',
                        'studio' => 'visible',
                        'label' => 'LBL_STATUS',
                    ),
                    1 => array (
                      'name' => 'template',
                      'studio' => 'visible',
                      'label' => 'LBL_TEMPLATE',
                      'displayParams' => array(
                        'call_back_function' => '$.fn.stic_MessagesComposeView.onTemplateSelect',
                        'initial_filter' => "&type_advanced[]=sms"
                    ),
                    ),
                ),
            ),
        ),
    ),
);
