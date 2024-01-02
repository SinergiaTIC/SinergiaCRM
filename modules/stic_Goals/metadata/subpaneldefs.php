<?php

$layout_defs["stic_Goals"]["subpanel_setup"]['stic_goals_stic_goals_destination'] = array(
    'order' => 100,
    'module' => 'stic_Goals',
    'subpanel_name' => 'DestinationGoalsSubpanelDef',
    'sort_order' => 'desc',
    'sort_by' => 'start_date',
    'title_key' => 'LBL_STIC_GOALS_STIC_GOALS_FROM_STIC_GOALS_L_TITLE',
    'get_subpanel_data' => 'function:getSticGoalsSticGoalsDestinationSide',
    // 'get_subpanel_data' => 'stic_goals_stic_goals',
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

$layout_defs["stic_Goals"]["subpanel_setup"]['stic_goals_stic_goals_origin'] = array(
    'order' => 100,
    'module' => 'stic_Goals',
    'subpanel_name' => 'OriginGoalsSubpanelDef',
    'sort_order' => 'desc',
    'sort_by' => 'start_date',
    'title_key' => 'LBL_STIC_GOALS_STIC_GOALS_FROM_STIC_GOALS_R_TITLE',
    'get_subpanel_data' => 'function:getSticGoalsSticGoalsOriginSide',
    // 'get_subpanel_data' => 'stic_goals_stic_goals',
    
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

$layout_defs["stic_Goals"]["subpanel_setup"]['stic_goals_stic_followups'] = array(
    'order' => 100,
    'module' => 'stic_FollowUps',
    'subpanel_name' => 'default',
    'sort_order' => 'desc',
    'sort_by' => 'start_date',
    'title_key' => 'LBL_STIC_GOALS_STIC_FOLLOWUPS_FROM_STIC_FOLLOWUPS_TITLE',
    'get_subpanel_data' => 'stic_goals_stic_followups',
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

$layout_defs['stic_Goals']['subpanel_setup']['securitygroups'] = array(
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
