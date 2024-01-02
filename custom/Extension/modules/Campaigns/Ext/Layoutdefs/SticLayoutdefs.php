<?php

$layout_defs["Campaigns"]["subpanel_setup"]['stic_payment_commitments_campaigns'] = array(
    'order' => 100,
    'module' => 'stic_Payment_Commitments',
    'subpanel_name' => 'default',
    'sort_order' => 'asc',
    'sort_by' => 'id',
    'title_key' => 'LBL_STIC_PAYMENT_COMMITMENTS_CAMPAIGNS_FROM_STIC_PAYMENT_COMMITMENTS_TITLE',
    'get_subpanel_data' => 'stic_payment_commitments_campaigns',
    'top_buttons' => array(
        // 0 => array(
        //     'widget_class' => 'SubPanelTopButtonQuickCreate',
        // ),
        1 => array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect',
        ),
    ),
);

$layout_defs['Campaigns']['subpanel_setup']['accounts']['override_subpanel_name'] = 'SticDefault';

$layout_defs['Campaigns']['subpanel_setup']['leads']['override_subpanel_name'] = 'SticDefault';

// Override button in order to avoid campaign wizard launching on EmailMarketing creation
$layout_defs['Campaigns']['subpanel_setup']['emailmarketing']['top_buttons'] = array(
    array('widget_class' => 'SubPanelTopCreateButton'),
);

// Subpanels default sorting
$layout_defs['Campaigns']['subpanel_setup']['tracked_urls']['sort_order'] = 'asc';
$layout_defs['Campaigns']['subpanel_setup']['tracked_urls']['sort_by'] = 'tracker_name';
$layout_defs['Campaigns']['subpanel_setup']['emailmarketing']['sort_order'] = 'desc';
$layout_defs['Campaigns']['subpanel_setup']['emailmarketing']['sort_by'] = 'date_start';

// Hide SinergiaCRM history subpanel because there is a bug displaying it
// STIC#624
unset($layout_defs["Campaigns"]["subpanel_setup"]['history']);