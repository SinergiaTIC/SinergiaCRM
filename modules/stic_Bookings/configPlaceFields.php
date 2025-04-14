<?php
// config_resource_fields.php
global $config_place_fields;
global $current_language;

$mod_strings = return_module_language($current_language, 'stic_Bookings');

$config_place_fields = [
    'name' => $mod_strings['LBL_RESOURCES_NAME'],
    'code' => $mod_strings['LBL_RESOURCES_CODE'],
    'user_type' => $mod_strings['LBL_RESOURCES_USER_TYPE'],
    'place_type' => $mod_strings['LBL_RESOURCES_PLACE_TYPE'],
    'gender' => $mod_strings['LBL_RESOURCES_GENDER'],
];


