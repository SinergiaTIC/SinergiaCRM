<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2019 SalesAgility Ltd.
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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$mod_strings = array(
    'LBL_ASSIGNED_TO_ID' => '',
    'LBL_ASSIGNED_TO_NAME' => '',
    'LBL_SECURITYGROUPS' => '',
    'LBL_SECURITYGROUPS_SUBPANEL_TITLE' => '',
    'LBL_ID' => '',
    'LBL_DATE_ENTERED' => '',
    'LBL_DATE_MODIFIED' => '',
    'LBL_MODIFIED' => '',
    'LBL_MODIFIED_NAME' => '',
    'LBL_CREATED' => '',
    'LBL_DESCRIPTION' => '',
    'LBL_DELETED' => '',
    'LBL_NAME' => '',
    'LBL_CREATED_USER' => '',
    'LBL_MODIFIED_USER' => '',
    'LBL_LIST_NAME' => '',
    'LBL_EDIT_BUTTON' => '',
    'LBL_REMOVE' => '',
    'LBL_LIST_FORM_TITLE' => '',
    'LBL_MODULE_NAME' => '',
    'LBL_MODULE_TITLE' => '',
    'LBL_HOMEPAGE_TITLE' => '',
    'LNK_NEW_RECORD' => '',
    'LNK_LIST' => '',
    'LBL_SEARCH_FORM_TITLE' => '',
    'LBL_HISTORY_SUBPANEL_TITLE' => '',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => '',
    'LBL_NEW_FORM_TITLE' => '',
    'LBL_CONFIG' => '',
    'LBL_TYPE' => '',
    'LNK_SPOT_LIST' => '',
    'LNK_SPOT_CREATE' => '',

    //Analytics
    'LBL_AN_CONFIGURATION' => '',

    'LBL_AN_UNSUPPORTED_DB' => '',

    //Analytics labels for accounts pivot
    'LBL_AN_ACCOUNTS_ACCOUNT_NAME' => '',
    'LBL_AN_ACCOUNTS_ACCOUNT_TYPE' => '',
    'LBL_AN_ACCOUNTS_ACCOUNT_INDUSTRY' => '',
    'LBL_AN_ACCOUNTS_ACCOUNT_BILLING_COUNTRY' => '',

    //Analytics labels for leads pivot
    'LBL_AN_LEADS_ASSIGNED_USER' => '',
    'LBL_AN_LEADS_STATUS' => '',
    'LBL_AN_LEADS_LEAD_SOURCE' => '',
    'LBL_AN_LEADS_CAMPAIGN_NAME' => '',
    'LBL_AN_LEADS_YEAR' => '',
    'LBL_AN_LEADS_QUARTER' => '',
    'LBL_AN_LEADS_MONTH' => '',
    'LBL_AN_LEADS_WEEK' => '',
    'LBL_AN_LEADS_DAY' => '',

    //Analytics labels for sales pivot
    'LBL_AN_SALES_ACCOUNT_NAME' => '',
    'LBL_AN_SALES_OPPORTUNITY_NAME' => '',
    'LBL_AN_SALES_ASSIGNED_USER' => '',
    'LBL_AN_SALES_OPPORTUNITY_TYPE' => '',
    'LBL_AN_SALES_LEAD_SOURCE' => '',
    'LBL_AN_SALES_AMOUNT' => '',
    'LBL_AN_SALES_STAGE' => '',
    'LBL_AN_SALES_PROBABILITY' => '',
    'LBL_AN_SALES_DATE' => '',
    'LBL_AN_SALES_QUARTER' => '',
    'LBL_AN_SALES_MONTH' => '',
    'LBL_AN_SALES_WEEK' => '',
    'LBL_AN_SALES_DAY' => '',
    'LBL_AN_SALES_YEAR' => '',
    'LBL_AN_SALES_CAMPAIGN' => '',

    //Analytics labels for service pivot
    'LBL_AN_SERVICE_ACCOUNT_NAME' => '',
    'LBL_AN_SERVICE_STATE' => '',
    'LBL_AN_SERVICE_STATUS' => '',
    'LBL_AN_SERVICE_PRIORITY' => '',
    'LBL_AN_SERVICE_CREATED_DAY' => '',
    'LBL_AN_SERVICE_CREATED_WEEK' => '',
    'LBL_AN_SERVICE_CREATED_MONTH' => '',
    'LBL_AN_SERVICE_CREATED_QUARTER' => '',
    'LBL_AN_SERVICE_CREATED_YEAR' => '',
    'LBL_AN_SERVICE_CONTACT_NAME' => '',
    'LBL_AN_SERVICE_ASSIGNED_TO' => '',

    //Analytics labels for the activities pivot
    'LBL_AN_ACTIVITIES_TYPE' => '',
    'LBL_AN_ACTIVITIES_NAME' => '',
    'LBL_AN_ACTIVITIES_STATUS' => '',
    'LBL_AN_ACTIVITIES_ASSIGNED_TO' => '',

    //Analytics labels for the marketing pivot
    'LBL_AN_MARKETING_STATUS' => '',
    'LBL_AN_MARKETING_TYPE' => '',
    'LBL_AN_MARKETING_BUDGET' => '',
    'LBL_AN_MARKETING_EXPECTED_COST' => '',
    'LBL_AN_MARKETING_EXPECTED_REVENUE' => '',
    'LBL_AN_MARKETING_OPPORTUNITY_NAME' => '',
    'LBL_AN_MARKETING_OPPORTUNITY_AMOUNT' => '',
    'LBL_AN_MARKETING_OPPORTUNITY_SALES_STAGE' => '',
    'LBL_AN_MARKETING_OPPORTUNITY_ASSIGNED_TO' => '',
    'LBL_AN_MARKETING_ACCOUNT_NAME' => '',

    //Analytics labels for the marketing activities pivot
    'LBL_AN_MARKETINGACTIVITY_CAMPAIGN_NAME' => '',
    'LBL_AN_MARKETINGACTIVITY_ACTIVITY_DATE' => '',
    'LBL_AN_MARKETINGACTIVITY_ACTIVITY_TYPE' => '',
    'LBL_AN_MARKETINGACTIVITY_RELATED_TYPE' => '',
    'LBL_AN_MARKETINGACTIVITY_RELATED_ID' => '',

    //Analytics labels for the quotes pivot
    'LBL_AN_QUOTES_OPPORTUNITY_NAME' => '',
    'LBL_AN_QUOTES_OPPORTUNITY_TYPE' => '',
    'LBL_AN_QUOTES_OPPORTUNITY_LEAD_SOURCE' => '',
    'LBL_AN_QUOTES_OPPORTUNITY_SALES_STAGE' => '',
    'LBL_AN_QUOTES_ACCOUNT_NAME' => '',
    'LBL_AN_QUOTES_CONTACT_NAME' => '',
    'LBL_AN_QUOTES_ITEM_NAME' => '',
    'LBL_AN_QUOTES_ITEM_TYPE' => '',
    'LBL_AN_QUOTES_ITEM_CATEGORY' => '',
    'LBL_AN_QUOTES_ITEM_QTY' => '',
    'LBL_AN_QUOTES_ITEM_LIST_PRICE' => '',
    'LBL_AN_QUOTES_ITEM_SALE_PRICE' => '',
    'LBL_AN_QUOTES_ITEM_COST_PRICE' => '',
    'LBL_AN_QUOTES_ITEM_DISCOUNT_PRICE' => '',
    'LBL_AN_QUOTES_ITEM_DISCOUNT_AMOUNT' => '',
    'LBL_AN_QUOTES_ITEM_TOTAL' => '',
    'LBL_AN_QUOTES_GRAND_TOTAL' => '',
    'LBL_AN_QUOTES_ASSIGNED_TO' => '',
    'LBL_AN_QUOTES_DATE_CREATED' => '',
    'LBL_AN_QUOTES_DAY_CREATED' => '',
    'LBL_AN_QUOTES_WEEK_CREATED' => '',
    'LBL_AN_QUOTES_MONTH_CREATED' => '',
    'LBL_AN_QUOTES_QUARTER_CREATED' => '',
    'LBL_AN_QUOTES_YEAR_CREATED' => '',

    //Error message when there are multiple values for the label
    'LBL_AN_DUPLICATE_LABEL_FOR_SUBAREA' => '',

    //Added to allow for the UI of the pivot to be language agnostic - PR 5452
    'LBL_RENDERERS_TABLE' =>'',
    'LBL_RENDERERS_TABLE_BARCHART' =>'',
    'LBL_RENDERERS_HEATMAP' =>'',
    'LBL_RENDERERS_ROW_HEATMAP' =>'',
    'LBL_RENDERERS_COL_HEATMAP' =>'',
    'LBL_RENDERERS_LINE_CHART' =>'',
    'LBL_RENDERERS_BAR_CHART' =>'',
    'LBL_RENDERERS_STACKED_BAR_CHART' =>'',
    'LBL_RENDERERS_AREA_CHART' =>'',
    'LBL_RENDERERS_SCATTER_CHART' =>'',

    'LBL_AGGREGATORS_COUNT' => '',
    'LBL_AGGREGATORS_COUNT_UNIQUE_VALUES' => '',
    'LBL_AGGREGATORS_LIST_UNIQUE_VALUES' => '',
    'LBL_AGGREGATORS_SUM' => '',
    'LBL_AGGREGATORS_INTEGER_SUM' => '',
    'LBL_AGGREGATORS_AVERAGE' => '',
    'LBL_AGGREGATORS_MINIMUM' => '',
    'LBL_AGGREGATORS_MAXIMUM' => '',
    'LBL_AGGREGATORS_SUM_OVER_SUM' => '',
    'LBL_AGGREGATORS_80%_UPPER_BOUND' => '',
    'LBL_AGGREGATORS_80%_LOWER_BOUND' => '',
    'LBL_AGGREGATORS_SUM_AS_FRACTION_OF_TOTAL' => '',
    'LBL_AGGREGATORS_SUM_AS_FRACTION_OF_ROWS' => '',
    'LBL_AGGREGATORS_SUM_AS_FRACTION_OF_COLUMNS' => '',
    'LBL_AGGREGATORS_COUNT_AS_FRACTION_OF_TOTAL' => '',
    'LBL_AGGREGATORS_COUNT_AS_FRACTION_OF_ROWS' => '',
    'LBL_AGGREGATORS_COUNT_AS_FRACTION_OF_COLUMNS' => '',

    'LBL_LOCALE_STRINGS_RENDER_ERROR' => '',
    'LBL_LOCALE_STRINGS_COMPUTING_ERROR' => '',
    'LBL_LOCALE_STRINGS_UI_RENDER_ERROR' => '',
    'LBL_LOCALE_STRINGS_SELECT_ALL' => '',
    'LBL_LOCALE_STRINGS_SELECT_NONE' => '',
    'LBL_LOCALE_STRINGS_TOO_MANY' => '',
    'LBL_LOCALE_STRINGS_FILTER_RESULTS' => '',
    'LBL_LOCALE_STRINGS_TOTALS' => '',
    'LBL_LOCALE_STRINGS_VS' => '',
    'LBL_LOCALE_STRINGS_BY' => '',
    'LBL_LOCALE_STRINGS_OK' => '',

    'LBL_ACTIVITIES_CALL'=>'',
    'LBL_ACTIVITIES_MEETING'=>'',
    'LBL_ACTIVITIES_TASK'=>'',
);
