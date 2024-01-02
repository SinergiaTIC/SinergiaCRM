{if !empty($FIELD.NAME)}
    <div>
        <a href="index.php?action=DetailView&module={$MODULE_NAME}&record={$FIELD.ID}">{$FIELD.NAME}</a>
    </div>
{/if}
<br>
{if !empty($FIELD.CHANNEL)}
    <div>
        <strong>{$PARAM.LBL_CHANNEL}:</strong>
        {$FIELD.CHANNEL}
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

{if !empty($FIELD.ANNUALIZED_FEE)}
<div>
    <strong>{$PARAM.LBL_ANNUALIZED_FEE}:</strong>
    {$FIELD.ANNUALIZED_FEE}
</div>
{/if}
{if !empty($FIELD.TRANSACTION_TYPE)}
<div>
    <strong>{$PARAM.LBL_TRANSACTION_TYPE}:</strong>
    {$FIELD.TRANSACTION_TYPE}
</div>
{/if}
{if !empty($FIELD.MANDATE)}
<div>
    <strong>{$PARAM.LBL_MANDATE}:</strong>
    {$FIELD.MANDATE}
</div>
{/if}
{if !empty($FIELD.DESTINATION)}
<div>
    <strong>{$PARAM.LBL_DESTINATION}:</strong>
    {$FIELD.DESTINATION}
</div>
{/if}
{if !empty($FIELD.SIGNATURE_DATE)}
<div>
    <strong>{$PARAM.LBL_SIGNATURE_DATE}:</strong>
    {$FIELD.SIGNATURE_DATE}
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
    || !empty($FIELD.SIGNATURE_DATE) 
    || !empty($FIELD.MANDATE) 
    || !empty($FIELD.TRANSACTION_TYPE)
    || !empty($FIELD.BANK_ACCOUNT)
    || !empty($FIELD.BANKING_CONCEPT)
    || !empty($FIELD.CHANNEL)
    || !empty($FIELD.CHANNEL)}
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