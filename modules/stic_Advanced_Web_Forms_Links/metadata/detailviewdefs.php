<?php
$module_name = 'stic_Advanced_Web_Forms_Links';
$viewdefs [$module_name] = array (
  'DetailView' => array (
    'templateMeta' => array (
      'form' => array (
        'buttons' => array (
          0 => 'DELETE',
        ),
      ),
      'maxColumns' => '2',
      'widths' => array (
        0 => array (
          'label' => '10',
          'field' => '30',
        ),
        1 => array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => true,
      'tabDefs' => array(
        'LBL_DEFAULT_PANEL' => array(
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_RECORD_DETAILS' => array(
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => array (
      'lbl_default_panel' => array (
        0 => array (
          0 => 'name',
          1 => 'assigned_user_name',
        ),
        1 => array (
          0 => array (
            'name' => 'stic_c271_links_name',
          ),
          1 => array (
            'name' => 'sequence_number',
            'label' => 'LBL_SEQUENCE_NUMBER',
          ),
        ),
        2 => array (
          0 => array (
            'name' => 'parent_name',
            'label' => 'LBL_FLEX_RELATE',
          ),
          1 => array (
            'name' => 'record_action',
            'label' => 'LBL_RECORD_ACTION',
          ),
        ),
        3 => array (
          0 => array (
            'name' => 'submitted_data_html',
            'label' => 'LBL_SUBMITTED_DATA',
          ),
        ),
        4 => array (
          0 => 'description',
        ),
      ),
      'lbl_panel_record_details' => array(
        0 => array(
          0 => array(
            'name' => 'created_by_name',
            'label' => 'LBL_CREATED',
          ),
          1 => array(
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value}',
            'label' => 'LBL_DATE_ENTERED',
          ),
        ),
        1 => array(
          0 => array(
            'name' => 'modified_by_name',
            'label' => 'LBL_MODIFIED_NAME',
          ),
          1 => array(
            'name' => 'date_modified',
            'customCode' => '{$fields.date_modified.value}',
            'label' => 'LBL_DATE_MODIFIED',
          ),
        ),
      ),
    ),
  ),
);
;
?>
