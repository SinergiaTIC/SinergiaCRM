<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $current_user;
$current_user->getSystemUser();

$GLOBALS['log']->debug('Entrypoint File: PaypalResponse.php: Processing PayPal response...');
require_once __DIR__ . '/../Catcher/Include/Payment/PaymentController.php';
$controller = new PaymentController();
$controller->setNoRequestParams(array('type' => PaymentController::RESPONSE_TYPE_PAYPAL_RESPONSE));
$controller->manage();
