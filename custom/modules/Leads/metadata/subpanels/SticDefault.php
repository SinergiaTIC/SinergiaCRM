<?php

$subpanel_layout['list_fields'] = array(
    'name' => array(
        'vname' => 'LBL_LIST_NAME',
        'widget_class' => 'SubPanelDetailViewLink',
        'sort_order' => 'asc',
        'sort_by' => 'last_name',
        'module' => 'Leads',
        'width' => '20%',
        'default' => true,
    ),
    'status' => array(
        'type' => 'enum',
        'vname' => 'LBL_STATUS',
        'width' => '10%',
        'default' => true,
    ),
    'email1' => array(
        'vname' => 'LBL_LIST_EMAIL',
        'width' => '10%',
        'widget_class' => 'SubPanelEmailLink',
        'default' => true,
    ),
    'phone_mobile' => array(
        'type' => 'phone',
        'vname' => 'LBL_MOBILE_PHONE',
        'width' => '10%',
        'default' => true,
    ),
    'phone_home' => array(
        'type' => 'phone',
        'vname' => 'LBL_HOME_PHONE',
        'width' => '10%',
        'default' => true,
    ),
    'assigned_user_name' => array(
        'name' => 'assigned_user_name',
        'vname' => 'LBL_LIST_ASSIGNED_TO_NAME',
        'widget_class' => 'SubPanelDetailViewLink',
        'target_record_key' => 'assigned_user_id',
        'target_module' => 'Employees',
        'width' => '10%',
        'default' => true,
    ),
    'edit_button' => array(
        'vname' => 'LBL_EDIT_BUTTON',
        'widget_class' => 'SubPanelEditButton',
        'module' => 'Leads',
        'width' => '4%',
        'default' => true,
    ),
    'remove_button' => array(
        'vname' => 'LBL_REMOVE',
        'widget_class' => 'SubPanelRemoveButton',
        'module' => 'Leads',
        'width' => '4%',
        'default' => true,
    ),
    'first_name' => array(
        'usage' => 'query_only',
    ),
    'last_name' => array(
        'usage' => 'query_only',
    ),
    'salutation' => array(
        'name' => 'salutation',
        'usage' => 'query_only',
    ),
);
