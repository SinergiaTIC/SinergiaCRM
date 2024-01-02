<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

if (!empty($_REQUEST['identifier'])) {
    global $current_language;
    $mod_strings = return_module_language($current_language, 'Campaigns');
    
    //Build Confirmation Message.
    $identifier = $_REQUEST['identifier'];
    $url = 'index.php?entryPoint=removemeConfirmed&identifier='.$identifier;
    $link = '<a href="'.$url.'">'.$mod_strings['LBL_CONFIRM_OPTOUT_HERE'].'</a>';
    $message = str_replace("%0", $link, $mod_strings['LBL_CONFIRM_OPTOUT']);

    //Print Confirmation Message.
    echo $message;
}
sugar_cleanup();