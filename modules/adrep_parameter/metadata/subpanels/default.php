<?php
$module_name='adrep_parameter';
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
      'popup_module' => 'adrep_parameter',
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
    'parameter_label' => 
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
    'priority' => 
    array (
      'type' => 'int',
      'default' => true,
      'vname' => 'LBL_PRIORITY',
      'width' => '10%',
    ),
    'default_value' => 
    array (
      'type' => 'varchar',
      'vname' => 'LBL_DEFAULT_VALUE',
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
    'dropdown_name' => 
    array (
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'vname' => 'LBL_DROPDOWN_NAME',
      'width' => '10%',
    ),
    'active_flag' => 
    array (
      'type' => 'bool',
      'default' => true,
      'vname' => 'LBL_ACTIVE_FLAG',
      'width' => '10%',
    ),
  ),
);
