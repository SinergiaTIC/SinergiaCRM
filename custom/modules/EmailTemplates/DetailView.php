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

require_once('include/upload_file.php');
require_once('include/DetailView/DetailView.php');

if (isset($_SESSION['EMAILTEMPLATE_FROM_LIST_VIEW'])) {
    $_SESSION['EMAIL_TEMPLATE_FROM_LIST_VIEW'] = $_SESSION['EMAILTEMPLATE_FROM_LIST_VIEW'];
}

global $app_strings;
global $mod_strings;
global $app_list_strings;

$focus = BeanFactory::newBean('EmailTemplates');

$detailView = new DetailView();
$offset = 0;
if (isset($_REQUEST['offset']) || isset($_REQUEST['record'])) {
    $result = $detailView->processSugarBean("EMAIL_TEMPLATE", $focus, $offset);
    if ($result == null) {
        sugar_die($app_strings['ERROR_NO_RECORD']);
    }
    $focus = $result;
} else {
    header("Location: index.php?module=Accounts&action=index");
}

if (isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
    $focus->id = "";
}

if (isset($_REQUEST['contact_name']) && is_null($focus->contact_name)) {
    $focus->contact_name = $_REQUEST['contact_name'];
}
if (isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
    $focus->contact_id = $_REQUEST['contact_id'];
}
if (isset($_REQUEST['opportunity_name']) && is_null($focus->parent_name)) {
    $focus->parent_name = $_REQUEST['opportunity_name'];
}
if (isset($_REQUEST['opportunity_id']) && is_null($focus->parent_id)) {
    $focus->parent_id = $_REQUEST['opportunity_id'];
}
if (isset($_REQUEST['account_name']) && is_null($focus->parent_name)) {
    $focus->parent_name = $_REQUEST['account_name'];
}
if (isset($_REQUEST['account_id']) && is_null($focus->parent_id)) {
    $focus->parent_id = $_REQUEST['account_id'];
}

$params = array();
$params[] = $focus->name;

echo getClassicModuleTitle($focus->module_dir, $params, true);

$GLOBALS['log']->info("EmailTemplate detail view");

$xtpl = new XTemplate(get_custom_file_if_exists('modules/EmailTemplates/DetailView.html'));

$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$buttons = array(
    <<<EOD
            <input type="submit" class="button" id="editEmailTemplatesButton" title="{$app_strings['LBL_EDIT_BUTTON_TITLE']}" accessKey="{$app_strings['LBL_EDIT_BUTTON_KEY']}" onclick="this.form.return_module.value='EmailTemplates'; this.form.return_action.value='DetailView'; this.form.return_id.value='{$focus->id}'; this.form.action.value='EditView'" value="{$app_strings['LBL_EDIT_BUTTON_LABEL']}">
EOD
,
    <<<EOD
            <input title="{$app_strings['LBL_DUPLICATE_BUTTON_TITLE']}" accessKey="{$app_strings['LBL_DUPLICATE_BUTTON_KEY']}" class="button" onclick="this.form.return_module.value='EmailTemplates'; this.form.return_action.value='index'; this.form.isDuplicate.value=true; this.form.action.value='EditView'" type="submit" name="button" value="{$app_strings['LBL_DUPLICATE_BUTTON_LABEL']}">
EOD
,
    <<<EOD
            <input title="{$app_strings['LBL_DELETE_BUTTON_TITLE']}" accessKey="{$app_strings['LBL_DELETE_BUTTON_KEY']}" class="button" onclick="check_deletable_EmailTemplate();" type="button" name="button" value="{$app_strings['LBL_DELETE_BUTTON_LABEL']}">
EOD
);
require_once('include/Smarty/plugins/function.sugar_action_menu.php');
$action_button = smarty_function_sugar_action_menu(array(
    'id'      => 'detail_header_action_menu',
    'buttons' => $buttons,
    'class'   => 'clickMenu fancymenu',
), $xtpl);

$xtpl->assign("ACTION_BUTTON", $action_button);

if (isset($_REQUEST['return_module'])) {
    $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
}
if (isset($_REQUEST['return_action'])) {
    $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
}
if (isset($_REQUEST['return_id'])) {
    $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
}

$xtpl->assign("GRIDLINE",           $gridline);
$xtpl->assign("PRINT_URL",          "index.php?" . $GLOBALS['request_string']);
$xtpl->assign("ID",                 $focus->id);
$xtpl->assign("CREATED_BY",         $focus->created_by_name);
$xtpl->assign("MODIFIED_BY",        $focus->modified_by_name);

