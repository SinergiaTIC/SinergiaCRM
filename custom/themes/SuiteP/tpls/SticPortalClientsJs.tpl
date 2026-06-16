{literal}
<script>
(function() {
  if (typeof STIC === 'undefined' || !STIC.record || !STIC.record.id) return;
  STIC.portalClients = {/literal}{$PORTAL_OAUTH_CLIENTS}{literal};
  if (!STIC.portalClients || !STIC.portalClients.length) return;

  STIC.portalClients.forEach(function(client, idx) {
    if (typeof createDetailViewButton !== 'function') return;
    var btn = {
      id: 'bt_portal_invitation_app_' + idx,
      title: (SUGAR && SUGAR.language ? SUGAR.language.get(module || 'Contacts', 'LBL_STIC_SEND_PORTAL_INVITATION') : 'Send Portal Invitation') + ' → ' + client.name,
      onclick: "location.href='index.php?entryPoint=sticPortalInvitation&id=" + STIC.record.id + "&return_module=" + (module || 'Contacts') + "&redirect_uri=" + encodeURIComponent(client.url) + "'",
    };
    createDetailViewButton(btn);
  });
})();
</script>
{/literal}
