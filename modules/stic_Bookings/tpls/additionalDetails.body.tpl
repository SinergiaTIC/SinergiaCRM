{if !empty($FIELD.NAME)}
    <div>
        <a href="index.php?action=DetailView&module={$MODULE_NAME}&record={$FIELD.ID}">{$FIELD.NAME}</a>
    </div>
{/if}
<br>
{if !empty($FIELD.RESOURCE_NAME)}
    <div>
        <strong>{$PARAM.LBL_RESOURCE_NAME}:</strong>
        <a href="index.php?action=DetailView&module=stic_Resources&record={$FIELD.RESOURCE_ID}">{$FIELD.RESOURCE_NAME}</a>
    </div>
{/if}
{if !empty($FIELD.RESOURCE_COUNT)}
    <div>
        <strong>{$PARAM.LBL_RESOURCE_COUNT}:</strong>
        {$FIELD.RESOURCE_COUNT}
    </div>
{/if}
{if !empty($FIELD.RESOURCES_LIST)}
    <div>
        <strong>{$PARAM.LBL_RESOURCES_LIST}:</strong>
        {foreach from=$FIELD.RESOURCES_LIST item=RESOURCE}
            </br>- <a href="index.php?module=stic_Resources&action=DetailView&record={$RESOURCE.id}">{$RESOURCE.name}</a>
        {/foreach}
    </div>
{/if}
{if !empty($FIELD.STATUS)}
    <div data-field="STATUS" data-date="{$FIELD.DB_STATUS}">
        <strong>{$PARAM.LBL_STATUS}:</strong>
        {$FIELD.STATUS}
    </div>
{/if}
{if !empty($FIELD.USER_START_DATE)}
    <div data-field="START_DATE" data-date="{$FIELD.DB_START_DATE}">
        <strong>{$PARAM.LBL_START_DATE}:</strong>
        {$FIELD.USER_START_DATE}
    </div>
{/if}

{if !empty($FIELD.USER_END_DATE)}
    <div data-field="END_DATE" data-date="{$FIELD.DB_END_DATE}">
        <strong>{$PARAM.LBL_END_DATE}:</strong>
        {$FIELD.USER_END_DATE}
    </div>
{/if}
{if !empty($FIELD.STIC_BOOKINGS_CONTACTSCONTACTS_IDA)}
    <div>
        <strong>{$PARAM.LBL_STIC_BOOKINGS_CONTACTS_FROM_CONTACTS_TITLE}:</strong>
        <a
            href="index.php?module=Contacts&action=DetailView&record={$FIELD.STIC_BOOKINGS_CONTACTSCONTACTS_IDA}">{$FIELD.STIC_BOOKINGS_CONTACTS_NAME}</a>
    </div>
{/if}

{if !empty($FIELD.STIC_BOOKINGS_ACCOUNTSACCOUNTS_IDA)}
    <div>
        <strong>{$PARAM.LBL_STIC_BOOKINGS_ACCOUNTS_FROM_ACCOUNTS_TITLE}:</strong>
        <a
            href="index.php?module=Accounts&action=DetailView&record={$FIELD.STIC_BOOKINGS_ACCOUNTSACCOUNTS_IDA}">{$FIELD.STIC_BOOKINGS_ACCOUNTS_NAME}</a>
    </div>
{/if}
{if !empty($FIELD.ASSIGNED_USER_ID)}
    <div>
        <strong>{$PARAM.LBL_ASSIGNED_TO}:</strong>
        <a
            href="index.php?module=Employees&action=DetailView&record={$FIELD.ASSIGNED_USER_ID}">{$FIELD.ASSIGNED_USER_NAME}</a>
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION)}
    <div>
        <strong>{$PARAM.LBL_DESCRIPTION}:</strong>
        {$FIELD.DESCRIPTION}
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION) 
    || !empty($FIELD.RESOURCES_LIST) 
    || !empty($FIELD.RESOURCE_COUNT)}
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
