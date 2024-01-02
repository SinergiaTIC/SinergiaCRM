<?php
// created: 2019-10-07 16:02:40
$layout_defs["stic_Payment_Commitments"]["subpanel_setup"]['stic_payments_stic_payment_commitments'] = array(
    'order' => 100,
    'module' => 'stic_Payments',
    'subpanel_name' => 'default',
    'sort_order' => 'desc',
    'sort_by' => 'payment_date',
    'title_key' => 'LBL_STIC_PAYMENTS_STIC_PAYMENT_COMMITMENTS_FROM_STIC_PAYMENTS_TITLE',
    'get_subpanel_data' => 'stic_payments_stic_payment_commitments',
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

$layout_defs["stic_Payment_Commitments"]["subpanel_setup"]['stic_payment_commitments_stic_registrations'] = array (
    'order' => 100,
    'module' => 'stic_Registrations',
    'subpanel_name' => 'ForPaymentCommitments',
    'sort_order' => 'asc',
    'sort_by' => 'id',
    'title_key' => 'LBL_STIC_PAYMENT_COMMITMENTS_STIC_REGISTRATIONS_FROM_STIC_REGISTRATIONS_TITLE',
    'get_subpanel_data' => 'stic_payment_commitments_stic_registrations',
    'top_buttons' => 
    array (
      0 => 
      array (
        'widget_class' => 'SubPanelTopButtonQuickCreate',
      ),
      1 => 
      array (
        'widget_class' => 'SubPanelTopSelectButton',
        'mode' => 'MultiSelect',
      ),
    ),
);

$layout_defs['stic_Payment_Commitments']['subpanel_setup']['securitygroups'] = array(
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
