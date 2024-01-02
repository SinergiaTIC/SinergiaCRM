<?php
// created: 2019-10-23 10:06:51
$dictionary["Opportunity"]["fields"]["project_opportunities_1"] = array (
  'name' => 'project_opportunities_1',
  'type' => 'link',
  'relationship' => 'project_opportunities_1',
  'source' => 'non-db',
  'module' => 'Project',
  'bean_name' => 'Project',
  'vname' => 'LBL_PROJECT_OPPORTUNITIES_1_FROM_PROJECT_TITLE',
  'id_name' => 'project_opportunities_1project_ida',
);
$dictionary["Opportunity"]["fields"]["project_opportunities_1_name"] = array (
  'name' => 'project_opportunities_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PROJECT_OPPORTUNITIES_1_FROM_PROJECT_TITLE',
  'save' => true,
  'id_name' => 'project_opportunities_1project_ida',
  'link' => 'project_opportunities_1',
  'table' => 'project',
  'module' => 'Project',
  'rname' => 'name',
);
$dictionary["Opportunity"]["fields"]["project_opportunities_1project_ida"] = array (
  'name' => 'project_opportunities_1project_ida',
  'type' => 'link',
  'relationship' => 'project_opportunities_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_PROJECT_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE',
);
