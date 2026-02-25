<div class="moduleTitle">
    <h2>{$MOD.LBL_SEND_PHONE_MESSAGES}</h2>
    <div class="clear">
    </div>
</div>
<form action="index.php" method="post" name="selectMessageMarketing" id="selectMessageMarketing">
        <input type="hidden" name="module" id="module" value="{$MAP.MODULE}">
        <input type="hidden" name="action" id="action" value="{$MAP.ACTION}">
        <input type="hidden" name="return_module" id="return_module" value="{$MAP.RETURN_MODULE}">
        <input type="hidden" name="return_id" id="return_id" value="{$MAP.RETURN_ID}">
        <input type="hidden" name="record" id="record" value="{$MAP.RETURN_ID}">
        <input type="hidden" name="return_action" id="return_action" value="{$MAP.RETURN_ACTION}">
        <input type="hidden" name="test" id="test" value="{$MAP.TEST}">
        <p>
        </p>
        <h3>{$MOD.LBL_CAMPAIGNS_TITLE}</h3>
        <input id="sendButton" title="Send" class="button" onclick="this.form.module.value='stic_Message_Marketing';this.form.action.value='sendMessages';" type="submit" name="Schedule" value="  {$APP.LBL_SEND}  ">
        <input title="Cancel" accesskey="l" class="button" onclick="this.form.module.value='Campaigns';this.form.action.value='DetailView';this.form.record.value='{$MAP.RETURN_ID}';" type="submit" name="Schedule" value="  {$APP.LBL_EMAIL_CANCEL}  ">


<table id="messageMarketingTable" cellpadding="0" cellspacing="0" width="100%" border="0" class="list view">
    <tbody>
        <tr class="pagination" role="presentation">
            <td colspan="23" align="right">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <td align="left" nowrap="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td nowrap="" align="right"><button type="button" name="listViewStartButton" title="Start"
                                    class="button" disabled=""><img
                                        src="themes/default/images/start_off.gif?v=Dx39YvmDDH-3ByxmcdskTg" aborder="0"
                                        align="absmiddle" alt="Start"></button>&nbsp;&nbsp;<button type="button"
                                    name="listViewPrevButton" title="Previous" class="button" disabled=""><img
                                        src="themes/default/images/previous_off.gif?v=Dx39YvmDDH-3ByxmcdskTg" border="0"
                                        align="absmiddle" alt="Previous"></button>&nbsp;&nbsp;<span
                                    class="pageNumbers">(1 - 4 of 4)</span>&nbsp;&nbsp;<button type="button"
                                    name="listViewNextButton" title="Next" class="button" disabled=""><img
                                        src="themes/default/images/next_off.gif?v=Dx39YvmDDH-3ByxmcdskTg" aborder="0"
                                        align="absmiddle" alt="Next"></button>&nbsp;&nbsp;<button type="button"
                                    name="listViewEndButton" title="End" class="button" disabled=""><img
                                        src="themes/default/images/end_off.gif?v=Dx39YvmDDH-3ByxmcdskTg" border="0"
                                        align="absmiddle" alt="End"></button></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr height="20">
            <td scope="col" nowrap="">
            <!--
                <label class="glyphicon bootstrap-checkbox initialized-checkbox glyphicon-unchecked"></label>
                <span class="suitepicon suitepicon-action-caret" style="display: none;"></span>
            -->
                <input type="checkbox" class="bootstrap-checkbox-hidden checkbox" title="Select all" name="massall" id="massall" value="" onclick="sListView.check_all(document.MassUpdate, &quot;mass[]&quot;, this.checked)">
            </td>
            <td scope="col" width="35%" nowrap=""><span>{$MOD.LBL_NAME}</span></td>
            <td scope="col" width="15%" nowrap=""><span>{$MOD.LBL_STATUS}</span></td>
            <td scope="col" width="50%" nowrap=""><span>{$MOD.LBL_PROSPECT_LISTS_TITLE}</span></td>
        </tr>

        {foreach from=$MAP.MMLIST key=KEY item=ITEM}
            <tr height="20" class="evenListRowS1">
                <td><input onclick="sListView.check_item(this, document.MassUpdate)" type="checkbox" class="checkbox"
                        name="mass[]" value="{$KEY}"></td>
                <td scope="row" valign="TOP"><span>{$ITEM.name}</span></td>
                <td valign="TOP"><span>{$ITEM.status}</span></td>
                <td valign="TOP"><span>{$ITEM.lists}</span></td>
            </tr>

        {/foreach}

        <tr class="pagination" role="presentation">
            <td colspan="23" align="right">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <td align="left" nowrap="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td nowrap="" align="right"><button type="button" name="listViewStartButton" title="Start"
                                    class="button" disabled=""><img
                                        src="themes/default/images/start_off.gif?v=Dx39YvmDDH-3ByxmcdskTg" aborder="0"
                                        align="absmiddle" alt="Start"></button>&nbsp;&nbsp;<button type="button"
                                    name="listViewPrevButton" title="Previous" class="button" disabled=""><img
                                        src="themes/default/images/previous_off.gif?v=Dx39YvmDDH-3ByxmcdskTg" border="0"
                                        align="absmiddle" alt="Previous"></button>&nbsp;&nbsp;<span
                                    class="pageNumbers">(1 - 4 of 4)</span>&nbsp;&nbsp;<button type="button"
                                    name="listViewNextButton" title="Next" class="button" disabled=""><img
                                        src="themes/default/images/next_off.gif?v=Dx39YvmDDH-3ByxmcdskTg" aborder="0"
                                        align="absmiddle" alt="Next"></button>&nbsp;&nbsp;<button type="button"
                                    name="listViewEndButton" title="End" class="button" disabled=""><img
                                        src="themes/default/images/end_off.gif?v=Dx39YvmDDH-3ByxmcdskTg" border="0"
                                        align="absmiddle" alt="End"></button></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>

    </tbody>
</table>




</form>