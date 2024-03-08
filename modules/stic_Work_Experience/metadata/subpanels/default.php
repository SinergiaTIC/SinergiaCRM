<?php
$module_name='stic_Work_Experience';
$subpanel_layout = array (
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopCreateButton',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'popup_module' => 'stic_Work_Experience',
    ),
  ),
  'where' => '',
  'list_fields' => 
  array (
    'name' => 
    array (
      'vname' => 'LBL_NAME',
      'widget_class' => 'SubPanelDetailViewLink',
      'width' => '45%',
      'default' => true,
    ),
    'position' => 
    array (
      'type' => 'varchar',
      'vname' => 'LBL_POSITION',
      'width' => '10%',
      'default' => true,
    ),
    'position_type' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'vname' => 'LBL_POSITION_TYPE',
      'width' => '10%',
      'default' => true,
    ),
    'contract_type' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'vname' => 'LBL_CONTRACT_TYPE',
      'width' => '10%',
      'default' => true,
    ),
    'sector' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'vname' => 'LBL_SECTOR',
      'width' => '10%',
      'default' => true,
    ),
    'date_modified' => 
    array (
      'vname' => 'LBL_DATE_MODIFIED',
      'width' => '45%',
      'default' => true,
    ),
    'edit_button' => 
    array (
      'vname' => 'LBL_EDIT_BUTTON',
      'widget_class' => 'SubPanelEditButton',
      'module' => 'stic_Work_Experience',
      'width' => '4%',
      'default' => true,
    ),
    'quickedit_button' => 
    array (
      'vname' => 'LBL_QUICKEDIT_BUTTON',
      'widget_class' => 'SubPanelQuickEditButton',
      'module' => 'stic_Work_Experience',
      'width' => '4%',
      'default' => true,
    ),
    'remove_button' => 
    array (
      'vname' => 'LBL_REMOVE',
      'widget_class' => 'SubPanelRemoveButton',
      'module' => 'stic_Work_Experience',
      'width' => '5%',
      'default' => true,
    ),
  ),
);