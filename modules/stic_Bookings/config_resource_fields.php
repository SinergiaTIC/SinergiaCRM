<?php
// config_resource_fields.php
global $config_resource_fields;
global $current_language;

$mod_strings = return_module_language($current_language, 'stic_Bookings');

$config_resource_fields = [
    'name' => $mod_strings['LBL_RESOURCES_NAME'],
    'code' => $mod_strings['LBL_RESOURCES_CODE'],
    'gender' => $mod_strings['LBL_RESOURCES_GENDER'],
    'color' => $mod_strings['LBL_RESOURCES_COLOR'],
    'type' => $mod_strings['LBL_RESOURCES_TYPE'],
    'hourly_rate' => $mod_strings['LBL_RESOURCES_HOURLY_RATE'],
    'daily_rate' => $mod_strings['LBL_RESOURCES_DAILY_RATE'],
];


