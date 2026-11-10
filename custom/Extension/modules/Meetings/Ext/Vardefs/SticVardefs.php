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
$dictionary["Meeting"]["audited"] = true;
$dictionary["Meeting"]["fields"]["stic_job_applications_activities_meetings"] = array (
    'name' => 'stic_job_applications_activities_meetings',
    'type' => 'link',
    'relationship' => 'stic_job_applications_activities_meetings',
    'source' => 'non-db',
    'vname' => 'LBL_STIC_JOB_APPLICATIONS_ACTIVITIES_MEETINGS_FROM_STIC_JOB_APPLICATIONS_TITLE',
);

$dictionary["Meeting"]["fields"]["stic_job_offers_activities_meetings"] = array (
    'name' => 'stic_job_offers_activities_meetings',
    'type' => 'link',
    'relationship' => 'stic_job_offers_activities_meetings',
    'source' => 'non-db',
    'vname' => 'LBL_STIC_JOB_OFFERS_ACTIVITIES_MEETINGS_FROM_STIC_JOB_OFFERS_TITLE',
);

$dictionary['Meeting']['fields']['email_reminder_time']['options'] = 'stic_email_reminder_time_list';

$dictionary['Meeting']['fields']['duration']['inline_edit'] = 0;
$dictionary['Meeting']['fields']['reminders']['inline_edit'] = 0;
$dictionary['Meeting']['fields']['parent_name']['inline_edit'] = 0;
$dictionary['Meeting']['fields']['description']['rows'] = '2'; // Make textarea fields shorter
$dictionary['Meeting']['fields']['description']['massupdate'] = 0;

$dictionary['Meeting']['fields']['accept_status']['massupdate'] = 0;
$dictionary['Meeting']['fields']['set_accept_links']['massupdate'] = 0;
$dictionary['Meeting']['fields']['location']['massupdate'] = 0;
$dictionary['Meeting']['fields']['password']['massupdate'] = 0;
$dictionary['Meeting']['fields']['join_url']['massupdate'] = 0;
$dictionary['Meeting']['fields']['host_url']['massupdate'] = 0;
$dictionary['Meeting']['fields']['creator']['massupdate'] = 0;
$dictionary['Meeting']['fields']['external_id']['massupdate'] = 0;
$dictionary['Meeting']['fields']['outlook_id']['massupdate'] = 0;

$dictionary['Meeting']['fields']['parent_name']['massupdate']='1';
$dictionary['Meeting']['fields']['status']['massupdate']='1';
$dictionary['Meeting']['fields']['date_start']['massupdate']='1';

// Fields for meeting minutes
$dictionary['Meeting']['fields']['stic_type_c'] = array(
    'id' => 'Meetingsstic_type_c',
    'name' => 'stic_type_c',
    'vname' => 'LBL_STIC_TYPE',
    'custom_module' => 'Meetings',
    'source' => 'custom_fields',
    'comments' => '',
    'help' => '',
    'type' => 'enum',
    'len' => 100,
    'size' => '20',
    'options' => 'stic_acta_type_list',
    'required' => 0,
    'audited' => 0,
    'unified_search' => 0,
    'default' => null,
    'no_default' => 0,
    'inline_edit' => 1,
    'importable' => 1,
    'massupdate' => 1,
    'reportable' => 1,
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => 1,
    'merge_filter' => 'enabled',
    'studio' => 'visible',
    'dependency' => 0,
);

$dictionary['Meeting']['fields']['stic_modality_c'] = array(
    'id' => 'Meetingsstic_modality_c',
    'name' => 'stic_modality_c',
    'vname' => 'LBL_STIC_MODALITY',
    'custom_module' => 'Meetings',
    'source' => 'custom_fields',
    'comments' => '',
    'help' => '',
    'type' => 'enum',
    'len' => 100,
    'size' => '20',
    'options' => 'stic_followups_channels_list',
    'required' => 0,
    'audited' => 0,
    'unified_search' => 0,
    'default' => null,
    'no_default' => 0,
    'inline_edit' => 1,
    'importable' => 1,
    'massupdate' => 0,
    'reportable' => 1,
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => 1,
    'merge_filter' => 'enabled',
    'studio' => 'visible',
    'dependency' => 0,
);

$dictionary['Meeting']['fields']['stic_order_of_the_day_c'] = array(
    'id' => 'Meetingsstic_order_of_the_day_c',
    'name' => 'stic_order_of_the_day_c',
    'vname' => 'LBL_STIC_ORDER_OF_THE_DAY',
    'custom_module' => 'Meetings',
    'source' => 'custom_fields',
    'comments' => '',
    'help' => '',
    'type' => 'html',
    'len' => 65535,
    'required' => 0,
    'audited' => 0,
    'unified_search' => 0,
    'default' => null,
    'no_default' => 0,
    'inline_edit' => 1,
    'importable' => 1,
    'massupdate' => 0,
    'reportable' => 1,
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => 1,
    'merge_filter' => 'enabled',
    'studio' => 'visible',
    'dependency' => 0,
);

$dictionary['Meeting']['fields']['stic_development_c'] = array(
    'id' => 'Meetingsstic_development_c',
    'name' => 'stic_development_c',
    'vname' => 'LBL_STIC_DEVELOPMENT',
    'custom_module' => 'Meetings',
    'source' => 'custom_fields',
    'comments' => '',
    'help' => '',
    'type' => 'html',
    'len' => 65535,
    'required' => 0,
    'audited' => 0,
    'unified_search' => 0,
    'default' => null,
    'no_default' => 0,
    'inline_edit' => 1,
    'importable' => 1,
    'massupdate' => 0,
    'reportable' => 1,
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => 1,
    'merge_filter' => 'enabled',
    'studio' => 'visible',
    'dependency' => 0,
);

$dictionary['Meeting']['fields']['stic_agreements_c'] = array(
    'id' => 'Meetingsstic_agreements_c',
    'name' => 'stic_agreements_c',
    'vname' => 'LBL_STIC_AGREEMENTS',
    'custom_module' => 'Meetings',
    'source' => 'custom_fields',
    'comments' => '',
    'help' => '',
    'type' => 'html',
    'len' => 65535,
    'required' => 0,
    'audited' => 0,
    'unified_search' => 0,
    'default' => null,
    'no_default' => 0,
    'inline_edit' => 1,
    'importable' => 1,
    'massupdate' => 0,
    'reportable' => 1,
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => 1,
    'merge_filter' => 'enabled',
    'studio' => 'visible',
    'dependency' => 0,
);

