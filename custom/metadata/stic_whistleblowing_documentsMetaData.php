<?php
// created: 2026-05-21 10:33:20
$dictionary["stic_whistleblowing_documents"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'stic_whistleblowing_documents' => 
    array (
      'lhs_module' => 'stic_Whistleblowing',
      'lhs_table' => 'stic_whistleblowing',
      'lhs_key' => 'id',
      'rhs_module' => 'Documents',
      'rhs_table' => 'documents',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'stic_whistleblowing_documents_c',
      'join_key_lhs' => 'stic_whistleblowing_documentsstic_whistleblowing_ida',
      'join_key_rhs' => 'stic_whistleblowing_documentsdocuments_idb',
    ),
  ),
  'table' => 'stic_whistleblowing_documents_c',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'stic_whistleblowing_documentsstic_whistleblowing_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'stic_whistleblowing_documentsdocuments_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
    5 => 
    array (
      'name' => 'document_revision_id',
      'type' => 'varchar',
      'len' => '36',
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'stic_whistleblowing_documentsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'stic_whistleblowing_documents_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'stic_whistleblowing_documentsstic_whistleblowing_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'stic_whistleblowing_documents_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'stic_whistleblowing_documentsdocuments_idb',
      ),
    ),
  ),
);