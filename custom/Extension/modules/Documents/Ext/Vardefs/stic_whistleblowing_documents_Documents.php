<?php
// created: 2026-05-21 10:33:20
$dictionary["Document"]["fields"]["stic_whistleblowing_documents"] = array (
  'name' => 'stic_whistleblowing_documents',
  'type' => 'link',
  'relationship' => 'stic_whistleblowing_documents',
  'source' => 'non-db',
  'module' => 'stic_Whistleblowing',
  'bean_name' => false,
  'vname' => 'LBL_STIC_WHISTLEBLOWING_DOCUMENTS_FROM_STIC_WHISTLEBLOWING_TITLE',
  'id_name' => 'stic_whistleblowing_documentsstic_whistleblowing_ida',
);
$dictionary["Document"]["fields"]["stic_whistleblowing_documents_name"] = array (
  'name' => 'stic_whistleblowing_documents_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_STIC_WHISTLEBLOWING_DOCUMENTS_FROM_STIC_WHISTLEBLOWING_TITLE',
  'save' => true,
  'id_name' => 'stic_whistleblowing_documentsstic_whistleblowing_ida',
  'link' => 'stic_whistleblowing_documents',
  'table' => 'stic_whistleblowing',
  'module' => 'stic_Whistleblowing',
  'rname' => 'name',
);
$dictionary["Document"]["fields"]["stic_whistleblowing_documentsstic_whistleblowing_ida"] = array (
  'name' => 'stic_whistleblowing_documentsstic_whistleblowing_ida',
  'type' => 'link',
  'relationship' => 'stic_whistleblowing_documents',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_STIC_WHISTLEBLOWING_DOCUMENTS_FROM_DOCUMENTS_TITLE',
);