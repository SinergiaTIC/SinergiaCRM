<div>
    <span class="required validation-message">{$message}</span>
    <table id="questionTable" class="table table-bordered">
        <tr>
            <th>
                {$MOD.LBL_SURVEY_QUESTION}
            </th>
            <th>
                {$MOD.LBL_SURVEY_TEXT}
            </th>
            <th>
                {$MOD.LBL_SURVEY_TYPE}
            </th>
            <th>
                {$MOD.LBL_SURVEY_REQUIRED}
            </th>
        </tr>
        {foreach from=$questions item=question}
            <tr>
                <td>
                    Q{$question.sort_order+1}
                </td>
                <td>
                    {$question.name}
                </td>
                <td>
                    {$question.type}
                </td>
                <td>
                    {if $question.required}
                        <span style="color:red;">*</span>
                    {/if}
                </td>
            </tr>
        {/foreach}
    </table>
</div>
