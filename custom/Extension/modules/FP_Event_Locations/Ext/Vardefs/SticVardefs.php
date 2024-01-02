<?php

$dictionary['FP_Event_Locations']['fields']['stic_address_county_c'] = array(
    'id' => 'FP_Event_Locationsstic_address_county_c',
    'name' => 'stic_address_county_c',
    'vname' => 'LBL_STIC_ADDRESS_COUNTY',
    'custom_module' => 'FP_Event_Locations',
    'source' => 'custom_fields',
    'comments' => '',
    'help' => '',
    'type' => 'enum',
    'len' => 100,
    'size' => '20',
    'options' => 'stic_catalonia_counties_list',
    'required' => 0,
    'audited' => 0,
    'unified_search' => 0,
    'default' => null,
    'no_default' => 0,
    'inline_edit' => 1,
    'importable' => 1,
    'massupdate' => 1,
    'reportable' => 1,
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => 1,
    'merge_filter' => 'enabled',
    'studio' => 'visible',
    'dependency' => 0,
);

$dictionary['FP_Event_Locations']['fields']['stic_address_region_c'] = array(
    'id' => 'FP_Event_Locationsstic_address_region_c',
    'name' => 'stic_address_region_c',
    'vname' => 'LBL_STIC_ADDRESS_REGION',
    'custom_module' => 'FP_Event_Locations',
    'source' => 'custom_fields',
    'comments' => '',
    'help' => '',
    'type' => 'enum',
    'len' => 100,
    'size' => '20',
    'options' => 'stic_spain_autonomous_communities_list',
    'required' => 0,
    'audited' => 0,
    'unified_search' => 0,
    'default' => null,
    'no_default' => 0,
    'inline_edit' => 1,
    'importable' => 1,
    'massupdate' => 1,
    'reportable' => 1,
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => 1,
    'merge_filter' => 'enabled',
    'studio' => 'visible',
    'dependency' => 0,
);

$dictionary["FP_Event_Locations"]["fields"]["stic_events_fp_event_locations"] = array(
    'name' => 'stic_events_fp_event_locations',
    'type' => 'link',
    'relationship' => 'stic_events_fp_event_locations',
    'source' => 'non-db',
    'module' => 'stic_Events',
    'bean_name' => false,
    'side' => 'right',
    'vname' => 'LBL_STIC_EVENTS_FP_EVENT_LOCATIONS_FROM_STIC_EVENTS_TITLE',
);

$dictionary['FP_Event_Locations']['fields']['address_state']['type'] = 'enum';
$dictionary['FP_Event_Locations']['fields']['address_state']['options'] = 'stic_spain_provinces_list';
$dictionary['FP_Event_Locations']['fields']['address_state']['default'] = '';
$dictionary['FP_Event_Locations']['fields']['address_state']['massupdate'] = 1;

$dictionary['FP_Event_Locations']['fields']['capacity']['type'] = 'int';
$dictionary['FP_Event_Locations']['fields']['capacity']['enable_range_search'] = 1;
$dictionary['FP_Event_Locations']['fields']['capacity']['options'] = 'numeric_range_search_dom';
$dictionary['FP_Event_Locations']['fields']['capacity']['massupdate'] = 1;

$dictionary['FP_Event_Locations']['fields']['address']['massupdate'] = 1;
$dictionary['FP_Event_Locations']['fields']['address_city']['massupdate'] = 1;
$dictionary['FP_Event_Locations']['fields']['address_country']['massupdate'] = 1;
$dictionary['FP_Event_Locations']['fields']['address_postalcode']['massupdate'] = 1;

$dictionary['FP_Event_Locations']['fields']['description']['rows'] = '2'; // Make textarea fields shorter
$dictionary['FP_Event_Locations']['fields']['description']['massupdate'] = 0;
