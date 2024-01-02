{if !empty($FIELD.NAME)}
    <div>
        <a href="index.php?action=DetailView&module={$MODULE_NAME}&record={$FIELD.ID}">{$FIELD.NAME}</a>
    </div>
{/if}
<br>
{if !empty($FIELD.CONTRACT_DESCRIPTION)}
    <div>
        <strong>{$PARAM.LBL_CONTRACT_DESCRIPTION}:</strong>
        {$FIELD.CONTRACT_DESCRIPTION}
    </div>
{/if}
{if !empty($FIELD.HOURS_PER_WEEK)}
    <div>
        <strong>{$PARAM.LBL_HOURS_PER_WEEK}:</strong>
        {$FIELD.HOURS_PER_WEEK}
    </div>
{/if}
{if !empty($FIELD.RETRIBUTION)}
    <div>
        <strong>{$PARAM.LBL_RETRIBUTION}:</strong>
        {$FIELD.RETRIBUTION}
    </div>
{/if}
{if !empty($FIELD.PROCESS_START_DATE)}
    <div>
        <strong>{$PARAM.LBL_PROCESS_START_DATE}:</strong>
        {$FIELD.PROCESS_START_DATE}
    </div>
{/if}
{if !empty($FIELD.OFFERED_POSITIONS)}
    <div>
        <strong>{$PARAM.LBL_OFFERED_POSITIONS}:</strong>
        {$FIELD.OFFERED_POSITIONS}
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION)}
    <div>
        <strong>{$PARAM.LBL_DESCRIPTION}:</strong>
        {$FIELD.DESCRIPTION}
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION) 
    || !empty($FIELD.OFFERED_POSITIONS) 
    || !empty($FIELD.PROCESS_START_DATE) 
    || !empty($FIELD.RETRIBUTION) 
    || !empty($FIELD.HOURS_PER_WEEK)
    || !empty($FIELD.CONTRACT_DESCRIPTION)}
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
