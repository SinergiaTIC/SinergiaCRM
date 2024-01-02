<?php

/**
 * This script can be run during the update of SinergiaCRM instances. 
 * 
 * It is in charge of running any SugraCRM PHP script that will find in SticUpdates/Scripts folder
 * 
 * If you want to run just one script, you can specify the file name in the argument list, withouth the PHP extension. Like this:
 * http://xxxxxxxxxx.sinergiacrm.org/SticRunScripts.php?file=FixPersonalEnvironmentModuleDisplay
 * 
 */

include ('include/MVC/preDispatch.php');
$startTime = microtime(true);
require_once('include/entryPoint.php');
ob_start();

if ($file = $_REQUEST['file']) {
    if (file_exists($file)) {
        if (is_dir($file)) {
            echo "It's a folder, retrieving files: <br>";
            $files = glob($file.'/*.php');
            foreach ($files as $file) {
                echo "$file <br>";
                require($file);   
            }
        } else {
            echo "$file <br>";
            require($file);   
        }
    } else {
        echo "File ".$file." doesn't exist in server";
    }
} else {
    echo "File isn't specified in URL, executing all files from SticUpdates/Scripts/ <br>";
    $files = glob('SticUpdates/Scripts/*.php');
    foreach ($files as $file) {
        echo "$file <br>";
        require($file);   
    }
}
