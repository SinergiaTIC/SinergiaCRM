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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $current_user;

$dashletData['stic_Validation_ResultsDashlet']['searchFields'] = array(
    'name' => array(
        'default' => '',
    ),
    'execution_date' => array(
        'default' => '',
    ),
    'validation_action' => array(
        'default' => '',
    ),
    'reviewed' => array(
        'default' => '',
    ),
    'scheduler' => array(
        'default' => '',
    ),
    'assigned_user_name' => array(
        'default' => '',
    ),
    'description' => array(
        'default' => '',
    ),
);
$dashletData['stic_Validation_ResultsDashlet']['columns'] = array(
    'name' => array(
        'width' => '40%',
        'label' => 'LBL_LIST_NAME',
        'link' => true,
        'default' => true,
        'name' => 'name',
    ),
    'execution_date' => array(
        'type' => 'datetimecombo',
        'studio' => array(
            'editview' => false,
            'quickcreate' => false,
        ),
        'label' => 'LBL_EXECUTION_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'execution_date',
    ),
    'validation_action' => array(
        'type' => 'relate',
        'studio' => array(
            'editview' => false,
            'quickcreate' => false,
        ),
        'label' => 'LBL_VALIDATION_ACTION',
        'id' => 'STIC_VALIDATION_ACTIONS_ID_C',
        'link' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'validation_action',
    ),
    'parent_name' => array(
        'width' => '10%',
        'label' => 'LBL_FLEX_RELATE',
        'sortable' => false,
        'dynamic_module' => 'PARENT_TYPE',
        'link' => true,
        'id' => 'PARENT_ID',
        'ACLTag' => 'PARENT',
        'related_fields' => array(
            0 => 'parent_id',
            1 => 'parent_type',
        ),
        'default' => true,
        'name' => 'parent_name',
    ),
    'reviewed' => array(
        'type' => 'enum',
        'default' => true,
        'label' => 'LBL_REVIEWED',
        'width' => '10%',
        'name' => 'reviewed',
    ),
    'assigned_user_name' => array(
        'width' => '8%',
        'label' => 'LBL_LIST_ASSIGNED_USER',
        'name' => 'assigned_user_name',
        'default' => true,
    ),
    'scheduler' => array(
        'type' => 'relate',
        'studio' => array(
            'editview' => false,
            'quickcreate' => false,
        ),
        'label' => 'LBL_SCHEDULER',
        'id' => 'SCHEDULERS_ID_C',
        'link' => true,
        'width' => '10%',
        'default' => false,
        'name' => 'scheduler',
    ),
    'description' => array(
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => false,
        'name' => 'description',
    ),
    'created_by_name' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_CREATED',
        'id' => 'CREATED_BY',
        'width' => '10%',
        'default' => false,
        'name' => 'created_by_name',
    ),
    'date_entered' => array(
        'width' => '15%',
        'label' => 'LBL_DATE_ENTERED',
        'default' => false,
        'name' => 'date_entered',
    ),
    'modified_by_name' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_MODIFIED_NAME',
        'id' => 'MODIFIED_USER_ID',
        'width' => '10%',
        'default' => false,
        'name' => 'modified_by_name',
    ),
    'date_modified' => array(
        'width' => '15%',
        'label' => 'LBL_DATE_MODIFIED',
        'name' => 'date_modified',
        'default' => false,
    ),
);
