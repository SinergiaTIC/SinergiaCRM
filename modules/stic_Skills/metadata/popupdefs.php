<?php
$popupMeta = array (
    'moduleMain' => 'stic_Skills',
    'varName' => 'stic_Skills',
    'orderBy' => 'stic_skills.name',
    'whereClauses' => array (
  'name' => 'stic_skills.name',
  'stic_skills_contacts_name' => 'stic_skills.stic_skills_contacts_name',
  'skill' => 'stic_skills.skill',
  'type' => 'stic_skills.type',
  'level' => 'stic_skills.level',
  'assigned_user_id' => 'stic_skills.assigned_user_id',
),
    'searchInputs' => array (
  1 => 'name',
  4 => 'stic_skills_contacts_name',
  5 => 'skill',
  6 => 'type',
  7 => 'level',
  8 => 'assigned_user_id',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'stic_skills_contacts_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_SKILLS_CONTACTS_FROM_CONTACTS_TITLE',
    'id' => 'STIC_SKILLS_CONTACTSCONTACTS_IDA',
    'width' => '10%',
    'name' => 'stic_skills_contacts_name',
  ),
  'skill' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_SKILL',
    'width' => '10%',
    'name' => 'skill',
  ),
  'type' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
    'width' => '10%',
    'name' => 'type',
  ),
  'level' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_LEVEL',
    'width' => '10%',
    'name' => 'level',
  ),
  'assigned_user_id' => 
  array (
    'name' => 'assigned_user_id',
    'label' => 'LBL_ASSIGNED_TO',
    'type' => 'enum',
    'function' => 
    array (
      'name' => 'get_user_array',
      'params' => 
      array (
        0 => false,
      ),
    ),
    'width' => '10%',
  ),
),
);
