<?php
$module_name = 'stic_AWF_Forms';
$layout_defs[$module_name]['subpanel_setup']['securitygroups'] = array(
    'top_buttons' => array(array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'SecurityGroups', 'mode' => 'MultiSelect')),
    'order' => 900,
    'sort_by' => 'name',
    'sort_order' => 'asc',
    'module' => 'SecurityGroups',
    'refresh_page' => 1,
    'subpanel_name' => 'default',
    'get_subpanel_data' => 'SecurityGroups',
    'add_subpanel_data' => 'securitygroup_id',
    'title_key' => 'LBL_SECURITYGROUPS_SUBPANEL_TITLE',
);
$layout_defs["stic_AWF_Forms"]["subpanel_setup"]['stic_69c1s_responses'] = array (
  'order' => 100,
  'module' => 'stic_AWF_Responses',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_stic_AWF_Forms_stic_AWF_Responses_FROM_stic_AWF_Responses_TITLE',
  'get_subpanel_data' => 'stic_69c1s_responses',
  'top_buttons' => array (),
);  
