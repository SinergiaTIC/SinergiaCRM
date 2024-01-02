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

$searchFields['KReports'] =
        array(
            'name' => array('query_type' => 'default'),
            'report_module' => array('query_type' => 'default'),
            'current_user_only' => array('query_type' => 'default', 'db_field' => array('assigned_user_id'), 'my_items' => true, 'vname' => 'LBL_CURRENT_USER_FILTER', 'type' => 'bool'),
            'assigned_user_id' => array('query_type' => 'default'),

            // Range Search Support
            // STIC-Custom 20211108 AAM - It is necessary to define these features, for the date_entered and date_modified filters to work
            // STIC#469
            'range_date_entered' => array('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
            'start_range_date_entered' => array('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
            'end_range_date_entered' => array('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),            
            'range_date_modified' => array('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
            'start_range_date_modified' => array('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
            'end_range_date_modified' => array('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
 
            'favorites_only' => array(
                'query_type' => 'format',
                'operator' => 'subquery',
                'checked_only' => true,
                'subquery' => 'SELECT favorites.parent_id FROM favorites
                                        WHERE favorites.deleted = 0
                                            and favorites.parent_type = \'KReports\'
                                            and favorites.assigned_user_id = \'{1}\'',
                'db_field' => array(
                    0 => 'id',
                ),
            ),
);
?>
