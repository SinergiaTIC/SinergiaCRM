<?php
// created: 2025-10-03 08:11:30
$subpanel_layout['list_fields'] = array (
  'name' => 
  array (
    'vname' => 'LBL_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '45%',
    'default' => true,
  ),
  'on_behalf_of_id' => 
  array (
    'type' => 'varchar',
    'vname' => 'LBL_ON_BEHALF_OF_ID',    
    'width' => '10%',
    'default' => true,
  ),
  // 'date_modified' => 
  // array (
  //   'vname' => 'LBL_DATE_MODIFIED',
  //   'width' => '45%',
  //   'default' => false,
  // ),
  'parent_name' => 
  array (
    'type' => 'parent',
    'studio' => 'visible',
    'vname' => 'LBL_FLEX_RELATE',
    'link' => true,
    'sortable' => false,
    'ACLTag' => 'PARENT',
    'dynamic_module' => 'PARENT_TYPE',
    'id' => 'PARENT_ID',
    'related_fields' => 
    array (
      0 => 'parent_id',
      1 => 'parent_type',
    ),
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => NULL,
    'target_record_key' => 'parent_id',
  ),
  'status' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_STATUS',
    'width' => '10%',
  ),
  'signature_date' => 
  array (
    'type' => 'datetimecombo',
    'vname' => 'LBL_SIGNATURE_DATE',
    'width' => '10%',
    'default' => true,
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