<?php
$module_name = 'stic_Advanced_Web_Forms_Sections';
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
$layout_defs["stic_Advanced_Web_Forms_Sections"]["subpanel_setup"]['stic_17bfections_ida'] = array (
    'order' => 100,
    'module' => 'stic_Advanced_Web_Forms_Sections',
    'subpanel_name' => 'default',
    'sort_order' => 'asc',
    'sort_by' => 'id',
    'title_key' => 'LBL_STIC_ADVANCED_WEB_FORMS_SECTIONS_STIC_ADVANCED_WEB_FORMS_SECTIONS_FROM_STIC_ADVANCED_WEB_FORMS_SECTIONS_R_TITLE',
    'get_subpanel_data' => 'stic_17bfections_ida',
    'top_buttons' => 
    array (
      0 => 
      array (
        'widget_class' => 'SubPanelTopButtonQuickCreate',
      ),
      1 => 
      array (
        'widget_class' => 'SubPanelTopSelectButton',
        'mode' => 'MultiSelect',
      ),
    ),
  );
  