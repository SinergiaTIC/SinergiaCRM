{literal}
<style>
    .whistleblowing-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 22px;
        vertical-align: middle;
    }
    .whistleblowing-switch input { opacity: 0; width: 0; height: 0; }
    .whistleblowing-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 22px;
    }
    .whistleblowing-slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .whistleblowing-slider { background-color: #2196F3; }
    input:checked + .whistleblowing-slider:before { transform: translateX(22px); }

    .whistleblowing-row {
        margin-bottom: 20px;
        padding: 20px;
        background: #fdfdfd;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .whistleblowing-label-header {
        font-weight: bold;
        font-size: 14px;
        color: #333;
        margin-bottom: 12px;
        display: block;
        text-transform: uppercase;
        border-bottom: 1px dashed #ccc;
        padding-bottom: 5px;
    }
    .whistle-textarea {
        width: 100%;
        padding: 10px;
        font-family: sans-serif;
        font-size: 13px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #fff;
        resize: vertical;
        min-height: 180px;
    }
</style>
{/literal}

<form id="whistleblowingConfig" name="whistleblowingConfig" method="POST" action="index.php?module=Administration&action=WhistleblowingConfig_Admin">
    <input type="hidden" name="do" value="save">
    
    <table width="100%" cellpadding="0" cellspacing="10" border="0" class="actionsContainer">
        <tr>
            <th align="left">
                <h2>{$MOD.LBL_WHISTLEBLOWING_TITLE}</h2>
            </th>
            <th align="right">
                <input class="button primary" type="submit" value="{$APP.LBL_SAVE_BUTTON_LABEL}" />
            </th>
        </tr>
    </table>

    <div class="edit view">
        <div class="whistleblowing-row">
            <span class="whistleblowing-label-header">{$MOD.LBL_WHISTLEBLOWING_STATUS_HEADER}</span>
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td width="25%" class="dataLabel">{$MOD.LBL_WHISTLEBLOWING_ENABLE_LABEL}</td>
                    <td width="75%" class="dataField">
                        <label class="whistleblowing-switch">
                            <input type="hidden" name="whistleblowing_enabled" value="0">
                            <input type="checkbox" id="whistleblowing_enabled" name="whistleblowing_enabled" value="1" 
                                   {if $config.whistleblowing_enabled == '1'}checked{/if} 
                                   onchange="toggleWhistle(this.checked)">
                            <span class="whistleblowing-slider"></span>
                        </label>
                    </td>
                </tr>
            </table>
        </div>

        <div id="whistleblowing_config_body" {if $config.whistleblowing_enabled != '1'}style="display:none;"{/if}>
            
            <div class="whistleblowing-row" style="background-color: #eef2e1; border: 1px solid #8ca33e;">
                <label class="whistleblowing-label-header" style="color: #5d6d29;">{$MOD.LBL_WHISTLEBLOWING_LINK_TITLE}</label>
                <p style="margin: 5px 0;">
                    <a href="{$config.whistleblowing_url}" target="_blank" style="font-weight: bold; color: #2196F3; text-decoration: underline;">
                        {$config.whistleblowing_url}
                    </a>
                </p>
                <div class="whistle-help-text">{$MOD.LBL_WHISTLEBLOWING_LINK_HELP}</div>
            </div>
            
            <div class="whistleblowing-row">
                <label class="whistleblowing-label-header">{$MOD.LBL_WHISTLEBLOWING_SECTION_ABOUT}</label>
                <textarea name="whistleblowing_text_about" class="whistle-textarea">{$config.whistleblowing_text_about}</textarea>
            </div>

            <div class="whistleblowing-row">
                <label class="whistleblowing-label-header">{$MOD.LBL_WHISTLEBLOWING_SECTION_CONFID}</label>
                <textarea name="whistleblowing_text_confidentiality" class="whistle-textarea">{$config.whistleblowing_text_confidentiality}</textarea>
            </div>

            <div class="whistleblowing-row">
                <label class="whistleblowing-label-header">{$MOD.LBL_WHISTLEBLOWING_SECTION_SECURITY}</label>
                <textarea name="whistleblowing_text_security" class="whistle-textarea">{$config.whistleblowing_text_security}</textarea>
            </div>

        </div>
    </div>
</form>

{literal}
<script type="text/javascript">
    function toggleWhistle(isEnabled) {
        isEnabled ? jQuery('#whistleblowing_config_body').slideDown() : jQuery('#whistleblowing_config_body').slideUp();
    }
</script>
{/literal}