if (isset($focus->text_only) && $focus->text_only) {
    $xtpl->assign("TEXT_ONLY_CHECKED", "CHECKED");
}

$xtpl->assign("NAME",               $focus->name);
$xtpl->assign("DESCRIPTION",        $focus->description);
$xtpl->assign("SUBJECT",            $focus->subject);
$xtpl->assign("BODY",               $focus->body);
$xtpl->assign("BODY_HTML",          json_encode(from_html($focus->body_html)));
$xtpl->assign("DATE_MODIFIED",      $focus->date_modified);
$xtpl->assign("DATE_ENTERED",       $focus->date_entered);
$xtpl->assign("ASSIGNED_USER_NAME", $focus->assigned_user_name);

if ($focus->type === 'workflow') {
    $xtpl->assign("TYPE", $app_list_strings['emailTemplates_type_list'][$focus->type]);
} else {
    $xtpl->assign("TYPE", $app_list_strings['emailTemplates_type_list_no_workflow'][$focus->type] ?? $focus->type);
}

if (!empty($focus->body)) {
    $xtpl->assign('ALT_CHECKED', 'CHECKED');
} else {
    $xtpl->assign('ALT_CHECKED', '');
}

if ($focus->published == 'on') {
    $xtpl->assign("PUBLISHED", "CHECKED");
}

// Attachments
$note = BeanFactory::newBean('Notes');
$where = "notes.parent_id='{$focus->id}'";
$notes_list = $note->get_full_list("notes.name", $where, true);
if (!isset($notes_list)) {
    $notes_list = array();
}
$attachments = '';
$notes_listCount = count($notes_list);
for ($i = 0; $i < $notes_listCount; $i++) {
    $the_note = $notes_list[$i];
    $attachments .= "<a href=\"index.php?entryPoint=download&id={$the_note->id}&type=Notes\">" . $the_note->name . "</a><br />";
}
$xtpl->assign("ATTACHMENTS", $attachments);

global $current_user;
if (is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])) {
    $xtpl->assign("ADMIN_EDIT", "<a href='index.php?action=index&module=DynamicLayout&from_action=" . $_REQUEST['action'] . "&from_module=" . $_REQUEST['module'] . "&record=" . $_REQUEST['record'] . "'>" . SugarThemeRegistry::current()->getImage("EditLayout", "border='0' align='bottom'", null, null, '.gif', $mod_strings['LBL_EDIT_LAYOUT']) . "</a>");
}

$xtpl->assign("DESCRIPTION", $focus->description);

// STIC-Custom AAM - Asignaciones campos WhatsApp
$stic_type_val     = isset($focus->type)                             ? $focus->type                             : '';
$stic_status_val   = isset($focus->stic_whatsapp_status_c)           ? $focus->stic_whatsapp_status_c           : '';
$stic_category_val = isset($focus->stic_whatsapp_category_c)         ? $focus->stic_whatsapp_category_c         : '';
$stic_twilio_id    = isset($focus->stic_whatsapp_twilio_id_c)        ? $focus->stic_whatsapp_twilio_id_c        : '';

$stic_status_label   = !empty($app_list_strings['stic_whatsapp_status_list'][$stic_status_val])
                            ? $app_list_strings['stic_whatsapp_status_list'][$stic_status_val]    : $stic_status_val;
$stic_category_label = !empty($app_list_strings['stic_whatsapp_category_list'][$stic_category_val])
                            ? $app_list_strings['stic_whatsapp_category_list'][$stic_category_val] : $stic_category_val;

$xtpl->assign('STIC_WHATSAPP_STATUS_LABEL',      htmlspecialchars($stic_status_label,   ENT_QUOTES));
$xtpl->assign('STIC_WHATSAPP_CATEGORY_LABEL',    htmlspecialchars($stic_category_label, ENT_QUOTES));
$xtpl->assign('STIC_WHATSAPP_TWILIO_ID',         htmlspecialchars($stic_twilio_id,      ENT_QUOTES));
$xtpl->assign('TYPE_RAW',                        htmlspecialchars($stic_type_val,        ENT_QUOTES));
// END STIC-Custom

$detailView->processListNavigation($xtpl, "EMAIL_TEMPLATE", $offset);

// Custom fields del core
require_once('modules/DynamicFields/templates/Files/DetailView.php');

$xtpl->parse("main");
$xtpl->out("main");

// STIC Custom 20240909 EPS - SMS messages
echo getVersionedScript("SticInclude/js/Utils.js");
echo getVersionedScript("custom/modules/EmailTemplates/SticUtils.js");
// END STIC Custom