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
    /*'ADMIN_EXPORT_ONLY'=>'Admin export only',*/
    'ADVANCED' => '',
    'DEFAULT_CURRENCY_ISO4217' => '',
    'DEFAULT_CURRENCY_NAME' => '',
    'DEFAULT_CURRENCY_SYMBOL' => '',
    'DEFAULT_DATE_FORMAT' => '',
    'DEFAULT_DECIMAL_SEP' => '',
    'DEFAULT_LANGUAGE' => '',
    'DEFAULT_SYSTEM_SETTINGS' => '',
    'DEFAULT_THEME' => '',
    'DEFAULT_TIME_FORMAT' => '',
    'DISPLAY_RESPONSE_TIME' => '',
    'IMAGES' => '',
    'LBL_ALLOW_USER_TABS' => '',
    'LBL_CONFIGURE_SETTINGS_TITLE' => '',
    'LBL_LOGVIEW' => '',
    'LBL_MAIL_SMTPAUTH_REQ' => '',
    'LBL_MAIL_SMTPPASS' => '',
    'LBL_MAIL_SMTPPORT' => '',
    'LBL_MAIL_SMTPSERVER' => '',
    'LBL_MAIL_SMTPUSER' => '',
    'LBL_MAIL_SMTP_SETTINGS' => '',
    'LBL_CHOOSE_EMAIL_PROVIDER' => '',
    'LBL_YAHOOMAIL_SMTPPASS' => '',
    'LBL_YAHOOMAIL_SMTPUSER' => '',
    'LBL_GMAIL_SMTPPASS' => '',
    'LBL_GMAIL_SMTPUSER' => '',
    'LBL_EXCHANGE_SMTPPASS' => '',
    'LBL_EXCHANGE_SMTPUSER' => '',
    'LBL_EXCHANGE_SMTPPORT' => '',
    'LBL_EXCHANGE_SMTPSERVER' => '',
    'LBL_ALLOW_DEFAULT_SELECTION' => '',
    'LBL_ALLOW_DEFAULT_SELECTION_HELP' => '',
    'LBL_MAILMERGE' => '',
    'LBL_MIN_AUTO_REFRESH_INTERVAL' => '',
    'LBL_MIN_AUTO_REFRESH_INTERVAL_HELP' => '',
    'LBL_MODULE_FAVICON' => '',
    'LBL_MODULE_FAVICON_HELP' => '',
    'LBL_MODULE_NAME' => '',
    'LBL_MODULE_ID' => '',
    'LBL_MODULE_TITLE' => '',
    'LBL_NOTIFY_FROMADDRESS' => '',
    'LBL_NOTIFY_SUBJECT' => '',
    'LBL_PROXY_AUTH' => '',
    'LBL_PROXY_HOST' => '',
    'LBL_PROXY_ON_DESC' => '',
    'LBL_PROXY_ON' => '',
    'LBL_PROXY_PASSWORD' => '',
    'LBL_PROXY_PORT' => '',
    'LBL_PROXY_TITLE' => '',
    'LBL_PROXY_USERNAME' => '',
    'LBL_RESTORE_BUTTON_LABEL' => '',
    'LBL_SYSTEM_SETTINGS' => '',
    'LBL_USE_REAL_NAMES' => '',
    'LBL_USE_REAL_NAMES_DESC' => '',
    'LBL_DISALBE_CONVERT_LEAD' => '',
    'LBL_DISALBE_CONVERT_LEAD_DESC' => '',
    'LBL_ENABLE_ACTION_MENU' => '',
    'LBL_ENABLE_ACTION_MENU_DESC' => '',
    'LBL_ENABLE_INLINE_EDITING_LIST' => '',
    'LBL_ENABLE_INLINE_EDITING_LIST_DESC' => '',
    'LBL_ENABLE_INLINE_EDITING_DETAIL' => '',
    'LBL_ENABLE_INLINE_EDITING_DETAIL_DESC' => '',
    'LBL_HIDE_SUBPANELS' => '',
    'LIST_ENTRIES_PER_LISTVIEW' => '',
    'LIST_ENTRIES_PER_SUBPANEL' => '',
    'LOG_MEMORY_USAGE' => '',
    'LOG_SLOW_QUERIES' => '',
    'CURRENT_LOGO' => '',
    'CURRENT_LOGO_HELP' => '',
    'NEW_LOGO' => '',
    'NEW_LOGO_HELP' => '',
    'NEW_LOGO_HELP_NO_SPACE' => '',
    'SLOW_QUERY_TIME_MSEC' => '',
    'STACK_TRACE_ERRORS' => '',
    'UPLOAD_MAX_SIZE' => '',
    'VERIFY_CLIENT_IP' => '',
    'LOCK_HOMEPAGE' => '',
    'LOCK_SUBPANELS' => '',
    'MAX_DASHLETS' => '',
    'SYSTEM_NAME' => '',
    'SYSTEM_NAME_WIZARD' => '',
    'SYSTEM_NAME_HELP' => '',
    'LBL_LDAP_TITLE' => '',
    'LBL_LDAP_ENABLE' => '',
    'LBL_LDAP_SERVER_HOSTNAME' => '',
    'LBL_LDAP_SERVER_PORT' => '',
    'LBL_LDAP_ADMIN_USER' => '',
    'LBL_LDAP_ADMIN_USER_DESC' => '',
    'LBL_LDAP_ADMIN_PASSWORD' => '',
    'LBL_LDAP_AUTHENTICATION' => '',
    'LBL_LDAP_AUTHENTICATION_DESC' => '',
    'LBL_LDAP_AUTO_CREATE_USERS' => '',
    'LBL_LDAP_USER_DN' => '',
    'LBL_LDAP_GROUP_DN' => '',
    'LBL_LDAP_GROUP_DN_DESC' => '',
    'LBL_LDAP_USER_FILTER' => '',
    'LBL_LDAP_GROUP_MEMBERSHIP' => '',
    'LBL_LDAP_GROUP_MEMBERSHIP_DESC' => '',
    'LBL_LDAP_GROUP_USER_ATTR' => '',
    'LBL_LDAP_GROUP_USER_ATTR_DESC' => '',
    'LBL_LDAP_GROUP_ATTR_DESC' => '',
    'LBL_LDAP_GROUP_ATTR' => '',
    'LBL_LDAP_USER_FILTER_DESC' => '',
    'LBL_LDAP_LOGIN_ATTRIBUTE' => '',
    'LBL_LDAP_BIND_ATTRIBUTE' => '',
    'LBL_LDAP_BIND_ATTRIBUTE_DESC' => '',
    'LBL_LDAP_LOGIN_ATTRIBUTE_DESC' => '',
    'LBL_LDAP_SERVER_HOSTNAME_DESC' => '',
    'LBL_LDAP_SERVER_PORT_DESC' => '',
    'LBL_LDAP_GROUP_NAME' => '',
    'LBL_LDAP_GROUP_NAME_DESC' => '',
    'LBL_LDAP_USER_DN_DESC' => '',
    'LBL_LDAP_AUTO_CREATE_USERS_DESC' => '',
    'LBL_LDAP_ENC_KEY' => '',
    'DEVELOPER_MODE' => '',

    'SHOW_DOWNLOADS_TAB' => '',
    'SHOW_DOWNLOADS_TAB_HELP' => '',
    'LBL_LDAP_ENC_KEY_DESC' => '',
    'LDAP_ENC_KEY_NO_FUNC_DESC' => '',
    'LDAP_ENC_KEY_NO_FUNC_OPENSSL_DESC' => '',
    'LBL_ALL' => '',
    'LBL_MARK_POINT' => '',
    'LBL_NEXT_' => '',
    'LBL_REFRESH_FROM_MARK' => '',
    'LBL_SEARCH' => '',
    'LBL_REG_EXP' => '',
    'LBL_IGNORE_SELF' => '',
    'LBL_MARKING_WHERE_START_LOGGING' => '',
    'LBL_DISPLAYING_LOG' => '',
    'LBL_YOUR_PROCESS_ID' => '',
    'LBL_YOUR_IP_ADDRESS' => '',
    'LBL_IT_WILL_BE_IGNORED' => '',
    'LBL_LOG_NOT_CHANGED' => '',
    'LBL_ALERT_JPG_IMAGE' => '',
    'LBL_ALERT_TYPE_IMAGE' => '',
    'LBL_ALERT_SIZE_RATIO' => '',
    'ERR_ALERT_FILE_UPLOAD' => '',
    'LBL_LOGGER' => '',
    'LBL_LOGGER_FILENAME' => '',
    'LBL_LOGGER_FILE_EXTENSION' => '',
    'LBL_LOGGER_MAX_LOG_SIZE' => '',
    'LBL_LOGGER_DEFAULT_DATE_FORMAT' => '',
    'LBL_LOGGER_LOG_LEVEL' => '',
    'LBL_LEAD_CONV_OPTION' => '',
    'LEAD_CONV_OPT_HELP' => "",
    'LBL_CONFIG_AJAX' => '',
    'LBL_CONFIG_AJAX_DESC' => '',
    'LBL_LOGGER_MAX_LOGS' => '',
    'LBL_LOGGER_FILENAME_SUFFIX' => '',
    'LBL_VCAL_PERIOD' => '',
    'LBL_IMPORT_MAX_RECORDS' => '',
    'LBL_IMPORT_MAX_RECORDS_HELP' => '',
    'vCAL_HELP' => '',
    'LBL_PDFMODULE_NAME' => '',
    'SUITEPDF_BASIC_SETTINGS' => '',
    'SUITEPDF_ADVANCED_SETTINGS' => '',
    'SUITEPDF_LOGO_SETTINGS' => '',

    'PDF_AUTHOR' => '',
    'PDF_AUTHOR_INFO' => '',

    'PDF_HEADER_LOGO' => '',
    'PDF_HEADER_LOGO_INFO' => '',

    'PDF_NEW_HEADER_LOGO' => '',
    'PDF_NEW_HEADER_LOGO_INFO' => '',

    'PDF_SMALL_HEADER_LOGO' => '',
    'PDF_SMALL_HEADER_LOGO_INFO' => '',

    'PDF_NEW_SMALL_HEADER_LOGO' => '',
    'PDF_NEW_SMALL_HEADER_LOGO_INFO' => '',

    'PDF_FILENAME' => '',
    'PDF_FILENAME_INFO' => '',

    'PDF_TITLE' => '',
    'PDF_TITLE_INFO' => '',

    'PDF_SUBJECT' => '',
    'PDF_SUBJECT_INFO' => '',

    'PDF_KEYWORDS' => '',
    'PDF_KEYWORDS_INFO' => '',

    'PDF_COMPRESSION' => '',
    'PDF_COMPRESSION_INFO' => '',

    'PDF_JPEG_QUALITY' => '',
    'PDF_JPEG_QUALITY_INFO' => '',

    'PDF_PDF_VERSION' => '',
    'PDF_PDF_VERSION_INFO' => '',

    'PDF_PROTECTION' => '',
    'PDF_PROTECTION_INFO' => '',

    'PDF_USER_PASSWORD' => '',
    'PDF_USER_PASSWORD_INFO' => '',

    'PDF_OWNER_PASSWORD' => '',
    'PDF_OWNER_PASSWORD_INFO' => '',

    'PDF_ACL_ACCESS' => '',
    'PDF_ACL_ACCESS_INFO' => '',

    'K_CELL_HEIGHT_RATIO' => '',
    'K_CELL_HEIGHT_RATIO_INFO' => '',

    'K_SMALL_RATIO' => '',
    'K_SMALL_RATIO_INFO' => '',

    'PDF_IMAGE_SCALE_RATIO' => '',
    'PDF_IMAGE_SCALE_RATIO_INFO' => '',

    'PDF_UNIT' => '',
    'PDF_UNIT_INFO' => '',
    'PDF_GD_WARNING' => '',
    'ERR_EZPDF_DISABLE' => '',
    'LBL_IMG_RESIZED' => "",


    'LBL_FONTMANAGER_BUTTON' => '',
    'LBL_FONTMANAGER_TITLE' => '',
    'LBL_FONT_BOLD' => '',
    'LBL_FONT_ITALIC' => '',
    'LBL_FONT_BOLDITALIC' => '',
    'LBL_FONT_REGULAR' => '',

    'LBL_FONT_TYPE_CID0' => '',
    'LBL_FONT_TYPE_CORE' => '',
    'LBL_FONT_TYPE_TRUETYPE' => '',
    'LBL_FONT_TYPE_TYPE1' => '',
    'LBL_FONT_TYPE_TRUETYPEU' => '',

    'LBL_FONT_LIST_NAME' => '',
    'LBL_FONT_LIST_FILENAME' => '',
    'LBL_FONT_LIST_TYPE' => '',
    'LBL_FONT_LIST_STYLE' => '',
    'LBL_FONT_LIST_STYLE_INFO' => '',
    'LBL_FONT_LIST_ENC' => '',
    'LBL_FONT_LIST_EMBEDDED' => '',
    'LBL_FONT_LIST_EMBEDDED_INFO' => '',
    'LBL_FONT_LIST_CIDINFO' => '',
    'LBL_FONT_LIST_CIDINFO_INFO' => '',
    'LBL_FONT_LIST_FILESIZE' => '',
    'LBL_ADD_FONT' => '',
    'LBL_BACK' => '',
    'LBL_REMOVE' => '',
    'LBL_JS_CONFIRM_DELETE_FONT' => '',

    'LBL_ADDFONT_TITLE' => '',
    'LBL_PDF_ENCODING_TABLE' => '',
    'LBL_PDF_ENCODING_TABLE_INFO' => '',
    'LBL_PDF_FONT_FILE' => '',
    'LBL_PDF_FONT_FILE_INFO' => '',
    'LBL_PDF_METRIC_FILE' => '',
    'LBL_PDF_METRIC_FILE_INFO' => '',
    'LBL_ADD_FONT_BUTTON' => '',
    'JS_ALERT_PDF_WRONG_EXTENSION' => '',

    'ERR_MISSING_CIDINFO' => '',
    'LBL_ADDFONTRESULT_TITLE' => '',
    'LBL_STATUS_FONT_SUCCESS' => '',
    'LBL_STATUS_FONT_ERROR' => '',

