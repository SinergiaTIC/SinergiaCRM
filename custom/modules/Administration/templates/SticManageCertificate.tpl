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
        {if $MSG_ERROR}
            <div class="stic-cert-alert stic-cert-error">{$MESSAGE}</div>
        {else}
            <div class="stic-cert-alert stic-cert-success">
                <strong>{$MESSAGE}</strong>
                {if $CERT_METADATA}
                    <br><br>
                    <strong>{$MOD.LBL_STIC_CERT_UPLOAD_INFO}:</strong><br>
                    <strong>{$MOD.LBL_STIC_CERT_FILENAME}:</strong> {$CERT_METADATA.original_filename}<br>
                    <strong>{$MOD.LBL_STIC_CERT_UPLOAD_DATE}:</strong> {$CERT_METADATA.upload_date_formatted}<br>
                    <strong>{$MOD.LBL_STIC_CERT_UPLOADED_BY}:</strong> {$CERT_METADATA.uploaded_by_name}
                {/if}
            </div>
        {/if}
        <br>
    {/if}

    <div class="stic-cert-box">
        <div class="stic-cert-box-title">{$MOD.LBL_STIC_CERT_CURRENT_STATUS}</div>
        {if $CERT_EXISTS}
            <span style="color: green; font-weight: bold;">{$MOD.LBL_STIC_CERT_EXISTS}</span>
            {if $CERT_METADATA}
                <div style="margin-left: 15px; margin-top: 10px;">
                    <strong>{$MOD.LBL_STIC_CERT_FILENAME}:</strong> {$CERT_METADATA.original_filename}<br>
                    <strong>{$MOD.LBL_STIC_CERT_UPLOAD_DATE}:</strong> {$CERT_METADATA.upload_date_formatted}<br>
                    <strong>{$MOD.LBL_STIC_CERT_UPLOADED_BY}:</strong> {$CERT_METADATA.uploaded_by_name}
                    
                    {* Extracted NIF and Holder Name - This is what will be used for Verifactu *}
                    <br><br>
                    <div style="padding: 10px; background-color: #e8f5e9; border-left: 4px solid #4caf50;">
                        <strong style="color: #2e7d32;">{$MOD.LBL_STIC_CERT_EXTRACTED_DATA}:</strong><br>
                        {if $EXTRACTED_NIF}
                            <strong>{$MOD.LBL_STIC_CERT_EXTRACTED_NIF}:</strong> <span style="font-size: 1.1em; color: #1976d2; font-weight: bold;">{$EXTRACTED_NIF}</span><br>
                        {else}
                            <strong>{$MOD.LBL_STIC_CERT_EXTRACTED_NIF}:</strong> <span style="color: red;">⚠ {$MOD.LBL_STIC_CERT_NOT_FOUND}</span><br>
                        {/if}
                        {if $EXTRACTED_NAME}
                            <strong>{$MOD.LBL_STIC_CERT_EXTRACTED_NAME}:</strong> <span style="font-size: 1.1em; color: #1976d2; font-weight: bold;">{$EXTRACTED_NAME}</span><br>
                        {else}
                            <strong>{$MOD.LBL_STIC_CERT_EXTRACTED_NAME}:</strong> <span style="color: red;">⚠ {$MOD.LBL_STIC_CERT_NOT_FOUND}</span><br>
                        {/if}
                        {if $IS_ENTITY_SEAL !== null}
                            <strong>{$MOD.LBL_STIC_CERT_EXTRACTED_TYPE}:</strong> 
                            {if $IS_ENTITY_SEAL == 1}
                                <span style="font-size: 1.1em; color: #1976d2; font-weight: bold;">{$MOD.LBL_STIC_CERT_TYPE_ENTITY_SEAL}</span>
                            {elseif $IS_ENTITY_SEAL == 0}
                                <span style="font-size: 1.1em; color: #1976d2; font-weight: bold;">{$MOD.LBL_STIC_CERT_TYPE_REPRESENTATIVE}</span>
                            {/if}
                        {else}
                            <strong>{$MOD.LBL_STIC_CERT_EXTRACTED_TYPE}:</strong> <span style="color: #ff9800;">⚠ {$MOD.LBL_STIC_CERT_TYPE_UNKNOWN}</span>
                        {/if}
                        <br><br>
                        <small style="color: #666;">
                            <em>{$MOD.LBL_STIC_CERT_EXTRACTED_INFO}</em>
                        </small>
                    </div>
                    
                    {if $CERT_METADATA.cert_details}
                        <br><br>
                        <strong style="text-decoration: underline;">{$MOD.LBL_STIC_CERT_UPLOAD_INFO}:</strong><br>
                        {if $CERT_METADATA.cert_details.subject}
                            <strong>{$MOD.LBL_STIC_CERT_SUBJECT}:</strong> {$CERT_METADATA.cert_details.subject}<br>
                        {/if}
                        {if $CERT_METADATA.cert_details.issuer}
                            <strong>{$MOD.LBL_STIC_CERT_ISSUER}:</strong> {$CERT_METADATA.cert_details.issuer}<br>
                        {/if}
                        {if $CERT_METADATA.cert_details.valid_from}
                            <strong>{$MOD.LBL_STIC_CERT_VALID_FROM}:</strong> {$CERT_METADATA.cert_details.valid_from}<br>
                        {/if}
                        {if $CERT_METADATA.cert_details.valid_to}
                            <strong>{$MOD.LBL_STIC_CERT_VALID_TO}:</strong> {$CERT_METADATA.cert_details.valid_to}<br>
                        {/if}
                        {if $CERT_METADATA.cert_details.serial_number}
                            <strong>{$MOD.LBL_STIC_CERT_SERIAL}:</strong> {$CERT_METADATA.cert_details.serial_number}<br>
                        {/if}
                    {/if}
                </div>
            {/if}
            <form action="index.php" method="POST" id="certificateDeleteForm" style="margin-top: 15px; display: flex; justify-content: flex-end;">
                <input type="hidden" name="module" value="Administration">
                <input type="hidden" name="action" value="SticDeleteCertificate">
                <input type="submit" class="button" value="{$MOD.LBL_STIC_CERT_DELETE_BTN}">
            </form>
        {else}
            <span style="color: red; font-weight: bold;">{$MOD.LBL_STIC_CERT_NOT_EXISTS}</span>
        {/if}
    </div>

    {if !$CERT_EXISTS}
        <div class="stic-cert-box">
            <div class="stic-cert-box-title">{$MOD.LBL_STIC_CERT_INSTALL_TITLE}</div>
            <form action="index.php" method="POST" enctype="multipart/form-data" id="certificateForm">
                <input type="hidden" name="module" value="Administration">
                <input type="hidden" name="action" value="SticSaveCertificate">
                
                <div class="stic-cert-field">
                    <div class="stic-cert-field-label">{$MOD.LBL_STIC_CERT_FILE}</div>
                    <div class="stic-cert-field-value">
                        <input type="file" name="certificate_file" required>
                        <span class="stic-cert-help">{$MOD.LBL_STIC_CERT_FILE_HELP}</span>
                    </div>
                </div>
                <div class="stic-cert-field">
                    <div class="stic-cert-field-label">{$MOD.LBL_STIC_CERT_PASSWORD}<span style="color:red;">*</span></div>
                    <div class="stic-cert-field-value">
                        <input type="password" name="certificate_password" id="certificate_password" required autocomplete="off">
                        <span class="stic-cert-help">{$MOD.LBL_STIC_CERT_PASSWORD_HELP}</span>
                    </div>
                </div>
                <div class="stic-cert-field">
                    <div class="stic-cert-field-label"></div>
                    <div class="stic-cert-field-value stic-cert-field-actions">
                        <input type="submit" class="button primary" value="{$MOD.LBL_STIC_CERT_UPLOAD_BTN}">
                    </div>
                </div>
            </form>
        </div>
    {/if}
