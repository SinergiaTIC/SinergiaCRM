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

// These entry points are used by the Stic WebForms module
$entry_point_registry['stic_Web_Forms_save'] = array('file' => 'modules/stic_Web_Forms/Entrypoints/Save.php', 'auth' => false);
$entry_point_registry['stic_Web_Forms_saveRecaptcha'] = array('file' => 'modules/stic_Web_Forms/Entrypoints/SaveRecaptcha.php', 'auth' => false);
$entry_point_registry['stic_Web_Forms_tpv_response'] = array('file' => 'modules/stic_Web_Forms/Entrypoints/TPVResponse.php', 'auth' => false);
$entry_point_registry['stic_Web_Forms_paypal_response'] = array('file' => 'modules/stic_Web_Forms/Entrypoints/PaypalResponse.php', 'auth' => false);
$entry_point_registry['stic_Web_Forms_stripe_response'] = array('file' => 'modules/stic_Web_Forms/Entrypoints/StripeResponse.php', 'auth' => false);
$entry_point_registry['stic_Web_Forms_attachment_limits_response'] = array('file' => 'modules/stic_Web_Forms/Entrypoints/AttachmentLimitsResponse.php', 'auth' => false);

// These entry points are used by the SuiteCRM native web forms 
$entry_point_registry['WebToPersonCapture'] = array('file' => 'custom/modules/Campaigns/WebToPersonCapture.php', 'auth' => false);

// Runs the function cleanCode in SticIncludes/CleanConfig.php
$entry_point_registry['sticCleanConfig'] = array('file' => 'SticInclude/CleanConfig.php', 'auth' => false);

// Used by the PDF Template generator
$entry_point_registry['sticGeneratePdf'] = array('file' => 'custom/modules/AOS_PDF_Templates/SticGeneratePdf.php', 'auth' => false);

// This entry point re-compiles SticCustom CSS
$entry_point_registry['sticCustomCSS'] = array('file' => 'SticInclude/SticCustomScss.php', 'auth' => false);

// Overrides Removeme from Campaing in order to get confirmation
$entry_point_registry['removemeConfirmed'] = $entry_point_registry['removeme'];
$entry_point_registry['removeme'] = array('file' => 'custom/modules/Campaigns/ConfirmRemoveMe.php', 'auth' => false);