<?php
$module_name='adrep_role';
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
      'popup_module' => 'adrep_role',
    ),*/
  ),
  'where' => '',
  'list_fields' => 
  array (
    'name' => 
    array (
      'vname' => 'LBL_NAME',
      //'widget_class' => 'SubPanelDetailViewLink',
      'width' => '45%',
      'default' => true,
    ),
    'allowed_flag' => 
    array (
      'type' => 'bool',
      'default' => true,
      'vname' => 'LBL_ALLOWED_FLAG',
      'width' => '55%',
    ),
  ),
);
