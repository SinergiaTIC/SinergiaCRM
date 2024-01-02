{if !empty($FIELD.NAME)}
    <div>
        <a href="index.php?action=DetailView&module={$MODULE_NAME}&record={$FIELD.ID}">{$FIELD.NAME}</a>
    </div>
{/if}
<br>
{if !empty($FIELD.TRANSACTION_CODE)}
    <div>
        <strong>{$PARAM.LBL_TRANSACTION_CODE}:</strong>
        {$FIELD.TRANSACTION_CODE}
    </div>
{/if}
{if !empty($FIELD.BANKING_CONCEPT)}
    <div>
        <strong>{$PARAM.LBL_BANKING_CONCEPT}:</strong>
        {$FIELD.BANKING_CONCEPT}
    </div>
{/if}
{if !empty($FIELD.BANK_ACCOUNT)}
    <div>
        <strong>{$PARAM.LBL_BANK_ACCOUNT}:</strong>
        {$FIELD.BANK_ACCOUNT}
    </div>
{/if}

{if !empty($FIELD.M182_EXCLUDED)}
    <div>
        <strong>{$PARAM.LBL_M182_EXCLUDED}:</strong>
        {$FIELD.M182_EXCLUDED}
    </div>
{/if}
{if !empty($FIELD.REJECTION_DATE)}
<div>
    <strong>{$PARAM.LBL_REJECTION_DATE}:</strong>
    {$FIELD.REJECTION_DATE}
</div>
{/if}
{if !empty($FIELD.PAYMENT_METHOD)}
<div>
    <strong>{$PARAM.LBL_PAYMENT_METHOD}:</strong>
    {$FIELD.PAYMENT_METHOD}
</div>
{/if}
{if !empty($FIELD.SEPA_REJECTED_REASON)}
<div>
    <strong>{$PARAM.LBL_SEPA_REJECTED_REASON}:</strong>
    {$FIELD.SEPA_REJECTED_REASON}
</div>
{/if}
{if !empty($FIELD.GATEWAY_REJECTION_REASON)}
<div>
    <strong>{$PARAM.LBL_GATEWAY_REJECTION_REASON}:</strong>
    {$FIELD.GATEWAY_REJECTION_REASON}
</div>
{/if}
{if !empty($FIELD.AGGREGATED_SERVICES_COMPLETE)}
<div>
    <strong>{$PARAM.LBL_AGGREGATED_SERVICES_COMPLETE}:</strong>
    {$FIELD.AGGREGATED_SERVICES_COMPLETE}
</div>
{/if}
{if !empty($FIELD.SEGMENTATION)}
<div>
    <strong>{$PARAM.LBL_SEGMENTATION}:</strong>
    {$FIELD.SEGMENTATION}
</div>
{/if}
{if !empty($FIELD.DESCRIPTION)}
    <div>
        <strong>{$PARAM.LBL_DESCRIPTION}:</strong>
        {$FIELD.DESCRIPTION}
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION) 
    || !empty($FIELD.SEGMENTATION) 
    || !empty($FIELD.AGGREGATED_SERVICES_COMPLETE) 
    || !empty($FIELD.GATEWAY_REJECTION_REASON) 
    || !empty($FIELD.SEPA_REJECTED_REASON)
    || !empty($FIELD.PAYMENT_METHOD)
    || !empty($FIELD.REJECTION_DATE)
    || !empty($FIELD.M182_EXCLUDED)
    || !empty($FIELD.BANK_ACCOUNT)
    || !empty($FIELD.BANKING_CONCEPT)
    || !empty($FIELD.TRANSACTION_CODE)}
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