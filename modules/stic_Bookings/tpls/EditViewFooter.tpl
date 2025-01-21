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
{* This template is showed both in Bookings' ListView and Bookings Calendar reservations popups *}

<h2>{$MOD.LBL_RESOURCES}  <button id="openCenterPopup" type="button" class="button">Reservar Centro</button>
</h2>

<div class="center_column">
    <div id="resourceSearchFields">
        <div id="selectedCentersContainer">
            <label>Nombre del Centro:</label>
            <div id="selectedCentersList"></div>
        </div>
        <br>

        <label for="resourceType">Tipo de recurso:</label>
        <select id="resourceType" name="resourceType"></select>

        <label for="resourceStatus">Estado de recurso:</label>
        <select id="resourceStatus" name="resourceStatus"></select>

        <label for="resourceOffice">Oficina del recurso:</label>
        <select id="resourceOffice" name="resourceOffice"></select>

        <label for="resourceName">Nombre del recurso:</label>
        <input type="text" id="resourceName" name="resourceName">
        <br>

        <label for="numberOfCenters">Número de Centros:</label>
        <input type="number" id="numberOfCenters" name="numberOfCenters">
        <br>

        <button id="loadCenterResourcesButton" type="button" class="button">Cargar Plazas</button>
        <br>

        <label id="resourceCount"></label>
    </div>
</div>
<br>
<table id="resourceLine" class="resource-table">
    <tr>
        {foreach from=$config_resource_fields key=field item=label}
            <th class="resource_column {if $field eq 'name'}resource_name{/if}
                {if $field eq 'hourly_rate' || $field eq 'daily_rate'}hidden-xs hidden-sm{/if}">
                {$label}
            </th>
        {/foreach}
        <th class="resource_column"></th>
    </tr>
</table>
<div style="padding-top: 2px">
    <input type="button" class="button" value="{$MOD.LBL_RESOURCES_ADD}" id="addResourceLine" />
</div>
<br>
{literal}
    <style>
        .resource-table .resouce_data_group>input {
            width: calc(100% - 85px);
        }

        .resource-table {
            width: 100%;
        }

        #resourceLine th {
            white-space: initial;
        }

        #resourceLine input.resource_color,
        #resourceLine input.resource_status,
        #resourceLine input.resource_hourly_rate,
        #resourceLine input.resource_daily_rate {
            max-width: 90px !important;
        }

        .resource-table th.resource_name {
            width: calc(20% + 85px);
            min-width: 250px;
        }

        .resource_data {
            color: grey;
            width: 95%;
            border-color: grey !important;
        }

        /* Responsive tables for firefox and bootstrap*/
        @-moz-document url-prefix() {
            fieldset {
                display: table-cell;
            }
        }
    </style>
{/literal}
{{include file='include/EditView/footer.tpl'}}