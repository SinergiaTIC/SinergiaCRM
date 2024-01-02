<?php
 // created: 2020-02-14 18:03:12
$layout_defs["Schedulers"]["subpanel_setup"]['stic_validation_actions_schedulers'] = array (
  'order' => 100,
  'module' => 'stic_Validation_Actions',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_STIC_VALIDATION_ACTIONS_SCHEDULERS_FROM_STIC_VALIDATION_ACTIONS_TITLE',
  'get_subpanel_data' => 'stic_validation_actions_schedulers',
  'top_buttons' => 
  array (
    // 0 => 
    // array (
    //   'widget_class' => 'SubPanelTopButtonQuickCreate',
    // ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);
