{if !empty($FIELD.NAME)}
    <div>
        <a href="index.php?action=DetailView&module={$MODULE_NAME}&record={$FIELD.ID}">{$FIELD.NAME}</a>
    </div>
{/if}
<br>
{if !empty($FIELD.AREA)}
    <div>
        <strong>{$PARAM.LBL_AREA}:</strong>
         {$FIELD.AREA}
    </div>
{/if}
{if !empty($FIELD.SUBAREA)}
    <div>
        <strong>{$PARAM.LBL_SUBAREA}:</strong>
        {$FIELD.SUBAREA}
    </div>
{/if}
{if !empty($FIELD.LEVEL)}
        <strong>{$PARAM.LBL_LEVEL}:</strong>
        {$FIELD.LEVEL}
    </div>
{/if}

{if !empty($FIELD.STIC_GOALS_PROJECT_NAME)}
    <div>
        <strong>{$PARAM.LBL_STIC_GOALS_PROJECT_FROM_PROJECT_TITLE}:</strong>
        <a href="index.php?module=Project&action=DetailView&record={$FIELD.STIC_GOALS_PROJECTPROJECT_IDA}">{$FIELD.STIC_GOALS_PROJECT_NAME}</a>
    </div>
{/if}
{if !empty($FIELD.STIC_GOALS_STIC_REGISTRATIONS_NAME)}
    <div>
        <strong>{$PARAM.LBL_STIC_GOALS_STIC_REGISTRATIONS_FROM_STIC_REGISTRATIONS_TITLE}:</strong>
        <a href="index.php?module=stic_Registrations&action=DetailView&record={$FIELD.STIC_GOALS_STIC_REGISTRATIONSSTIC_REGISTRATIONS_IDA}">{$FIELD.STIC_GOALS_STIC_REGISTRATIONS_NAME}</a>
    </div>
{/if}
{if !empty($FIELD.STIC_GOALS_STIC_ASSESSMENTS_NAME)}
    <div>
        <strong>{$PARAM.LBL_STIC_GOALS_STIC_ASSESSMENTS_FROM_STIC_ASSESSMENTS_TITLE}:</strong>
        <a href="index.php?module=stic_Assessments&action=DetailView&record={$FIELD.STIC_GOALS_STIC_ASSESSMENTSSTIC_ASSESSMENTS_IDA}">{$FIELD.STIC_GOALS_STIC_ASSESSMENTS_NAME}</a>
    </div>
{/if}
{if !empty($FIELD.FOLLOW_UP)}
    <div>
        <strong>{$PARAM.LBL_FOLLOW_UP}:</strong>
        {$FIELD.FOLLOW_UP}
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION)}
    <div>
        <strong>{$PARAM.LBL_DESCRIPTION}:</strong>
        {$FIELD.DESCRIPTION}
    </div>
{/if}
{if !empty($FIELD.DESCRIPTION) 
    || !empty($FIELD.FOLLOW_UP) 
    || !empty($FIELD.STIC_GOALS_STIC_ASSESSMENTS_NAME) 
    || !empty($FIELD.STIC_GOALS_PROJECT_NAME)
    || !empty($FIELD.LEVEL) 
    || !empty($FIELD.SUBAREA) 
    || !empty($FIELD.AREA)}
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