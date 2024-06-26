{*
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
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
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 *}

{$INSTRUCTION}
<br />
<div class="hr"></div>
<br />

<form enctype="multipart/form-data" real_id="importconfirm" id="importconfirm" name="importconfirm" method="POST" action="index.php">
<input type="hidden" name="module" value="stic_Import_Validation">
<input type="hidden" name="type" value="{$TYPE}">
<input type="hidden" name="source" id="source" value="{$SOURCE}">
<input type="hidden" name="source_id" value="{$SOURCE_ID}">
<input type="hidden" name="action" value="Step3">
<input type="hidden" name="import_module" value="{$IMPORT_MODULE}">
<input type="hidden" name="import_type" value="{$TYPE}">
<input type="hidden" name="file_name" value="{$FILE_NAME}">
<input type="hidden" name="current_step" value="{$CURRENT_STEP}">
<input type="hidden" name="from_admin_wizard" value="{$smarty.request.from_admin_wizard}">
    
{if $AUTO_DETECT_ERROR != ''}
    <div class="errorMessage">
        <span class="error">{$AUTO_DETECT_ERROR}</span>
    </div>
{/if}

<div id="confirm_table" class="confirmTable">
{include file='modules/stic_Import_Validation/tpls/confirm_table.tpl'}
</div>

