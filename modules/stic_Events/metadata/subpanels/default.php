<?php
$module_name = 'stic_Events';
$subpanel_layout = array(
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopCreateButton',
        ),
        1 => array(
            'widget_class' => 'SubPanelTopSelectButton',
            'popup_module' => 'stic_Events',
        ),
    ),
    'where' => '',
    'list_fields' => array(
        'name' => array(
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
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
        'start_date' => array(
            'type' => 'date',
            'vname' => 'LBL_START_DATE',
            'width' => '10%',
            'default' => true,
        ),
        'end_date' => array(
            'type' => 'date',
            'vname' => 'LBL_END_DATE',
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
        'max_attendees' => array(
            'type' => 'int',
            'vname' => 'LBL_MAX_ATTENDEES',
            'width' => '10%',
            'align' => 'right',
            'default' => true,
        ),

        'total_hours' => array(
            'type' => 'decimal',
            'vname' => 'LBL_TOTAL_HOURS',
            'align' => 'right',
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
            'module' => 'stic_Events',
            'width' => '4%',
            'default' => true,
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => 'stic_Events',
            'width' => '5%',
            'default' => true,
        ),
    ),
);