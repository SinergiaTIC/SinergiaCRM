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
    'ERR_UW_CANNOT_DETERMINE_GROUP' => '',
    'ERR_UW_CANNOT_DETERMINE_USER' => '',
    'ERR_UW_CONFIG_WRITE' => '',
    'ERR_UW_CONFIG' => '',
    'ERR_UW_DIR_NOT_WRITABLE' => '',
    'ERR_UW_FILE_NOT_COPIED' => '',
    'ERR_UW_FILE_NOT_DELETED' => '',
    'ERR_UW_FILE_NOT_READABLE' => '',
    'ERR_UW_FILE_NOT_WRITABLE' => '',
    'ERR_UW_FLAVOR_2' => '',
    'ERR_UW_FLAVOR' => '',
    'ERR_UW_LOG_FILE_UNWRITABLE' => '',
    'ERR_UW_MBSTRING_FUNC_OVERLOAD' => '',
    'ERR_UW_NO_FILE_UPLOADED' => '',
    'ERR_UW_NO_FILES' => '',
    'ERR_UW_NO_MANIFEST' => '',
    'ERR_UW_NO_VIEW' => '',
    'ERR_UW_NOT_VALID_UPLOAD' => '',
    'ERR_UW_NO_CREATE_TMP_DIR' => '',
    'ERR_UW_ONLY_PATCHES' => '',
    'ERR_UW_PREFLIGHT_ERRORS' => '',
    'ERR_UW_UPLOAD_ERR' => '' . PHP_EOL,
    'ERR_UW_VERSION' => '',
    'ERR_UW_PHP_VERSION' => '',
    'ERR_UW_SUITECRM_VERSION' => '',
    'ERR_UW_WRONG_TYPE' => '',
    'LBL_BUTTON_BACK' => '',
    'LBL_BUTTON_CANCEL' => '',
    'LBL_BUTTON_DELETE' => '',
    'LBL_BUTTON_DONE' => '',
    'LBL_BUTTON_EXIT' => '',
    'LBL_BUTTON_NEXT' => '',
    'LBL_BUTTON_RECHECK' => '',
    'LBL_BUTTON_RESTART' => '',

    'LBL_UPLOAD_UPGRADE' => '',
    'LBL_UW_BACKUP_FILES_EXIST_TITLE' => '',
    'LBL_UW_BACKUP_FILES_EXIST' => '',
    'LBL_UW_BACKUP' => '',
    'LBL_UW_CANCEL_DESC' => '',
    'LBL_UW_CHECK_ALL' => '',
    'LBL_UW_CHECKLIST' => '',
    'LBL_UW_COMMIT_ADD_TASK_DESC_1' => '' . PHP_EOL,
    'LBL_UW_COMMIT_ADD_TASK_DESC_2' => '' . PHP_EOL,
    'LBL_UW_COMMIT_ADD_TASK_NAME' => '',
    'LBL_UW_COMMIT_ADD_TASK_OVERVIEW' => '',
    'LBL_UW_COMPLETE' => '',
    'LBL_UW_COMPLIANCE_ALL_OK' => '',
    'LBL_UW_COMPLIANCE_CALLTIME' => '',
    'LBL_UW_COMPLIANCE_CURL' => '',
    'LBL_UW_COMPLIANCE_IMAP' => '',
    'LBL_UW_COMPLIANCE_MBSTRING' => '',
    'LBL_UW_COMPLIANCE_MBSTRING_FUNC_OVERLOAD' => '',
    'LBL_UW_COMPLIANCE_MEMORY' => '',
    'LBL_UW_COMPLIANCE_STREAM' => '',
    'LBL_UW_COMPLIANCE_DB' => '',
    'LBL_UW_COMPLIANCE_PHP_INI' => '',
    'LBL_UW_COMPLIANCE_PHP_VERSION' => '',
    'LBL_UW_COMPLIANCE_SAFEMODE' => '',
    'LBL_UW_COMPLIANCE_TITLE2' => '',
    'LBL_UW_COMPLIANCE_XML' => '',
    'LBL_UW_COMPLIANCE_ZIPARCHIVE' => '',
    'LBL_UW_COMPLIANCE_PCRE_VERSION' => '',
    'LBL_UW_COPIED_FILES_TITLE' => '',

    'LBL_UW_DB_CHOICE1' => '',
    'LBL_UW_DB_CHOICE2' => '',
    'LBL_UW_DB_ISSUES_PERMS' => '',
    'LBL_UW_DB_METHOD' => '',
    'LBL_UW_DB_NO_ADD_COLUMN' => '',
    'LBL_UW_DB_NO_CHANGE_COLUMN' => '',
    'LBL_UW_DB_NO_CREATE' => '',
    'LBL_UW_DB_NO_DELETE' => '',
    'LBL_UW_DB_NO_DROP_COLUMN' => '',
    'LBL_UW_DB_NO_DROP_TABLE' => '',
    'LBL_UW_DB_NO_ERRORS' => '',
    'LBL_UW_DB_NO_INSERT' => '',
    'LBL_UW_DB_NO_SELECT' => '',
    'LBL_UW_DB_NO_UPDATE' => '',
    'LBL_UW_DB_PERMS' => '',

    'LBL_UW_DESC_MODULES_INSTALLED' => '',
    'LBL_UW_END_LOGOUT_PRE' => '',
    'LBL_UW_END_LOGOUT_PRE2' => '',
    'LBL_UW_END_LOGOUT' => '',

    'LBL_UW_FILE_DELETED' => '',
    'LBL_UW_FILE_GROUP' => '',
    'LBL_UW_FILE_ISSUES_PERMS' => '',
    'LBL_UW_FILE_NO_ERRORS' => '',
    'LBL_UW_FILE_OWNER' => '',
    'LBL_UW_FILE_PERMS' => '',
    'LBL_UW_FILE_UPLOADED' => '',
    'LBL_UW_FILE' => '',
    'LBL_UW_FILES_QUEUED' => '',
    'LBL_UW_FILES_REMOVED' => '' . PHP_EOL,
    'LBL_UW_NEXT_TO_UPLOAD' => '',
    'LBL_UW_FROZEN' => '',
    'LBL_UW_HIDE_DETAILS' => '',
    'LBL_UW_IN_PROGRESS' => '',
    'LBL_UW_INCLUDING' => '',
    'LBL_UW_INCOMPLETE' => '',
    'LBL_UW_MANUAL_MERGE' => '',
    'LBL_UW_MODULE_READY' => '',
    'LBL_UW_NO_INSTALLED_UPGRADES' => '',
    'LBL_UW_NONE' => '',
    'LBL_UW_OVERWRITE_DESC' => '',

    'LBL_UW_PREFLIGHT_ADD_TASK' => '',
    'LBL_UW_PREFLIGHT_EMAIL_REMINDER' => '',
    'LBL_UW_PREFLIGHT_FILES_DESC' => '',
    'LBL_UW_PREFLIGHT_NO_DIFFS' => '',
    'LBL_UW_PREFLIGHT_NOT_NEEDED' => '',
    'LBL_UW_PREFLIGHT_PRESERVE_FILES' => '',
    'LBL_UW_PREFLIGHT_TESTS_PASSED' => '',
    'LBL_UW_PREFLIGHT_TESTS_PASSED2' => '',
    'LBL_UW_PREFLIGHT_TESTS_PASSED3' => '',
    'LBL_UW_PREFLIGHT_TOGGLE_ALL' => '',

    'LBL_UW_REBUILD_TITLE' => '',
    'LBL_UW_SCHEMA_CHANGE' => '',

    'LBL_UW_SHOW_COMPLIANCE' => '',
    'LBL_UW_SHOW_DB_PERMS' => '',
    'LBL_UW_SHOW_DETAILS' => '',
    'LBL_UW_SHOW_DIFFS' => '',
    'LBL_UW_SHOW_NW_FILES' => '',
    'LBL_UW_SHOW_SCHEMA' => '',
    'LBL_UW_SHOW_SQL_ERRORS' => '',
    'LBL_UW_SHOW' => '',

    'LBL_UW_SKIPPED_FILES_TITLE' => '',
    'LBL_UW_SQL_RUN' => '',
    'LBL_UW_START_DESC' => '',
    'LBL_UW_START_DESC2' => '', // Keep the <pre>composer install --no-dev</pre> words at the end of the sentence and do not translate it
    'LBL_UW_START_DESC3' => '',
    'LBL_UW_START_UPGRADED_UW_DESC' => '',
    'LBL_UW_START_UPGRADED_UW_TITLE' => '',

    'LBL_UW_TITLE_CANCEL' => '',
    'LBL_UW_TITLE_COMMIT' => '',
    'LBL_UW_TITLE_END' => '',
    'LBL_UW_TITLE_PREFLIGHT' => '',
    'LBL_UW_TITLE_START' => '',
    'LBL_UW_TITLE_SYSTEM_CHECK' => '',
    'LBL_UW_TITLE_UPLOAD' => '',
    'LBL_UW_TITLE' => '',
    'LBL_UW_UNINSTALL' => '',
    //500 upgrade labels
    'LBL_UW_ACCEPT_THE_LICENSE' => '',
    'LBL_UW_CONVERT_THE_LICENSE' => '',

    'LBL_START_UPGRADE_IN_PROGRESS' => '',
    'LBL_SYSTEM_CHECKS_IN_PROGRESS' => '',
    'LBL_LICENSE_CHECK_IN_PROGRESS' => '',
    'LBL_PREFLIGHT_CHECK_IN_PROGRESS' => '',
    'LBL_PREFLIGHT_FILE_COPYING_PROGRESS' => '',
    'LBL_COMMIT_UPGRADE_IN_PROGRESS' => '',
    'LBL_UW_COMMIT_DESC' => '',
    'LBL_UPGRADE_SCRIPTS_IN_PROGRESS' => '',
    'LBL_UPGRADE_SUMMARY_IN_PROGRESS' => '',
    'LBL_UPGRADE_IN_PROGRESS' => '',
    'LBL_UPGRADE_TIME_ELAPSED' => '',
    'LBL_UPGRADE_CANCEL_IN_PROGRESS' => '',
    'LBL_UPGRADE_TAKES_TIME_HAVE_PATIENCE' => '',
    'LBL_UPLOADE_UPGRADE_IN_PROGRESS' => '',
    'LBL_UPLOADING_UPGRADE_PACKAGE' => '',
    'LBL_UW_DROP_SCHEMA_UPGRADE_WIZARD' => '',
    'LBL_UW_DROP_SCHEMA_MANUAL' => '',
    'LBL_UW_DROP_SCHEMA_METHOD' => '',
    'LBL_UW_SHOW_OLD_SCHEMA_TO_DROP' => '',
    'LBL_UW_SKIPPED_QUERIES_ALREADY_EXIST' => '',
    'LBL_INCOMPATIBLE_PHP_VERSION' => '',
    'ERR_CHECKSYS_PHP_INVALID_VER' => '',
    'LBL_BACKWARD_COMPATIBILITY_ON' => '',
    //including some strings from moduleinstall that are used in Upgrade
    'LBL_ML_ACTION' => '',
    'LBL_ML_CANCEL' => '',
    'LBL_ML_COMMIT' => '',
    'LBL_ML_DESCRIPTION' => '',
    'LBL_ML_INSTALLED' => '',
    'LBL_ML_NAME' => '',
    'LBL_ML_PUBLISHED' => '',
    'LBL_ML_TYPE' => '',
    'LBL_ML_UNINSTALLABLE' => '',
    'LBL_ML_VERSION' => '',
    'LBL_ML_INSTALL' => '',
    //adding the string used in tracker. copying from homepage
    'LBL_CURRENT_PHP_VERSION' => '',
    'LBL_RECOMMENDED_PHP_VERSION_1' => '',
    'LBL_RECOMMENDED_PHP_VERSION_2' => '', // End of a sentence as in Recommended PHP version is version X.Y or above

    'LBL_MODULE_NAME' => '',
    'LBL_UPLOAD_SUCCESS' => '',
    'LBL_UW_TITLE_LAYOUTS' => '',
    'LBL_LAYOUT_MODULE_TITLE' => '',
    'LBL_LAYOUT_MERGE_DESC' => '',
    'LBL_LAYOUT_MERGE_TITLE' => '',
    'LBL_LAYOUT_MERGE_TITLE2' => '',
    'LBL_UW_CONFIRM_LAYOUTS' => '',
    'LBL_UW_CONFIRM_LAYOUT_RESULTS' => '',
    'LBL_UW_CONFIRM_LAYOUT_RESULTS_DESC' => '',
    'LBL_SELECT_FILE' => '',
    'ERROR_VERSION_INCOMPATIBLE' => '',
    'ERROR_PHP_VERSION_INCOMPATIBLE' => '',
    'ERROR_SUITECRM_VERSION_INCOMPATIBLE' => '',
    'LBL_LANGPACKS' => '' /*for 508 compliance fix*/,
    'LBL_MODULELOADER' => '' /*for 508 compliance fix*/,
    'LBL_PATCHUPGRADES' => '' /*for 508 compliance fix*/,
    'LBL_THEMES' => '' /*for 508 compliance fix*/,
    'LBL_WORKFLOW' => '' /*for 508 compliance fix*/,
    'LBL_UPGRADE' => '' /*for 508 compliance fix*/,
    'LBL_PROCESSING' => '' /*for 508 compliance fix*/,
    'ERROR_NO_VERSION_SET' => '',
    'LBL_UPGRD_CSTM_CHK' => '',
    'ERR_UW_PHP_FILE_ERRORS' => array(
        1 => '',
        2 => '',
        3 => '',
        4 => '',
        5 => '',
        6 => '',
        7 => '',
        8 => '',
    ),
    'LBL_PASSWORD_EXPIRATON_CHANGED' => '',
    'LBL_PASSWORD_EXPIRATON_REDIRECT' => '',
);
