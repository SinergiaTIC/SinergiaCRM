<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2019 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
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
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$mod_strings = array(
    'LBL_RECORDS_SKIPPED_DUE_TO_ERROR' => '',
    'LBL_UPDATE_SUCCESSFULLY' => '',
    'LBL_SUCCESSFULLY_IMPORTED' => '',
    'LBL_STEP_4_TITLE' => '',
    'LBL_STEP_5_TITLE' => '',
    'LBL_CUSTOM_ENCLOSURE' => '',
    'LBL_ERROR_UNABLE_TO_PUBLISH' => '',
    'LBL_ERROR_UNABLE_TO_UNPUBLISH' => '',
    'LBL_ERROR_IMPORTS_NOT_SET_UP' => '',
    'LBL_IMPORT_TYPE' => '',
    'LBL_IMPORT_BUTTON' => '',
    'LBL_UPDATE_BUTTON' => '',
    'LBL_CREATE_BUTTON_HELP' => '',
    'LBL_UPDATE_BUTTON_HELP' => '',
    'LBL_ERROR_INVALID_BOOL' => '',
    'LBL_IMPORT_ERROR' => '',
    'LBL_ERROR' => '',
    'LBL_FIELD_NAME' => '',
    'LBL_VALUE' => '',
    'LBL_NONE' => '',
    'LBL_REQUIRED_VALUE' => '',
    'LBL_ERROR_SYNC_USERS' => '',
    'LBL_ID_EXISTS_ALREADY' => '',
    'LBL_ASSIGNED_USER' => '',
    'LBL_ERROR_DELETING_RECORD' => '',
    'LBL_ERROR_INVALID_ID' => '',
    'LBL_ERROR_INVALID_PHONE' => '',
    'LBL_ERROR_INVALID_NAME' => '',
    'LBL_ERROR_INVALID_VARCHAR' => '',
    'LBL_ERROR_INVALID_DATE' => '',
    'LBL_ERROR_INVALID_DATETIME' => '',
    'LBL_ERROR_INVALID_DATETIMECOMBO' => '',
    'LBL_ERROR_INVALID_TIME' => '',
    'LBL_ERROR_INVALID_INT' => '',
    'LBL_ERROR_INVALID_NUM' => '',
    'LBL_ERROR_INVALID_EMAIL' => '',
    'LBL_ERROR_INVALID_USER' => '',
    'LBL_ERROR_INVALID_TEAM' => '',
    'LBL_ERROR_INVALID_ACCOUNT' => '',
    'LBL_ERROR_INVALID_RELATE' => '',
    'LBL_ERROR_INVALID_CURRENCY' => '',
    'LBL_ERROR_INVALID_FLOAT' => '',
    'LBL_ERROR_NOT_IN_ENUM' => '',
    'LBL_IMPORT_MODULE_ERROR_NO_UPLOAD' => '',
    'LBL_MODULE_NAME' => '',
    'LBL_TRY_AGAIN' => '',
    'LBL_IMPORT_ERROR_MAX_REC_LIMIT_REACHED' => '',
    'ERR_IMPORT_SYSTEM_ADMININSTRATOR' => '',
    'ERR_MULTIPLE' => '',
    'ERR_MISSING_REQUIRED_FIELDS' => '',
    'ERR_SELECT_FILE' => '',
    'LBL_SELECT_FILE' => '',
    'LBL_EXTERNAL_SOURCE' => '',
    'LBL_CUSTOM_DELIMITER' => '',
    'LBL_DONT_MAP' => '',
    'LBL_STEP_MODULE' => '',
    'LBL_STEP_1_TITLE' => '',
    'LBL_CONFIRM_TITLE' => '',
    'LBL_MICROSOFT_OUTLOOK' => '',
    'LBL_MICROSOFT_OUTLOOK_HELP' => '',
    'LBL_SALESFORCE' => '',
    'LBL_PUBLISH' => '',
    'LBL_DELETE' => '',
    'LBL_PUBLISHED_SOURCES' => '',
    'LBL_UNPUBLISH' => '',
    'LBL_NEXT' => '',
    'LBL_BACK' => '',
    'LBL_STEP_2_TITLE' => '',
    'LBL_HAS_HEADER' => '',
    'LBL_NUM_1' => '1.',
    'LBL_NUM_2' => '2.',
    'LBL_NUM_3' => '3.',
    'LBL_NUM_4' => '4.',
    'LBL_NUM_5' => '',
    'LBL_NUM_6' => '',
    'LBL_NUM_7' => '',
    'LBL_NUM_8' => '8.',
    'LBL_NUM_9' => '9.',
    'LBL_NUM_10' => '10.',
    'LBL_NUM_11' => '11.',
    'LBL_NUM_12' => '',
    'LBL_NOTES' => '',
    'LBL_STEP_3_TITLE' => '',
    'LBL_STEP_DUP_TITLE' => '',
    'LBL_DATABASE_FIELD' => '',
    'LBL_HEADER_ROW' => '',
    'LBL_HEADER_ROW_OPTION_HELP' => '',
    'LBL_ROW' => '',
    'LBL_CONTACTS_NOTE_1' => '',
    'LBL_CONTACTS_NOTE_2' => '',
    'LBL_CONTACTS_NOTE_3' => '',
    'LBL_CONTACTS_NOTE_4' => '',
    'LBL_ACCOUNTS_NOTE_1' => '',
    'LBL_IMPORT_NOW' => '',
    'LBL_' => '',
    'LBL_CANNOT_OPEN' => '',
    'LBL_NO_LINES' => '',
    'LBL_SUCCESS' => '',
    'LBL_LAST_IMPORT_UNDONE' => '',
    'LBL_NO_IMPORT_TO_UNDO' => '',
    'LBL_CREATED_TAB' => '',
    'LBL_DUPLICATE_TAB' => '',
    'LBL_ERROR_TAB' => '',
    'LBL_IMPORT_MORE' => '',
    'LBL_FINISHED' => '',
    'LBL_UNDO_LAST_IMPORT' => '',
    'LBL_DUPLICATES' => '',
    'LNK_DUPLICATE_LIST' => '',
    'LNK_ERROR_LIST' => '',
    'LNK_RECORDS_SKIPPED_DUE_TO_ERROR' => '',
    'LBL_INDEX_USED' => '',
    'LBL_INDEX_NOT_USED' => '',
    'LBL_IMPORT_FIELDDEF_ID' => '',
    'LBL_IMPORT_FIELDDEF_RELATE' => '',
    'LBL_IMPORT_FIELDDEF_PHONE' => '',
    'LBL_IMPORT_FIELDDEF_TEAM_LIST' => '',
    'LBL_IMPORT_FIELDDEF_NAME' => '',
    'LBL_IMPORT_FIELDDEF_VARCHAR' => '',
    'LBL_IMPORT_FIELDDEF_TEXT' => '',
    'LBL_IMPORT_FIELDDEF_TIME' => '',
    'LBL_IMPORT_FIELDDEF_DATE' => '',
    'LBL_IMPORT_FIELDDEF_ASSIGNED_USER_NAME' => '',
    'LBL_IMPORT_FIELDDEF_BOOL' => '',
    'LBL_IMPORT_FIELDDEF_ENUM' => '',
    'LBL_IMPORT_FIELDDEF_EMAIL' => '',
    'LBL_IMPORT_FIELDDEF_INT' => '',
    'LBL_IMPORT_FIELDDEF_DOUBLE' => '',
    'LBL_IMPORT_FIELDDEF_NUM' => '',
    'LBL_IMPORT_FIELDDEF_CURRENCY' => '',
    'LBL_IMPORT_FIELDDEF_FLOAT' => '',
    'LBL_DATE_FORMAT' => '',
    'LBL_TIME_FORMAT' => '',
    'LBL_TIMEZONE' => '',
    'LBL_ADD_ROW' => '',
    'LBL_REMOVE_ROW' => '',
    'LBL_DEFAULT_VALUE' => '',
    'LBL_SHOW_ADVANCED_OPTIONS' => '',
    'LBL_HIDE_ADVANCED_OPTIONS' => '',
    'LBL_SHOW_NOTES' => '',
    'LBL_HIDE_NOTES' => '',
    'LBL_SAVE_MAPPING_AS' => '',
    'LBL_IMPORT_COMPLETE' => '',
    'LBL_IMPORT_COMPLETED' => '',
    'LBL_IMPORT_RECORDS' => '',
    'LBL_IMPORT_RECORDS_OF' => '',
    'LBL_IMPORT_RECORDS_TO' => '',
    'LBL_CURRENCY' => '',
    'LBL_CURRENCY_SIG_DIGITS' => '',
    'LBL_NUMBER_GROUPING_SEP' => '',
    'LBL_DECIMAL_SEP' => '',
    'LBL_LOCALE_DEFAULT_NAME_FORMAT' => '',
    'LBL_LOCALE_EXAMPLE_NAME_FORMAT' => '',
    'LBL_LOCALE_NAME_FORMAT_DESC' => '',
    'LBL_CHARSET' => '',
    'LBL_MY_SAVED_HELP' => '',
    'LBL_MY_SAVED_ADMIN_HELP' => '',
    'LBL_ENCLOSURE_HELP' => '',
    'LBL_DATABASE_FIELD_HELP' => '',
    'LBL_HEADER_ROW_HELP' => '',
    'LBL_DEFAULT_VALUE_HELP' => '',
    'LBL_ROW_HELP' => '',
    'LBL_SAVE_MAPPING_HELP' => '',
    'LBL_IMPORT_FILE_SETTINGS_HELP' => '',
    'LBL_IMPORT_STARTED' => '',
    'LBL_RECORD_CANNOT_BE_UPDATED' => '',
    'LBL_DELETE_MAP_CONFIRMATION' => '',
    'LBL_THIRD_PARTY_CSV_SOURCES' => '',
    'LBL_THIRD_PARTY_CSV_SOURCES_HELP' => '',
    'LBL_EXAMPLE_FILE' => '',
    'LBL_CONFIRM_IMPORT' => '',
    'LBL_CONFIRM_MAP_OVERRIDE' => '',
    'LBL_SAMPLE_URL_HELP' => '',
    'LBL_AUTO_DETECT_ERROR' => '',
    'LBL_MIME_TYPE_ERROR_1' => '',
    'LBL_MIME_TYPE_ERROR_2' => '',
    'LBL_FIELD_DELIMETED_HELP' => '',
    'LBL_FILE_UPLOAD_WIDGET_HELP' => '',
    'LBL_ERROR_IMPORT_CACHE_NOT_WRITABLE' => '',
    'LBL_ADD_FIELD_HELP' => '',
    'LBL_MISSING_HEADER_ROW' => '',
    'LBL_CANCEL' => '',
    'LBL_SELECT_DS_INSTRUCTION' => '',
    'LBL_SELECT_UPLOAD_INSTRUCTION' => '',
    'LBL_SELECT_PROPERTY_INSTRUCTION' => '',
    'LBL_SELECT_MAPPING_INSTRUCTION' => '',
    'LBL_SELECT_DUPLICATE_INSTRUCTION' => '',
    'LBL_DUP_HELP' => '',
    'LBL_SUMMARY' => '',
    'LBL_OK' => '',
    'LBL_ERROR_HELP' => '',
    'LBL_EXTERNAL_ASSIGNED_TOOLTIP' => '',
    'LBL_EXTERNAL_TEAM_TOOLTIP' => '',
    // STIC-Custom 20221103 MHP - STIC#904
    'LBL_ERROR_CYCLIC_DEPENDENCY' => '',
    // END STIC-Custom
);

global $timedate;
