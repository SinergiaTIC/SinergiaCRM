<?php

$layout_defs["stic_Events"]["subpanel_setup"]['stic_registrations_stic_events'] = array(
    'order' => 100,
    'module' => 'stic_Registrations',
    'subpanel_name' => 'default',
    'sort_order' => 'desc',
    'sort_by' => 'registration_date',
    'title_key' => 'LBL_STIC_REGISTRATIONS_STIC_EVENTS_FROM_STIC_REGISTRATIONS_TITLE',
    'get_subpanel_data' => 'stic_registrations_stic_events',
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopButtonQuickCreate',
        ),
        // 1 =>
        // array (
        //   'widget_class' => 'SubPanelTopSelectButton',
        //   'mode' => 'MultiSelect',
        // ),
    ),
);
$layout_defs["stic_Events"]["subpanel_setup"]['stic_sessions_stic_events'] = array(
    'order' => 100,
    'module' => 'stic_Sessions',
    'subpanel_name' => 'default',
    'sort_order' => 'desc',
    'sort_by' => 'start_date',
    'title_key' => 'LBL_STIC_SESSIONS_STIC_EVENTS_FROM_STIC_SESSIONS_TITLE',
    'get_subpanel_data' => 'stic_sessions_stic_events',
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopButtonQuickCreate',
        ),
        // 1 =>
        // array (
        //   'widget_class' => 'SubPanelTopSelectButton',
        //   'mode' => 'MultiSelect',
        // ),
    ),
);
$layout_defs['stic_Events']['subpanel_setup']['securitygroups'] = array(
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
