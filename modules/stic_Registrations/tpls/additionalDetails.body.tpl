{if !empty($FIELD.NAME)}
    <div>
        <a href="index.php?action=DetailView&module={$MODULE_NAME}&record={$FIELD.ID}">{$FIELD.NAME}</a>
    </div>
{/if}
{if !empty($FIELD.PARTICIPATION_TYPE)}
    <div>
        <strong>{$PARAM.LBL_PARTICIPATION_TYPE}:</strong>
        {$FIELD.PARTICIPATION_TYPE}
    </div>
{/if}
{if !empty($FIELD.ATTENDEES)}
    <div>
        <strong>{$PARAM.LBL_ATTENDEES}:</strong>
        {$FIELD.ATTENDEES}
    </div>
{/if}
{if !empty($FIELD.NOT_PARTICIPATING_REASON)}
    <div>
        <strong>{$PARAM.LBL_NOT_PARTICIPATING_REASON}:</strong>
        {$FIELD.NOT_PARTICIPATING_REASON}
    </div>
{/if}
{if !empty($FIELD.REJECTION_REASON)}
    <div>
        <strong>{$PARAM.LBL_REJECTION_REASON}:</strong>
        {$FIELD.REJECTION_REASON}
    </div>
{/if}
{if !empty($FIELD.SPECIAL_NEEDS)}
    <div>
        <strong>{$PARAM.LBL_SPECIAL_NEEDS}:</strong>
        {$FIELD.SPECIAL_NEEDS}
    </div>
{/if}
{if !empty($FIELD.SPECIAL_NEEDS_DESCRIPTION)}
    <div>
        <strong>{$PARAM.LBL_SPECIAL_NEEDS_DESCRIPTION}:</strong>
        {$FIELD.SPECIAL_NEEDS_DESCRIPTION}
    </div>
{/if}
{if !empty($FIELD.SESSION_AMOUNT)}
    <div>
        <strong>{$PARAM.LBL_SESSION_AMOUNT}:</strong>
        {sugar_number_format var=$FIELD.SESSION_AMOUNT stringFormat=false}
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION)}
    <div>
        <strong>{$PARAM.LBL_DESCRIPTION}:</strong>
        {$FIELD.DESCRIPTION}
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION) 
    || !empty($FIELD.SESSION_AMOUNT) 
    || !empty($FIELD.SPECIAL_NEEDS_DESCRIPTION) 
    || !empty($FIELD.SPECIAL_NEEDS) 
    || !empty($FIELD.REJECTION_REASON)
    || !empty($FIELD.NOT_PARTICIPATING_REASON)
    || !empty($FIELD.PARTICIPATION_TYPE) 
    || !empty($FIELD.ATTENDEES)}
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