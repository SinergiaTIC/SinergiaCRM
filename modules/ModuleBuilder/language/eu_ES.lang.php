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
    'LBL_LOADING' => '' /*for 508 compliance fix*/,
    'LBL_HIDEOPTIONS' => '' /*for 508 compliance fix*/,
    'LBL_DELETE' => '' /*for 508 compliance fix*/,
    'help' => array(
        'package' => array(
            'create' => '',
            'modify' => '',
            'name' => '',
            'author' => '',
            'description' => '',
            'publishbtn' => '',
            'deploybtn' => '',
            'duplicatebtn' => '',
            'exportbtn' => '',
            'deletebtn' => '',
            'savebtn' => '',
            'existing_module' => '',
            'new_module' => '',
            'key' => '',
            'readme' => '',

        ),
        'main' => array(),
        'module' => array(
            'create' => '',
            'modify' => '',
            'importable' => '',
            'team_security' => '',
            'reportable' => '',
            'assignable' => '',
            'has_tab' => '',
            'acl' => '',
            'studio' => '',
            'audit' => '',
            'viewfieldsbtn' => '',
            'viewrelsbtn' => '',
            'viewlayoutsbtn' => '',
            'duplicatebtn' => '',
            'deletebtn' => '',
            'name' => '',
            'label' => '',
            'savebtn' => '',
            'type_basic' => '',
            'type_company' => '',
            'type_issue' => '',
            'type_person' => '',
            'type_sale' => '',
            'type_file' => '',

        ),
        'dropdowns' => array(
            'default' => '',
            'editdropdown' => '',

        ),
        'subPanelEditor' => array(
            'modify' => ''
        ,
            'savebtn' => '',
            'historyBtn' => '',
            'historyDefault' => '',
            'Hidden' => '',
            'Default' => '',

        ),
        'listViewEditor' => array(
            'modify' => ''
        ,
            'savebtn' => '',
            'historyBtn' => '',
            'historyDefault' => '',
            'Hidden' => '',
            'Available' => '',
            'Default' => ''
        ),
        'popupListViewEditor' => array(
            'modify' => ''
        ,
            'savebtn' => '',
            'historyBtn' => '',
            'historyDefault' => '',
            'Hidden' => '',
            'Default' => ''
        ),
        'searchViewEditor' => array(
            'modify' => ''
        ,
            'savebtn' => '',
            'Hidden' => '',
            'historyBtn' => '',
            'historyDefault' => '',
            'Default' => ''
        ),
        'layoutEditor' => array(
            'defaultdetailview' => '',
            'defaultquickcreate' => '',
            //this default will be used for edit view
            'default' => '',
            'saveBtn' => '',
            'historyBtn' => '',
            'historyDefault' => '',
            'publishBtn' => '',
            'toolbox' => '',
            'panels' => '',
            'delete' => '',
            'property' => '',
        ),
        'fieldsEditor' => array(
            'default' => '',
            'mbDefault' => '',
            'addField' => '',
            'editField' => '',
            'mbeditField' => ''

        ),
        'exportcustom' => array(
            'exportHelp' => '',
            'exportCustomBtn' => '',
            'name' => '',
            'author' => '',
            'description' => '',
        ),
        'studioWizard' => array(
            'mainHelp' => '',
            'studioBtn' => '',
            'mbBtn' => '',
            'sugarPortalBtn' => '',
            'dropDownEditorBtn' => '',
            'appBtn' => '',
            'backBtn' => '',
            'studioHelp' => '',
            'moduleBtn' => '',
            'moduleHelp' => '',
            'fieldsBtn' => '',
            'labelsBtn' => '',
            'relationshipsBtn' => '',
            'layoutsBtn' => '',
            'subpanelBtn' => '',
            'portalBtn' => '',
            'layoutsHelp' => '',
            'subpanelHelp' => '',
            'newPackage' => '',
            'exportBtn' => '',
            'mbHelp' => '',
            'viewBtnEditView' => '',
            'viewBtnDetailView' => '',
            'viewBtnDashlet' => '',
            'viewBtnListView' => '',
            'searchBtn' => '',
            'viewBtnQuickCreate' => '',
            'addLayoutHelp' => "",
            'searchHelp' => '',
            'dashletHelp' => '',
            'DashletListViewBtn' => '',
            'DashletSearchViewBtn' => '',
            'popupHelp' => '',
            'PopupListViewBtn' => '',
            'PopupSearchViewBtn' => '',
            'BasicSearchBtn' => '',
            'AdvancedSearchBtn' => '',
            'portalHelp' => '',
            'SPUploadCSS' => '',
            'SPSync' => '',
            'Layouts' => '',
            'portalLayoutHelp' => '',
            'relationshipsHelp' => '',
            'relationshipHelp' => '',
            'convertLeadHelp' => '',


            'editDropDownBtn' => '',
            'addDropDownBtn' => '',
        ),
        'fieldsHelp' => array(
            'default' => '',
        ),
        'relationshipsHelp' => array(
            'default' => '',
            'addrelbtn' => '',
            'addRelationship' => '',
        ),
        'labelsHelp' => array(
            'default' => '',
            'saveBtn' => '',
            'publishBtn' => '',
        ),
        'portalSync' => array(
            'default' => '',
        ),
        'portalStyle' => array(
            'default' => '',
        ),
    ),

    'assistantHelp' => array(
        'package' => array(
            //custom begin
            'nopackages' => '',
            'somepackages' => '',
            'afterSave' => '',
            'create' => '',
        ),
        'main' => array(
            'welcome' => '',
            'studioWelcome' => ''
        ),
        'module' => array(
            'somemodules' => "",
            'editView' => '',
            'create' => '',
            'afterSave' => '',
            'viewfields' => '',
            'viewrelationships' => '',
            'viewlayouts' => '',
            'existingModule' => '',
            'labels' => '',
        ),
        'listViewEditor' => array(
            'modify' => '',
            'savebtn' => '',
            'Hidden' => '',
            'Available' => '',
            'Default' => ''
        ),

        'searchViewEditor' => array(
            'modify' => '',
            'savebtn' => '',
            'Hidden' => '',
            'Default' => ''
        ),
        'layoutEditor' => array(
            'default' => '',
            'saveBtn' => '',
            'publishBtn' => '',
            'toolbox' => '',
            'panels' => ''
        ),
        'dropdownEditor' => array(
            'default' => '',
            'dropdownaddbtn' => '',

        ),
        'exportcustom' => array(
            'exportHelp' => '',
            'exportCustomBtn' => '',
            'name' => '',
            'author' => '',
            'description' => '',
        ),
        'studioWizard' => array(
            'mainHelp' => '',
            'studioBtn' => '',
            'mbBtn' => '',
            'appBtn' => '',
            'backBtn' => '',
            'studioHelp' => '',
            'moduleBtn' => '',
            'moduleHelp' => '',
            'fieldsBtn' => '',
            'labelsBtn' => '',
            'layoutsBtn' => '',
            'subpanelBtn' => '',
            'layoutsHelp' => '',
            'subpanelHelp' => '',
            'searchHelp' => '',
            'newPackage' => '',
            'mbHelp' => '',
            'exportBtn' => '',
        ),


    ),
