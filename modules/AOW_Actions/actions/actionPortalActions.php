<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by
 * the Free Software Foundation.
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

// STIC-Custom 20260927 AAM - Portal Actions workflow action (invitation / password reset)
// Same business logic as the Portal Actions popup: the shared public API in
// SticInclude/Portal/ActionsUtils.php (also available to custom code).
//
// Extends actionSendEmail to reuse its email-recipient lines (To/Cc/Bcc with
// Email Address / Record Email / Related Field / User targets), the email
// template picker and the advanced sender options.
require_once 'modules/AOW_Actions/actions/actionSendEmail.php';
require_once 'SticInclude/Portal/ActionsUtils.php';

class actionPortalActions extends actionSendEmail
{
    /**
     * Workflow edit view: portal action + target app, email template (defaulting
     * to the one selected in Portal Configuration), recipient lines, advanced
     * sender options and "send individual emails".
     *
     * @param int $line Action line number.
     * @param SugarBean|null $bean Workflow module bean (Contacts/Accounts).
     * @param array $params Saved action parameters.
     * @return string HTML for the action configuration.
     */
    public function edit_display($line, ?SugarBean $bean = null, $params = array())
    {
        global $app_list_strings, $db;

        $actionType = $params['portal_action'] ?? 'invitation';
        $appId = $params['portal_app'] ?? '';

        $actionOptions = array(
            'invitation' => translate('LBL_PORTAL_ACTIONS_INVITATION', 'AOW_Actions'),
            'pwreset' => translate('LBL_PORTAL_ACTIONS_RESET', 'AOW_Actions'),
        );

        // Target app: generic (no redirect) + the portal OAuth2 clients
        $appOptions = array('' => translate('LBL_PORTAL_ACTIONS_GENERIC_APP', 'AOW_Actions'));
        $r = $db->query("SELECT id, name FROM oauth2clients WHERE allowed_grant_type='portal_authorization_code' AND deleted=0 ORDER BY name");
        while ($row = $db->fetchByAssoc($r)) {
            $appOptions[$row['id']] = $row['name'];
        }

        // Email templates: the default is the one selected in Portal
        // Configuration for the current action type (same template the Portal
        // Actions popup of the detail/list views uses); the user may pick any other.
        $defaultTemplates = array(
            'invitation' => SticPortalConfigUtils::get(
                ($bean && $bean->module_dir === 'Accounts') ? 'PORTAL_TMPL_CRED_ACCOUNTS' : 'PORTAL_TMPL_CRED_CONTACTS',
                ''
            ),
            'pwreset' => SticPortalConfigUtils::get('PORTAL_TMPL_RESET', ''),
        );
        $selectedTemplate = array_key_exists('email_template', $params)
            ? (string) $params['email_template']
            : (string) $defaultTemplates[$actionType];
        $emailTemplatesArr = get_bean_select_array(true, 'EmailTemplate', 'name', '', 'name');
        $templateOptions = array('' => translate('LBL_PORTAL_ACTIONS_TEMPLATE_DEFAULT', 'AOW_Actions')) + $emailTemplatesArr;

        // Recipient target lists (same source lists as the Send Email action)
        if ($bean && !in_array($bean->module_dir, getEmailableModules())) {
            unset($app_list_strings['aow_email_type_list']['Record Email']);
        }
        if ($bean && empty(getRelatedEmailableFields($bean->module_dir))) {
            unset($app_list_strings['aow_email_type_list']['Related Field']);
        }

        $html = '<input type="hidden" name="aow_email_type_list" id="aow_email_type_list" value="' . get_select_options_with_id($app_list_strings['aow_email_type_list'], '') . '">'
            . '<input type="hidden" name="aow_email_to_list" id="aow_email_to_list" value="' . get_select_options_with_id($app_list_strings['aow_email_to_list'], '') . '">';

        $html .= "<table border='0' cellpadding='0' cellspacing='0' width='100%' data-workflow-action='portal-actions'>";

        // Row 1: Action + Target App (full-width selects so long names are readable)
        $html .= '<tr>';
        $html .= '<td id="portal_action_label' . $line . '" scope="row" valign="top"><label>' . translate('LBL_PORTAL_ACTIONS_TYPE', 'AOW_Actions') . ':</label></td>';
        $html .= '<td valign="top" style="width:35%"><select style="width:100%" name="aow_actions_param[' . $line . '][portal_action]" id="aow_actions_param_portal_action' . $line . '" onchange="portalActionsSyncTemplate(' . $line . ');">'
            . get_select_options_with_id($actionOptions, $actionType) . '</select></td>';
        $html .= '<td id="portal_app_label' . $line . '" scope="row" valign="top"><label>' . translate('LBL_PORTAL_ACTIONS_TARGET_APP', 'AOW_Actions') . ':</label></td>';
        $html .= '<td valign="top" style="width:35%"><select style="width:100%" name="aow_actions_param[' . $line . '][portal_app]" id="aow_actions_param_portal_app' . $line . '">'
            . get_select_options_with_id($appOptions, $appId) . '</select></td>';
        $html .= '</tr>';

        // Row 2: Send Individual Emails + Email Template (default from Portal Configuration)
        $checked = '';
        if (isset($params['individual_email']) && $params['individual_email']) {
            $checked = 'CHECKED';
        }
        $html .= '<tr>';
        $html .= '<td id="relate_label' . $line . '" scope="row" valign="top"><label>' . translate('LBL_INDIVIDUAL_EMAILS', 'AOW_Actions') . ':</label></td>';
        $html .= '<td valign="top">';
        $html .= "<input type='hidden' name='aow_actions_param[" . $line . "][individual_email]' value='0' >";
        $html .= "<input type='checkbox' id='aow_actions_param[" . $line . "][individual_email]' name='aow_actions_param[" . $line . "][individual_email]' value='1' $checked></td>";

        $hidden = "style='visibility: hidden;'";
        if ($selectedTemplate != '') {
            $hidden = "";
        }
        $html .= '<td id="name_label' . $line . '" scope="row" valign="top"><label>' . translate('LBL_EMAIL_TEMPLATE', 'AOW_Actions') . ':</label></td>';
        $html .= '<td valign="top" style="text-align:right; white-space:nowrap;">';
        $html .= "&nbsp;<a href='javascript:open_email_template_form(" . $line . ")' >" . translate('LBL_CREATE_EMAIL_TEMPLATE', 'AOW_Actions') . "</a>";
        $html .= "&nbsp;<span name='edit_template' id='aow_actions_edit_template_link" . $line . "' $hidden><a href='javascript:edit_email_template_form(" . $line . ")' >" . translate('LBL_EDIT_EMAIL_TEMPLATE', 'AOW_Actions') . "</a></span>";
        $html .= "&nbsp;<select style='width:65%;' name='aow_actions_param[" . $line . "][email_template]' id='aow_actions_param_email_template" . $line . "' onchange='show_edit_template_link(this," . $line . ");'"
            . " data-default-invitation='" . htmlspecialchars((string) $defaultTemplates['invitation'], ENT_QUOTES) . "'"
            . " data-default-pwreset='" . htmlspecialchars((string) $defaultTemplates['pwreset'], ENT_QUOTES) . "'>"
            . get_select_options_with_id($templateOptions, $selectedTemplate) . "</select>";
        $html .= '</td>';
        $html .= '</tr>';

        // Advanced options section (outbound account + sender identity), as in
        // the Send Email action
        $html .= "<tr style='margin-top:20px;' >";
        $html .= '<td id="relate_label_2_' . $line . '" scope="row" valign="top" style="width:20%;" > <a href="javascript:void($(\'.advancedOptions\').toggle());">' . translate('LBL_SHOW_ADVANCED', 'AOW_Actions')
            . '<span class="inline-help glyphicon glyphicon-triangle-bottom"></span> <span id="info-availability-portal' . $line . '" class="inline-help glyphicon glyphicon-info-sign" data-hasqtip="1" aria-describedby="qtip-1"></span></a> ';
        $html .= '</td>';
        $html .= '</tr>';

        $emailsList = $this->get_output_smtps();
        list($fromName, $fromAddress) = $this->getSelectedSMTPData($emailsList, $params['output_smtp'] ?? '');

        $html .= "<tr style='margin-top:20px; margin-bottom:20px; display:none;' class='advancedOptions'>";
        $html .= '<td id="relate_label_5_' . $line . '" scope="row" valign="top" style="width:20%;"><label>' . translate('LBL_OUTPUT_SMTP', 'AOW_Actions') . ':</label>';
        $html .= '</td>';
        $html .= "<td valign='top' style='width:20%; margin-bottom:20px;'>";
        $html .= "<select name='aow_actions_param[" . $line . "][output_smtp]' id='aow_actions_param[" . $line . "][output_smtp]' >" . $this->get_output_smtps_options($emailsList, $params['output_smtp'] ?? '') . "</select>";
        $html .= '</td>';
        $html .= '</tr>';

        if (isset($params['from_email_name']) && $params['from_email_name']) {
            $from_name = $params['from_email_name'];
        } else {
            $from_name = $fromName;
        }
        $html .= "<tr style='margin-top:20px; display:none;' class='advancedOptions'>";
        $html .= '<td id="relate_label_3_' . $line . '" scope="row" valign="top" style="width:20%;"><label>' . translate('LBL_FROM_NAME', 'AOW_Actions') . ':</label>';
        $html .= '</td>';
        $html .= "<td valign='top' style='width:20% !important;'>";
        $html .= "<input type='hidden' name='aow_actions_param[" . $line . "][from_email_name]' value='0' >";
        $html .= "<input type='text' id='aow_actions_param[" . $line . "][from_email_name]' name='aow_actions_param[" . $line . "][from_email_name]' value='{$from_name}' ></td>";

        if (isset($params['from_email_address']) && $params['from_email_address']) {
            $from = $params['from_email_address'];
        } else {
            $from = $fromAddress;
        }
        $html .= '<td id="relate_label_2_' . $line . '" scope="row" valign="top" style="width:20%;"> <label>' . translate('LBL_FROM_EMAIL', 'AOW_Actions') . ':</label>';
        $html .= '</td>';
        $html .= "<td valign='top' style='width:20% !important;'>";
        $html .= "<input type='hidden' name='aow_actions_param[" . $line . "][from_email_address]' value='' >";
        $html .= "<input type='text' id='aow_actions_param[" . $line . "][from_email_address]' name='aow_actions_param[" . $line . "][from_email_address]' value='{$from}' ></td>";
        $html .= '</tr>';

        if (isset($params['reply_to_name']) && $params['reply_to_name']) {
            $reply_to_name = $params['reply_to_name'];
        } else {
            $reply_to_name = '';
        }
        $html .= "<tr style='margin-top:20px; margin-bottom:20px; display:none' class='advancedOptions'>";
        $html .= '<td id="relate_label_4_' . $line . '" scope="row" valign="top" style="width:20%;"><label>' . translate('LBL_REPLY_TO_NAME', 'AOW_Actions') . ':</label>';
        $html .= '</td>';
        $html .= "<td valign='top' style='width:20% !important;'>";
        $html .= "<input type='hidden' name='aow_actions_param[" . $line . "][reply_to_name]' value='0' >";
        $html .= "<input type='text' id='aow_actions_param[" . $line . "][reply_to_name'] name='aow_actions_param[" . $line . "][reply_to_name]' value='{$reply_to_name}' ></td>";
        $html .= '</td>';

        if (isset($params['reply_to']) && $params['reply_to']) {
            $reply_to = $params['reply_to'];
        } else {
            $reply_to = '';
        }
        $html .= '<td id="relate_label_4_' . $line . '" scope="row" valign="top" style="width:20%;"><label>' . translate('LBL_REPLY_TO_EMAIL', 'AOW_Actions') . ':</label>';
        $html .= '</td>';
        $html .= "<td valign='top' style='width:20% !important;'>";
        $html .= "<input type='hidden' name='aow_actions_param[" . $line . "][reply_to]' value='0' >";
        $html .= "<input type='text' id='aow_actions_param[" . $line . "][reply_to'] name='aow_actions_param[" . $line . "][reply_to]' value='{$reply_to}' ></td>";
        $html .= '</td>';
        $html .= '</tr>';

        $html .= "<tr style='margin-top:20px;' >";
        $html .= "<td valign='top' style='width:20% !important;'>";
        $html .= '</td>';
        $html .= '</tr>';

        // Row 3: recipient lines (same UI as the Send Email action)
        $html .= '<tr>';
        $html .= '<td id="email_label' . $line . '" scope="row" valign="top"><label>' . translate('LBL_EMAIL', 'AOW_Actions') . ':</label></td>';
        $html .= '<td valign="top" scope="row">';
        $html .= '<button type="button" onclick="add_emailLine(' . $line . ')"><img src="' . SugarThemeRegistry::current()->getImageURL('id-ff-add.png') . '"></button>';
        $html .= '<table id="emailLine' . $line . '_table" width="100%" class="email-line"></table>';
        $html .= '</td>';
        $html .= '</tr>';

        if ($bean && !in_array($bean->module_dir, array('Contacts', 'Accounts'), true)) {
            $html .= '<tr><td colspan="4" style="color:#c62828">' . translate('LBL_PORTAL_ACTIONS_MODULES_HELP', 'AOW_Actions') . '</td></tr>';
        }

        $html .= '</table>';

        // Restore saved recipient lines
        $html .= "<script id ='aow_script" . $line . "'>";
        if (isset($params['email_target_type']) && !is_array($params['email_target_type'])) {
            $email = '';
            switch ($params['email_target_type']) {
                case 'Email Address':
                    $email = $params['email'];
                    break;
                case 'Specify User':
                    $email = $params['email_user_id'];
                    break;
                case 'Related Field':
                    $email = $params['email_target'];
                    break;
            }
            $html .= "load_emailline('" . $line . "','to','" . $params['email_target_type'] . "','" . $email . "');";
        }
        if (isset($params['email_target_type']) && is_array($params['email_target_type'])) {
            foreach ($params['email_target_type'] as $key => $field) {
                if (is_array($params['email'][$key])) {
                    $params['email'][$key] = json_encode($params['email'][$key]);
                }
                $html .= "load_emailline('" . $line . "','" . $params['email_to_type'][$key] . "','" . $params['email_target_type'][$key] . "','" . $params['email'][$key] . "');";
            }
        }
        $html .= '</script>';

        // Template default sync + advanced tooltip, as in the Send Email action
        $html .= "<script id ='portal_actions_script" . $line . "'>
        // Switch the default template when the action type changes, unless the
        // user has explicitly picked a custom template.
        function portalActionsSyncTemplate(line) {
            var actionSel = document.getElementById('aow_actions_param_portal_action' + line);
            var tplSel = document.getElementById('aow_actions_param_email_template' + line);
            if (!actionSel || !tplSel) return;
            var invDef = tplSel.getAttribute('data-default-invitation') || '';
            var resDef = tplSel.getAttribute('data-default-pwreset') || '';
            if (tplSel.value === '' || tplSel.value === invDef || tplSel.value === resDef) {
                tplSel.value = (actionSel.value === 'pwreset') ? resDef : invDef;
                if (typeof show_edit_template_link === 'function') show_edit_template_link(tplSel, line);
            }
        }

        $('#info-availability-portal" . $line . "').qtip({
            content: {
              text: '" . translate('LBL_ADVANCED_TOOLTIP_BODY', 'AOW_Actions') . "',
              title: {
                text: '" . translate('LBL_ADVANCED_TOOLTIP_HEADER', 'AOW_Actions') . "',
              },
              style: {
                classes: 'qtip-inline-help'
              }
            },
        });

        var selection = document.getElementById('aow_actions_param[" . $line . "][output_smtp]');
        selection.onchange = function(event){
          let from = event.target.options[event.target.selectedIndex].dataset.from;
          let address = event.target.options[event.target.selectedIndex].dataset.address;
          document.getElementById('aow_actions_param[" . $line . "][from_email_address]').setAttribute('value', address);
          document.getElementById('aow_actions_param[" . $line . "][from_email_name]').setAttribute('value', from);
        };";
        if (isset($params['output_smtp']) && ($params['output_smtp'] != 'system' || $fromName != $from_name || $fromAddress != $from
            || !empty($params['reply_to']) || !empty($params['reply_to_name']))) {
            $html .= "$('.advancedOptions').toggle();";
        }
        $html .= '</script>';

        return $html;
    }

