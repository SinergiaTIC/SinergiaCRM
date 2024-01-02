{if !empty($FIELD.ID)}
    <div>
        <a href="index.php?action=DetailView&module={$MODULE_NAME}&record={$FIELD.ID}">{$FIELD.NAME}</a>
    </div>
{/if}
<br>
{if !empty($FIELD.END_REASON)}
    <div>
        <strong>{$PARAM.LBL_END_REASON}:</strong>
        {$FIELD.END_REASON}
    </div>
{/if}
{if !empty($FIELD.OTHER_END_REASONS)}
    <div>
        <strong>{$PARAM.LBL_OTHER_END_REASONS}:</strong>
        {$FIELD.OTHER_END_REASONS}
    </div>
{/if}
{if !empty($FIELD.ROLE)}
    <div>
        <strong>{$PARAM.LBL_ROLE}:</strong>
        {$FIELD.ROLE}
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION)}
    <div>
        <strong>{$PARAM.LBL_DESCRIPTION}:</strong>
        {$FIELD.DESCRIPTION}
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION) 
    || !empty($FIELD.ROLE) 
    || !empty($FIELD.OTHER_END_REASONS) 
    || !empty($FIELD.END_REASON)}
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