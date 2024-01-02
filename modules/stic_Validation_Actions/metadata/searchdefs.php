<?php
$module_name = 'stic_Validation_Actions';
$searchdefs[$module_name] =
array(
    'layout' => array(
        'basic_search' => array(
            'name' => array(
                'name' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            'last_execution' => array(
                'type' => 'datetimecombo',
                'label' => 'LBL_LAST_EXECUTION',
                'width' => '10%',
                'default' => true,
                'name' => 'last_execution',
            ),
            'report_always' => array(
                'type' => 'bool',
                'default' => true,
                'label' => 'LBL_REPORT_ALWAYS',
                'width' => '10%',
                'name' => 'report_always',
            ),
            'priority' => array(
                'type' => 'int',
                'default' => true,
                'label' => 'LBL_PRIORITY',
                'width' => '10%',
                'name' => 'priority',
            ),
            'assigned_user_id' => array(
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
            ),
            'current_user_only' => array(
                'name' => 'current_user_only',
                'label' => 'LBL_CURRENT_USER_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
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
                'width' => '10%',
            ),
            'last_execution' => array(
                'type' => 'datetimecombo',
                'label' => 'LBL_LAST_EXECUTION',
                'width' => '10%',
                'default' => true,
                'name' => 'last_execution',
            ),
            'function' => array(
                'type' => 'enum',
                'studio' => 'visible',
                'label' => 'LBL_FUNCTION',
                'width' => '10%',
                'default' => true,
                'name' => 'function',
            ),
            'report_always' => array(
                'type' => 'bool',
                'default' => true,
                'label' => 'LBL_REPORT_ALWAYS',
                'width' => '10%',
                'name' => 'report_always',
            ),
            'priority' => array(
                'type' => 'int',
                'default' => true,
                'label' => 'LBL_PRIORITY',
                'width' => '10%',
                'name' => 'priority',
            ),
            'assigned_user_id' => array(
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
            ),
            'description' => array(
                'type' => 'text',
                'label' => 'LBL_DESCRIPTION',
                'sortable' => false,
                'width' => '10%',
                'default' => true,
                'name' => 'description',
            ),
            'created_by' => array(
                'type' => 'assigned_user_name',
                'label' => 'LBL_CREATED',
                'width' => '10%',
                'default' => true,
                'name' => 'created_by',
            ),
            'modified_user_id' => array(
                'type' => 'assigned_user_name',
                'label' => 'LBL_MODIFIED',
                'width' => '10%',
                'default' => true,
                'name' => 'modified_user_id',
            ),
            'date_modified' => array(
                'type' => 'datetime',
                'label' => 'LBL_DATE_MODIFIED',
                'width' => '10%',
                'default' => true,
                'name' => 'date_modified',
            ),
            'date_entered' => array(
                'type' => 'datetime',
                'label' => 'LBL_DATE_ENTERED',
                'width' => '10%',
                'default' => true,
                'name' => 'date_entered',
            ),

            'current_user_only' => array(
                'label' => 'LBL_CURRENT_USER_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
                'name' => 'current_user_only',
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
    'templateMeta' => array(
        'maxColumns' => '3',
        'maxColumnsBasic' => '4',
        'widths' => array(
            'label' => '10',
            'field' => '30',
        ),
    ),
);

?>
