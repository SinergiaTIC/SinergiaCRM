<?php
$layout_defs["stic_Registrations"]["subpanel_setup"]['stic_attendances_stic_registrations'] = array(
    'order' => 100,
    'module' => 'stic_Attendances',
    'subpanel_name' => 'default',
    'sort_order' => 'desc',
    'sort_by' => 'start_date',
    'title_key' => 'LBL_STIC_ATTENDANCES_STIC_REGISTRATIONS_FROM_STIC_ATTENDANCES_TITLE',
    'get_subpanel_data' => 'stic_attendances_stic_registrations',
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

$layout_defs['stic_Registrations']['subpanel_setup']['securitygroups'] = array(
    'top_buttons' => array(
        array(
            'widget_class' => 'SubPanelTopSelectButton',
            'popup_module' => 'SecurityGroups',
            'mode' => 'MultiSelect',
        ),
    ),
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

$layout_defs["stic_Registrations"]["subpanel_setup"]['activities'] = array(
    'order' => 10,
    'sort_order' => 'desc',
    'sort_by' => 'date_due',
    'title_key' => 'LBL_ACTIVITIES_SUBPANEL_TITLE',
    'type' => 'collection',
    'subpanel_name' => 'activities',
    'module' => 'Activities',
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopCreateTaskButton',
        ),
        1 => array(
            'widget_class' => 'SubPanelTopScheduleMeetingButton',
        ),
        2 => array(
            'widget_class' => 'SubPanelTopScheduleCallButton',
        ),
        3 => array(
            'widget_class' => 'SubPanelTopComposeEmailButton',
        ),
    ),
    'collection_list' => array(
        'meetings' => array(
            'module' => 'Meetings',
            'subpanel_name' => 'ForActivities',
            'get_subpanel_data' => 'stic_registrations_activities_meetings',
        ),
        'tasks' => array(
            'module' => 'Tasks',
            'subpanel_name' => 'ForActivities',
            'get_subpanel_data' => 'stic_registrations_activities_tasks',
        ),
        'calls' => array(
            'module' => 'Calls',
            'subpanel_name' => 'ForActivities',
            'get_subpanel_data' => 'stic_registrations_activities_calls',
        ),
    ),
    'get_subpanel_data' => 'activities',
);
$layout_defs["stic_Registrations"]["subpanel_setup"]['history'] = array(
    'order' => 20,
    'sort_order' => 'desc',
    'sort_by' => 'date_modified',
    'title_key' => 'LBL_HISTORY',
    'type' => 'collection',
    'subpanel_name' => 'history',
    'module' => 'History',
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopCreateNoteButton',
        ),
        1 => array(
            'widget_class' => 'SubPanelTopArchiveEmailButton',
        ),
        2 => array(
            'widget_class' => 'SubPanelTopSummaryButton',
        ),
    ),
    'collection_list' => array(
        'meetings' => array(
            'module' => 'Meetings',
            'subpanel_name' => 'ForHistory',
            'get_subpanel_data' => 'stic_registrations_activities_meetings',
        ),
        'tasks' => array(
            'module' => 'Tasks',
            'subpanel_name' => 'ForHistory',
            'get_subpanel_data' => 'stic_registrations_activities_tasks',
        ),
        'calls' => array(
            'module' => 'Calls',
            'subpanel_name' => 'ForHistory',
            'get_subpanel_data' => 'stic_registrations_activities_calls',
        ),
        'notes' => array(
            'module' => 'Notes',
            'subpanel_name' => 'ForHistory',
            'get_subpanel_data' => 'stic_registrations_activities_notes',
        ),
        'emails' => array(
            'module' => 'Emails',
            'subpanel_name' => 'ForHistory',
            'get_subpanel_data' => 'stic_registrations_activities_emails',
        ),
    ),
    'get_subpanel_data' => 'history',
);

$layout_defs["stic_Registrations"]["subpanel_setup"]['stic_followups_stic_registrations'] = array(
    'order' => 100,
    'module' => 'stic_FollowUps',
    'subpanel_name' => 'default',
    'sort_order' => 'desc',
    'sort_by' => 'start_date',
    'title_key' => 'LBL_STIC_FOLLOWUPS_STIC_REGISTRATIONS_FROM_STIC_FOLLOWUPS_TITLE',
    'get_subpanel_data' => 'stic_followups_stic_registrations',
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopButtonQuickCreate',
        ),
        1 => array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect',
        ),
    ),
);

$layout_defs["stic_Registrations"]["subpanel_setup"]['stic_goals_stic_registrations'] = array(
    'order' => 100,
    'module' => 'stic_Goals',
    'subpanel_name' => 'default',
    'sort_order' => 'desc',
    'sort_by' => 'start_date',
    'title_key' => 'LBL_STIC_GOALS_STIC_REGISTRATIONS_FROM_STIC_GOALS_TITLE',
    'get_subpanel_data' => 'stic_goals_stic_registrations',
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopButtonQuickCreate',
        ),
        1 => array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect',
        ),
    ),
);