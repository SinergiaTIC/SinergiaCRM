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

$mod_strings = array (
  'LBL_ASSIGNED_TO_ID' => 'Assigned to (ID)',
  'LBL_ASSIGNED_TO_NAME' => 'Assigned to',
  'LBL_ASSIGNED_TO' => 'Assigned to',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Assigned to',
  'LBL_LIST_ASSIGNED_USER' => 'Assigned to',
  'LBL_CREATED' => 'Created By',
  'LBL_CREATED_USER' => 'Created By',
  'LBL_CREATED_ID' => 'Created By (ID)',
  'LBL_MODIFIED' => 'Modified By',
  'LBL_MODIFIED_NAME' => 'Modified By',
  'LBL_MODIFIED_USER' => 'Modified By',
  'LBL_MODIFIED_ID' => 'Modified By (ID)',
  'LBL_SECURITYGROUPS' => 'Security Groups',
  'LBL_SECURITYGROUPS_SUBPANEL_TITLE' => 'Security Groups',
  'LBL_ID' => 'ID',
  'LBL_DATE_ENTERED' => 'Date Created',
  'LBL_DATE_MODIFIED' => 'Date Modified',
  'LBL_DESCRIPTION' => 'Description',
  'LBL_DELETED' => 'Deleted',
  'LBL_NAME' => 'Name',
  'LBL_LIST_NAME' => 'Name',
  'LBL_EDIT_BUTTON' => 'Edit',
  'LBL_REMOVE' => 'Remove',
  'LBL_ASCENDING' => 'Ascending',
  'LBL_DESCENDING' => 'Descending',
  'LBL_LIST_FORM_TITLE' => 'List of Work calendar records',
  'LBL_MODULE_NAME' => 'Work Calendar',
  'LBL_MODULE_TITLE' => 'Work Calendar',
  'LBL_HOMEPAGE_TITLE' => 'My Work calendar records',
  'LNK_NEW_RECORD' => 'Create Work calendar record',
  'LNK_LIST' => 'View Work calendar records',
  'LNK_IMPORT_STIC_WORK_CALENDAR' => 'Import Work calendar records',
  'LBL_SEARCH_FORM_TITLE' => 'Search for Work calendar records',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'View History',
  'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Activities',
  'LBL_STIC_WORK_CALENDAR_SUBPANEL_TITLE' => 'Work calendar',
  'LBL_NEW_FORM_TITLE' => 'New Work calendar',
  'LBL_TYPE' => 'Type',
  'LBL_START_DATE' => 'Start date and time',
  'LBL_END_DATE' => 'End date and time',
  'LBL_START_DATE_ERROR' => 'The start date and time must be before the end date and time.',
  'LBL_END_DATE_ERROR' => 'The end date and time must be later than the start date and time.',
  'LBL_END_DATE_EXCCEDS_24_HOURS' => 'The difference between the end date and time and the start date and time must be less than 24 hours.',  
  'LBL_INCOMPATIBLE_TYPE_WITH_EXISTING_RECORDS' => 'There are other records with a type incompatible with the current one for the same assigned user and within the defined time range. Check these records and modify the time range of this record or the one that generates the conflict.',
  'LBL_ERROR_REQUEST_INCOMPATIBLE_TYPE' => 'Error checking if there are already incompatible Work Calendar records with the record being created. Check with your administrator.',
  'LBL_ERROR_CODE_REQUEST_INCOMPATIBLE_TYPE' => 'Error code: ',
  'LBL_DURATION' => 'Duration',
  'LBL_WEEKDAY' => 'Weekday',  
  'LBL_DEFAULT_PANEL' => 'Overview',
  'LBL_PANEL_RECORD_DETAILS' => 'Record details',
  'LBL_ALL_DAY_HELP' => 'If the type of registration is neither workday nor punctual absence, the registration affects the entire day and it will not be necessary to indicate the start time or the end date and time as they will be calculated automatically.',
  
  // Work Calendar record creation wizard
  'LNK_CREATE_PERIODIC_RECORDS' => 'Create records periodically',
  'LBL_PERIODIC_WORK_CALENDAR_BUTTON' => 'Periodic creation of Work Calendar records',
  'LBL_PERIODIC_WORK_CALENDAR_SUMMARY_TITLE_BY_USER' => 'Summary by user',  
  'LBL_CANCEL_BUTTON' => 'Cancel',
  'LBL_REPEAT_DOW' => 'Day of week',
  'LBL_REPEAT_END_AFTER' => 'After',
  'LBL_REPEAT_END_BY' => 'By',
  'LBL_REPEAT_END' => 'End',
  'LBL_REPEAT_INTERVAL' => 'Interval',
  'LBL_REPEAT_OCCURRENCES' => 'sessions',
  'LBL_REPEAT_TYPE' => 'Repeat',
  'LBL_REPEAT_UNTIL' => 'Repetat until',
  'LBL_SAVE_BUTTON' => 'Save',
  'LBL_TIME_START' => '1st session final date and hour',
  'LBL_TIME_FINAL' => '1st session start date and hour',
  'LBL_TITLE' => 'Create periodic sessions',
  'LBL_WORK_CALENDAR_DURATION' => 'Work Calendar Record Duration',
  'LBL_ERROR_IN_VALIDATION' => 'Error in the value indicated in the end date and time field.',
  'LBL_END_DATE_ERROR' => 'The end date and time must be later than the start date and time.',
  'LBL_END_DATE_EXCCEDS_24_HOURS' => 'The difference between the end and start date and time must be less than 24 hours.',
  'LBL_INCOMPATIBLE_TYPE_WITH_EXISTING_RECORDS' => 'There are other records with a type incompatible with the current one for the same assigned user and within the defined time range. Check these records and modify the time range of this record or the one that generates the conflict.',

  // Work Calendar record creation wizard summary
  'LBL_PERIODIC_WORK_CALENDAR_SUMMARY_TITLE' => 'SUMMARY:',
  'LBL_PERIODIC_WORK_CALENDAR_SUMMARY_RECORDS_PROCESSED' => 'Number of records to create',
  'LBL_PERIODIC_WORK_CALENDAR_SUMMARY_RECORDS_CREATED' => 'Number of records created',
  'LBL_PERIODIC_WORK_CALENDAR_SUMMARY_RECORDS_NOT_CREATED' => 'Number of records not created',
  'LBL_PERIODIC_WORK_CALENDAR_SUMMARY_RECORDS_NOT_CREATED_TITLE' => 'List of records not created',
  'LBL_PERIODIC_WORK_CALENDAR_SUMMARY_RECORDS_NOT_CREATED_TEXT' => 'Records have not been created due to overlapping with some other existing record of incompatible type',
  'LBL_PERIODIC_WORK_CALENDAR_SUMMARY_BUTTON_EMPLOYEES' => 'Go to Employees',
  'LBL_PERIODIC_WORK_CALENDAR_SUMMARY_BUTTON_WOK_CALENDAR' => 'Go to Work Calendar',

  // Wizard to modify the time of Work Calendar records
  'LBL_MASS_UPDATE_DATES_BUTTON_TITTLE' => 'Update start and end time',
  'LBL_MASS_UPDATE_DATES_TITTLE' => 'Mass update start and end time',
  'LBL_MASS_UPDATE_VALIDATION_IMPORTANT' => 'IMPORTANT:',
  'LBL_MASS_UPDATE_VALIDATION_TEXT' => 'The following validations are not applied in this process and its are applied from the editing view:',
  'LBL_MASS_UPDATE_VALIDATION_1' => 'Check start date and time is before the end date and time.',
  'LBL_MASS_UPDATE_VALIDATION_2' => 'Check the duration is not more than 24 hours.',
  'LBL_MASS_UPDATE_VALIDATION_3' => 'Check if there is already another record with an incompatible type that overlaps with the new time range.',
  'LBL_MASS_UPDATE_TEXT' => 'Indicate the value you want to add, subtract or assign to one or both fields:',
  'LBL_MASS_UPDATE_DATES_FIELD' => 'Field',
  'LBL_MASS_UPDATE_DATES_OPERADOR' => 'Operator',
  'LBL_MASS_UPDATE_DATES_HORAS' => 'Hours',
  'LBL_MASS_UPDATE_DATES_MINUTES' => 'Minutes',
  'LBL_CANCEL_BUTTON' => 'Cancel',
  'LBL_UPDATE_BUTTON' => 'Update',
);