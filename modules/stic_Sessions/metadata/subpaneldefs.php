<?php
$layout_defs["stic_Sessions"]["subpanel_setup"]['stic_attendances_stic_sessions'] = array(
    'order' => 100,
    'module' => 'stic_Attendances',
    'subpanel_name' => 'default',
    'sort_order' => 'desc',
    'sort_by' => 'start_date',
    'title_key' => 'LBL_STIC_ATTENDANCES_STIC_SESSIONS_FROM_STIC_ATTENDANCES_TITLE',
    'get_subpanel_data' => 'stic_attendances_stic_sessions',
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopButtonQuickCreate',
        ),
        // 1 => array(
        //     'widget_class' => 'SubPanelTopSelectButton',
        //     'mode' => 'MultiSelect',
        // ),
    ),
);

$layout_defs["stic_Sessions"]["subpanel_setup"]['stic_sessions_documents'] = array(
    'order' => 100,
    'module' => 'Documents',
    'subpanel_name' => 'SticDefault',
    'sort_order' => 'asc',
    'sort_by' => 'id',
    'title_key' => 'LBL_STIC_SESSIONS_DOCUMENTS_FROM_DOCUMENTS_TITLE',
    'get_subpanel_data' => 'stic_sessions_documents',
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

$layout_defs['stic_Sessions']['subpanel_setup']['securitygroups'] = array(
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
