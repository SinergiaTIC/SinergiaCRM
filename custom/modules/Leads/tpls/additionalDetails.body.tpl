{if !empty($FIELD.NAME)}
    <div>
        <a href="index.php?action=DetailView&module={$MODULE_NAME}&record={$FIELD.ID}">{$FIELD.NAME}</a>
    </div>
{/if}
<br>
{if !empty($FIELD.STIC_ACQUISITION_CHANNEL_C)}
    <div>
        <strong>{$PARAM.LBL_STIC_ACQUISITION_CHANNEL}:</strong>
        {$FIELD.STIC_ACQUISITION_CHANNEL_C}
    </div>
{/if}
{if !empty($FIELD.STIC_IDENTIFICATION_NUMBER_C)}
    <div>
        <strong>{$FIELD.STIC_IDENTIFICATION_TYPE_C}:</strong>
        {$FIELD.STIC_IDENTIFICATION_NUMBER_C}
    </div>
{/if}
{if !empty($FIELD.STIC_LANGUAGE_C)}
    <div>
        <strong>{$PARAM.LBL_STIC_LANGUAGE}:</strong>
        {$FIELD.STIC_LANGUAGE_C}
    </div>
{/if}
{if !empty($FIELD.PRIMARY_ADDRESS_STREET) || !empty($FIELD.PRIMARY_ADDRESS_CITY) || !empty($FIELD.PRIMARY_ADDRESS_STATE) || !empty($FIELD.PRIMARY_ADDRESS_POSTALCODE)}
    <div>
        <strong>{$PARAM.LBL_PRIMARY_ADDRESS}</strong>
        {$FIELD.PRIMARY_ADDRESS_STREET}, {$FIELD.PRIMARY_ADDRESS_CITY}, {$FIELD.PRIMARY_ADDRESS_STATE}, {$FIELD.PRIMARY_ADDRESS_POSTALCODE} 
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION)}
    <div>
        <strong>{$PARAM.LBL_DESCRIPTION}</strong>
        {$FIELD.DESCRIPTION}
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION) 
    || !empty($FIELD.STIC_ACQUISITION_CHANNEL_C) 
    || !empty($FIELD.STIC_LANGUAGE_C) 
    || !empty($FIELD.PRIMARY_ADDRESS_STREET) 
    || !empty($FIELD.PRIMARY_ADDRESS_CITY) 
    || !empty($FIELD.PRIMARY_ADDRESS_STATE) 
    || !empty($FIELD.PRIMARY_ADDRESS_POSTALCODE)
    || !empty($FIELD.STIC_IDENTIFICATION_NUMBER_C)}
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
