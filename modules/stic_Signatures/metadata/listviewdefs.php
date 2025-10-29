<?php
$module_name = 'stic_Signatures';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'MAIN_MODULE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_MAIN_MODULE',
    'width' => '10%',
    'default' => true,
  ),
  'STATUS' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
  ),
  'TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'ACTIVATION_DATE' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_ACTIVATION_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'EXPIRATION_DATE' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_EXPIRATION_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'AUTH_METHOD' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_AUTH_METHOD',
    'width' => '10%',
  ),
  'EMAIL_TEMPLATE' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_EMAIL_TEMPLATE',
    'id' => 'EMAILTEMPLATE_ID_C',
    'link' => true,
    'width' => '10%',
    'default' => true,
  ),

  'ON_BEHALF_OF' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_ON_BEHALF_OF',
    'width' => '10%',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  
  'PDF_AUDIT_PAGE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_PDF_AUDIT_PAGE',
    'width' => '10%',
    'default' => false,
  ),
  'SIGNER_PATH' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_SIGNER_PATH',
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
