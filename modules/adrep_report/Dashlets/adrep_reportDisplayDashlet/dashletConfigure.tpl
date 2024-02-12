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
<div>
    <form action='index.php' name='EditView' id='configure_{$id}' method='post' onSubmit='return SUGAR.dashlets.postForm("configure_{$id}", SUGAR.mySugar.uncoverPage);'>
        <input type='hidden' name='id' value='{$id}'>
        <input type='hidden' name='module' value='Home'>
        <input type='hidden' name='action' value='ConfigureDashlet'>
        <input type='hidden' name='configure' value='true'>
        <input type='hidden' name='to_pdf' value='true'>

        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="edit view">
            <tr>
                <td scope='row'>
                    {$MOD.LBL_DASHLET_TITLE}
                </td>
                <td>
                    <input type='text' name='dashletTitle' value='{$dashletTitle}'>
                </td>
            </tr>
            <tr>
                <td scope='row'>
                    {$MOD.LBL_REPORT_NAME}
                </td>
                <td>
                    <input type="text" name="adrep_report_name" class="sqsEnabled" tabindex="0" id="adrep_report_name" size="" value="{$adrep_report_name}" title='' autocomplete="off">
                    <input type="hidden" name="adrep_report_id" id="adrep_report_id" value="{$adrep_report_id}">
                    <span class="id-ff multiple">
                        <button type="button" name="btn_adrep_report_name" id="btn_adrep_report_name" tabindex="0" title="{$MOD.LBL_DASHLET_SELECT_REPORT}" class="button firstChild" value="{$MOD.LBL_DASHLET_SELECT_REPORT}"
                                {literal}
                                    onclick='open_popup(
                                            "adrep_report",
                                            600,
                                            400,
                                            "",
                                            true,
                                            false,
                                            {"call_back_function":"adrep_report_set_return","form_name":"EditView","field_to_name_array":{"id":"adrep_report_id","name":"adrep_report_name"}},
                                            "single",
                                            true
                                    );' >
                                {/literal}
                            <span class="suitepicon suitepicon-action-select"></span>
                        </button>
                        <button type="button" name="btn_clr_adrep_report_name" id="btn_clr_adrep_report_name" tabindex="0" title="{$MOD.LBL_DASHLET_CLEAR_REPORT}"  class="button lastChild"
                            onclick="SUGAR.clearRelateField(this.form, 'adrep_report_name', 'adrep_report_id');"  value="{$MOD.LBL_DASHLET_CLEAR_REPORT}" >
                            <span class="suitepicon suitepicon-action-clear"></span>
                        </button>
                    </span>
                    <script type="text/javascript">
                        {literal}
                       if(typeof sqs_objects == 'undefined'){
                            var sqs_objects = new Array;
                        }
                        sqs_objects['EditView']={
                            "form":"EditView",
                            "method":"query",
                            "modules": ["adrep_report"],
                            "field_list":["name","id"],
                            "populate_list":["adrep_report_name","adrep_report_id"],
                            "required_list":["adrep_report_id"],
                            "conditions": [{
                                "name": "name",
                                "op": "like_custom",
                                "end": "%",
                                "value": ""
                            }],
                            "limit":"30",
                            "no_match_text":"No Match"};
                        SUGAR.util.doWhen(
                                "typeof(sqs_objects) != 'undefined' && typeof(sqs_objects['EditView_adrep_report_name']) != 'undefined'",
                                enableQS
                        );
                        {/literal}
                    </script>
                </td>
            </tr>

						<tr>
							<td scope="row">
										{$MOD.LBL_DASHLET_HEIGHT}
							</td>
							<td>
							<input type='text' name='adrep_height' value='{$adrep_height}'>
								</td>
						</tr>

						<tr>
							<td scope="row">
										{$MOD.LBL_DASHLET_TEMPLATE}
							</td>
							<td>
								<select name="adrep_template">

									{foreach from=$template_list key=temp item=name }
											<option label="{$name}" value="{$temp}"
											{if $adrep_template eq $name}
												 selected="selected"
											{/if}
											>{$name}</option>
									{/foreach}


                        </select>
								</td>
						</tr>

						<tr>
							<td scope="row">
										{$MOD.LBL_DASHLET_NUMROWS}
							</td>
							<td>
								<input type='text' name='adrep_noresults' value='{$adrep_noresults}'>
								</td>
						</tr>

            <tr>
                <td scope='row'>

                </td>
                <td align='right'>
                    <input type='submit' class='button' value='{$MOD.LBL_DASHLET_SAVE}'>
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
    {literal}

    function adrep_report_set_return(ret){
        //loadCharts(ret.name_to_value_array.adrep_report_id);
      //  loadParameters(ret.name_to_value_array.adrep_report_id);
        set_return(ret);
    }
    {/literal}
</script>
