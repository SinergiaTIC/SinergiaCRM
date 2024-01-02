<?php

$layout_defs["stic_Remittances"]["subpanel_setup"]['stic_payments_stic_remittances'] = array(
    'order' => 100,
    'module' => 'stic_Payments',
    'subpanel_name' => 'default',
    'sort_order' => 'desc',
    'sort_by' => 'payment_date',
    'title_key' => 'LBL_STIC_PAYMENTS_STIC_REMITTANCES_FROM_STIC_PAYMENTS_TITLE',
    'get_subpanel_data' => 'stic_payments_stic_remittances',
    'top_buttons' => array(
        // 0 =>
        // array (
        //   'widget_class' => 'SubPanelTopButtonQuickCreate',
        // ),
        0 => array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect',
        ),
    ),
);

$layout_defs['stic_Remittances']['subpanel_setup']['securitygroups'] = array(
    'top_buttons' => array(array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'SecurityGroups', 'mode' => 'MultiSelect'),),
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
