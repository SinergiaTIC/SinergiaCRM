{if !empty($FIELD.NAME)}
    <div>
        <a href="index.php?action=DetailView&module={$MODULE_NAME}&record={$FIELD.ID}">{$FIELD.NAME}</a>
    </div>
{/if}
<br>

{if !empty($FIELD.PARENT_ID)}
    <div>
        <strong>{$MOD.LBL_RELATED_TO}</strong>
        <a href="index.php?module={$FIELD.PARENT_TYPE}&action=DetailView&record={$FIELD.PARENT_ID}">{$FIELD.PARENT_TYPE} - {$FIELD.PARENT_NAME}</a>
    </div>
{/if}
{if !empty($FIELD.STATUS)}
    <div>
        <strong>{$MOD.LBL_STATUS}</strong>
        {$FIELD.STATUS}
    </div>
{/if}
{if !empty($FIELD.DATE_START)}
        <strong>{$PARAM.LBL_LIST_DATE}:</strong>
        {$FIELD.DATE_START}
    </div>
{/if}

{if !empty($FIELD.DURATION_HOURS)  or !empty($FIELD.DURATION_MINUTES)}
    <div>
        <strong>{$MOD.LBL_DURATION}</strong>
        {if !empty($FIELD.DURATION_HOURS)}
            {$FIELD.DURATION_HOURS} {$MOD.LBL_HOURS_ABBREV}
        {/if}

        {if !empty($FIELD.DURATION_MINUTES)}
            {$FIELD.DURATION_MINUTES} {$MOD.LBL_MINSS_ABBREV}
        {/if}
    </div>
{/if}

{if !empty($FIELD.LOCATION)}
    <div>
        <strong>{$PARAM.LBL_LOCATION}</strong>
        {$FIELD.LOCATION}
    </div>
{/if}
{if !empty($FIELD.DIRECTION)}
    <div>
        <strong>{$PARAM.LBL_DIRECTION}:</strong>
        {$FIELD.DIRECTION}
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION)}
    <div>
        <strong>{$PARAM.LBL_DESCRIPTION}</strong>
        {$FIELD.DESCRIPTION}
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION) 
    || !empty($FIELD.DIRECTION) 
    || !empty($FIELD.LOCATION) 
    || !empty($FIELD.LIST_SUBJECT)
    || !empty($FIELD.DATE_START)
    || !empty($FIELD.DURATION_HOURS) 
    || !empty($FIELD.DURATION_MINUTES)}
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
