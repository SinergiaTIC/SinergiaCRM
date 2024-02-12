<?php
$module_name='adrep_menu_link';
$subpanel_layout = array (
  'top_buttons' => 
  array (
  ),
  'where' => '',
  'list_fields' => 
  array (
    'menu_module' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'vname' => 'LBL_MENU_MODULE',
      'width' => '10%',
      'default' => true,
    ),
    'menu_view' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'vname' => 'LBL_MENU_VIEW',
      'width' => '10%',
      'default' => true,
    ),
    'name' => 
    array (
      'vname' => 'LBL_NAME',
      'width' => '10%',
      'default' => true,
      'widget_class' => 'SubPanelDetailViewLink',
    ),
    'active_flag' => 
    array (
      'type' => 'bool',
      'default' => true,
      'vname' => 'LBL_ACTIVE_FLAG',
      'width' => '10%',
    ),
    'run_flag' => 
    array (
      'type' => 'bool',
      'default' => true,
      'vname' => 'LBL_RUN_FLAG',
      'width' => '10%',
    ),
  ),
);