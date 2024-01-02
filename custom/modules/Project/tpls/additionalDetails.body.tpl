{if !empty($FIELD.NAME)}
    <div>
        <a href="index.php?action=DetailView&module={$MODULE_NAME}&record={$FIELD.ID}">{$FIELD.NAME}</a>
    </div>
{/if}{if !empty($FIELD.PRIORITY)}
    <div>
        <strong>{$PARAM.LBL_PRIORITY}</strong>
        {$FIELD.PRIORITY}
    </div>
{/if}
{if !empty($FIELD.LIST_TOTAL_ESTIMATED_EFFORT)}
    <div>
        <strong>{$PARAM.LBL_LIST_TOTAL_ESTIMATED_EFFORT}</strong>
        {$FIELD.LIST_TOTAL_ESTIMATED_EFFORT}
    </div>
{/if}
{if !empty($FIELD.LIST_TOTAL_ACTUAL_EFFORT)}
    <div>
        <strong>{$PARAM.LBL_LIST_TOTAL_ACTUAL_EFFORT}</strong>
        {$FIELD.LIST_TOTAL_ACTUAL_EFFORT}
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION)}
    <div>
        <strong>{$PARAM.LBL_DESCRIPTION}</strong>
        {$FIELD.DESCRIPTION}
    </div>
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