//HOME
    'LBL_HOME_EDIT_DROPDOWNS' => '',

//STUDIO2
    'LBL_MODULEBUILDER' => '',
    'LBL_STUDIO' => '',
    'LBL_DROPDOWNEDITOR' => '',
    'LBL_DEVELOPER_TOOLS' => '',
    'LBL_SUITEPORTAL' => '',
    'LBL_PACKAGE_LIST' => '',
    'LBL_HOME' => '',
    'LBL_NONE' => '',
    'LBL_DEPLOYE_COMPLETE' => '',
    'LBL_DEPLOY_FAILED' => '',
    'LBL_AVAILABLE_SUBPANELS' => '',
    'LBL_ADVANCED' => '',
    'LBL_ADVANCED_SEARCH' => '',
    'LBL_BASIC' => '',
    'LBL_BASIC_SEARCH' => '',
    'LBL_CURRENT_LAYOUT' => '',
    'LBL_CURRENCY' => '',
    'LBL_DASHLET' => '',
    'LBL_DASHLETLISTVIEW' => '',
    'LBL_POPUP' => '',
    'LBL_POPUPLISTVIEW' => '',
    'LBL_POPUPSEARCH' => '',
    'LBL_DASHLETSEARCHVIEW' => '',
    'LBL_DETAILVIEW' => '',
    'LBL_DROP_HERE' => '',
    'LBL_EDIT' => '',
    'LBL_EDIT_LAYOUT' => '',
    'LBL_EDIT_FIELDS' => '',
    'LBL_EDITVIEW' => '',
    'LBL_FILLER' => '',
    'LBL_FIELDS' => '',
    'LBL_FAILED_TO_SAVE' => '',
    'LBL_FAILED_PUBLISHED' => '',
    'LBL_HOMEPAGE_PREFIX' => '',
    'LBL_LAYOUT_PREVIEW' => '',
    'LBL_LAYOUTS' => '',
    'LBL_LISTVIEW' => '',
    'LBL_MODULES' => '',
    'LBL_MODULE_TITLE' => '',
    'LBL_NEW_PACKAGE' => '',
    'LBL_NEW_PANEL' => '',
    'LBL_NEW_ROW' => '',
    'LBL_PACKAGE_DELETED' => '',
    'LBL_PUBLISHING' => '',
    'LBL_PUBLISHED' => '',
    'LBL_SELECT_FILE' => '',
    'LBL_SUBPANELS' => '',
    'LBL_SUBPANEL' => '',
    'LBL_SUBPANEL_TITLE' => '',
    'LBL_SEARCH_FORMS' => '',
    'LBL_SEARCH' => '',
    'LBL_SEARCH_BUTTON' => '',
    'LBL_FILTER' => '',
    'LBL_TOOLBOX' => '',
    'LBL_QUICKCREATE' => '',
    'LBL_EDIT_DROPDOWNS' => '',
    'LBL_ADD_DROPDOWN' => '',
    'LBL_BLANK' => '',
    'LBL_TAB_ORDER' => '',
    'LBL_TABDEF_TYPE' => '',
    'LBL_TABDEF_TYPE_HELP' => '',
    'LBL_TABDEF_TYPE_OPTION_TAB' => '',
    'LBL_TABDEF_TYPE_OPTION_PANEL' => '',
    'LBL_TABDEF_TYPE_OPTION_HELP' => '',
    'LBL_TABDEF_COLLAPSE' => '',
    'LBL_TABDEF_COLLAPSE_HELP' => '',
    'LBL_DROPDOWN_TITLE_NAME' => '',
    'LBL_DROPDOWN_LANGUAGE' => '',
    'LBL_DROPDOWN_ITEMS' => '',
    'LBL_DROPDOWN_ITEM_NAME' => '',
    'LBL_DROPDOWN_ITEM_LABEL' => '',
    'LBL_SYNC_TO_DETAILVIEW' => '',
    'LBL_SYNC_TO_DETAILVIEW_HELP' => '',
    'LBL_SYNC_TO_DETAILVIEW_NOTICE' => '',
    'LBL_COPY_FROM_EDITVIEW' => '',
    'LBL_DROPDOWN_BLANK_WARNING' => '',
    'LBL_DROPDOWN_KEY_EXISTS' => '',
    'LBL_NO_SAVE_ACTION' => '',
    'LBL_BADLY_FORMED_DOCUMENT' => '',


