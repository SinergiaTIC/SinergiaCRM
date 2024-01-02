<?php
// created: 2020-07-04 10:28:55
$viewdefs['FP_Event_Locations']['DetailView'] = array (
  'templateMeta' => 
  array (
    'form' => 
    array (
      'buttons' => 
      array (
        0 => 'EDIT',
        1 => 'DUPLICATE',
        2 => 'DELETE',
        3 => 'FIND_DUPLICATES',
      ),
    ),
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
    'useTabs' => true,
    'tabDefs' => 
    array (
      'LBL_FP_EVENT_LOCATIONS_INFORMATION' => 
      array (
        'newTab' => true,
        'panelDefault' => 'expanded',
      ),
      'LBL_STIC_PANEL_ADDRESS' => 
      array (
        'newTab' => false,
        'panelDefault' => 'expanded',
      ),
      'LBL_STIC_PANEL_RECORD_DETAILS' => 
      array (
        'newTab' => true,
        'panelDefault' => 'expanded',
      ),
    ),
    'syncDetailEditViews' => true,
  ),
  'panels' => 
  array (
    'lbl_fp_event_locations_information' => 
    array (
      0 => 
      array (
        0 => 'name',
        1 => 
        array (
          'name' => 'assigned_user_name',
          'label' => 'LBL_ASSIGNED_TO_NAME',
        ),
      ),
      1 => 
      array (
        0 => 
        array (
          'name' => 'capacity',
          'label' => 'LBL_CAPACITY',
        ),
        1 => '',
      ),
      2 => 
      array (
        0 => 'description',
      ),
    ),
    'lbl_stic_panel_address' => 
    array (
      0 => 
      array (
        0 => 
        array (
          'name' => 'address',
          'label' => 'LBL_ADDRESS',
        ),
        1 => '',
      ),
      1 => 
      array (
        0 => 
        array (
          'name' => 'address_postalcode',
          'label' => 'LBL_ADDRESS_POSTALCODE',
        ),
        1 => '',
      ),
      2 => 
      array (
        0 => 
        array (
          'name' => 'address_city',
          'label' => 'LBL_ADDRESS_CITY',
        ),
        1 => '',
      ),
      3 => 
      array (
        0 => 
        array (
          'name' => 'stic_address_county_c',
          'studio' => 'visible',
          'label' => 'LBL_STIC_ADDRESS_COUNTY',
        ),
        1 => '',
      ),
      4 => 
      array (
        0 => 
        array (
          'name' => 'address_state',
          'label' => 'LBL_ADDRESS_STATE',
        ),
        1 => '',
      ),
      5 => 
      array (
        0 => 
        array (
          'name' => 'stic_address_region_c',
          'studio' => 'visible',
          'label' => 'LBL_STIC_ADDRESS_REGION',
        ),
        1 => '',
      ),
      6 => 
      array (
        0 => 
        array (
          'name' => 'address_country',
          'label' => 'LBL_ADDRESS_COUNTRY',
        ),
        1 => '',
      ),
    ),
    'lbl_stic_panel_record_details' => 
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
          'customCode' => '{$fields.date_entered.value}',
          'label' => 'LBL_DATE_ENTERED',
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
          'customCode' => '{$fields.date_modified.value}',
          'label' => 'LBL_DATE_MODIFIED',
        ),
      ),
    ),
  ),
);