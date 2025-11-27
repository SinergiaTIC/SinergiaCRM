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
<div style="padding: 20px;">
    <h2>{$MOD.LBL_STIC_CERT_UPLOAD_TITLE}</h2>

    {if $MESSAGE}
        <div class="error">{$MESSAGE}</div>
        {if $CERT_METADATA && $MESSAGE == $MOD.LBL_STIC_CERT_SUCCESS}
            <div style="margin-top: 10px; padding: 10px; background-color: #e8f5e9; border: 1px solid #4caf50;">
                <strong>{$MOD.LBL_STIC_CERT_UPLOAD_INFO}:</strong><br>
                <strong>{$MOD.LBL_STIC_CERT_FILENAME}:</strong> {$CERT_METADATA.original_filename}<br>
                <strong>{$MOD.LBL_STIC_CERT_UPLOAD_DATE}:</strong> {$CERT_METADATA.upload_date_formatted}<br>
                <strong>{$MOD.LBL_STIC_CERT_UPLOADED_BY}:</strong> {$CERT_METADATA.uploaded_by_name}
            </div>
        {/if}
        <br>
    {/if}

    <div style="margin-bottom: 20px; padding: 10px; background-color: #f9f9f9; border: 1px solid #ccc;">
        <strong>{$MOD.LBL_STIC_CERT_CURRENT_STATUS}</strong> 
        {if $CERT_EXISTS}
            <span style="color: green; font-weight: bold;">{$MOD.LBL_STIC_CERT_EXISTS}</span>
            {if $CERT_METADATA}
                <br><br>
                <strong>{$MOD.LBL_STIC_CERT_FILENAME}:</strong> {$CERT_METADATA.original_filename}<br>
                <strong>{$MOD.LBL_STIC_CERT_UPLOAD_DATE}:</strong> {$CERT_METADATA.upload_date_formatted}<br>
                <strong>{$MOD.LBL_STIC_CERT_UPLOADED_BY}:</strong> {$CERT_METADATA.uploaded_by_name}
            {/if}
        {else}
            <span style="color: red; font-weight: bold;">{$MOD.LBL_STIC_CERT_NOT_EXISTS}</span>
        {/if}
    </div>

    <form action="index.php" method="POST" enctype="multipart/form-data" id="certificateForm">
        <input type="hidden" name="module" value="Administration">
        <input type="hidden" name="action" value="SticSaveCertificate">
        
        <table class="edit view" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="20%" scope="row" style="vertical-align: middle;">
                    {$MOD.LBL_STIC_CERT_FILE}
                </td>
                <td width="80%">
                    <input type="file" name="certificate_file" required>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 20px;">
                    <input type="submit" class="button primary" value="{$MOD.LBL_STIC_CERT_UPLOAD_BTN}">
                </td>
            </tr>
        </table>
    </form>
</div>

<script type="text/javascript">
{literal}
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('certificateForm');
    var certExists = {/literal}{if $CERT_EXISTS}true{else}false{/if}{literal};
    
    if (form && certExists) {
        form.addEventListener('submit', function(e) {
            var confirmMsg = {/literal}'{$MOD.LBL_STIC_CERT_CONFIRM_OVERWRITE}'{literal};
            if (!confirm(confirmMsg)) {
                e.preventDefault();
                return false;
            }
        });
    }
});
{/literal}
</script>