//RELATIONSHIPS
    'LBL_MODULE' => '',
    'LBL_LHS_MODULE' => '',
    'LBL_CUSTOM_RELATIONSHIPS' => '',
    'LBL_RELATIONSHIPS' => '',
    'LBL_RELATIONSHIP_EDIT' => '',
    'LBL_REL_NAME' => '',
    'LBL_REL_LABEL' => '',
    'LBL_REL_TYPE' => '',
    'LBL_RHS_MODULE' => '',
    'LBL_NO_RELS' => '',
    'LBL_RELATIONSHIP_ROLE_ENTRIES' => '',
    'LBL_RELATIONSHIP_ROLE_COLUMN' => '',
    'LBL_RELATIONSHIP_ROLE_VALUE' => '',
    'LBL_SUBPANEL_FROM' => '',
    'LBL_RELATIONSHIP_ONLY' => '',
    'LBL_ONETOONE' => '',
    'LBL_ONETOMANY' => '',
    'LBL_MANYTOONE' => '',
    'LBL_MANYTOMANY' => '',


//STUDIO QUESTIONS
    'LBL_QUESTION_EDIT' => '',
    'LBL_QUESTION_LAYOUT' => '',
    'LBL_QUESTION_SUBPANEL' => '',
    'LBL_QUESTION_SEARCH' => '',
    'LBL_QUESTION_MODULE' => '',
    'LBL_QUESTION_PACKAGE' => '',
    'LBL_QUESTION_EDITOR' => '',
    'LBL_QUESTION_DASHLET' => '',
    'LBL_QUESTION_POPUP' => '',
//CUSTOM FIELDS
    'LBL_NAME' => '',
    'LBL_LABELS' => '',
    'LBL_MASS_UPDATE' => '',
    'LBL_DEFAULT_VALUE' => '',
    'LBL_REQUIRED' => '',
    'LBL_DATA_TYPE' => '',
    'LBL_HCUSTOM' => '',
    'LBL_HDEFAULT' => '',
    'LBL_LANGUAGE' => '',
    'LBL_CUSTOM_FIELDS' => '',

