/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2026 SinergiaTIC Association
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

// Portal Actions popup — shared between Accounts and Contacts, and between the
// detail view (single record) and the list view (the selected records).
// PortalPopupUtils::echoPortalActionsPopup() injects the popup HTML (with the
// STIC.portalClients / getPortalInvitationLimit() data) in both views.

// The detail view always has STIC.record.id set (SticViews::preDisplay); on list
// views STIC.record is an empty object, so the popup acts on the mass[] selection.
function portalActionsIsListView() {
    return !(typeof STIC !== "undefined" && STIC.record && STIC.record.id);
}

function portalActionTargetIds() {
    if (!portalActionsIsListView()) {
        return [STIC.record.id];
    }
    var ids = [];
    document.querySelectorAll('input[name="mass[]"]:checked').forEach(function (cb) {
        ids.push(cb.value);
    });
    return ids;
}

function openPortalActionsPopup() {
    // On list views the action is a bulk one: require a selection and enforce
    // the configured invitation limit before opening the popup.
    if (portalActionsIsListView()) {
        if (typeof sugarListView === "undefined") return false;
        sugarListView.get_checks();
        var noSelectionMsg = SUGAR.language.get("app_strings", "LBL_LISTVIEW_NO_SELECTED") || "Please select at least one record.";
        if (sugarListView.get_checks_count() < 1) {
            alert(noSelectionMsg);
            return false;
        }
        var limit = (typeof getPortalInvitationLimit === "function") ? getPortalInvitationLimit() : 0;
        if (limit > 0 && sugarListView.get_checks_count() > limit) {
            alert(SUGAR.language.get("app_strings", "LBL_PORTAL_INVITATION_LIMIT_ALERT") || "The invitation limit has been exceeded.");
            return false;
        }
    }

    var sel = document.getElementById("portalAppSelect");
    sel.innerHTML = "";
    // Apps first (alphabetical, as delivered by portalClients); the "Generic"
    // option always goes last so it stays at the bottom of the list.
    if (STIC.portalClients) {
        STIC.portalClients.forEach(function(c) {
            var opt = document.createElement("option");
            opt.value = c.url;
            opt.textContent = c.name;
            sel.appendChild(opt);
        });
    }
    var genOpt = document.createElement("option");
    genOpt.value = "";
    genOpt.textContent = "Generic (no specific app)";
    sel.appendChild(genOpt);
    // Default to the first option: the first app; Generic is only preselected
    // when it is the sole option (no specific apps defined).
    sel.selectedIndex = 0;
    portalActionSyncTemplateSelect();
    document.getElementById("portalActionsPopup").style.display = "flex";
}

// Populate the template select (default option + every email template) and
// preselect the default for the current action type — the same template the
// Portal Configuration settings use. When the action type changes, the
// selection follows the new default unless a custom template was picked.
function portalActionSyncTemplateSelect() {
    var tplSel = document.getElementById("portalTemplateSelect");
    if (!tplSel) return;
    var previous = tplSel.value;
    var defaults = STIC.portalTemplateDefaults || {};
    tplSel.innerHTML = "";
    var defOpt = document.createElement("option");
    defOpt.value = "";
    defOpt.textContent = SUGAR.language.get("Administration", "LBL_PORTAL_ACTIONS_TEMPLATE_DEFAULT") || "Default (Portal Configuration)";
    tplSel.appendChild(defOpt);
    (STIC.portalTemplates || []).forEach(function(t) {
        var opt = document.createElement("option");
        opt.value = t.id;
        opt.textContent = t.name;
        tplSel.appendChild(opt);
    });
    var action = document.getElementById("portalActionType").value;
    var defaultId = defaults[action] || "";
    // Follow the action's default unless the user picked a custom template
    if (previous === "" || previous === defaults.invitation || previous === defaults.pwreset) {
        tplSel.value = defaultId;
    } else {
        tplSel.value = previous;
    }
}

function closePortalActionsPopup() {
    document.getElementById("portalActionsPopup").style.display = "none";
}

function executePortalAction() {
    var action = document.getElementById("portalActionType").value;
    var redirectUri = document.getElementById("portalAppSelect").value;
    var ids = portalActionTargetIds();
    if (!ids.length) {
        alert(SUGAR.language.get("app_strings", "LBL_LISTVIEW_NO_SELECTED") || "Please select at least one record.");
        return false;
    }
    // Detail view returns to the record, list view back to the (filtered) list
    var returnAction = portalActionsIsListView() ? "index" : "DetailView";
    var templateId = document.getElementById("portalTemplateSelect") ? document.getElementById("portalTemplateSelect").value : "";
    // Resolve the selected app's client id (for the audit log and the app name
    // variable in the email templates)
    var appId = "";
    (STIC.portalClients || []).forEach(function(c) {
        if (c.url === redirectUri) appId = c.id;
    });
    if (!STIC.portalCsrf) {
        // Popup initialized without a session token — reload to get one
        location.reload();
        return false;
    }
    var params = "id=" + ids.join(",")
        + "&return_module=" + module
        + "&return_action=" + returnAction
        + "&redirect_uri=" + encodeURIComponent(redirectUri)
        + "&app_id=" + encodeURIComponent(appId)
        + "&template=" + encodeURIComponent(templateId)
        + "&portal_token=" + encodeURIComponent(STIC.portalCsrf);
    closePortalActionsPopup();
    if (action === "invitation") {
        location.href = "index.php?entryPoint=sticPortalInvitation&" + params;
    } else if (action === "pwreset") {
        location.href = "index.php?entryPoint=sticPortalResetRequest&" + params;
    }
}
