{if !empty($FIELD.NAME)}
    <div>
        <a href="index.php?action=DetailView&module={$MODULE_NAME}&record={$FIELD.ID}">{$FIELD.NAME}</a>
    </div>
{/if}
<br>
{if !empty($FIELD.STIC_TOTAL_ANNUAL_DONATIONS_C)}
    <div>
        <strong>{$PARAM.LBL_STIC_TOTAL_ANNUAL_DONATIONS}:</strong>
        {$FIELD.STIC_TOTAL_ANNUAL_DONATIONS_C}
    </div>
{/if}
{if !empty($FIELD.STIC_182_ERROR)}
    <div>
        <strong>{$PARAM.LBL_STIC_182_ERROR}:</strong>
        {$FIELD.STIC_182_ERROR}
    </div>
{/if}
{if !empty($FIELD.STIC_182_EXCLUDED)}
    <div>
        <strong>{$PARAM.LBL_STIC_182_EXCLUDED}:</strong>
        {$FIELD.STIC_182_EXCLUDED}
    </div>
{/if}
{if !empty($FIELD.STIC_LANGUAGE_C)}
    <div>
        <strong>{$PARAM.LBL_STIC_LANGUAGE}</strong>
        {$FIELD.STIC_LANGUAGE_C}
    </div>
{/if}
{if !empty($FIELD.BILLING_ADDRESS_STREET) || !empty($FIELD.BILLING_ADDRESS_CITY) || !empty($FIELD.BILLING_ADDRESS_STATE) || !empty($FIELD.BILLING_ADDRESS_POSTALCODE)}
    <div>
        <strong>{$PARAM.LBL_BILLING_ADDRESS}:</strong>
        {$FIELD.BILLING_ADDRESS_STREET}, {$FIELD.BILLING_ADDRESS_CITY}, {$FIELD.BILLING_ADDRESS_STATE}, {$FIELD.BILLING_ADDRESS_POSTALCODE} 
    </div>
{/if}
{if !empty($FIELD.STIC_IDENTIFICATION_NUMBER)}
    <div>
        <strong>{$PARAM.LBL_STIC_IDENTIFICATION_NUMBER}:</strong>
        {$FIELD.STIC_IDENTIFICATION_NUMBER}
    </div>
{/if}
{if !empty($FIELD.WEBSITE) && preg_match('/^https?:\/\/.+$/', $FIELD.WEBSITE)}
    <div>
        <strong>{$PARAM.LBL_WEBSITE}</strong>
        <a target='_blank' href="{$FIELD.WEBSITE}">{$FIELD.WEBSITE}</a>  

    </div>
{/if}
{if !empty($FIELD.DESCRIPTION)}
    <div>
        <strong>{$PARAM.LBL_DESCRIPTION}</strong>
        {$FIELD.DESCRIPTION}
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION) 
    || !empty($FIELD.WEBSITE) 
    || !empty($FIELD.STIC_IDENTIFICATION_NUMBER) 
    || !empty($FIELD.BILLING_ADDRESS_STREET) 
    || !empty($FIELD.BILLING_ADDRESS_CITY) 
    || !empty($FIELD.BILLING_ADDRESS_STATE) 
    || !empty($FIELD.BILLING_ADDRESS_POSTALCODE)
    || !empty($FIELD.STIC_LANGUAGE_C)
    || !empty($FIELD.STIC_182_EXCLUDED)
    || !empty($FIELD.STIC_182_ERROR) 
    || !empty($FIELD.STIC_TOTAL_ANNUAL_DONATIONS_C)}
    <br>
{/if}
{if !empty($FIELD.DATE_ENTERED)}
    <div>
        <strong>{$PARAM.LBL_DATE_ENTERED}</strong>
        {$FIELD.DATE_ENTERED}
    </div>
{/if}
{if !empty($FIELD.DATE_MODIFIED)}
    <div>
        <strong>{$PARAM.LBL_DATE_MODIFIED}</strong>
        {$FIELD.DATE_MODIFIED}
    </div>
{/if}