//SECTION
    'LBL_SECTION_EDLABELS' => '',
    'LBL_SECTION_PACKAGES' => '',
    'LBL_SECTION_PACKAGE' => '',
    'LBL_SECTION_MODULES' => '',
    'LBL_SECTION_DROPDOWNS' => '',
    'LBL_SECTION_PROPERTIES' => '',
    'LBL_SECTION_DROPDOWNED' => '',
    'LBL_SECTION_HELP' => '',
    'LBL_SECTION_MAIN' => '',
    'LBL_SECTION_FIELDEDITOR' => '',
    'LBL_SECTION_DEPLOY' => '',
    'LBL_SECTION_MODULE' => '',
//WIZARDS

//LIST VIEW EDITOR
    'LBL_DEFAULT' => '',
    'LBL_HIDDEN' => '',
    'LBL_AVAILABLE' => '',
    'LBL_LISTVIEW_DESCRIPTION' => '',
    'LBL_LISTVIEW_EDIT' => '',

//Manager Backups History
    'LBL_MB_PREVIEW' => '',
    'LBL_MB_RESTORE' => '',
    'LBL_MB_DELETE' => '',
    'LBL_MB_DEFAULT_LAYOUT' => '',

//END WIZARDS

//BUTTONS
    'LBL_BTN_ADD' => '',
    'LBL_BTN_SAVE' => '',
    'LBL_BTN_SAVE_CHANGES' => '',
    'LBL_BTN_DONT_SAVE' => '',
    'LBL_BTN_CANCEL' => '',
    'LBL_BTN_CLOSE' => '',
    'LBL_BTN_SAVEPUBLISH' => '',
    'LBL_BTN_CLONE' => '',
    'LBL_BTN_ADDROWS' => '',
    'LBL_BTN_ADDFIELD' => '',
    'LBL_BTN_ADDDROPDOWN' => '',
    'LBL_BTN_SORT_ASCENDING' => '',
    'LBL_BTN_SORT_DESCENDING' => '',
    'LBL_BTN_EDLABELS' => '',
    'LBL_BTN_UNDO' => '',
    'LBL_BTN_REDO' => '',
    'LBL_BTN_ADDCUSTOMFIELD' => '',
    'LBL_BTN_EXPORT' => '',
    'LBL_BTN_DUPLICATE' => '',
    'LBL_BTN_PUBLISH' => '',
    'LBL_BTN_DEPLOY' => '',
    'LBL_BTN_EXP' => '',
    'LBL_BTN_DELETE' => '',
    'LBL_BTN_VIEW_LAYOUTS' => '',
    'LBL_BTN_VIEW_FIELDS' => '',
    'LBL_BTN_VIEW_RELATIONSHIPS' => '',
    'LBL_BTN_ADD_RELATIONSHIP' => '',
    'LBL_BTN_RENAME_MODULE' => '',
//TABS


//ERRORS
    'ERROR_ALREADY_EXISTS' => '',
    'ERROR_INVALID_KEY_VALUE' => "",
    'ERROR_NO_HISTORY' => '',
    'ERROR_MINIMUM_FIELDS' => '',
    'ERROR_GENERIC_TITLE' => '',
    'ERROR_REQUIRED_FIELDS' => '',


//PACKAGE AND MODULE BUILDER
    'LBL_PACKAGE_NAME' => '',
    'LBL_MODULE_NAME' => '',
    'LBL_AUTHOR' => '',
    'LBL_DESCRIPTION' => '',
    'LBL_KEY' => '',
    'LBL_ADD_README' => '',
    'LBL_LAST_MODIFIED' => '',
    'LBL_NEW_MODULE' => '',
    'LBL_LABEL' => '',
    'LBL_LABEL_TITLE' => '',
    'LBL_WIDTH' => '',
    'LBL_PACKAGE' => '',
    'LBL_TYPE' => '',
    'LBL_NAV_TAB' => '',
    'LBL_CREATE' => '',
    'LBL_LIST' => '',
    'LBL_VIEW' => '',
    'LBL_HISTORY' => '',
    'LBL_RESTORE_DEFAULT' => '',
    'LBL_ACTIVITIES' => '',
    'LBL_NEW' => '',
    'LBL_TYPE_BASIC' => '',
    'LBL_TYPE_COMPANY' => '',
    'LBL_TYPE_PERSON' => '',
    'LBL_TYPE_ISSUE' => '',
    'LBL_TYPE_SALE' => '',
    'LBL_TYPE_FILE' => '',
    'LBL_RSUB' => '',
    'LBL_MSUB' => '',
    'LBL_MB_IMPORTABLE' => '',

// VISIBILITY EDITOR
    'LBL_PACKAGE_WAS_DELETED' => '',

