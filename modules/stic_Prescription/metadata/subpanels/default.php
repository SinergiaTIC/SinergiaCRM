<?php
$module_name='stic_Prescription';
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
      'popup_module' => 'stic_Prescription',
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
    'start_date' => 
    array (
      'type' => 'date',
      'vname' => 'LBL_START_DATE',
      'width' => '10%',
      'default' => true,
    ),
    'end_date' => 
    array (
      'type' => 'date',
      'vname' => 'LBL_END_DATE',
      'width' => '10%',
      'default' => true,
    ),
    'active' => 
    array (
      'type' => 'bool',
      'default' => true,
      'vname' => 'LBL_ACTIVE',
      'width' => '10%',
    ),
    'frequency' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'vname' => 'LBL_FREQUENCY',
      'width' => '10%',
      'default' => true,
    ),
    'schedule' => 
    array (
      'type' => 'multienum',
      'studio' => 'visible',
      'vname' => 'LBL_SCHEDULE',
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
      'module' => 'stic_Prescription',
      'width' => '4%',
      'default' => true,
    ),
    // 'remove_button' => 
    // array (
    //   'vname' => 'LBL_REMOVE',
    //   'widget_class' => 'SubPanelRemoveButton',
    //   'module' => 'stic_Prescription',
    //   'width' => '5%',
    //   'default' => true,
    // ),
  ),
);