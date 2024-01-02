<?php

$subpanel_layout['list_fields'] = array(
    'first_name' => array(
        'name' => 'first_name',
        'usage' => 'query_only',
    ),
    'last_name' => array(
        'name' => 'last_name',
        'usage' => 'query_only',
    ),
    'name' => array(
        'name' => 'name',
        'vname' => 'LBL_LIST_NAME',
        'widget_class' => 'SubPanelDetailViewLink',
        'module' => 'Contacts',
        'width' => '43%',
    ),
    'stic_relationship_type_c' => array(
        'name' => 'stic_relationship_type_c',
        'module' => 'Contacts',
        'vname' => 'LBL_STIC_RELATIONSHIP_TYPE',
        'width' => '20%',
        'sortable' => true,
    ),
    'account_name' => array(
        'name' => 'account_name',
        'module' => 'Accounts',
        'target_record_key' => 'account_id',
        'target_module' => 'Accounts',
        'widget_class' => 'SubPanelDetailViewLink',
        'vname' => 'LBL_LIST_ACCOUNT_NAME',
        'width' => '20%',
        'sortable' => true,
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
    'email1' => array(
        'name' => 'email1',
        'vname' => 'LBL_LIST_EMAIL',
        'widget_class' => 'SubPanelEmailLink',
        'width' => '10%',
        'sortable' => true,
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
        'module' => 'Contacts',
        'width' => '5%',
        'default' => true,
    ),
    'remove_button' => array(
        'vname' => 'LBL_REMOVE',
        'widget_class' => 'SubPanelRemoveButton',
        'module' => 'Contacts',
        'width' => '5%',
        'default' => true,
    ),
);
