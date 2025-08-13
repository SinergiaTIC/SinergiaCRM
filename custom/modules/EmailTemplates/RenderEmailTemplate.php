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
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$GLOBALS['log']->debug('Entrypoint File: RenderEmailTemplate.php: Generating the HTML of an email template.');

// Validate if the email marketing ID parameter has been indicated
if (empty($_REQUEST['emailMarketingId'])) {
    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": You must provide the required parameter: Email Marketing ID.");
    die("You must provide the required parameters. Contact the administrator or the support team.");
}

// Get the email marketing indicated as a parameter
$emailMarketing = BeanFactory::getBean('EmailMarketing', $_REQUEST['emailMarketingId']);

// Validate if the ID matches any CRM record
if (empty($emailMarketing)) {
    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": The indicated marketing email must have a related email template.");
    die("The indicated email marketing Id does not match any record in the CRM.");
}

// Get the campaign related to the marketing email
if (!empty($emailMarketing->campaign_id)) {
    $campaign = BeanFactory::getBean('Campaigns', $emailMarketing->campaign_id);
}

// Get the email template related to the marketing email
if (!empty($emailMarketing->template_id)) {
    $emailTemplate = BeanFactory::getBean('EmailTemplates', $emailMarketing->template_id);
}

// Validate if the marketing email had an associated email template and campaign
if (empty($campaign) || empty($emailTemplate)) {
    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": The indicated marketing email must have a related email template.");
    die("The indicated marketing email must have a related campaign and related email template.");
}

// Get objects related with optional parameters
$optionalParams = ['module', 'recordId', 'targetId', 'trackingURL'];
foreach ($optionalParams as $key) {
    if (empty($_REQUEST[$key])) {
        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . "You have not provided the optional parameter:: " . $key);
    }
}

$record = BeanFactory::newBean($_REQUEST['module']);
$record->retrieve($_REQUEST['recordId']);
$targetId = $_REQUEST['targetId'];
$trackingURL = $_REQUEST['trackingURL'];

// Get template body data
require_once __DIR__ . '/../../../modules/EmailTemplates/EmailTemplateParser.php';
$template_data = (new EmailTemplateParser(
    $emailTemplate,
    $campaign,
    $record,
    $sugar_config['site_url'],
    $targetId
))->parseVariables();

// Load defined tracked_urls
$campaign->load_relationship('tracked_urls');
$query_array = $campaign->tracked_urls->getQuery(true);
$query_array['select'] = "SELECT tracker_name, tracker_key, id, is_optout";
$result = $campaign->db->query(implode(' ', $query_array));

$tracker_urls = array();
while (($row = $campaign->db->fetchByAssoc($result)) != null) {
    $tracker_urls['{' . $row['tracker_name'] . '}'] = $row;
}

// Parse and replace tracking urls.
$tracker_url_template = $trackingURL . 'index.php?entryPoint=campaign_trackerv2&track=%s' . '&identifier=' . $targetId;
$removeme_url_template = $trackingURL . 'index.php?entryPoint=removeme&identifier=' . $targetId;
$template_data = $emailTemplate->parse_tracker_urls($template_data, $tracker_url_template, $tracker_urls, $removeme_url_template);

// Render HTML
// echo html_entity_decode($template_data->template->body_html, ENT_QUOTES, 'UTF-8');
echo html_entity_decode($template_data['body_html'], ENT_QUOTES, 'UTF-8');
die();