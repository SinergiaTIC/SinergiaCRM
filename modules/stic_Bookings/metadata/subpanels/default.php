<?php
$module_name = 'stic_Bookings';
$subpanel_layout = array(
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopCreateButton',
        ),
        1 => array(
            'widget_class' => 'SubPanelTopSelectButton',
            'popup_module' => 'stic_Bookings',
        ),
    ),
    'where' => '',
    'list_fields' => array(
        'name' => array(
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '15%',
            'default' => true,
        ),
        'code' => array(
            'vname' => 'LBL_CODE',
            'width' => '10%',
            'default' => true,
        ),
        'status' => array(
            'vname' => 'LBL_STATUS',
            'width' => '10%',
            'default' => true,
        ),
        'start_date' => array(
            'type' => 'datetimecombo',
            'vname' => 'LBL_START_DATE',
            'width' => '10%',
            'default' => true,
        ),
        'end_date' => array(
            'type' => 'datetimecombo',
            'vname' => 'LBL_END_DATE',
            'width' => '10%',
            'default' => true,
        ),
        'stic_bookings_contacts_name' => array(
            'type' => 'relate',
            'link' => true,
            'vname' => 'LBL_STIC_BOOKINGS_CONTACTS_FROM_CONTACTS_TITLE',
            'id' => 'STIC_BOOKINGS_CONTACTSCONTACTS_IDA',
            'width' => '20%',
            'default' => true,
            'widget_class' => 'SubPanelDetailViewLink',
            'target_module' => 'Contacts',
            'target_record_key' => 'stic_bookings_contactscontacts_ida',
        ),
        'stic_bookings_accounts_name' => array(
            'type' => 'relate',
            'link' => true,
            'vname' => 'LBL_STIC_BOOKINGS_ACCOUNTS_FROM_ACCOUNTS_TITLE',
            'id' => 'STIC_BOOKINGS_ACCOUNTSACCOUNTS_IDA',
            'width' => '20%',
            'default' => true,
            'widget_class' => 'SubPanelDetailViewLink',
            'target_module' => 'Accounts',
            'target_record_key' => 'stic_bookings_accountsaccounts_ida',
        ),
        'assigned_user_name' => array(
            'link' => true,
            'type' => 'relate',
            'vname' => 'LBL_ASSIGNED_TO_NAME',
            'id' => 'ASSIGNED_USER_ID',
            'width' => '10%',
            'default' => true,
            'widget_class' => 'SubPanelDetailViewLink',
            'target_module' => 'Users',
            'target_record_key' => 'assigned_user_id',
        ),
        'edit_button' => array(
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditButton',
            'module' => 'stic_Bookings',
            'width' => '4%',
            'default' => true,
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => 'stic_Bookings',
            'width' => '5%',
            'default' => true,
        ),
    ),
);
