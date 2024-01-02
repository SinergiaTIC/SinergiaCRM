{if !empty($FIELD.NAME)}
    <div>
        <strong>{$PARAM.LBL_NAME}</strong>
        <a href="index.php?action=DetailView&module={$MODULE_NAME}&record={$FIELD.ID}">{$FIELD.NAME}</a>
    </div>
{/if}
<br>
{if !empty($FIELD.PARENT_ID)}
    <div>
        <strong>{$FIELD.LBL_RELATED_TO}</strong>
        <a href="index.php?module={$FIELD.PARENT_TYPE}&action=DetailView&record={$FIELD.PARENT_ID}">{$FIELD.PARENT_TYPE} - {$FIELD.PARENT_NAME}</a>
    </div>
{/if}
{if !empty($FIELD.PRIORITY)}
    <div>
        <strong>{$PARAM.LBL_PRIORITY}</strong>
        {$FIELD.PRIORITY}
    </div>
{/if}

{if !empty($FIELD.DATE_START)}
    <div data-field="DATE_START" data-date="{$FIELD.DB_DATE_START}">
        <strong>{$PARAM.LBL_DATE_TIME}</strong>
        {$FIELD.DATE_START}
    </div>
{/if}

{if !empty($FIELD.DATE_DUE)}
    <div data-field="DATE_DUE">
        <strong>{$PARAM.LBL_DUE_DATE}</strong>
        {$FIELD.DATE_DUE}
    </div>
{/if}
{if !empty($FIELD.STATUS)}
    <div>
        <strong>{$MOD.LBL_STATUS}</strong>
        {$FIELD.STATUS}
    </div>
{/if}


{if !empty($FIELD.DESCRIPTION)
    || !empty($FIELD.DATE_DUE)
    || !empty($FIELD.STATUS)
    || !empty($FIELD.DATE_START)
    || !empty($FIELD.PARENT_ID)
    || !empty($FIELD.PRIORITY)}
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
