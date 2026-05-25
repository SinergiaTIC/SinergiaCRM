<?php
 // created: 2026-05-21 10:33:20
$layout_defs["stic_Whistleblowing"]["subpanel_setup"]['stic_whistleblowing_documents'] = array (
  'order' => 100,
  'module' => 'Documents',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_STIC_WHISTLEBLOWING_DOCUMENTS_FROM_DOCUMENTS_TITLE',
  'get_subpanel_data' => 'stic_whistleblowing_documents',
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