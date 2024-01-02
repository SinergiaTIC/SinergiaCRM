<?php
$module_name = 'stic_Resources';
$subpanel_layout = array(
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopCreateButton',
        ),
        1 => array(
            'widget_class' => 'SubPanelTopSelectButton',
            'popup_module' => 'stic_Resources',
        ),
    ),
    'where' => '',
    'list_fields' => array(
        'name' => array(
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '15%',
            'default' => true,
        ),
        'status' => array(
            'vname' => 'LBL_STATUS',
            'width' => '10%',
            'default' => true,
        ),
        'code' => array(
            'type' => 'varchar',
            'vname' => 'LBL_CODE',
            'width' => '10%',
            'default' => true,
        ),
        'type' => array(
            'type' => 'enum',
            'studio' => 'visible',
            'vname' => 'LBL_TYPE',
            'width' => '10%',
            'default' => true,
        ),
        'color' => array(
            'type' => 'ColorPicker',
            'studio' => 'visible',
            'vname' => 'LBL_COLOR',
            'width' => '10%',
            'default' => true,
        ),
        'hourly_rate' => array(
            'type' => 'decimal',
            'vname' => 'LBL_HOURLY_RATE',
            'width' => '10%',
            'default' => true,
        ),
        'daily_rate' => array(
            'type' => 'decimal',
            'vname' => 'LBL_DAILY_RATE',
            'width' => '10%',
            'default' => true,
        ),
        'assigned_user_name' => array(
            'link' => true,
            'type' => 'relate',
            'vname' => 'LBL_ASSIGNED_TO_NAME',
            'id' => 'ASSIGNED_USER_ID',
            'width' => '10%',
            'default' => true,
            'widget_class' => 'SubPanelDetailViewLink',
            'target_module' => 'Users',
            'target_record_key' => 'assigned_user_id',
        ),
        'edit_button' => array(
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditButton',
            'module' => 'stic_Resources',
            'width' => '4%',
            'default' => true,
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => 'stic_Resources',
            'width' => '5%',
            'default' => true,
        ),
    ),
);
