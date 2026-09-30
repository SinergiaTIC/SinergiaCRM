<?php
// created: 2026-09-29 16:53:54
$dictionary["stic_Organizational_Environment"]["fields"]["stic_organizational_environment_contacts"] = array (
  'name' => 'stic_organizational_environment_contacts',
  'type' => 'link',
  'relationship' => 'stic_organizational_environment_contacts',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'vname' => 'LBL_STIC_ORGANIZATIONAL_ENVIRONMENT_CONTACTS_FROM_CONTACTS_TITLE',
  'id_name' => 'stic_organizational_environment_contactscontacts_ida',
);
$dictionary["stic_Organizational_Environment"]["fields"]["stic_organizational_environment_contacts_name"] = array (
  'name' => 'stic_organizational_environment_contacts_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_STIC_ORGANIZATIONAL_ENVIRONMENT_CONTACTS_FROM_CONTACTS_TITLE',
  'save' => true,
  'id_name' => 'stic_organizational_environment_contactscontacts_ida',
  'link' => 'stic_organizational_environment_contacts',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["stic_Organizational_Environment"]["fields"]["stic_organizational_environment_contactscontacts_ida"] = array (
  'name' => 'stic_organizational_environment_contactscontacts_ida',
  'type' => 'link',
  'relationship' => 'stic_organizational_environment_contacts',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_STIC_ORGANIZATIONAL_ENVIRONMENT_CONTACTS_FROM_STIC_ORGANIZATIONAL_ENVIRONMENT_TITLE',
);
