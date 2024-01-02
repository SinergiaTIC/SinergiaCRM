{if !empty($FIELD.NAME)}
    <div>
        <a href="index.php?action=DetailView&module={$MODULE_NAME}&record={$FIELD.ID}">{$FIELD.NAME}</a>
    </div>
{/if}
<br>
{if !empty($FIELD.CONCLUSIONS)}
    <div>
        <strong>{$PARAM.LBL_CONCLUSIONS}:</strong>
        {$FIELD.CONCLUSIONS}
    </div>
{/if}
{if !empty($FIELD.DERIVATION)}
    <div>
        <strong>{$PARAM.LBL_DERIVATION}:</strong>
        {$FIELD.DERIVATION}
    </div>
{/if}
{if !empty($FIELD.WORKING_WITH)}
    <div>
        <strong>{$PARAM.LBL_WORKING_WITH}:</strong>
        {$FIELD.WORKING_WITH}
    </div>
{/if}
{if !empty($FIELD.RECOMMENDATIONS)}
    <div>
        <strong>{$PARAM.LBL_RECOMMENDATIONS}:</strong>
        {$FIELD.RECOMMENDATIONS}
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION)}
    <div>
        <strong>{$PARAM.LBL_DESCRIPTION}:</strong>
        {$FIELD.DESCRIPTION}
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION) 
    || !empty($FIELD.RECOMMENDATIONS) 
    || !empty($FIELD.WORKING_WITH) 
    || !empty($FIELD.DERIVATION)
    || !empty($FIELD.CONCLUSIONS)}
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