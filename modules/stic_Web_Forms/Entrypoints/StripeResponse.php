<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */
// Verify if this request is a valid entry point
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Set flag mails_allowed to SKIP_CONFIGURATION
require_once __DIR__ . '/../Catcher/Include/Mailer/WebFormMailer.php';
if (!(isset($_REQUEST['stic_mails_allowed']))) {
    $_REQUEST['stic_mails_allowed'] = WebFormMailer::SEND_SKIP_CONFIGURATION;
}

global $current_user;
$current_user->getSystemUser();

$GLOBALS['log']->debug('Entrypoint File: StripeResponse.php: Processing Stripe response...');
require_once __DIR__ . '/../Catcher/Include/Payment/PaymentController.php';
$controller = new PaymentController();
$controller->setNoRequestParams(array('type' => PaymentController::RESPONSE_TYPE_STRIPE_RESPONSE));
$controller->manage();

