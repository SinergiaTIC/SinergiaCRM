<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once "include/utils/additional_details.php";

function additionalDetailsstic_Medication($fields, SugarBean $bean = null, $params = array())
{

    if (file_exists('custom/modules/' . $bean->module_name . '/metadata/customAdditionalDetails.php')) {
        $additionalDetailsFile = 'custom/modules/' . $bean->module_name . '/metadata/customAdditionalDetails.php';
        require_once($additionalDetailsFile);
        
        $mod_strings = return_module_language($current_language, $bean->module_name);
        return customAdditionalDetails::customAdditionalDetailsstic_Medication($fields, $bean, $mod_strings);
    
    } else {
        global $current_language;
        $mod_strings = return_module_language($current_language, $bean->module_name);
        return additional_details($fields, $bean, $mod_strings);
    }    
}
