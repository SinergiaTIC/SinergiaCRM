{if !empty($FIELD.NAME)}
    <div>
        <a href="index.php?action=DetailView&module={$MODULE_NAME}&record={$FIELD.ID}">{$FIELD.NAME}</a>
    </div>
{/if}
<br>
{if !empty($FIELD.MEDICATION)}
    <div>
        <strong>{$PARAM.LBL_MEDICATION}:</strong>
        {$FIELD.MEDICATION}
    </div>
{/if}
{if !empty($FIELD.DOSAGE)}
    <div>
        <strong>{$PARAM.LBL_DOSAGE}:</strong>
        {$FIELD.DOSAGE}
    </div>
{/if}
{if !empty($FIELD.STOCK_DEPLETION)}
    <div>
        <strong>{$PARAM.LBL_STOCK_DEPLETION}:</strong>
        {$FIELD.STOCK_DEPLETION}
    </div>
{/if}
{if !empty($FIELD.TIME)}
    <div>
        <strong>{$PARAM.LBL_TIME}:</strong>
        {$FIELD.TIME}
    </div>
{/if}
{if !empty($FIELD.PRESCRIPTION)}
    <div>
        <strong>{$PARAM.LBL_PRESCRIPTION}:</strong>
        Yes
    </div>
{/if}
{if !empty($FIELD.CONTACT_ID_C)}
    <div>
        <strong>{$PARAM.LBL_PRESCRIBER}:</strong>
        <a href="index.php?module=Contacts&action=DetailView&record={$FIELD.CONTACT_ID_C}">{$FIELD.PRESCRIBER}</a>
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION)}
    <div>
        <strong>{$PARAM.LBL_DESCRIPTION}:</strong>
        {$FIELD.DESCRIPTION}
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION) 
    || !empty($FIELD.CONTACT_ID_C) 
    || !empty($FIELD.TIME) 
    || !empty($FIELD.STOCK_DEPLETION) 
    || !empty($FIELD.LBL_DOSAGE)
    || !empty($FIELD.PRESCRIPTION)
    || !empty($FIELD.MEDICATION)}
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
