<?php
// created: 2020-07-04 10:28:56
$viewdefs['Schedulers']['DetailView'] = array (
  'templateMeta' => 
  array (
    'maxColumns' => '2',
    'widths' => 
    array (
      0 => 
      array (
        'label' => '10',
        'field' => '30',
      ),
      1 => 
      array (
        'label' => '10',
        'field' => '30',
      ),
    ),
    'includes' => 
    array (
      0 => 
      array (
        'file' => 'modules/Schedulers/Schedulers.js',
      ),
    ),
    'useTabs' => true,
    'tabDefs' => 
    array (
      'LBL_SCHEDULERS_INFORMATION' => 
      array (
        'newTab' => true,
        'panelDefault' => 'expanded',
      ),
      'LBL_STIC_PANEL_RECORD_DETAILS' => 
      array (
        'newTab' => true,
        'panelDefault' => 'expanded',
      ),
    ),
  ),
  'panels' => 
  array (
    'LBL_SCHEDULERS_INFORMATION' => 
    array (
      0 => 
      array (
        0 => 'name',
        1 => 'status',
      ),
      1 => 
      array (
        0 => 'date_time_start',
        1 => 
        array (
          'name' => 'time_from',
          'customCode' => '{$fields.time_from.value|default:$MOD.LBL_ALWAYS}',
        ),
      ),
      2 => 
      array (
        0 => 'date_time_end',
        1 => 
        array (
          'name' => 'time_to',
          'customCode' => '{$fields.time_to.value|default:$MOD.LBL_ALWAYS}',
        ),
      ),
      3 => 
      array (
        0 => 
        array (
          'name' => 'last_run',
          'customCode' => '{$fields.last_run.value|default:$MOD.LBL_NEVER}',
        ),
        1 => 
        array (
          'name' => 'job_interval',
          'customCode' => '{$JOB_INTERVAL}',
        ),
      ),
      4 => 
      array (
        0 => 'catch_up',
        1 => 'job',
      ),
    ),
    'LBL_STIC_PANEL_RECORD_DETAILS' => 
    array (
      0 => 
      array (
        0 => 
        array (
          'name' => 'created_by_name',
          'label' => 'LBL_CREATED',
        ),
        1 => 
        array (
          'name' => 'date_entered',
          'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}&nbsp;',
        ),
      ),
      1 => 
      array (
        0 => 
        array (
          'name' => 'modified_by_name',
          'label' => 'LBL_MODIFIED_NAME',
        ),
        1 => 
        array (
          'name' => 'date_modified',
          'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}&nbsp;',
        ),
      ),
    ),
  ),
);