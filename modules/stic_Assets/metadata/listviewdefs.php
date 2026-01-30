<?php
$module_name = 'stic_Assets';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'STIC_ASSETS_CONTACTS_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_ASSETS_CONTACTS_FROM_CONTACTS_TITLE',
    'id' => 'STIC_ASSETS_CONTACTSCONTACTS_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'CODE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_CODE',
    'width' => '10%',
    'default' => true,
  ),
  'TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'START_DATE' => 
  array (
    'type' => 'date',
    'label' => 'LBL_START_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'UTILITIES_NOTES' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_UTILITIES_NOTES',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'SECURITY' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_SECURITY',
    'id' => 'ACCOUNT_ID6_C',
    'link' => true,
    'width' => '10%',
    'default' => false,
  ),
  'PHONE' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_PHONE',
    'id' => 'ACCOUNT_ID5_C',
    'link' => true,
    'width' => '10%',
    'default' => false,
  ),
  'WATER' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_WATER',
    'id' => 'ACCOUNT_ID4_C',
    'link' => true,
    'width' => '10%',
    'default' => false,
  ),
  'GAS' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_GAS',
    'id' => 'ACCOUNT_ID3_C',
    'link' => true,
    'width' => '10%',
    'default' => false,
  ),
  'ELECTRICITY' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_ELECTRICITY',
    'id' => 'ACCOUNT_ID2_C',
    'link' => true,
    'width' => '10%',
    'default' => false,
  ),
  'INSURANCE_NOTES' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_INSURANCE_NOTES',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'INSURED_CONTENTS' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_INSURED_CONTENTS',
    'width' => '10%',
    'default' => false,
  ),
  'INSURED_BUILDING' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_INSURED_BUILDING',
    'width' => '10%',
    'default' => false,
  ),
  'POLICY_NUMBER' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_POLICY_NUMBER',
    'width' => '10%',
    'default' => false,
  ),
  'INSURANCE' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_INSURANCE',
    'id' => 'ACCOUNT_ID1_C',
    'link' => true,
    'width' => '10%',
    'default' => false,
  ),
  'OWNERSHIP_NOTES' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_OWNERSHIP_NOTES',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'KEY_SET' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_KEY_SET',
    'width' => '10%',
    'default' => false,
  ),
  'OWNERS_PRESIDENT' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_OWNERS_PRESIDENT',
    'id' => 'CONTACT_ID2_C',
    'link' => true,
    'width' => '10%',
    'default' => false,
  ),
  'OWNERSHIP_PERCENTAGE' => 
  array (
    'type' => 'int',
    'default' => false,
    'label' => 'LBL_OWNERSHIP_PERCENTAGE',
    'width' => '10%',
  ),
  'ESTATE_COMPANY' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_ESTATE_COMPANY',
    'id' => 'ACCOUNT_ID_C',
    'link' => true,
    'width' => '10%',
    'default' => false,
  ),
  'ESTATE_CONTACT' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_ESTATE_CONTACT',
    'id' => 'CONTACT_ID1_C',
    'link' => true,
    'width' => '10%',
    'default' => false,
  ),
  'CONTACT_PERSON' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_CONTACT_PERSON',
    'id' => 'CONTACT_ID_C',
    'link' => true,
    'width' => '10%',
    'default' => false,
  ),
  'OCCUPANT_TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_OCCUPANT_TYPE',
    'width' => '10%',
    'default' => false,
  ),
  'STATUS' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'default' => false,
  ),
  'OWNERSHIP' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_OWNERSHIP',
    'width' => '10%',
    'default' => false,
  ),
  'ADDRESS_NOTES' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_ADDRESS_NOTES',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'LONGITUDE' => 
  array (
    'type' => 'float',
    'label' => 'LBL_LONGITUDE',
    'width' => '10%',
    'default' => false,
  ),
  'LATITUDE' => 
  array (
    'type' => 'float',
    'label' => 'LBL_LATITUDE',
    'width' => '10%',
    'default' => false,
  ),
  'ADDRESS_LOCATION_LINK' => 
  array (
    'type' => 'url',
    'label' => 'LBL_ADDRESS_LOCATION_LINK',
    'width' => '10%',
    'default' => false,
  ),
  'ADDRESS_COUNTRY' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ADDRESS_COUNTRY',
    'width' => '10%',
    'default' => false,
  ),
  'ADDRESS_REGION' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_ADDRESS_REGION',
    'width' => '10%',
    'default' => false,
  ),
  'ADDRESS_STATE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_ADDRESS_STATE',
    'width' => '10%',
    'default' => false,
  ),
  'ADDRESS_POSTALCODE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ADDRESS_POSTALCODE',
    'width' => '10%',
    'default' => false,
  ),
  'ADDRESS_CITY' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ADDRESS_CITY',
    'width' => '10%',
    'default' => false,
  ),
  'ADDRESS_STREET' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ADDRESS_STREET',
    'width' => '10%',
    'default' => false,
  ),
  'END_DATE' => 
  array (
    'type' => 'date',
    'label' => 'LBL_END_DATE',
    'width' => '10%',
    'default' => false,
  ),
  'OTHER' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_OTHER',
    'width' => '10%',
    'default' => false,
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'CREATED_BY_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '10%',
    'default' => false,
  ),
  'MODIFIED_BY_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_MODIFIED_NAME',
    'id' => 'MODIFIED_USER_ID',
    'width' => '10%',
    'default' => false,
  ),
  'DATE_MODIFIED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => false,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => false,
  ),
);
;
?>
