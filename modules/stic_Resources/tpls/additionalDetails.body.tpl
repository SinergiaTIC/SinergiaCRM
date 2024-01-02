{* This template is showed both in Resources' ListView and Bookings Calendar availability popups *}

{if !empty($FIELD.NAME)}
    <div>
        <a href="index.php?action=DetailView&module={$MODULE_NAME}&record={$FIELD.ID}">{$FIELD.NAME}</a>
    </div>
{/if}
{if !empty($FIELD.CONTACT_ID_C)}
    <div>
        <strong>{$PARAM.LBL_OWNER_CONTACT}: </strong>
        <a href="index.php?module=Contacts&action=DetailView&record={$FIELD.CONTACT_ID_C}">{$FIELD.OWNER_CONTACT}</a>
    </div>
{/if}
{if !empty($FIELD.ACCOUNT_ID_C)}
    <div>
        <strong>{$PARAM.LBL_OWNER_ACCOUNT}: </strong>
        <a href="index.php?module=Accounts&action=DetailView&record={$FIELD.ACCOUNT_ID_C}">{$FIELD.OWNER_ACCOUNT}</a>
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION)}
    <div>
        <strong>{$PARAM.LBL_DESCRIPTION}: </strong>
        {$FIELD.DESCRIPTION}
    </div>
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
