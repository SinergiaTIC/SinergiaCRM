<?php

// Prospect Lists subpanel
$layout_defs["Users"]["subpanel_setup"]["prospect_lists"] = array(
    'get_subpanel_data' => 'prospect_lists',
    'module' => 'ProspectLists',
    'order' => 10,
    'sort_by' => 'name',
    'sort_order' => 'asc',
    'subpanel_name' => 'default',
    'title_key' => 'LBL_STIC_PROSPECT_LISTS_SUBPANEL_TITLE',
    'top_buttons' => array(
        array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect',
        ),
    ),
);