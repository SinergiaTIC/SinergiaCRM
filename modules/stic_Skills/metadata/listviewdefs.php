<?php
$module_name = 'stic_Skills';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'STIC_SKILLS_CONTACTS_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_SKILLS_CONTACTS_FROM_CONTACTS_TITLE',
    'id' => 'STIC_SKILLS_CONTACTSCONTACTS_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'SKILL' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_SKILL',
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
  'LEVEL' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_LEVEL',
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
  'LANGUAGE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_LANGUAGE',
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
  'WRITTEN' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_WRITTEN ',
    'width' => '10%',
    'default' => false,
  ),
  'ORAL' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_ORAL ',
    'width' => '10%',
    'default' => false,
  ),
  'CERTIFICATE' => 
  array (
    'type' => 'dynamicenum',
    'studio' => 'visible',
    'label' => 'LBL_CERTIFICATE',
    'width' => '10%',
    'default' => false,
  ),
  'CERTIFICATE_DATE' => 
  array (
    'type' => 'date',
    'label' => 'LBL_CERTIFICATE_DATE',
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
