<?php
$module_name = 'stic_Prescription';
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
$layout_defs["stic_Prescription"]["subpanel_setup"]['stic_medication_log_stic_prescription'] = array (
    'order' => 100,
    'module' => 'stic_Medication_Log',
    'subpanel_name' => 'default',
    'sort_order' => 'asc',
    'sort_by' => 'id',
    'title_key' => 'LBL_STIC_MEDICATION_LOG_STIC_PRESCRIPTION_FROM_STIC_MEDICATION_LOG_TITLE',
    'get_subpanel_data' => 'stic_medication_log_stic_prescription',
    'top_buttons' => 
    array (
      0 => 
      array (
        'widget_class' => 'SubPanelTopButtonQuickCreate',
      ),
      // 1 => 
      // array (
      //   'widget_class' => 'SubPanelTopSelectButton',
      //   'mode' => 'MultiSelect',
      // ),
    ),
);