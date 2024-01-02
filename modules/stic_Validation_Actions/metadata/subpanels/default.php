<?php
$module_name = 'stic_Validation_Actions';
$subpanel_layout = array(
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopCreateButton',
        ),
        1 => array(
            'widget_class' => 'SubPanelTopSelectButton',
            'popup_module' => 'stic_Validation_Actions',
        ),
    ),
    'where' => '',
    'list_fields' => array(
        'name' => array(
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '45%',
            'default' => true,
        ),
        'last_execution' => array(
            'type' => 'datetimecombo',
            'vname' => 'LBL_LAST_EXECUTION',
            'width' => '10%',
            'default' => true,
        ),
        'report_always' => array(
            'type' => 'bool',
            'default' => true,
            'vname' => 'LBL_REPORT_ALWAYS',
            'width' => '10%',
        ),
        'priority' => array(
            'type' => 'int',
            'default' => true,
            'vname' => 'LBL_PRIORITY',
            'width' => '10%',
        ),
        'date_modified' => array(
            'vname' => 'LBL_DATE_MODIFIED',
            'width' => '45%',
            'default' => true,
        ),
        'edit_button' => array(
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditButton',
            'module' => 'stic_Validation_Actions',
            'width' => '4%',
            'default' => true,
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => 'stic_Validation_Actions',
            'width' => '5%',
            'default' => true,
        ),
    ),
);