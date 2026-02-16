<?php
$module_name = 'stic_AWF_Responses';
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
$layout_defs["stic_AWF_Responses"]["subpanel_setup"]['stic_1c31forms_links'] = array (
    'order' => 100,
    'module' => 'stic_AWF_Links',
    'subpanel_name' => 'default',
    'sort_order' => 'asc',
    'sort_by' => 'id',
    'title_key' => 'LBL_stic_AWF_Responses_stic_AWF_Links_FROM_stic_AWF_Links_TITLE',
    'get_subpanel_data' => 'stic_1c31forms_links',
    'top_buttons' => array (),
);
$layout_defs["stic_AWF_Responses"]["subpanel_setup"]['details_link'] = array(
    'order' => 10,
    'module' => 'stic_AWF_Response_Details',
    'subpanel_name' => 'default',
    'sort_order' => 'asc',
    'sort_by' => 'question_sort_order',
    'title_key' => 'LBL_ANSWERS_SUBPANEL_TITLE',
    'get_subpanel_data' => 'details_link',
    'top_buttons' => array(), 
);