<?php
// created: 2021-02-18 12:46:06
$subpanel_layout['list_fields'] = array (
  'name' => 
  array (
    'vname' => 'LBL_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '45%',
    'default' => true,
  ),
  'stic_personal_environment_contacts_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_STIC_PERSONAL_ENVIRONMENT_CONTACTS_FROM_CONTACTS_TITLE',
    'id' => 'STIC_PERSONAL_ENVIRONMENT_CONTACTSCONTACTS_IDA',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Contacts',
    'target_record_key' => 'stic_personal_environment_contactscontacts_ida',
  ),
  'relationship_type' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'vname' => 'LBL_RELATIONSHIP_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  // 'stic_personal_environment_contacts_1_name' => 
  // array (
  //   'type' => 'relate',
  //   'link' => true,
  //   'vname' => 'LBL_STIC_PERSONAL_ENVIRONMENT_CONTACTS_1_FROM_CONTACTS_TITLE',
  //   'id' => 'STIC_PERSONAL_ENVIRONMENT_CONTACTS_1CONTACTS_IDA',
  //   'width' => '10%',
  //   'default' => true,
  //   'widget_class' => 'SubPanelDetailViewLink',
  //   'target_module' => 'Contacts',
  //   'target_record_key' => 'stic_personal_environment_contacts_1contacts_ida',
  // ),
  // 'stic_personal_environment_accounts_name' => 
  // array (
  //   'type' => 'relate',
  //   'link' => true,
  //   'vname' => 'LBL_STIC_PERSONAL_ENVIRONMENT_ACCOUNTS_FROM_ACCOUNTS_TITLE',
  //   'id' => 'STIC_PERSONAL_ENVIRONMENT_ACCOUNTSACCOUNTS_IDA',
  //   'width' => '10%',
  //   'default' => true,
  //   'widget_class' => 'SubPanelDetailViewLink',
  //   'target_module' => 'Accounts',
  //   'target_record_key' => 'stic_personal_environment_accountsaccounts_ida',
  // ),
  'reference_contact' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'vname' => 'LBL_REFERENCE_CONTACT',
    'width' => '10%',
    'default' => true,
  ),
  'start_date' => 
  array (
    'type' => 'date',
    'vname' => 'LBL_START_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'end_date' => 
  array (
    'type' => 'date',
    'vname' => 'LBL_END_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'assigned_user_name' => 
  array (
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
  'edit_button' => 
  array (
    'vname' => 'LBL_EDIT_BUTTON',
    'widget_class' => 'SubPanelEditButton',
    'module' => 'stic_Personal_Environment',
    'width' => '4%',
    'default' => true,
  ),
  'remove_button' => array(
    'vname' => 'LBL_REMOVE',
    'widget_class' => 'SubPanelRemoveButtonAccount',
    'width' => '4%',
    'default' => true,
),
);