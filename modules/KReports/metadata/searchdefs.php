<?php
/* * *******************************************************************************
 * This file is part of KReporter. KReporter is an enhancement developed
 * by Christian Knoll. All rights are (c) 2012 by Christian Knoll
 *
 * This Version of the KReporter is licensed software and may only be used in
 * alignment with the License Agreement received with this Software.
 * This Software is copyrighted and may not be further distributed without
 * witten consent of Christian Knoll
 *
 * You can contact us at info@kreporter.org
 * ****************************************************************************** */
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$searchdefs['KReports'] = array(
    'templateMeta' => array(
        'maxColumns' => '3',
        'widths' => array('label' => '10', 'field' => '30'),
    ),
    'layout' => array(
        'basic_search' => array(
            'name' => array(
                'name' => 'name',
                'default' => true,
                'label' => 'LBL_LIST_SUBJECT',
                'width' => '10%',
            ),
            'report_modules' => array(
                'type' => 'enum',
                'name' => 'report_module', 
                'default' => true,
                'label' => 'LBL_MODULE',
                'width' => '10%',
            ),
            'date_entered' => array(
                'type' => 'datetime',
                'label' => 'LBL_DATE_ENTERED',
                'default' => true,
                'name' => 'date_entered',
            ),
            'date_modified' => array(
                'type' => 'datetime',
                'label' => 'LBL_DATE_MODIFIED',
                'default' => true,
                'name' => 'date_modified',
            ),
            'assigned_user_id' => array(
                // STIC-Custom 20211108 AAM - Fixing field type, so the filter would work
                // STIC#469
                'name' => 'assigned_user_id',
                'label' => 'LBL_ASSIGNED_TO',
                'type' => 'enum',
                'function' => array(
                    'name' => 'get_user_array',
                    'params' => array(
                        0 => false,
                    ),
                ),
                'width' => '10%',
                'default' => true,
                // END STIC
            ),
            'favorites_only' => array(
                'name' => 'favorites_only',
                'label' => 'LBL_FAVORITES_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
            ),
        ),
        'advanced_search' => array(
            'name' => array(
                'name' => 'name',
                'default' => true,
                'label' => 'LBL_LIST_SUBJECT',
                'width' => '10%',
            ),
            'report_modules' => array(
                'type' => 'enum',
                'name' => 'report_module', 
                'default' => true,
                'label' => 'LBL_MODULE',
                'width' => '10%',
            ),
            'date_entered' => array(
                'type' => 'datetime',
                'label' => 'LBL_DATE_ENTERED',
                'default' => true,
                'name' => 'date_entered',
            ),
            'date_modified' => array(
                'type' => 'datetime',
                'label' => 'LBL_DATE_MODIFIED',
                'default' => true,
                'name' => 'date_modified',
            ),
            'description' => array(
                'type' => 'text',
                'label' => 'LBL_DESCRIPTION',
                'sortable' => false,
                'width' => '10%',
                'default' => true,
                'name' => 'description',
            ),
            'assigned_user_id' => array(
                // STIC-Custom 20211108 AAM - Fixing field type, so the filter would work
                // STIC#469
                'name' => 'assigned_user_id',
                'label' => 'LBL_ASSIGNED_TO',
                'type' => 'enum',
                'function' => array(
                    'name' => 'get_user_array',
                    'params' => array(
                        0 => false,
                    ),
                ),
                'width' => '10%',
                'default' => true,
                // END STIC
            ),
            'created_by' => array(
                // STIC-Custom 20211108 AAM - Fixing field type, so the filter would work
                // STIC#469
                // 'type' => 'relate',
                'type' => 'assigned_user_name',
                'label' => 'LBL_CREATED',
                'width' => '10%',
                'default' => true,
                'name' => 'created_by',
                // END STIC
            ),
            'modified_user_id' => array(
                // STIC-Custom 20211108 AAM - Fixing field type, so the filter would work
                // STIC#469
                // 'type' => 'relate',
                'type' => 'assigned_user_name',
                'label' => 'LBL_MODIFIED',
                'width' => '10%',
                'default' => true,
                'name' => 'modified_user_id',
                // END STIC
            ),
            'current_user_only' => array(
                'name' => 'current_user_only', 
                'label' => 'LBL_CURRENT_USER_FILTER', 
                'width' => '10%',
                'default' => true,
                'type' => 'bool'
            ),
            'favorites_only' => array(
                'name' => 'favorites_only',
                'label' => 'LBL_FAVORITES_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
            ),
        ),
    ),
);
?>
