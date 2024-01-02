<?php
$module_name = 'stic_Payments';
$subpanel_layout = array(
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopCreateButton',
        ),
        1 => array(
            'widget_class' => 'SubPanelTopSelectButton',
            'popup_module' => 'stic_Payments',
        ),
    ),
    'where' => '',
    'list_fields' => array(
        'name' => array(
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '25%',
            'default' => true,
        ),
        'amount' => array(
            'type' => 'decimal',
            'align' => 'right',
            'vname' => 'LBL_AMOUNT',
            'width' => '10%',
            'default' => true,
        ),
        'status' => array(
            'type' => 'enum',
            'studio' => 'visible',
            'vname' => 'LBL_STATUS',
            'width' => '10%',
            'default' => true,
        ),
        'payment_date' => array(
            'type' => 'date',
            'vname' => 'LBL_PAYMENT_DATE',
            'width' => '10%',
            'default' => true,
        ),
        'payment_type' => array(
            'type' => 'enum',
            'studio' => 'visible',
            'vname' => 'LBL_PAYMENT_TYPE',
            'width' => '10%',
            'default' => true,
        ),
        'payment_method' => array(
            'type' => 'enum',
            'studio' => 'visible',
            'vname' => 'LBL_PAYMENT_METHOD',
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
            'module' => 'stic_Payments',
            'width' => '4%',
            'default' => true,
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => 'stic_Payments',
            'width' => '5%',
            'default' => true,
        ),
    ),
);
