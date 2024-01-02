{if !empty($FIELD.NAME)}
    <div>
        <a href="index.php?action=DetailView&module={$MODULE_NAME}&record={$FIELD.ID}">{$FIELD.NAME}</a>
    </div>
{/if}
<br>
{if !empty($FIELD.START_DATE)}
    <div data-field="START_DATE" data-date="{$FIELD.DB_START_DATE}">
        <strong>{$PARAM.LBL_START_DATE}:</strong>
        {$FIELD.START_DATE}
    </div>
{/if}
{if !empty($FIELD.END_DATE)}
    <div data-field="END_DATE" data-date="{$FIELD.DB_END_DATE}">
        <strong>{$PARAM.LBL_END_DATE}:</strong>
        {$FIELD.END_DATE}
    </div>
{/if}
{if !empty($FIELD.WEEKDAY)}
    <div data-field="WEEKDAY" data-date="{$FIELD.DB_WEEKDAY}">
        <strong>{$PARAM.LBL_WEEKDAY}:</strong>
        {$FIELD.WEEKDAY}
    </div>
{/if}
{if !empty($FIELD.CONTACT_ID_C)}
    <div>
        <strong>{$PARAM.LBL_STIC_RESPONSIBLE}:</strong>
        <a href="index.php?module=Contacts&action=DetailView&record={$FIELD.CONTACT_ID_C}">{$FIELD.RESPONSIBLE}</a>
    </div>
{/if}
{if !empty($FIELD.DURATION)}
    <div data-field="DURATION" data-date="{$FIELD.DB_DURATION}">
        <strong>{$PARAM.LBL_DURATION}:</strong>
        {$FIELD.DURATION}
    </div>
{/if}

{if !empty($FIELD.TOTAL_ATTENDANCES) || $FIELD.TOTAL_ATTENDANCES == 0}
    <div data-field="TOTAL_ATTENDANCES" data-date="{$FIELD.DB_TOTAL_ATTENDANCES}">
        <strong>{$PARAM.LBL_TOTAL_ATTENDANCES}:</strong>
        {$FIELD.TOTAL_ATTENDANCES}
    </div>
{/if}

{if !empty($FIELD.VALIDATED_ATTENDANCES) || $FIELD.VALIDATED_ATTENDANCES == 0}
    <div data-field="VALIDATED_ATTENDANCES" data-date="{$FIELD.DB_VALIDATED_ATTENDANCES}">
        <strong>{$PARAM.LBL_VALIDATED_ATTENDANCES}:</strong>
        {$FIELD.VALIDATED_ATTENDANCES}
    </div>
{/if}

{if !empty($FIELD.STIC_SESSIONS_STIC_EVENTSSTIC_EVENTS_IDA)}
    <div>
        <strong>{$PARAM.LBL_STIC_SESSIONS_STIC_EVENTS_FROM_STIC_EVENTS_TITLE}:</strong>
        <a href="index.php?module=stic_Events&action=DetailView&record={$FIELD.STIC_SESSIONS_STIC_EVENTSSTIC_EVENTS_IDA}">{$FIELD.STIC_SESSIONS_STIC_EVENTS_NAME}</a>
    </div>
{/if}

{if !empty($FIELD.ACTIVITY_TYPE)}
    <div>
        <strong>{$PARAM.LBL_ACTIVITY_TYPE}:</strong>
        {$FIELD.ACTIVITY_TYPE}
    </div>
{/if}
{if !empty($FIELD.ASSIGNED_USER_ID)}
    <div>
        <strong>{$PARAM.LBL_ASSIGNED_TO}:</strong>
        <a href="index.php?module=Employees&action=DetailView&record={$FIELD.ASSIGNED_USER_ID}">{$FIELD.ASSIGNED_USER_NAME}</a>
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
    || !empty($FIELD.WEEKDAY) 
    || !empty($FIELD.END_DATE)    
    || !empty($FIELD.START_DATE) 
    || !empty($FIELD.DURATION) 
    || !empty($FIELD.TOTAL_ATTENDANCES) 
    || !empty($FIELD.VALIDATED_ATTENDANCES)    
    || !empty($FIELD.STIC_SESSIONS_STIC_EVENTSSTIC_EVENTS_IDA) 
    || !empty($FIELD.ACTIVITY_TYPE) 
    || !empty($FIELD.ASSIGNED_USER_ID)}
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