// Font manager
    'ERR_PDF_NO_UPLOAD' => '',

// Wizard
    //Wizard Scenarios
    'LBL_WIZARD_SCENARIOS' => '',
    'LBL_WIZARD_SCENARIOS_EMPTY_LIST' => '',
    'LBL_WIZARD_SCENARIOS_DESC' => '',

    'LBL_WIZARD_TITLE' => '',
    'LBL_WIZARD_WELCOME_TAB' => '',
    'LBL_WIZARD_WELCOME_TITLE' => '',
    'LBL_WIZARD_WELCOME' => '',
    'LBL_WIZARD_NEXT_BUTTON' => '',
    'LBL_WIZARD_BACK_BUTTON' => '',
    'LBL_WIZARD_SKIP_BUTTON' => '',
    'LBL_WIZARD_CONTINUE_BUTTON' => '',
    'LBL_WIZARD_FINISH_TITLE' => '',
    'LBL_WIZARD_SYSTEM_TITLE' => '',
    'LBL_WIZARD_SYSTEM_DESC' => '',
    'LBL_WIZARD_LOCALE_DESC' => '',
    'LBL_WIZARD_SMTP_DESC' => '',
    'LBL_LOADING' => '' /*for 508 compliance fix*/,
    'LBL_DELETE' => '' /*for 508 compliance fix*/,
    'LBL_WELCOME' => '' /*for 508 compliance fix*/,
    'LBL_LOGO' => '' /*for 508 compliance fix*/,
    'LBL_ENABLE_HISTORY_CONTACTS_EMAILS' => '',

    // Google auth PR 6146
    'LBL_GOOGLE_AUTH_TITLE' => '',
    'LBL_GOOGLE_AUTH_JSON' => '',
    'LBL_GOOGLE_AUTH_JSON_HELP' => '',

);
