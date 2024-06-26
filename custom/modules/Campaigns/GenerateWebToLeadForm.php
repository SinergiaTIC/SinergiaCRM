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



require_once('include/formbase.php');




require_once('include/utils/db_utils.php');




global $app_list_strings, $app_strings,$mod_strings;

$site_url = $sugar_config['site_url'];
$web_form_header = $mod_strings['LBL_LEAD_DEFAULT_HEADER'];
$web_form_description = $mod_strings['LBL_DESCRIPTION_TEXT_LEAD_FORM'];
$web_form_submit_label = $mod_strings['LBL_DEFAULT_LEAD_SUBMIT'];
$web_form_required_fields_msg = $mod_strings['LBL_PROVIDE_WEB_TO_LEAD_FORM_FIELDS'];
$web_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
$web_not_valid_email_address = $mod_strings['LBL_NOT_VALID_EMAIL_ADDRESS'];
$web_post_url = $site_url.'/index.php?entryPoint=WebToPersonCapture';
$web_redirect_url = '';
$web_notify_campaign = '';
$web_assigned_user = '';
$web_team_user = '';
$web_form_footer = '';
$regex = "/^\w+(['\.\-\+]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+\$/";

$moduleDir = '';
if (!empty($_REQUEST['moduleDir'])) {
    $moduleDir= $_REQUEST['moduleDir'];
}

if (!empty($_REQUEST['web_header'])) {
    $web_form_header= $_REQUEST['web_header'];
}
if (!empty($_REQUEST['web_description'])) {
    $web_form_description= $_REQUEST['web_description'];
}
if (!empty($_REQUEST['web_submit'])) {
    $web_form_submit_label=to_html($_REQUEST['web_submit']);
}
if (!empty($_REQUEST['post_url'])) {
    $web_post_url= $_REQUEST['post_url'];
}
if (!empty($_REQUEST['redirect_url']) && $_REQUEST['redirect_url'] !="http://") {
    $web_redirect_url= $_REQUEST['redirect_url'];
}
if (!empty($_REQUEST['notify_campaign'])) {
    $web_notify_campaign = $_REQUEST['notify_campaign'];
}
if (!empty($_REQUEST['web_footer'])) {
    $web_form_footer= $_REQUEST['web_footer'];
}
if (!empty($_REQUEST['campaign_id'])) {
    $web_form_campaign= $_REQUEST['campaign_id'];
}
if (!empty($_REQUEST['assigned_user_id'])) {
    $web_assigned_user = $_REQUEST['assigned_user_id'];
}

$typeOfPerson = !empty($_REQUEST['typeOfPerson']) ? $_REQUEST['typeOfPerson'] : 'Lead';
 $person = new $typeOfPerson();
 $fieldsMetaData = BeanFactory::newBean('EditCustomFields');
 // STIC-Custom 20230823 JBL - Add reCAPTCHA validation to forms
 // STIC#1180
 //$xtpl=new XTemplate('modules/Campaigns/WebToLeadForm.html');
 $xtpl=new XTemplate('custom/modules/Campaigns/WebToLeadForm.html');
 // END STIC-Custom
 $xtpl->assign("MOD", $mod_strings);
 $xtpl->assign("APP", $app_strings);

// STIC-Custom 20230823 JBL - Add reCAPTCHA validation to forms
// STIC#1180
$includeRecaptcha = 0;
$recaptchaSelected = 0;
$redirectUrlKoRecaptcha = '';
if (isset($_REQUEST['include_recaptcha'])) {
    $includeRecaptcha= intval($_REQUEST['include_recaptcha']);
}
if (isset($_REQUEST['recaptcha_selected'])) {
    $recaptchaSelected= intval($_REQUEST['recaptcha_selected']);
}
if (!empty($_REQUEST['redirect_url_ko_recaptcha']) && $_REQUEST['redirect_url_ko_recaptcha'] !="https://") {
    $redirectUrlKoRecaptcha= $_REQUEST['redirect_url_ko_recaptcha'];
}
$recaptchaName = "";
$recaptchaKey = "";
$recaptchaWebKey = "";
$recaptchaVersion = "";
require_once "modules/stic_Web_Forms/sticUtils.php";
$recaptchaConfigs = SticWebFormsUtils::getRecaptchaConfigurations();
$recaptchaConfiguration = null;
if($includeRecaptcha) {
    // Get selected reCAPTCHA configuration
    $key = array_keys($recaptchaConfigs)[$recaptchaSelected];
    $recaptchaConfiguration = $recaptchaConfigs[$key];
    $recaptchaName = $recaptchaConfiguration["NAME"];
    $recaptchaKey = $recaptchaConfiguration["KEY"];
    $recaptchaWebKey = $recaptchaConfiguration["WEBKEY"];
    $recaptchaVersion = $recaptchaConfiguration["VERSION"];
}
$xtpl->assign("RECAPTCHA_CHECKED", $includeRecaptcha);
$xtpl->assign("RECAPTCHA_NAME", $recaptchaName);
$xtpl->assign("RECAPTCHA_KEY", $recaptchaKey);
$xtpl->assign("RECAPTCHA_WEBKEY", $recaptchaWebKey);
$xtpl->assign("RECAPTCHA_VERSION", $recaptchaVersion);
$xtpl->assign("REDIRECT_URL_KO_RECAPTCHA", $redirectUrlKoRecaptcha);
// END STIC-Custom

// STIC-Custom 20230823 JBL - Add reCAPTCHA validation to forms
// STIC#1180
// include_once 'WebToLeadFormBuilder.php';
include_once 'modules/Campaigns/WebToLeadFormBuilder.php';
// END STIC-Custom
$Web_To_Lead_Form_html = WebToLeadFormBuilder::generate(
    $_REQUEST,
    $person,
    $moduleDir,
    $site_url,
    $web_post_url,
    $web_form_header,
    $web_form_description,
    $app_list_strings,
    $web_required_symbol,
    $web_form_footer,
    $web_form_submit_label,
    $web_form_campaign,
    $web_redirect_url,
    $web_assigned_user,
    $web_form_required_fields_msg
);
$xtpl->assign("BODY", $Web_To_Lead_Form_html ? $Web_To_Lead_Form_html : '');
$xtpl->assign("BODY_HTML", $Web_To_Lead_Form_html ? $Web_To_Lead_Form_html : '');


require_once('include/SugarTinyMCE.php');
$tiny = new SugarTinyMCE();
$tiny->defaultConfig['height']=700;
$tiny->defaultConfig['apply_source_formatting']=true;
$tiny->defaultConfig['cleanup']=false;
$tiny->defaultConfig['extended_valid_elements'] .= ',input[name|id|type|value|onclick|required|placeholder|title],select[name|id|multiple|tabindex|onchange|required|title],option[value|selected|title]';
$ed = $tiny->getInstance('body_html');
$xtpl->assign("tiny", $ed);

$xtpl->parse("main.textarea");

$xtpl->assign("INSERT_VARIABLE_ONCLICK", "insert_variable_html(document.EditView.variable_text.value)");
$xtpl->parse("main.variable_button");




$xtpl->parse("main");
$xtpl->out("main");

function ifRadioButton($customFieldName)
{
    $custRow = null;
    $query="select id,type from fields_meta_data where deleted = 0 and name = '$customFieldName'";
    $result=DBManagerFactory::getInstance()->query($query);
    $row = DBManagerFactory::getInstance()->fetchByAssoc($result);
    if ($row != null && $row['type'] == 'radioenum') {
        return $custRow = $row;
    }
    return $custRow;
}
