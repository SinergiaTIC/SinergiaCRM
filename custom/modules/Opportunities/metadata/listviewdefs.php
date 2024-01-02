<?php
// created: 2020-07-04 10:28:56
$listViewDefs['Opportunities'] = array (
  'NAME' => 
  array (
    'width' => '30%',
    'label' => 'LBL_LIST_OPPORTUNITY_NAME',
    'link' => true,
    'default' => true,
  ),
  'STIC_STATUS_C' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_STIC_STATUS',
    'width' => '10%',
  ),
  'ACCOUNT_NAME' => 
  array (
    'width' => '20%',
    'label' => 'LBL_LIST_ACCOUNT_NAME',
    'id' => 'ACCOUNT_ID',
    'module' => 'Accounts',
    'link' => true,
    'default' => true,
    'sortable' => true,
    'ACLTag' => 'ACCOUNT',
    'contextMenu' => 
    array (
      'objectType' => 'sugarAccount',
      'metaData' => 
      array (
        'return_module' => 'Contacts',
        'return_action' => 'ListView',
        'module' => 'Accounts',
        'parent_id' => '{$ACCOUNT_ID}',
        'parent_name' => '{$ACCOUNT_NAME}',
        'account_id' => '{$ACCOUNT_ID}',
        'account_name' => '{$ACCOUNT_NAME}',
      ),
    ),
    'related_fields' => 
    array (
      0 => 'account_id',
    ),
  ),
  'STIC_TYPE_C' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_STIC_TYPE',
    'width' => '10%',
  ),
  'AMOUNT' => 
  array (
    'type' => 'currency',
    'default' => true,
    'label' => 'LBL_AMOUNT',
    'currency_format' => true,
    'width' => '10%',
  ),
  'STIC_TARGET_C' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_STIC_TARGET',
    'width' => '10%',
  ),
  'STIC_PRESENTATION_DATE_C' => 
  array (
    'type' => 'date',
    'default' => true,
    'label' => 'LBL_STIC_PRESENTATION_DATE',
    'width' => '10%',
  ),
  'STIC_RESOLUTION_DATE_C' => 
  array (
    'type' => 'date',
    'default' => true,
    'label' => 'LBL_STIC_RESOLUTION_DATE',
    'width' => '10%',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '5%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'STIC_AMOUNT_AWARDED_C' => 
  array (
    'type' => 'currency',
    'default' => false,
    'label' => 'LBL_STIC_AMOUNT_AWARDED',
    'currency_format' => true,
    'width' => '10%',
  ),
  'STIC_AMOUNT_RECEIVED_C' => 
  array (
    'type' => 'currency',
    'default' => false,
    'label' => 'LBL_STIC_AMOUNT_RECEIVED',
    'currency_format' => true,
    'width' => '10%',
  ),
  'STIC_JUSTIFICATION_DATE_C' => 
  array (
    'type' => 'date',
    'default' => false,
    'label' => 'LBL_STIC_JUSTIFICATION_DATE',
    'width' => '10%',
  ),
  'STIC_ADVANCE_DATE_C' => 
  array (
    'type' => 'date',
    'default' => false,
    'label' => 'LBL_STIC_ADVANCE_DATE',
    'width' => '10%',
  ),
  'STIC_PAYMENT_DATE_C' => 
  array (
    'type' => 'date',
    'default' => false,
    'label' => 'LBL_STIC_PAYMENT_DATE',
    'width' => '10%',
  ),
  'STIC_DOCUMENTATION_TO_DELIVER_C' => 
  array (
    'type' => 'multienum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_STIC_DOCUMENTATION_TO_DELIVER',
    'width' => '10%',
  ),
  'PROJECT_OPPORTUNITIES_1_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_PROJECT_OPPORTUNITIES_1_FROM_PROJECT_TITLE',
    'id' => 'PROJECT_OPPORTUNITIES_1PROJECT_IDA',
    'width' => '10%',
    'default' => false,
  ),
  'CREATED_BY_NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_CREATED',
    'default' => false,
  ),
  'DATE_ENTERED' => 
  array (
    'width' => '10%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => false,
  ),
  'DATE_MODIFIED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => false,
  ),
  'MODIFIED_BY_NAME' => 
  array (
    'width' => '5%',
    'label' => 'LBL_MODIFIED',
    'default' => false,
  ),
);
