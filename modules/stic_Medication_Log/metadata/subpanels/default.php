<?php
$module_name='stic_Medication_Log';
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
      'popup_module' => 'stic_Medication_Log',
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
    'intake_date' => 
    array (
      'type' => 'date',
      'vname' => 'LBL_INTAKE_DATE',
      'width' => '10%',
      'default' => true,
    ),
    'administered' => 
    array (
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'vname' => 'LBL_ADMINISTERED',
      'width' => '10%',
    ),
    'time' => 
    array (
      'type' => 'varchar',
      'vname' => 'LBL_TIME',
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
      'module' => 'stic_Medication_Log',
      'width' => '4%',
      'default' => true,
    ),
    // 'remove_button' => 
    // array (
    //   'vname' => 'LBL_REMOVE',
    //   'widget_class' => 'SubPanelRemoveButton',
    //   'module' => 'stic_Medication_Log',
    //   'width' => '5%',
    //   'default' => true,
    // ),
  ),
);