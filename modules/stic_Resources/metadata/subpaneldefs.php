<?php
$layout_defs["stic_Resources"]["subpanel_setup"]['stic_resources_stic_bookings'] = array(
    'order' => 100,
    'module' => 'stic_Bookings',
    'subpanel_name' => 'default',
    'sort_order' => 'desc',
    'sort_by' => 'start_date',
    'title_key' => 'LBL_STIC_RESOURCES_STIC_BOOKINGS_FROM_STIC_BOOKINGS_TITLE',
    'get_subpanel_data' => 'stic_resources_stic_bookings',
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopCreateButton',
        ),
        // 1 =>
        // array (
        //   'widget_class' => 'SubPanelTopSelectButton',
        //   'mode' => 'MultiSelect',
        // ),
    ),
);

$layout_defs['stic_Resources']['subpanel_setup']['securitygroups'] = array(
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
