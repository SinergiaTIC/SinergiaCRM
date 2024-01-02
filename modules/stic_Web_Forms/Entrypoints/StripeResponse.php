<?php
// Verify if this request is a valid entry point
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $current_user;
$current_user->getSystemUser();

$GLOBALS['log']->debug('Entrypoint File: StripeResponse.php: Processing Stripe response...');
require_once __DIR__ . '/../Catcher/Include/Payment/PaymentController.php';
$controller = new PaymentController();
$controller->setNoRequestParams(array('type' => PaymentController::RESPONSE_TYPE_STRIPE_RESPONSE));
$controller->manage();