    /**
     * Run the configured portal action on the workflow bean through the shared
     * public API (SticPortalActionsUtils::send()).
     *
     * @param SugarBean $bean Contact or Account the workflow runs on.
     * @param array $params Saved action parameters (portal_action, portal_app,
     *                      email_template, recipient lines, advanced options).
     * @param bool $in_save Whether the workflow runs during the bean save.
     * @return bool True on success, false otherwise.
     */
    public function run_action(SugarBean $bean, $params = array(), $in_save = false)
    {
        if (!in_array($bean->module_dir, array('Contacts', 'Accounts'), true)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Portal Actions only apply to Contacts/Accounts records, got: ' . $bean->module_dir);
            return false;
        }

        $action = $params['portal_action'] ?? 'invitation';
        if (!in_array($action, array('invitation', 'pwreset'), true)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Unknown portal action: ' . $action);
            return false;
        }

        // Recipients from the action's email lines (same format as the Send
        // Email action); empty → the record's primary email, as the popup does.
        $emails = $this->getEmailsFromParams($bean, $params);

        $options = array(
            'template_id'   => (string) ($params['email_template'] ?? ''),
            'to'            => isset($emails['to']) ? $emails['to'] : array(),
            'cc'            => isset($emails['cc']) ? $emails['cc'] : array(),
            'bcc'           => isset($emails['bcc']) ? $emails['bcc'] : array(),
            'individual'    => !empty($params['individual_email']),
            'output_smtp'   => (string) ($params['output_smtp'] ?? ''),
            'from_email'    => (string) ($params['from_email_address'] ?? ''),
            'from_name'     => (string) ($params['from_email_name'] ?? ''),
            'reply_to'      => (string) ($params['reply_to'] ?? ''),
            'reply_to_name' => (string) ($params['reply_to_name'] ?? ''),
        );

        $result = SticPortalActionsUtils::send($action, $bean, $params['portal_app'] ?? '', $options);
        if (empty($result['success'])) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Portal action "' . $action . '" failed for ' . $bean->module_dir . ':' . $bean->id
                . ' - ' . ($result['error'] ?? 'unknown error'));
            return false;
        }
        return true;
    }
}
// END STIC-Custom
