{*
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

*}
{* BEGIN - SECURITY GROUPS *}
<script type="text/javascript" src='{sugar_getjspath file ='include/javascript/yui/build/selector/selector-min.js'}'></script>
<script language="Javascript" type="text/javascript">
{literal}
function cascadeAccessOption(action,selectEle) {
	var accessOption = selectEle.options[selectEle.selectedIndex].value;
	var accessLabel = selectEle.options[selectEle.selectedIndex].text;
	var nodes = YAHOO.util.Selector.query('.'+action);
	var selectId = '';
	for(i=0; i < nodes.length; i++) {
		// STIC-Custom AAM 20260206 - Verify if the row of the current select is hidden by the category filter, if so skip it to avoid changing values in hidden rows
		var parentRow = nodes[i].closest('tr');
		if (parentRow && parentRow.style.display === 'none') {
			continue; // Skip this element if its row is hidden
		}
		// END STIC-Custom
		
		selectId = nodes[i].id.substring(8);
//alert('selectId: '+selectId);
		nodes[i].value = accessOption;
		var roleCell = document.getElementById(selectId+'link');
		if(roleCell != undefined) {
			roleCell.innerHTML = accessLabel;
		}
	}
}
{/literal}
</script>
{* END - SECURITY GROUPS *}
<form method='POST' name='EditView' id='ACLEditView'>
<input type='hidden' name='record' value='{$ROLE.id}'>
<input type='hidden' name='module' value='ACLRoles'>
<input type='hidden' name='action' value='Save'>
<input type='hidden' name='return_record' value='{$RETURN.record}'>
<input type='hidden' name='return_action' value='{$RETURN.action}'>
<input type='hidden' name='return_module' value='{$RETURN.module}'>
<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button" onclick="this.form.action.value='Save';aclviewer.save('ACLEditView');return false;" type="button" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " id="SAVE_HEADER"> &nbsp;
<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}"   class='button' accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" type='button' name='save' value="  {$APP.LBL_CANCEL_BUTTON_LABEL} " class='button' onclick='aclviewer.view("{$ROLE.id}", "All");'>
</p>
<p>
</p>
{* STIC-Custom AAM 20251104 - Added scroll wrapper to ACL Roles Edit All Body to improve usability when many roles exist *}
<div id="acl-scroll-wrapper">
{* END STIC-Custom *}
<TABLE width='100%' class='detail view' border='0' cellpadding=0 cellspacing = 1  >
<TR id="ACLEditView_Access_Header">
<td id="ACLEditView_Access_Header_category">
	{* STIC-Custom AAM 20260106 - Added category filter input to ACL Roles Edit All Body *}
	<div style="position: relative;">
		<span style="position: absolute; left: 8px; top: 50%; transform: translateY(-50%); color: #666; font-size: 14px; pointer-events: none;">🔍</span>
		<input type="text" 
			   id="categoryFilter" 
			   placeholder="{$MOD.LBL_FILTER_CATEGORIES}" 
			   style="width: 100%; padding: 5px 45px 5px 30px; box-sizing: border-box;"
			   onkeyup="aclviewer.filterCategories(this.value)"
			   onchange="aclviewer.filterCategories(this.value)">
		<span id="clearFilter" 
			  onclick="aclviewer.clearCategoryFilter()" 
			  style="display: none; position: absolute; right: 8px; top: 50%; transform: translateY(-50%); color: #dc3545; font-size: 12px; cursor: pointer; font-weight: bold;"
			  title="{$MOD.LBL_CLEAR_FILTER}">❌</span>
	</div>
	{* END STIC-Custom *}
</td>

{* BEGIN - SECURITY GROUPS
Just get the accessOptions for the Accounts module and use for the header select...less file edits this way.
Not ideal but it'll work since it's the only way to get that info without editing DetailView.php to pass this with ACTION_NAMES
{foreach from=$ACTION_NAMES item="ACTION_LABEL" key="ACTION_NAME"}
*}
{foreach from=$CATEGORIES item="TYPES" key="CATEGORY_NAME"}
{if $CATEGORY_NAME=='Accounts'}

	{foreach from=$ACTION_NAMES item="ACTION_LABEL" key="ACTION_NAME"}
		{foreach from=$TYPES item="ACTIONS"}
			{foreach from=$ACTIONS item="ACTION" key="ACTION_NAME_ACTIVE"}
			{if $ACTION_NAME==$ACTION_NAME_ACTIVE}

			<td align='center'>
				<div align='center' id="{$ACTION_NAME}link" onclick="aclviewer.toggleDisplay('{$ACTION_NAME}')"><b>{$ACTION_LABEL}</b></div>
			{* STIC-Custom AAM 20260106 - Do not reset styles *}
				<div style="display: none; text-align: center;" id="{$ACTION_NAME}">
				<select name='act_guid{$ACTION_NAME}' id='act_guid{$ACTION_NAME}' onblur="cascadeAccessOption('{$ACTION_NAME}',this); aclviewer.toggleDisplay('{$ACTION_NAME}');" style="font-family: inherit; font-size: inherit; padding: 2px 4px;">
			{* END STIC-Custom *}
					{html_options options=$ACTION.accessOptions selected=$ACTION.aclaccess }
					</select>
				</div>
			</td>
			{*
	<td align='center' id="ACLEditView_Access_Header_{$ACTION_NAME}"><div align='center'><b>{$ACTION_LABEL}</b></div></td>
			*}
			{/if}
			{/foreach}
		{/foreach}
{foreachelse}

          <td colspan="2">&nbsp;</td>

