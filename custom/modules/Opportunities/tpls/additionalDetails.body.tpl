{if !empty($FIELD.NAME)}
    <div>
        <a href="index.php?action=DetailView&module={$MODULE_NAME}&record={$FIELD.ID}">{$FIELD.NAME}</a>
    </div>
{/if}
<br>
{if !empty($FIELD.STIC_ADVANCE_DATE_C)}
    <div>
        <strong>{$PARAM.LBL_STIC_ADVANCE_DATE}:</strong>
        {$FIELD.STIC_ADVANCE_DATE_C}
    </div>
{/if}
{if !empty($FIELD.DATE_CLOSED)}
    <div>
        <strong>{$PARAM.LBL_DATE_CLOSED}:</strong>
        {$FIELD.DATE_CLOSED}
    </div>
{/if}
{if !empty($FIELD.STIC_JUSTIFICATION_DATE_C)}
    <div>
        <strong>{$PARAM.LBL_STIC_JUSTIFICATION_DATE}:</strong>
        {$FIELD.STIC_JUSTIFICATION_DATE_C}
    </div>
{/if}
{if !empty($FIELD.STIC_PAYMENT_DATE_C)}
    <div>
        <strong>{$PARAM.LBL_STIC_PAYMENT_DATE}:</strong>
        {$FIELD.STIC_PAYMENT_DATE_C}
    </div>
{/if}
{if !empty($FIELD.STIC_AMOUNT_AWARDED_C)}
    <div>
        <strong>{$PARAM.LBL_STIC_AMOUNT_AWARDED}:</strong>
        {sugar_number_format var=$FIELD.STIC_AMOUNT_AWARDED_C stringFormat=false}

    </div>
{/if}
{if !empty($FIELD.STIC_AMOUNT_RECEIVED_C)}
    <div>
        <strong>{$PARAM.LBL_STIC_AMOUNT_RECEIVED}:</strong>
        {sugar_number_format var=$FIELD.STIC_AMOUNT_RECEIVED_C stringFormat=false}
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION)}
    <div>
        <strong>{$PARAM.LBL_DESCRIPTION}</strong>
        {$FIELD.DESCRIPTION}
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION) 
    || !empty($FIELD.STIC_AMOUNT_RECEIVED_C) 
    || !empty($FIELD.STIC_AMOUNT_AWARDED_C) 
    || !empty($FIELD.STIC_PAYMENT_DATE_C) 
    || !empty($FIELD.STIC_JUSTIFICATION_DATE_C)
    || !empty($FIELD.DATE_CLOSED)
    || !empty($FIELD.STIC_ADVANCE_DATE_C)}
    <br>
{/if}
{if !empty($FIELD.DATE_ENTERED)}
    <div>
        <strong>{$PARAM.LBL_DATE_ENTERED}:</strong>
        {$FIELD.DATE_ENTERED}
    </div>
{/if}
{if !empty($FIELD.DATE_MODIFIED)}
    <div>
        <strong>{$PARAM.LBL_DATE_MODIFIED}:</strong>
        {$FIELD.DATE_MODIFIED}
    </div>
{/if}