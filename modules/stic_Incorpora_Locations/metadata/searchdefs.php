<?php
$module_name = 'stic_Incorpora_Locations';
$searchdefs[$module_name] =
array(
    'layout' => array(
        'basic_search' => array(
            'name' => array(
                'name' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            'town' => array(
                'type' => 'varchar',
                'label' => 'LBL_TOWN',
                'width' => '10%',
                'default' => true,
                'name' => 'town',
            ),
            'municipality' => array(
                'type' => 'varchar',
                'label' => 'LBL_MUNICIPALITY',
                'width' => '10%',
                'default' => true,
                'name' => 'municipality',
            ),
            'state' => array(
                'type' => 'varchar',
                'label' => 'LBL_STATE',
                'width' => '10%',
                'default' => true,
                'name' => 'state',
            ),
            'assigned_user_name' => array(
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
        'advanced_search' => array(
            'name' => array(
                'name' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            'town' => array(
                'type' => 'varchar',
                'label' => 'LBL_TOWN',
                'width' => '10%',
                'default' => true,
                'name' => 'town',
            ),
            'municipality' => array(
                'type' => 'varchar',
                'label' => 'LBL_MUNICIPALITY',
                'width' => '10%',
                'default' => true,
                'name' => 'municipality',
            ),
            'state' => array(
                'type' => 'varchar',
                'label' => 'LBL_STATE',
                'width' => '10%',
                'default' => true,
                'name' => 'state',
            ),
            'town_code' => array(
                'type' => 'int',
                'label' => 'LBL_TOWN_CODE',
                'width' => '10%',
                'default' => true,
                'name' => 'town_code',
            ),
            'municipality_code' => array(
                'type' => 'int',
                'label' => 'LBL_MUNICIPALITY_CODE',
                'width' => '10%',
                'default' => true,
                'name' => 'municipality_code',
            ),
            'state_code' => array(
                'type' => 'int',
                'label' => 'LBL_STATE_CODE',
                'width' => '10%',
                'default' => true,
                'name' => 'state_code',
            ),
            'assigned_user_name' => array(
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
            'date_modified' => array(
                'type' => 'datetime',
                'label' => 'LBL_DATE_MODIFIED',
                'width' => '10%',
                'default' => true,
                'name' => 'date_modified',
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
