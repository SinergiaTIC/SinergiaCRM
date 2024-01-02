{if !empty($FIELD.NAME)}
    <div>
        <strong>{$FIELD.NAME}</strong>
    </div>
{/if}
{if !empty($FIELD.LBL_ADDRESS_STREET) || !empty($FIELD.LBL_ADDRESS_CITY) || !empty($FIELD.LBL_ADDRESS_STATE) || !empty($FIELD.ADDRESS_POSTALCODE)}
    <div>
        <strong>{$PARAM.LBL_ADDRESS}</strong>
        {$FIELD.ADDRESS_STREET}, {$FIELD.ADDRESS_CITY}, {$FIELD.ADDRESS_STATE} {$FIELD.ADDRESS_POSTALCODE}
    </div>
{/if}
{if !empty($FIELD.MOBILE_PHONE)}
    <div>
        <strong>{$PARAM.LBL_MOBILE_PHONE}:</strong>
        {$FIELD.MOBILE_PHONE}
    </div>
{/if}
{if !empty($FIELD.ANY_PHONE)}
    <div>
        <strong>{$PARAM.LBL_ANY_PHONE}:</strong>
        {$FIELD.ANY_PHONE}
    </div>
{/if}
{if !empty($FIELD.EMPLOYEE_STATUS)}
    <div>
        <strong>{$PARAM.LBL_EMPLOYEE_STATUS}:</strong>
        {$FIELD.EMPLOYEE_STATUS}
    </div>
{/if}
{if !empty($FIELD.PSW_MODIFIED)}
    <div>
        <strong>{$PARAM.LBL_PSW_MODIFIED}:</strong>
        {$FIELD.PSW_MODIFIED}
    </div>
{/if}

{if !empty($FIELD.DESCRIPTION)}
    <div>
        <strong>{$PARAM.LBL_DESCRIPTION}:</strong>
        {$FIELD.DESCRIPTION}
    </div>
{/if}
{if !empty($FIELD.DATE_ENTERED) }
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