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

$module_name = 'stic_Advanced_Web_Forms_Response_Details';
$listViewDefs[$module_name] = array(
    'QUESTION_SORT_ORDER' => array(
        'width' => '10',
        'label' => 'LBL_QUESTION_SORT_ORDER',
        'default' => true,
    ),
    'QUESTION_KEY' => array(
        'width' => '10',
        'label' => 'LBL_QUESTION_KEY',
        'default' => false,
    ),
    'QUESTION_SECTION' => array(
        'width' => '10',
        'label' => 'LBL_QUESTION_SECTION',
        'default' => true,
    ),
    'QUESTION_LABEL' => array(
        'width' => '10',
        'label' => 'LBL_QUESTION_LABEL',
        'default' => true,
    ),
    'QUESTION_HELP_TEXT' => array(
        'width' => '10',
        'label' => 'LBL_QUESTION_HELP_TEXT',
        'default' => true,
    ),
    'ANSWER_VALUE' => array(
        'width' => '10',
        'label' => 'LBL_ANSWER_VALUE',
        'default' => false,
    ), 
    'ANSWER_TEXT' => array(
        'width' => '10',
        'label' => 'LBL_ANSWER_TEXT',
        'default' => true,
    ),
    'ANSWER_FORM_TYPE' => array(
        'width' => '10',
        'label' => 'LBL_ANSWER_FORM_TYPE',
        'default' => false,
    ), 
    'ANSWER_INTEGER' => array(
        'width' => '10',
        'label' => 'LBL_ANSWER_INTEGER',
        'default' => false,
    ),
    // 'FORM_NAME' => array(
    //     'width' => '9',
    //     'label' => 'LBL_FORM_NAME',
    //     'module' => 'stic_Advanced_Web_Forms',
    //     'id' => 'FORM_ID',
    //     'default' => true
    // ),
    // 'RESPONSE_NAME' => array(
    //     'width' => '9',
    //     'label' => 'LBL_RESPONSE_NAME',
    //     'module' => 'stic_Advanced_Web_Forms_Responses',
    //     'id' => 'RESPONSE_ID',
    //     'default' => true
    // ),
    'DATE_MODIFIED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => false,
    ),
    'DATE_ENTERED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => false,
    ),
);
