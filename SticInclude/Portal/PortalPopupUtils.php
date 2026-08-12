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

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/**
 * Portal Actions popup utilities for Contacts and Accounts detail views.
 * Injects OAuth2 client list and popup HTML into the detail view.
 */
class PortalPopupUtils
{
    /**
     * Echo the Portal Actions popup HTML and inject the OAuth2 client list
     * for the detail view's Portal Actions button.
     *
     * @param string $module Module name (Contacts or Accounts).
     */
    public static function echoDetailViewPopup($module)
    {
        global $db, $mod_strings;

        if (empty($mod_strings)) {
            $mod_strings = return_module_language($GLOBALS['current_language'], $module);
        }

        // Inject OAuth2 portal clients for invitation buttons
        $clients = array();
        $r = $db->query("SELECT id, name, redirect_url FROM oauth2clients WHERE allowed_grant_type='portal_authorization_code' AND deleted=0 ORDER BY name");
        while ($row = $db->fetchByAssoc($r)) {
            $clients[] = array('name' => $row['name'], 'url' => $row['redirect_url']);
        }
        echo '<script>STIC.portalClients = ' . json_encode($clients) . "</script>\n";
        echo '<script>function getPortalInvitationLimit() {return ' . (int)SticPortalConfigUtils::get('PORTAL_INVITATION_LIMIT', 100) . ';}</script>' . "\n";
        echo '<script src="' . getVersionedScript('SticInclude/Portal/PortalActions.js') . '"></script>' . "\n";

        $titleHelp = $mod_strings['LBL_STIC_PORTAL_ACTIONS_HELP'] ?? 'Send an invitation or password reset.';
        $actionHelp = $mod_strings['LBL_STIC_PORTAL_ACTION_TYPE_HELP'] ?? 'Invitation vs Password Reset.';
        $enabledLabel = htmlspecialchars($mod_strings['LBL_STIC_PORTAL_ENABLED'] ?? 'Portal Enabled');
        $actionHelp = str_replace('%PORTAL_ENABLED_LABEL%', $enabledLabel, $actionHelp);
        $appHelp = $mod_strings['LBL_STIC_PORTAL_TARGET_APP_HELP'] ?? 'Redirect after password setup.';

        // Portal Actions popup HTML
        echo '
<div id="portalActionsPopup" class="pap-overlay">
<div class="pap-dialog">
    <h3>
        ' . htmlspecialchars($mod_strings['LBL_STIC_PORTAL_ACTIONS'] ?? 'Portal Actions') . '
        <i class="inline-help glyphicon glyphicon-info-sign" data-hasqtip="1" aria-describedby="qtip-1"></i>
        <div class="inline-help-content" style="display:none;">' . $titleHelp . '</div>
    </h3>
    <div class="pap-field">
        <label class="pap-label">
            ' . htmlspecialchars($mod_strings['LBL_STIC_PORTAL_POPUP_ACTION'] ?? 'Action') . '
            <i class="inline-help glyphicon glyphicon-info-sign" data-hasqtip="2" aria-describedby="qtip-2"></i>
            <div class="inline-help-content" style="display:none;">' . $actionHelp . '</div>
        </label>
        <select id="portalActionType" class="form-control" style="width:100%">
            <option value="invitation">' . htmlspecialchars($mod_strings['LBL_STIC_PORTAL_POPUP_INVITATION'] ?? 'Send Invitation Email') . '</option>
            <option value="pwreset">' . htmlspecialchars($mod_strings['LBL_STIC_PORTAL_POPUP_RESET'] ?? 'Send Password Reset') . '</option>
        </select>
    </div>
    <div class="pap-field">
        <label class="pap-label">
            ' . htmlspecialchars($mod_strings['LBL_STIC_PORTAL_POPUP_TARGET_APP'] ?? 'Target App for Redirect') . '
            <i class="inline-help glyphicon glyphicon-info-sign" data-hasqtip="3" aria-describedby="qtip-3"></i>
            <div class="inline-help-content" style="display:none;">' . $appHelp . '</div>
        </label>
        <select id="portalAppSelect" class="form-control" style="width:100%"></select>
    </div>
    <div class="pap-actions">
        <button onclick="closePortalActionsPopup()" class="pap-btn-cancel">' . htmlspecialchars($mod_strings['LBL_STIC_PORTAL_POPUP_CANCEL'] ?? 'Cancel') . '</button>
        <button onclick="executePortalAction()" class="pap-btn-execute">' . htmlspecialchars($mod_strings['LBL_STIC_PORTAL_POPUP_EXECUTE'] ?? 'Execute') . '</button>
    </div>
</div></div>
<script>
$("#portalActionsPopup .inline-help").removeAttr("data-hasqtip");
setInlineHelpQtip();
// Force qtips above the overlay — inline style from qtip plugin wins over CSS
$("#portalActionsPopup .inline-help").on("mouseover", function() {
    setTimeout(function() { $(".qtip").css("z-index", "100000"); }, 100);
});
</script>';
    }
}