{/foreach}
{/if}
{/foreach}
{* END - SECURITY GROUPS *}
</TR>
{literal}

	{/literal}
{foreach from=$CATEGORIES item="TYPES" key="CATEGORY_NAME"}


	{if $APP_LIST.moduleList[$CATEGORY_NAME]!='Users'}

	<TR id="ACLEditView_Access_{$CATEGORY_NAME}">
	<td width='20%' style="min-width: 20%; white-space: normal;" id="ACLEditView_Access_{$CATEGORY_NAME}_category"><b>
	{if $APP_LIST.moduleList[$CATEGORY_NAME]=='Users'}
	   {$MOD.LBL_USER_NAME_FOR_ROLE}
	{elseif !empty($APP_LIST.moduleList[$CATEGORY_NAME])}
	   {$APP_LIST.moduleList[$CATEGORY_NAME]}
	{else}
        {$CATEGORY_NAME}
	{/if}
	</b></td>
	{foreach from=$ACTION_NAMES item="ACTION_LABEL" key="ACTION_NAME"}
		{assign var='ACTION_FIND' value='false'}
		{foreach from=$TYPES item="ACTIONS"}
			{foreach from=$ACTIONS item="ACTION" key="ACTION_NAME_ACTIVE"}
				{if $ACTION_NAME==$ACTION_NAME_ACTIVE}
					{* STIC-Custom AAM 20260106 - Remove TDWITH *}
					<td nowrap style="text-align: center;" id="ACLEditView_Access_{$CATEGORY_NAME}_{$ACTION_NAME}">
					{* END STIC-Custom	*}					
				
					<div  style="display: none" id="{$ACTION.id}">
					{if $APP_LIST.moduleList[$CATEGORY_NAME]==$APP_LIST.moduleList.Users && $ACTION_LABEL != $MOD.LBL_ACTION_ADMIN}
					<select DISABLED name='act_guid{$ACTION.id}' id = 'act_guid{$ACTION.id}' onblur="document.getElementById('{$ACTION.id}link').innerHTML=this.options[this.selectedIndex].text; aclviewer.toggleDisplay('{$ACTION.id}');" >
                    {html_options options=$ACTION.accessOptions selected=$ACTION.aclaccess }
                    </select>
					{else}
{* BEGIN - SECURITY GROUPS : Add class='{$ACTION_NAME}' *}
{*
					<select name='act_guid{$ACTION.id}' id = 'act_guid{$ACTION.id}' onblur="document.getElementById('{$ACTION.id}link').innerHTML=this.options[this.selectedIndex].text; aclviewer.toggleDisplay('{$ACTION.id}');" >
*}
                        <select class='{$ACTION_NAME}' style="all: initial" name='act_guid{$ACTION.id}' id = 'act_guid{$ACTION.id}' onblur="document.getElementById('{$ACTION.id}link').innerHTML=this.options[this.selectedIndex].text; aclviewer.toggleDisplay('{$ACTION.id}');" >
 					{* END - SECURITY GROUPS *}
					{html_options options=$ACTION.accessOptions selected=$ACTION.aclaccess }
					</select>
					{/if}
					</div>
					{if $ACTION.accessLabel == 'dev' || $ACTION.accessLabel == 'admin_dev'}
					   <div class="aclAdmin"  id="{$ACTION.id}link" onclick="aclviewer.toggleDisplay('{$ACTION.id}')">{$ACTION.accessName}</div>
					{else}
                       <div class="acl{$ACTION.accessName}"  id="{$ACTION.id}link" onclick="aclviewer.toggleDisplay('{$ACTION.id}')">{$ACTION.accessName}</div>
                    {/if}
					</td>
					{assign var='ACTION_FIND' value='true'}
				{/if}
			{/foreach}
		{/foreach}
		{if $ACTION_FIND=='false'}
			{* STIC-Custom AAM 20260106 - Remove TDWITH *}	
			<td nowrap style="text-align: center;" id="ACLEditView_Access_{$CATEGORY_NAME}_{$ACTION_NAME}">
			{* END STIC-Custom *}
			<div><font color='red'>N/A</font></div>
			</td>
		{/if}
	{/foreach}
	</TR>


    {/if}


{foreachelse}
    <tr> <td colspan="2">No Actions Defined</td></tr>
{/foreach}
</TABLE>
{* STIC-Custom AAM 20251104 - Added scroll wrapper to ACL Roles Edit All Body to improve usability when many roles exist *}
</div>
{* END STIC-Custom *}
<div style="padding-top:10px;">
&nbsp;<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" class="button" onclick="this.form.action.value='Save';aclviewer.save('ACLEditView');return false;" type="button" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " id="SAVE_FOOTER"> &nbsp;
<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}"   class='button' type='button' name='save' value="  {$APP.LBL_CANCEL_BUTTON_LABEL} " class='button' onclick='aclviewer.view("{$ROLE.id}", "All");'>
</div>
</form>
