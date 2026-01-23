<?php
$module_name = 'stic_Advanced_Web_Forms_Responses';
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
            'name' => 'status',
            'label' => 'LBL_STATUS',
          ),
          1 => array (
            'name' => 'stic_1bb8ponses_name',
          ),
        ),
        2 => array (
          0 => array (
            'name' => 'form_url',
            'label' => 'LBL_FORM_URL',
          ),
        ),
        3 => array (
          0 => array (
            'name' => 'user_agent',
            'label' => 'LBL_USER_AGENT',
          ),
        ),
        4 => array (
          0 => array (
            'name' => 'remote_ip',
            'label' => 'LBL_REMOTE_IP',
          ),
          1 => array (
            'name' => 'response_hash',
            'label' => 'LBL_RESPONSE_HASH',
          ),
        ),
        5 => array (
          0 => array (
            'name' => 'html_summary',
            'label' => 'LBL_HTML_SUMMARY',
            'customCode' => '{$fields.html_summary.value|html_entity_decode}',
          ),
        ),
        6 => array (
          0 => array (
            'name' => 'execution_log',
            'label' => 'LBL_EXECUTION_LOG',
          ),
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
