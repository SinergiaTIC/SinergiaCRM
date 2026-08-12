// Portal Actions popup — shared between Accounts and Contacts
function openPortalActionsPopup() {
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
    var params = "id=" + STIC.record.id + "&return_module=" + module + "&redirect_uri=" + encodeURIComponent(redirectUri);
    closePortalActionsPopup();
    if (action === "invitation") {
        location.href = "index.php?entryPoint=sticPortalInvitation&" + params;
    } else if (action === "pwreset") {
        location.href = "index.php?entryPoint=sticPortalResetRequest&" + params;
    }
}
function onClickPortalInvitationButton() {
    sugarListView.get_checks();
    if (sugarListView.get_checks_count() < 1) {
        alert(SUGAR.language.get("app_strings", "LBL_LISTVIEW_NO_SELECTED"));
        return false;
    }
    if (sugarListView.get_checks_count() > getPortalInvitationLimit()) {
        alert(SUGAR.language.get("app_strings", "LBL_PORTAL_INVITATION_LIMIT_ALERT") || "The invitation limit has been exceeded.");
        return false;
    }
    var ids = [];
    document.querySelectorAll("input[name=mass\\[]]:checked").forEach(function(cb) { ids.push(cb.value); });
    location.href = "index.php?entryPoint=sticPortalInvitation&id=" + ids.join(",") + "&return_module=" + module + "&return_action=index";
}
