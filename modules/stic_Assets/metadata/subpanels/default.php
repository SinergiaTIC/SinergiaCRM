<?php
// created: 2026-02-02 11:16:58
$subpanel_layout['list_fields'] = array(
    'name' => array(
        'vname' => 'LBL_NAME',
        'widget_class' => 'SubPanelDetailViewLink',
        'width' => '45%',
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
    'start_date' => array(
        'type' => 'date',
        'vname' => 'LBL_START_DATE',
        'width' => '10%',
        'default' => true,
    ),
    'address_street' => array(
        'type' => 'varchar',
        'vname' => 'LBL_ADDRESS_STREET',
        'width' => '10%',
        'default' => true,
    ),
    'address_city' => array(
        'type' => 'varchar',
        'vname' => 'LBL_ADDRESS_CITY',
        'width' => '10%',
        'default' => true,
    ),
    'date_modified' => array(
        'vname' => 'LBL_DATE_MODIFIED',
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
        'module' => 'stic_Skills',
        'width' => '4%',
        'default' => true,
    ),
    'quickedit_button' => array(
        'width' => '4%',
        'vname' => 'LBL_QUICKEDIT_BUTTON',
        'default' => true,
        'widget_class' => 'SubPanelQuickEditButton',
    ),
    'remove_button' => array(
        'vname' => 'LBL_REMOVE',
        'widget_class' => 'SubPanelRemoveButton',
        'module' => 'stic_Skills',
        'width' => '4%',
        'default' => true,
    ),
);
