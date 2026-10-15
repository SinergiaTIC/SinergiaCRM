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
    var genOpt = document.createElement("option");
    genOpt.value = "";
    genOpt.textContent = "Generic (no specific app)";
    sel.appendChild(genOpt);
    if (STIC.portalClients) {
        STIC.portalClients.forEach(function(c) {
            var opt = document.createElement("option");
            opt.value = c.url;
            opt.textContent = c.name;
            sel.appendChild(opt);
        });
    }
    document.getElementById("portalActionsPopup").style.display = "flex";
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
    var params = "id=" + ids.join(",")
        + "&return_module=" + module
        + "&return_action=" + returnAction
        + "&redirect_uri=" + encodeURIComponent(redirectUri);
    closePortalActionsPopup();
    if (action === "invitation") {
        location.href = "index.php?entryPoint=sticPortalInvitation&" + params;
    } else if (action === "pwreset") {
        location.href = "index.php?entryPoint=sticPortalResetRequest&" + params;
    }
}
