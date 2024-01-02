<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$GLOBALS['log']->debug('Entrypoint File: AttachmentLimitsResponse.php.php:  getConfigVariables...');

require_once 'include/utils.php';
$someArray = [
    "uploadMaxFilesize" => ini_get('upload_max_filesize'),
    "uploadMaxFilesizeBytes" => return_bytes(ini_get('upload_max_filesize')),
    "postMaxSize" => ini_get('post_max_size'),
    "postMaxSizeBytes" => return_bytes(ini_get('post_max_size')),
];

// Convert Array to JSON String
$someJSON = json_encode($someArray);
echo "getConfigVariables(" . $someJSON . ");";