//EXPORT CUSTOMS
    'LBL_EC_TITLE' => '',
    'LBL_EC_NAME' => '',
    'LBL_EC_AUTHOR' => '',
    'LBL_EC_DESCRIPTION' => '',
    'LBL_EC_CHECKERROR' => '',
    'LBL_EC_CUSTOMFIELD' => '',
    'LBL_EC_CUSTOMLAYOUT' => '',
    'LBL_EC_NOCUSTOM' => '',
    'LBL_EC_EMPTYCUSTOM' => '',
    'LBL_EC_EXPORTBTN' => '',
    'LBL_MODULE_DEPLOYED' => '',
    'LBL_UNDEFINED' => '',
    'LBL_EC_VIEWS' => '',
    'LBL_EC_SUITEFEEDS' => '',
    'LBL_EC_DASHLETS' => '',
    'LBL_EC_CSS' => '',
    'LBL_EC_TPLS' => '',
    'LBL_EC_IMAGES' => '',
    'LBL_EC_JS' => '',
    'LBL_EC_QTIP' => '',

//AJAX STATUS
    'LBL_AJAX_FAILED_DATA' => '',
    'LBL_AJAX_LOADING' => '',
    'LBL_AJAX_DELETING' => '',
    'LBL_AJAX_BUILDPROGRESS' => '',
    'LBL_AJAX_DEPLOYPROGRESS' => '',

    'LBL_AJAX_RESPONSE_TITLE' => '',
    'LBL_AJAX_RESPONSE_MESSAGE' => '',
    'LBL_AJAX_LOADING_TITLE' => '',
    'LBL_AJAX_LOADING_MESSAGE' => '',

//JS
    'LBL_JS_REMOVE_PACKAGE' => '',
    'LBL_JS_REMOVE_MODULE' => '',
    'LBL_JS_DEPLOY_PACKAGE' => '',

    'LBL_DEPLOY_IN_PROGRESS' => '',
    'LBL_JS_VALIDATE_NAME' => '',
    'LBL_JS_VALIDATE_PACKAGE_NAME' => '',
    'LBL_JS_VALIDATE_KEY' => '',
    'LBL_JS_VALIDATE_LABEL' => '',
    'LBL_JS_VALIDATE_TYPE' => '',
    'LBL_JS_VALIDATE_REL_LABEL' => '',

//CONFIRM
    'LBL_CONFIRM_FIELD_DELETE' => '',

    'LBL_CONFIRM_RELATIONSHIP_DELETE' => '',
    'LBL_CONFIRM_DONT_SAVE' => '',
    'LBL_CONFIRM_DONT_SAVE_TITLE' => '',
    'LBL_CONFIRM_LOWER_LENGTH' => '',

//POPUP HELP
    'LBL_POPHELP_FIELD_DATA_TYPE' => '',
    'LBL_POPHELP_IMPORTABLE' => '',
    'LBL_POPHELP_IMAGE_WIDTH' => '',
    'LBL_POPHELP_IMAGE_HEIGHT' => '',
    'LBL_POPHELP_DUPLICATE_MERGE' => '',
    'LBL_POPHELP_FIELD_DATA_TYPE' => '',

//Revert Module labels
    'LBL_RESET' => '',
    'LBL_RESET_MODULE' => '',
    'LBL_REMOVE_CUSTOM' => '',
    'LBL_CLEAR_RELATIONSHIPS' => '',
    'LBL_RESET_LABELS' => '',
    'LBL_RESET_LAYOUTS' => '',
    'LBL_REMOVE_FIELDS' => '',
    'LBL_CLEAR_EXTENSIONS' => '',
    'LBL_HISTORY_TIMESTAMP' => '',
    'LBL_HISTORY_TITLE' => '',

    'fieldTypes' => array(
        'varchar' => '',
        'int' => '',
        'float' => '',
        'bool' => '',
        'enum' => '',
        'dynamicenum' => '',
        'multienum' => '',
        'date' => '',
        'phone' => '',
        'currency' => '',
        'html' => '',
        'radioenum' => '',
        'relate' => '',
        'address' => '',
        'text' => '',
        'url' => '',
        'iframe' => '',
        'datetimecombo' => '',
        'decimal' => '',
        'image' => '',
        'wysiwyg' => '',
    ),
    'labelTypes' => array(
        "frequently_used" => "",
        "all" => "",
    ),

    'parent' => '',

    'LBL_CONFIRM_SAVE_DROPDOWN' => "",

    'LBL_ALL_MODULES' => '',
    'LBL_RELATED_FIELD_ID_NAME_LABEL' => '',
);
