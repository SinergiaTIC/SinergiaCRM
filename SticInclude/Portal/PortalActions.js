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
// NOTE: onClickPortalInvitationButton() (list-view bulk action) lives in the
// module SticUtils.js files — PortalActions.js is only loaded on detail views.
