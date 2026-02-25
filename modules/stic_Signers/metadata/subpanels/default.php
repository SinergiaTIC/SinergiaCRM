<?php
// created: 2025-10-03 08:11:30
$subpanel_layout['list_fields'] = array(
    'name' => array(
        'vname' => 'LBL_NAME',
        'widget_class' => 'SubPanelDetailViewLink',
        'width' => '45%',
        'default' => true,
    ),
    'status' => array(
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'vname' => 'LBL_STATUS',
        'width' => '10%',
    ),
    'signature_date' => array(
        'type' => 'datetimecombo',
        'vname' => 'LBL_SIGNATURE_DATE',
        'width' => '10%',
        'default' => true,
    ),
    'on_behalf_of_id' => array(
        'type' => 'varchar',
        'vname' => 'LBL_ON_BEHALF_OF_ID',
        'width' => '10%',
        'default' => true,
    ),
    'record_name' => array(
        'type' => 'varchar',
        'vname' => 'LBL_RECORD_NAME',
        'width' => '10%',
        'default' => true,
        'link' => true,
        'sortable' => false,
        'widget_class' => 'SubPanelDetailViewLink',
        'target_module_key' => 'record_type',
        'target_record_key' => 'record_id',
    ),
    'parent_name' => array(
        'type' => 'varchar',
        'vname' => 'LBL_FLEX_RELATE',
        'width' => '10%',
        'default' => true,
        'link' => true,
        'sortable' => false,
        'widget_class' => 'SubPanelDetailViewLink',
        'target_module_key' => 'parent_type',
        'target_record_key' => 'parent_id',
    ),

    // 'phone' =>
    // array (
    //   'type' => 'varchar',
    //   'vname' => 'LBL_PHONE',
    //   'width' => '10%',
    //   'default' => false,
    // ),
    // 'email_address' =>
    // array (
    //   'type' => 'varchar',
    //   'vname' => 'LBL_EMAIL_ADDRESS',
    //   'width' => '10%',
    //   'default' => false,
    // ),
    // 'edit_button' =>
    // array (
    //   'vname' => 'LBL_EDIT_BUTTON',
    //   'widget_class' => 'SubPanelEditButton',
    //   'module' => 'stic_Signers',
    //   'width' => '4%',
    //   'default' => true,
    // ),
    // 'quickedit_button' =>
    // array (
    //   'vname' => 'LBL_QUICKEDIT_BUTTON',
    //   'widget_class' => 'SubPanelQuickEditButton',
    //   'module' => 'stic_Signers',
    //   'width' => '4%',
    //   'default' => true,
    // ),
    // 'remove_button' =>
    // array (
    //   'vname' => 'LBL_REMOVE',
    //   'widget_class' => 'SubPanelRemoveButton',
    //   'module' => 'stic_Signers',
    //   'width' => '5%',
    //   'default' => true,
    // ),
);

