<?php
// created: 2020-07-04 10:28:56
$viewdefs['Opportunities']['QuickCreate'] = array (
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
    'javascript' => '{$PROBABILITY_SCRIPT}',
    'useTabs' => true,
    'tabDefs' => 
    array (
      'LBL_OPPORTUNITIES_INFORMATION' => 
      array (
        'newTab' => true,
        'panelDefault' => 'expanded',
      ),
    ),
  ),
  'panels' => 
  array (
    'LBL_OPPORTUNITIES_INFORMATION' => 
    array (
      0 => 
      array (
        0 => 
        array (
          'name' => 'name',
          'displayParams' => 
          array (
            'required' => true,
          ),
        ),
        1 => 
        array (
          'name' => 'assigned_user_name',
        ),
      ),
      1 => 
      array (
        0 => 
        array (
          'name' => 'account_name',
        ),
        1 => 
        array (
          'name' => 'stic_type_c',
          'studio' => 'visible',
          'label' => 'LBL_STIC_TYPE',
        ),
      ),
      2 => 
      array (
        0 => 
        array (
          'name' => 'stic_target_c',
          'studio' => 'visible',
          'label' => 'LBL_STIC_TARGET',
        ),
        1 => 
        array (
          'name' => 'project_opportunities_1_name',
          'label' => 'LBL_PROJECT_OPPORTUNITIES_1_FROM_PROJECT_TITLE',
        ),
      ),
      3 => 
      array (
        0 => 
        array (
          'name' => 'stic_status_c',
          'studio' => 'visible',
          'label' => 'LBL_STIC_STATUS',
        ),
        1 => 
        array (
          'name' => 'stic_documentation_to_deliver_c',
          'studio' => 'visible',
          'label' => 'LBL_STIC_DOCUMENTATION_TO_DELIVER',
        ),
      ),
      4 => 
      array (
        0 => 'amount',
        1 => 
        array (
          'name' => 'stic_amount_awarded_c',
          'label' => 'LBL_STIC_AMOUNT_AWARDED',
        ),
      ),
      5 => 
      array (
        0 => 
        array (
          'name' => 'stic_amount_received_c',
          'label' => 'LBL_STIC_AMOUNT_RECEIVED',
        ),
        1 => 
        array (
          'name' => 'stic_presentation_date_c',
          'label' => 'LBL_STIC_PRESENTATION_DATE',
        ),
      ),
      6 => 
      array (
        0 => 
        array (
          'name' => 'stic_resolution_date_c',
          'label' => 'LBL_STIC_RESOLUTION_DATE',
        ),
        1 => 
        array (
          'name' => 'stic_advance_date_c',
          'label' => 'LBL_STIC_ADVANCE_DATE',
        ),
      ),
      7 => 
      array (
        0 => 
        array (
          'name' => 'stic_justification_date_c',
          'label' => 'LBL_STIC_JUSTIFICATION_DATE',
        ),
        1 => 
        array (
          'name' => 'stic_payment_date_c',
          'label' => 'LBL_STIC_PAYMENT_DATE',
        ),
      ),
      8 => 
      array (
        0 => 
        array (
          'name' => 'description',
          'comment' => 'Full text of the note',
          'label' => 'LBL_DESCRIPTION',
        ),
      ),
    ),
  ),
);