<?php
 // created: 2026-09-29 16:53:54
$layout_defs["Contacts"]["subpanel_setup"]['stic_organizational_environment_contacts'] = array (
  'order' => 100,
  'module' => 'stic_Organizational_Environment',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_STIC_ORGANIZATIONAL_ENVIRONMENT_CONTACTS_FROM_STIC_ORGANIZATIONAL_ENVIRONMENT_TITLE',
  'get_subpanel_data' => 'stic_organizational_environment_contacts',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);
