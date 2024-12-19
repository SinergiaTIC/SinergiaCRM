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
<form name="form_filters" id="form_filters" method="POST" action="index.php?module=stic_Bookings_Places_Calendar&action=SaveFilters">
    <td scope="row" style="width:60%;"><label>
        {$MOD.LBL_FILTERS_STIC_CENTER}</label>
    </td>
    <td>
        <input type='text' class='sqsEnabled' name='stic_center_name'
            id='stic_center_name' autocomplete='off'
            value='{$stic_center_name}' title='' tabindex='3'>
        <input type='hidden' name='stic_center_id'
            id='stic_center_id'
            value='{$stic_center_id}'>
        <span class='id-ff multiple'>
            <button title='{$MOD.LBL_SELECT_BUTTON_TITLE}' type='button'
                class='button' name='btn_1'
                onclick='openSelectPopup("stic_Centers", "stic_center")'>
                <span class='suitepicon suitepicon-action-select'></span>
            </button>
            <button type='button' name='btn_1' class='button lastChild'
                onclick='clearRow(this.form, "stic_center")'>
                <span class='suitepicon suitepicon-action-clear'></span>
            </button>
        </span>
    </td>
    <td scope="row" style="width:60%;">
        {$MOD.LBL_FILTERS_STIC_PLACE_GENDER}
    </td>
    <td>
        <select multiple id="stic_resources_places_gender_list" name="stic_resources_places_gender_list[]" tabindex="102">
            {$stic_resources_places_gender_list}
        </select>
    </td>
    <td scope="row" style="width:60%;">
        {$MOD.LBL_FILTERS_STIC_PLACE_TYPE}
    </td>
    <td>
        <select multiple id="stic_resources_places_type_list" name="stic_resources_places_type_list[]" tabindex="102">
            {$stic_resources_places_type_list}
        </select>
    </td>
    <td scope="row" style="width:60%;">
        {$MOD.LBL_FILTERS_STIC_PLACE_USER_TYPE}
    </td>
    <td>
        <select multiple id="stic_resources_places_users_list" name="stic_resources_places_users_list[]" tabindex="102">
            {$stic_resources_places_users_list}
        </select>
    </td>
    <div class="filter-group">
        <span type="button" id="cal_filters" class="btn-filter btn btn-info glyphicon glyphicon-filter parent-dropdown-handler" onclick="$('#form_filters').submit();"></span>
        <a title='{$MOD.LBL_FILTERS_CROSS_REMOVE_FILTERS_TOOLTIP}' id='cross_filters' href="javascript:void(0)" class="cross glyphicon glyphicon-remove" onClick=handleCrossRemoveFilters()></a>
    </div>
</form>

{literal}
	<style>
		.filter-group {
			display: inline-block;
		}
		.btn-filter {
			font-size: 14px;
			font-weight: 500;
			height: 32px;
			padding-left: 12px;
			padding-right: 12px;
			top: auto;
		}
		.cross {
			left: -14px;
			top: -10px;
			background-color: #b5bc31;
			color: white;
			font-size: 10px;
			padding: 4px;
		}
        select[multiple] {
            height: 32px;
            overflow: hidden;
            vertical-align: middle;
            display: inline-block;
        }

        select[multiple]:focus {
            height: auto;
            position: absolute;
            background: white;
            z-index: 1000;
        }

	</style>
{/literal}
