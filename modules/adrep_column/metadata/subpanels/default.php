<?php
$module_name='adrep_column';
$subpanel_layout = array (
  'top_buttons' => 
  array (
    /*0 => 
    array (
      'widget_class' => 'SubPanelTopCreateButton',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'popup_module' => 'adrep_column',
    ),*/
  ),
  'where' => '',
  'list_fields' => 
  array (
    'name' => 
    array (
      'vname' => 'LBL_NAME',
      //'widget_class' => 'SubPanelDetailViewLink',
      'width' => '10%',
      'default' => true,
    ),
    'column_label' => 
    array (
      'type' => 'varchar',
      'vname' => 'LBL_PARAMETER_LABEL',
      'width' => '10%',
      'default' => true,
    ),
    'type' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'vname' => 'LBL_TYPE',
      'width' => '10%',
      'default' => true,
    ),
    'decimals' => 
    array (
      'type' => 'int',
      'default' => true,
      'vname' => 'LBL_DECIMALS',
      'width' => '10%',
    ),
    'priority' => 
    array (
      'type' => 'int',
      'default' => true,
      'vname' => 'LBL_PRIORITY',
      'width' => '10%',
    ),
    'dropdown_name' => 
    array (
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'vname' => 'LBL_DROPDOWN_NAME',
      'width' => '10%',
    ),
    'linked_module_name' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'vname' => 'LBL_LINKED_MODULE_NAME',
      'width' => '10%',
      'default' => true,
    ),
    'total_type' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'default' => true,
      'vname' => 'LBL_TOTAL_TYPE',
      'width' => '10%',
    ),
    'width' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'default' => true,
      'vname' => 'LBL_WIDTH',
      'width' => '10%',
    ),
    /*'active_flag' => 
    array (
      'type' => 'bool',
      'default' => true,
      'vname' => 'LBL_ACTIVE_FLAG',
      'width' => '10%',
    ),*/
  ),
);
