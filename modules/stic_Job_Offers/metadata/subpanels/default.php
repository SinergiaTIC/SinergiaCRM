<?php

$module_name = 'stic_Job_Offers';
$subpanel_layout = array(
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateButton'),
        array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => $module_name),
    ),
    'where' => '',
    'list_fields' => array(
        'name' => array(
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '20%',
            'default' => true,
        ),
        'offer_code' => array(
            'vname' => 'LBL_OFFER_CODE',
            'width' => '10%',
            'default' => true,
        ),
        'status' => array(
            'studio' => 'visible',
            'vname' => 'LBL_STATUS',
            'width' => '10%',
            'default' => true,
        ),
        'type' => array(
            'studio' => 'visible',
            'vname' => 'LBL_TYPE',
            'width' => '10%',
            'default' => true,
        ),
        'stic_job_offers_accounts_name' => array(
            'link' => true,
            'vname' => 'LBL_STIC_JOB_OFFERS_ACCOUNTS_FROM_ACCOUNTS_TITLE',
            'id' => 'STIC_JOB_OFFERS_ACCOUNTSACCOUNTS_IDA',
            'width' => '10%',
            'default' => true,
            'widget_class' => 'SubPanelDetailViewLink',
            'target_module' => 'Accounts',
            'target_record_key' => 'stic_job_offers_accountsaccounts_ida',
        ),
        'professional_profile' => array(
            'studio' => 'visible',
            'vname' => 'LBL_PROFESSIONAL_PROFILE',
            'width' => '10%',
            'default' => true,
        ),
        'process_end_date' => array(
            'type' => 'date',
            'vname' => 'LBL_PROCESS_END_DATE',
            'width' => '10%',
            'default' => true,
        ),
        'assigned_user_name' => array(
            'width' => '9%',
            'vname' => 'LBL_ASSIGNED_TO_NAME',
            'module' => 'Employees',
            'id' => 'ASSIGNED_USER_ID',
            'default' => true,
            'widget_class' => 'SubPanelDetailViewLink',
            'target_module' => 'Users',
            'target_record_key' => 'assigned_user_id',
        ),
        'edit_button' => array(
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditButton',
            'module' => 'stic_Job_Offers',
            'width' => '4%',
            'default' => true,
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => 'stic_Job_Offers',
            'width' => '5%',
            'default' => true,
        ),
    ),
);
