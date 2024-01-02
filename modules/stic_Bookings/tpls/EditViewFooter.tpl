{* This template is showed both in Bookings' ListView and Bookings Calendar reservations popups *}

<h2>{$MOD.LBL_RESOURCES}</h2>
<table id="resourceLine" class="resource-table">
    <tr>
        <th class="resource_column resource_name">{$MOD.LBL_RESOURCES_NAME}</th>
        <th class="resource_column">{$MOD.LBL_RESOURCES_CODE}</th>
        <th class="resource_column">{$MOD.LBL_RESOURCES_STATUS}</th>
        <th class="resource_column">{$MOD.LBL_RESOURCES_TYPE}</th>
        <th class="resource_column">{$MOD.LBL_RESOURCES_COLOR}</th>
        <th class="resource_column hidden-xs hidden-sm">{$MOD.LBL_RESOURCES_HOURLY_RATE}</th>
        <th class="resource_column hidden-xs hidden-sm">{$MOD.LBL_RESOURCES_DAILY_RATE}</th>
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