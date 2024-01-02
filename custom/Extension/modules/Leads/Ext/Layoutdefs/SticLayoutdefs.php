<?php

// Prospect Lists subpanel
$layout_defs["Leads"]["subpanel_setup"]["prospect_lists"] = array(
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

// Registrations subpanel
$layout_defs["Leads"]["subpanel_setup"]['stic_registrations_leads'] = array(
    'module' => 'stic_Registrations',
    'order' => 100,
    'subpanel_name' => 'default',
    'sort_order' => 'desc',
    'sort_by' => 'registration_date',
    'title_key' => 'LBL_STIC_REGISTRATIONS_LEADS_FROM_STIC_REGISTRATIONS_TITLE',
    'get_subpanel_data' => 'stic_registrations_leads',
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

// Documents subpanel
$layout_defs["Leads"]["subpanel_setup"]['leads_documents_1'] = array(
    'order' => 100,
    'module' => 'Documents',
    'subpanel_name' => 'SticDefault',
    'sort_order' => 'asc',
    'sort_by' => 'id',
    'title_key' => 'LBL_LEADS_DOCUMENTS_1_FROM_DOCUMENTS_TITLE',
    'get_subpanel_data' => 'leads_documents_1',
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

// Subpanels default sorting
$layout_defs['Leads']['subpanel_setup']['activities']['sort_order'] = 'asc';
$layout_defs['Leads']['subpanel_setup']['activities']['sort_by'] = 'date_due';
$layout_defs['Leads']['subpanel_setup']['history']['sort_order'] = 'desc';
$layout_defs['Leads']['subpanel_setup']['history']['sort_by'] = 'date_modified';
$layout_defs['Leads']['subpanel_setup']['campaigns']['sort_order'] = 'desc';
$layout_defs['Leads']['subpanel_setup']['campaigns']['sort_by'] = 'activity_date';
