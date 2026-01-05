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

{* /*****************************************
.tpl template of 182 Model - Issuing Organization Selection
****************************************/ *}
<table width="100%">
    <tr>
        {*TITLE*} <th style="text-align:left">
            <h2>{$MOD.LBL_M182_TITLE}</h2>
        </th>
    </tr>
</table>

<p style="text-align:left;color:#000000;" class="wizard_info letter13">{$MOD.LBL_M182_SELECT_ISSUING_ORGANIZATION}</p>

<br>
<form name="stic_Payments" method="POST">
    <input type="hidden" id="module" name="module" value="stic_Payments">
    <input type="hidden" id="action" name="action" value="M182selectissuingorganization">

    <table border="0" cellspacing="5">
        <br>
        <select required id="issuing_organization_selected" name="issuing_organization_selected">
            {html_options values=$ORGS_KEYS output=$ORGS_LABELS}
        </select>
    </table>
    <table width="100%">
        <tr>
            <br><br>
            <td align="left" style="padding-bottom: 2px;">
                <input id="send_wizard" title="{$MOD.LBL_M182_SELECT_ISSUING_ORGANIZATION_NEXT}" class="button" type="submit" value="{$MOD.LBL_M182_SELECT_ISSUING_ORGANIZATION_NEXT}">
            </td>
        </tr>
    </table>
</form>
