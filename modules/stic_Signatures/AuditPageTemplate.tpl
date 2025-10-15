<div style="width: 100%; margin-bottom: 10px; background-color: #eee; border: 15px solid #eee;">

    <h1 style="font-size: 14pt; color: #161616; margin: 0; ">{$MOD_STRINGS.LBL_AUDIT_PAGE_TITLE}</h1>

    <h3 style="font-size: 11pt; font-weight: bold; color: #555;">{$MOD_STRINGS.LBL_AUDIT_PAGE_SIGNATURE_DATA_TITLE}</h3>

    <ul style="font-size: 8pt; line-height: .75;">
        <li><strong>{$MOD_STRINGS.LBL_AUDIT_PAGE_DOCUMENT_NAME}:</strong>&nbsp;<span>{$DOCUMENT_NAME}</span></li>
        <li><strong>{$MOD_STRINGS.LBL_AUDIT_PAGE_SIGNATURE_STATUS}:</strong>&nbsp;<span>{$SIGNER_STATUS}</span></li>
        <li><strong>{$MOD_STRINGS.LBL_AUDIT_PAGE_SIGNATURE_DATETIME}:</strong>&nbsp;<span>{$SIGNER_USER_TIME}</span></li>
        <li><strong>{$MOD_STRINGS.LBL_AUDIT_PAGE_SIGNATURE_MODE}:</strong>&nbsp;<span>{$SIGNER_MODE}</span></li>
    </ul>

    <h3 style="font-size: 11pt; font-weight: bold; color: #555;">{$MOD_STRINGS.LBL_AUDIT_PAGE_SIGNER_DATA_TITLE}</h3>

    <ul style="font-size: 8pt; line-height: .75;">
        <li><strong>{$MOD_STRINGS.LBL_AUDIT_PAGE_SIGNER_NAME}:</strong>&nbsp;<span>{$SIGNER_NAME}</span></li>
        <li><strong>{$MOD_STRINGS.LBL_AUDIT_PAGE_REPRESENTING}:</strong>&nbsp;<span>[propio|nombre representado]</span></li>
        <li><strong>{$MOD_STRINGS.LBL_AUDIT_PAGE_EMAIL}:</strong>&nbsp;<span>{$SIGNER_EMAIL}</span></li>
        <li><strong>{$MOD_STRINGS.LBL_AUDIT_PAGE_PHONE}:</strong>&nbsp;<span>{$SIGNER_PHONE}</span></li>
    </ul>

    <h3 style="font-size: 11pt; font-weight: bold; color: #555;">{$MOD_STRINGS.LBL_AUDIT_PAGE_EVENTS_LOG_TITLE}</h3>

    <ul style="font-size: 8pt; line-height: .75;">
        {foreach from=$SIGNER_LOG item=event}
            <li><strong>{$event.name}:</strong>&nbsp;<span>{$event.description}</span></li>
        {/foreach}
    </ul>
</div>