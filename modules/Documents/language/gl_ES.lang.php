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
    //module
    'LBL_MODULE_NAME' => '',
    'LBL_MODULE_TITLE' => '',
    'LNK_NEW_DOCUMENT' => '',
    'LNK_DOCUMENT_LIST' => '',
    'LBL_DOC_REV_HEADER' => '',
    'LBL_SEARCH_FORM_TITLE' => '',
    //vardef labels
    'LBL_NAME' => '',
    'LBL_DESCRIPTION' => '',
    'LBL_CATEGORY' => '',
    'LBL_SUBCATEGORY' => '',
    'LBL_STATUS' => '',
    'LBL_CREATED_BY' => '',
    'LBL_DATE_ENTERED' => '',
    'LBL_DATE_MODIFIED' => '',
    'LBL_DELETED' => '',
    'LBL_MODIFIED' => '',
    'LBL_MODIFIED_USER' => '',
    'LBL_CREATED' => '',
    'LBL_REVISIONS' => '',
    'LBL_RELATED_DOCUMENT_ID' => '',
    'LBL_RELATED_DOCUMENT_REVISION_ID' => '',
    'LBL_IS_TEMPLATE' => '',
    'LBL_TEMPLATE_TYPE' => '',
    'LBL_ASSIGNED_TO_NAME' => '',
    'LBL_REVISION_NAME' => '',
    'LBL_MIME' => '',
    'LBL_REVISION' => '',
    'LBL_DOCUMENT' => '',
    'LBL_LATEST_REVISION' => '',
    'LBL_CHANGE_LOG' => '',
    'LBL_ACTIVE_DATE' => '',
    'LBL_EXPIRATION_DATE' => '',
    'LBL_FILE_EXTENSION' => '',
    'LBL_LAST_REV_MIME_TYPE' => '',
    'LBL_CAT_OR_SUBCAT_UNSPEC' => '',
    'LBL_HOMEPAGE_TITLE' => '',
    //quick search
    'LBL_NEW_FORM_TITLE' => '',
    //document edit and detail view
    'LBL_DOC_NAME' => '',
    'LBL_FILENAME' => '',
    'LBL_LIST_FILENAME' => '',
    'LBL_DOC_VERSION' => '',
    'LBL_FILE_UPLOAD' => '',

    'LBL_CATEGORY_VALUE' => '',
    'LBL_LIST_CATEGORY' => '',
    'LBL_SUBCATEGORY_VALUE' => '',
    'LBL_DOC_STATUS' => '',
    'LBL_LAST_REV_CREATOR' => '',
    'LBL_LASTEST_REVISION_NAME' => '',
    'LBL_SELECTED_REVISION_NAME' => '',
    'LBL_CONTRACT_STATUS' => '',
    'LBL_CONTRACT_NAME' => '',
    'LBL_DET_RELATED_DOCUMENT' => '',
    'LBL_DET_RELATED_DOCUMENT_VERSION' => "",
    'LBL_DET_IS_TEMPLATE' => '',
    'LBL_DET_TEMPLATE_TYPE' => '',
    'LBL_DOC_DESCRIPTION' => '',
    'LBL_DOC_ACTIVE_DATE' => '',
    'LBL_DOC_EXP_DATE' => '',

    //document list view.
    'LBL_LIST_FORM_TITLE' => '',
    'LBL_LIST_DOCUMENT' => '',
    'LBL_LIST_SUBCATEGORY' => '',
    'LBL_LIST_REVISION' => '',
    'LBL_LIST_LAST_REV_CREATOR' => '',
    'LBL_LIST_LAST_REV_DATE' => '',
    'LBL_LIST_VIEW_DOCUMENT' => '',
    'LBL_LIST_ACTIVE_DATE' => '',
    'LBL_LIST_EXP_DATE' => '',
    'LBL_LIST_STATUS' => '',
    'LBL_LINKED_ID' => '',
    'LBL_SELECTED_REVISION_ID' => '',
    'LBL_LATEST_REVISION_ID' => '',
    'LBL_SELECTED_REVISION_FILENAME' => '',
    'LBL_FILE_URL' => '',

    //document search form.
    'LBL_SF_CATEGORY' => '',
    'LBL_SF_SUBCATEGORY' => '',

    'DEF_CREATE_LOG' => '',

    //error messages
    'ERR_DOC_NAME' => '',
    'ERR_DOC_ACTIVE_DATE' => '',
    'ERR_FILENAME' => '',
    'ERR_DOC_VERSION' => '',
    'ERR_DELETE_CONFIRM' => '',
    'ERR_DELETE_LATEST_VERSION' => '',
    'LNK_NEW_MAIL_MERGE' => '',
    'ERR_MISSING_FILE' => '',

    //sub-panel vardefs.
    'LBL_LIST_DOCUMENT_NAME' => '',
    'LBL_LIST_IS_TEMPLATE' => '',
    'LBL_LIST_TEMPLATE_TYPE' => '',
    'LBL_LAST_REV_CREATE_DATE' => '',
    'LBL_CONTRACTS' => '',
    'LBL_CREATED_USER' => '',
    'LBL_DOCUMENT_INFORMATION' => '', //Can be translated in all caps. This string will be used by SuiteP template menu actions
    'LBL_DOC_ID' => '',
    'LBL_DOC_TYPE' => '',
    'LBL_DOC_TYPE_POPUP' => '',
    'LBL_DOC_URL' => '',
    'LBL_SEARCH_EXTERNAL_DOCUMENT' => '',
    'LBL_EXTERNAL_DOCUMENT_NOTE' => '',
    'LBL_LIST_EXT_DOCUMENT_NAME' => '',
    'ERR_INVALID_EXTERNAL_API_ACCESS' => '',
    'ERR_INVALID_EXTERNAL_API_LOGIN' => '',

    // Links around the world
    'LBL_ACCOUNTS_SUBPANEL_TITLE' => '',
    'LBL_CONTACTS_SUBPANEL_TITLE' => '',
    'LBL_OPPORTUNITIES_SUBPANEL_TITLE' => '',
    'LBL_CASES_SUBPANEL_TITLE' => '',
    'LBL_BUGS_SUBPANEL_TITLE' => '',

    'LBL_AOS_CONTRACTS' => '',
);