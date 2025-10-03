<?php
// created: 2025-09-04 18:23:01
$subpanel_layout['list_fields'] = array (
  'name' => 
  array (
    'vname' => 'LBL_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '45%',
    'default' => true,
  ),
  'date_entered' => 
  array (
    'type' => 'datetime',
    'vname' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
  ),
  'date_modified' => 
  array (
    'vname' => 'LBL_DATE_MODIFIED',
    'width' => '45%',
    'default' => true,
  ),
  'action' => 
  array (
    'type' => 'varchar',
    'vname' => 'LBL_ACTION',
    'width' => '10%',
    'default' => true,
  ),
  'description' => 
  array (
    'type' => 'text',
    'vname' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
  ),
  'date' => 
  array (
    'type' => 'datetimecombo',
    'vname' => 'LBL_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'ip_address' => 
  array (
    'type' => 'varchar',
    'vname' => 'LBL_IP_ADDRESS',
    'width' => '10%',
    'default' => true,
  ),
  'user_agent' => 
  array (
    'type' => 'varchar',
    'vname' => 'LBL_USER_AGENT',
    'width' => '10%',
    'default' => true,
  ),
  'edit_button' => 
  array (
    'vname' => 'LBL_EDIT_BUTTON',
    'widget_class' => 'SubPanelEditButton',
    'module' => 'stic_Signature_Log',
    'width' => '4%',
    'default' => true,
  ),
  'quickedit_button' => 
  array (
    'vname' => 'LBL_QUICKEDIT_BUTTON',
    'widget_class' => 'SubPanelQuickEditButton',
    'module' => 'stic_Signature_Log',
    'width' => '4%',
    'default' => true,
  ),
  'remove_button' => 
  array (
    'vname' => 'LBL_REMOVE',
    'widget_class' => 'SubPanelRemoveButton',
    'module' => 'stic_Signature_Log',
    'width' => '5%',
    'default' => true,
  ),
);