</div>

<script type="text/javascript">
{literal}
document.addEventListener('DOMContentLoaded', function() {
    var deleteForm = document.getElementById('certificateDeleteForm');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            var confirmMsg = {/literal}'{$MOD.LBL_STIC_CERT_CONFIRM_DELETE}'{literal};
            if (!confirm(confirmMsg)) {
                e.preventDefault();
                return false;
            }
        });
    }
});
{/literal}
</script>

<style>
{literal}
.stic-cert-alert {
    margin-bottom: 20px;
    padding: 12px;
    border-radius: 4px;
    border: 1px solid;
}
.stic-cert-success {
    background-color: #e8f5e9;
    border-color: #4caf50;
    color: #2e7d32;
}
.stic-cert-error {
    background-color: #fbe9e7;
    border-color: #d32f2f;
    color: #c62828;
}
.stic-cert-error a {
    color: #c62828;
}
.stic-cert-box {
    margin-bottom: 20px;
    padding: 15px;
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    border-radius: 4px;
}
.stic-cert-box-title {
    font-weight: bold;
    font-size: 1.05em;
    margin-bottom: 15px;
}
.stic-cert-field {
    display: flex;
    flex-wrap: wrap;
    align-items: baseline;
    margin-bottom: 12px;
}
.stic-cert-field-label {
    width: 20%;
    padding-right: 15px;
    box-sizing: border-box;
}
.stic-cert-field-value {
    width: 80%;
    box-sizing: border-box;
}
.stic-cert-field-value input[type="file"],
.stic-cert-field-value input[type="password"] {
    width: 100%;
    max-width: 400px;
    box-sizing: border-box;
}
.stic-cert-help {
    display: block;
    font-size: 0.9em;
    color: #666;
    margin-top: 4px;
}
.stic-cert-field-actions {
    display: flex;
    justify-content: flex-end;
}
@media (max-width: 768px) {
    .stic-cert-field-label,
    .stic-cert-field-value {
        width: 100%;
        padding-right: 0;
    }
    .stic-cert-field-label {
        margin-bottom: 4px;
    }
}
{/literal}
</style>