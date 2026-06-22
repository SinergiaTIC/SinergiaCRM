{*
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
 *}
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
