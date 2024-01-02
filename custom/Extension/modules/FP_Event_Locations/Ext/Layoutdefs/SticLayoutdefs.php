<?php
$layout_defs["FP_Event_Locations"]["subpanel_setup"]['stic_events_fp_event_locations'] = array(
    'order' => 100,
    'module' => 'stic_Events',
    'subpanel_name' => 'default',
    'sort_order' => 'asc',
    'sort_by' => 'id',
    'title_key' => 'LBL_STIC_EVENTS_FP_EVENT_LOCATIONS_FROM_STIC_EVENTS_TITLE',
    'get_subpanel_data' => 'stic_events_fp_event_locations',
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
