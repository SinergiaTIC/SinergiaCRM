<?php
$layout_defs["stic_Families"]["subpanel_setup"]['stic_families_stic_personal_environment'] = array(
    'order' => 100,
    'module' => 'stic_Personal_Environment',
    'subpanel_name' => 'default',
    'sort_order' => 'asc',
    'sort_by' => 'id',
    'title_key' => 'LBL_STIC_FAMILIES_STIC_PERSONAL_ENVIRONMENT_FROM_STIC_PERSONAL_ENVIRONMENT_TITLE',
    'get_subpanel_data' => 'stic_families_stic_personal_environment',
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

$layout_defs["stic_Families"]["subpanel_setup"]['stic_families_stic_followups'] = array(
    'order' => 100,
    'module' => 'stic_FollowUps',
    'subpanel_name' => 'default',
    'sort_order' => 'asc',
    'sort_by' => 'id',
    'title_key' => 'LBL_STIC_FAMILIES_STIC_FOLLOWUPS_FROM_STIC_FOLLOWUPS_TITLE',
    'get_subpanel_data' => 'stic_families_stic_followups',
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

$layout_defs["stic_Families"]["subpanel_setup"]['stic_families_documents'] = array(
    'order' => 100,
    'module' => 'Documents',
    'subpanel_name' => 'default',
    'sort_order' => 'asc',
    'sort_by' => 'id',
    'title_key' => 'LBL_STIC_FAMILIES_DOCUMENTS_FROM_DOCUMENTS_TITLE',
    'get_subpanel_data' => 'stic_families_documents',
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

$layout_defs["stic_Families"]["subpanel_setup"]['stic_families_stic_assessments'] = array(
    'order' => 100,
    'module' => 'stic_Assessments',
    'subpanel_name' => 'default',
    'sort_order' => 'asc',
    'sort_by' => 'id',
    'title_key' => 'LBL_STIC_FAMILIES_STIC_ASSESSMENTS_FROM_STIC_ASSESSMENTS_TITLE',
    'get_subpanel_data' => 'stic_families_stic_assessments',
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

$layout_defs["stic_Families"]["subpanel_setup"]['stic_families_stic_goals'] = array(
    'order' => 100,
    'module' => 'stic_Goals',
    'subpanel_name' => 'default',
    'sort_order' => 'asc',
    'sort_by' => 'id',
    'title_key' => 'LBL_STIC_FAMILIES_STIC_GOALS_FROM_STIC_GOALS_TITLE',
    'get_subpanel_data' => 'stic_families_stic_goals',
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

$layout_defs["stic_Families"]['subpanel_setup']['securitygroups'] = array(
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
