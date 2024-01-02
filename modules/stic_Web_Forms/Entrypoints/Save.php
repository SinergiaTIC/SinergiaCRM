<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Prevent spam or unknown web form calls to avoid false error notifications. Just checking that main $_REQUEST items are present.
if (
    !isset($_REQUEST['defParams'])
    || !isset($_REQUEST['webFormClass'])
) {
    $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": Entrypoint stic_Web_Forms_save locked. REQUEST:" . print_r($_REQUEST, true));
    die('Unauthorized, check log.');
}

$GLOBALS['log']->debug('Entrypoint File: Save.php: Processing WebFormDataController...');

global $current_user;
$current_user->getSystemUser();

require_once __DIR__ . '/../Catcher/WebFormDataController.php';
$controller = new WebFormDataController();
$controller->manage();