<!-- STIC-Code MHP - If we are in multimodule import, do not show the advanced options configuration -->
{if !$smarty.session.stic_ImporValidation.multimodule}
    <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="left" colspan="4" style="background: transparent;">
                <input title="{$MOD.LBL_SHOW_ADVANCED_OPTIONS}"  id="toggleImportOptions" class="button" type="button"
                       name="button" value="  {$MOD.LBL_SHOW_ADVANCED_OPTIONS}  "> {sugar_help text=$MOD.LBL_STIC_IMPORT_VALIDATION_FILE_SETTINGS_HELP}
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
            <div style="overflow: auto; width: 1056px;">
                <table border=0 class="edit view noBorder" style="display: none;" id="importOptions">
                    <tr>
                        <td scope="col">
                            <span><label for="importlocale_charset">{$MOD.LBL_CHARSET}</label></span>
                        </td>
                        <td>
                            <span><select tabindex='4' id='importlocale_charset'  name='importlocale_charset'>{$CHARSETOPTIONS}</select></span>
                        </td>
                        <td scope="col">
                            <span><label for="custom_delimiter">{$MOD.LBL_CUSTOM_DELIMITER}</label></span>
                        </td>
                        <td>
                            <span>
                                <select name="custom_delimiter" id="custom_delimiter"> {$IMPORT_DELIMETER_OPTIONS}</select>
                                <input type="text" name="custom_delimiter_other" id="custom_delimiter_other" style="display: none; width: 5em;" maxlength="1" />
                                {sugar_help text=$MOD.LBL_FIELD_DELIMETED_HELP}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td scope="col">
                            <span><label for="custom_enclosure">{$MOD.LBL_CUSTOM_ENCLOSURE}</label></span>
                        </td>
                        <td>
                            <span>
                                <select name="custom_enclosure" id="custom_enclosure">
                                {$IMPORT_ENCLOSURE_OPTIONS}
                                </select>
                                <input type="text" name="custom_enclosure_other" id="custom_enclosure_other" style="display: none; width: 5em;" maxlength="1" />
                            {sugar_help text=$MOD.LBL_ENCLOSURE_HELP}
                            </span>
                        </td>
                        <td scope="col">
                        <label for="has_header">{$MOD.LBL_HAS_HEADER}</label>
                        </td>
                        <td>
                            {* STIC-Code MHP *}
                            {* <input class="checkBox" value='on' type="checkbox" name="has_header" id="has_header" {$HAS_HEADER_CHECKED}> {sugar_help text=$MOD.LBL_HEADER_ROW_OPTION_HELP} *}
                            <input class="checkBox" value='on' type="checkbox" name="has_header" id="has_header" {$HAS_HEADER_CHECKED}  disabled> {sugar_help text=$MOD.LBL_HEADER_ROW_OPTION_HELP}
                        </td>
                    </tr>
                    <tr>
                        <td scope="col"><span><label for="importlocale_dateformat">{$MOD.LBL_DATE_FORMAT}</label></span></td>
                        <td ><span><select tabindex='4' name='importlocale_dateformat' id='importlocale_dateformat'>{$DATEOPTIONS}</select></span></td>
                        <td scope="col"><span><label for="importlocale_time_format">{$MOD.LBL_TIME_FORMAT}</label></span></td>
                        <td ><span><select tabindex='4' id='importlocale_time_format' name='importlocale_timeformat'>{$TIMEOPTIONS}</select></span></td>
                    </tr>
                    <tr>
                        <td scope="col"><span><label for="importlocale_timezone">{$MOD.LBL_TIMEZONE}</label></span></td>
                        <td ><span><select tabindex='4' name='importlocale_timezone' id='importlocale_timezone'>{html_options options=$TIMEZONEOPTIONS selected=$TIMEZONE_CURRENT}</select></span></td>
                        <td scope="col"><span><label for="currency_select">{$MOD.LBL_CURRENCY}</label></span></td>
                        <td ><span>
                            <select tabindex='4' id='currency_select' name='importlocale_currency' onchange='setSymbolValue(this.selectedIndex);setSigDigits();'>{$CURRENCY}</select>
                            <input type="hidden" id="symbol" value="">
                        </span></td>
                    </tr>
                    <tr>
                        <td scope="col"><span><label for="sigDigits">{$MOD.LBL_CURRENCY_SIG_DIGITS}:</label></span></td>
                        <td ><span><select id='sigDigits' onchange='setSigDigits(this.value);' name='importlocale_default_currency_significant_digits'>{$sigDigits}</select>
                        </span></td>
                        <td scope="col"><span><i>{$MOD.LBL_LOCALE_EXAMPLE_NAME_FORMAT}</i>:</span></td>
                        <td ><span><input type="text" disabled id="sigDigitsExample" name="sigDigitsExample"></span></td>
                    </tr>
                    <tr>
                        <td scope="col"><span><label for="default_number_grouping_seperator">{$MOD.LBL_NUMBER_GROUPING_SEP}</label></span></td>
                        <td ><span>
                            <input tabindex='4' name='importlocale_num_grp_sep' id='default_number_grouping_seperator'
                                   type='text' maxlength='1' size='1' value='{$NUM_GRP_SEP}' onkeydown='setSigDigits();' onkeyup='setSigDigits();'>
                        </span></td>
                        <td scope="col"><span><label for="default_decimal_seperator">{$MOD.LBL_DECIMAL_SEP}</label></span></td>
                        <td ><span>
                            <input tabindex='4' name='importlocale_dec_sep' id='default_decimal_seperator'
                                   type='text' maxlength='1' size='1' value='{$DEC_SEP}' onkeydown='setSigDigits();' onkeyup='setSigDigits();'>
                        </span></td>
                    </tr>
                    <tr>
                        <td scope="col" valign="top"><label for="default_locale_name_format">{$MOD.LBL_LOCALE_DEFAULT_NAME_FORMAT}</label>: </td>
                        <td  valign="top">
                            <input onkeyup="setPreview();" onkeydown="setPreview();" id="default_locale_name_format" type="text" tabindex='4' name="importlocale_default_locale_name_format" value="{$default_locale_name_format}">
                            <br />{$MOD.LBL_LOCALE_NAME_FORMAT_DESC}
                        </td>
                        <td scope="col" valign="top"><i>{$MOD.LBL_LOCALE_EXAMPLE_NAME_FORMAT}:</i> </td>
                        <td  valign="top"><input tabindex='4' id="nameTarget" name="no_value" id=":q" value="" style="border: none;" disabled size="50"></td>
                    </tr>
                </table>
            </div>
                
            </td>
        </tr>
        <tr>
            <td colspan="2"><div class="hr" style="margin-top: 0px;"></div></td>
        </tr>
    </table>
{/if}
    <table width="100%" cellpadding="2" cellspacing="0" border="0">
        <tr>
            <td align="left">
                <input title="{$MOD.LBL_NEXT}"  class="button" type="submit" name="button" value="  {$MOD.LBL_NEXT}  " id="gonext">
            </td>
        </tr>
    </table>
</form>
