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
    'LBL_ASSIGNED_TO_ID' => 'Id de usuario asignado',
    'LBL_ASSIGNED_TO_NAME' => 'Asignado a',
    'LBL_SECURITYGROUPS' => 'Grupos de Seguridad',
    'LBL_SECURITYGROUPS_SUBPANEL_TITLE' => 'Grupos de Seguridad',
    'LBL_ID' => 'ID',
    'LBL_DATE_ENTERED' => 'Fecha de Creación',
    'LBL_DATE_MODIFIED' => 'Fecha de Modificación',
    'LBL_MODIFIED' => 'Modificado Por',
    'LBL_MODIFIED_NAME' => 'Modificado Por Nombre',
    'LBL_CREATED' => 'Creado Por',
    'LBL_DESCRIPTION' => 'Descripción',
    'LBL_DELETED' => 'Eliminado',
    'LBL_NAME' => 'Nombre',
    'LBL_CREATED_USER' => 'Creado por el Usuario',
    'LBL_MODIFIED_USER' => 'Modificado por el Usuario',
    'LBL_LIST_NAME' => 'Nombre',
    'LBL_EDIT_BUTTON' => 'Editar',
    'LBL_REMOVE' => 'Quitar',
    'LBL_LIST_FORM_TITLE' => 'Listado',
    'LBL_MODULE_NAME' => 'Tabla dinámica',
    'LBL_MODULE_TITLE' => 'Tabla dinámica',
    'LBL_HOMEPAGE_TITLE' => 'Mi Tabla Dinámica',
    'LNK_NEW_RECORD' => 'Crear Pivot',
    'LNK_LIST' => 'Ver Pivot',
    'LBL_SEARCH_FORM_TITLE' => 'Buscar Tabla dinámica',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'Ver Historial',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Actividades',
    'LBL_NEW_FORM_TITLE' => 'Nueva Tabla Dinámica',
    'LBL_CONFIG' => 'Configuración',
    'LBL_TYPE' => 'Área para Análisis',
    'LNK_SPOT_LIST' => 'Ver Spots',
    'LNK_SPOT_CREATE' => 'Crear Spot',

    //Analytics
    'LBL_AN_CONFIGURATION' => 'Configuración',

    'LBL_AN_UNSUPPORTED_DB' => 'Lo sentimos, Suite Spots actualmente sólo está configurado para MySQL y MS SQL',

    //Analytics labels for accounts pivot
    'LBL_AN_ACCOUNTS_ACCOUNT_NAME' => 'Nombre',
    'LBL_AN_ACCOUNTS_ACCOUNT_TYPE' => 'Tipo de cuenta',
    'LBL_AN_ACCOUNTS_ACCOUNT_INDUSTRY' => 'Industria',
    'LBL_AN_ACCOUNTS_ACCOUNT_BILLING_COUNTRY' => 'País de facturación',

    //Analytics labels for leads pivot
    'LBL_AN_LEADS_ASSIGNED_USER' => 'Usuario Asignado',
    'LBL_AN_LEADS_STATUS' => 'Estado',
    'LBL_AN_LEADS_LEAD_SOURCE' => 'Toma de contacto',
    'LBL_AN_LEADS_CAMPAIGN_NAME' => 'Nombre de Campaña',
    'LBL_AN_LEADS_YEAR' => 'Año',
    'LBL_AN_LEADS_QUARTER' => 'Trimestre',
    'LBL_AN_LEADS_MONTH' => 'Mes',
    'LBL_AN_LEADS_WEEK' => 'Semana',
    'LBL_AN_LEADS_DAY' => 'Día',

    //Analytics labels for sales pivot
    'LBL_AN_SALES_ACCOUNT_NAME' => 'Nombre de Cuenta',
    'LBL_AN_SALES_OPPORTUNITY_NAME' => 'Nombre de la oportunidad',
    'LBL_AN_SALES_ASSIGNED_USER' => 'Usuario Asignado',
    'LBL_AN_SALES_OPPORTUNITY_TYPE' => 'Tipo de Oportunidad',
    'LBL_AN_SALES_LEAD_SOURCE' => 'Toma de contacto',
    'LBL_AN_SALES_AMOUNT' => 'Cantidad',
    'LBL_AN_SALES_STAGE' => 'Etapa de Ventas',
    'LBL_AN_SALES_PROBABILITY' => 'Probabilidad',
    'LBL_AN_SALES_DATE' => 'Fecha de venta',
    'LBL_AN_SALES_QUARTER' => 'Trimestre de venta',
    'LBL_AN_SALES_MONTH' => 'Ventas del mes',
    'LBL_AN_SALES_WEEK' => 'Ventas de la semana',
    'LBL_AN_SALES_DAY' => 'Ventas del día',
    'LBL_AN_SALES_YEAR' => 'Ventas del año',
    'LBL_AN_SALES_CAMPAIGN' => 'Campaña',

    //Analytics labels for service pivot
    'LBL_AN_SERVICE_ACCOUNT_NAME' => 'Nombre de Cuenta',
    'LBL_AN_SERVICE_STATE' => 'Estado',
    'LBL_AN_SERVICE_STATUS' => 'Estado',
    'LBL_AN_SERVICE_PRIORITY' => 'Prioridad',
    'LBL_AN_SERVICE_CREATED_DAY' => 'Día de creación',
    'LBL_AN_SERVICE_CREATED_WEEK' => 'Semana de creación',
    'LBL_AN_SERVICE_CREATED_MONTH' => 'Mes de creación',
    'LBL_AN_SERVICE_CREATED_QUARTER' => 'Trimestre de creación',
    'LBL_AN_SERVICE_CREATED_YEAR' => 'Año de creación',
    'LBL_AN_SERVICE_CONTACT_NAME' => 'Nombre Contacto',
    'LBL_AN_SERVICE_ASSIGNED_TO' => 'Usuario Asignado',

    //Analytics labels for the activities pivot
    'LBL_AN_ACTIVITIES_TYPE' => 'Tipo',
    'LBL_AN_ACTIVITIES_NAME' => 'Nombre',
    'LBL_AN_ACTIVITIES_STATUS' => 'Estado',
    'LBL_AN_ACTIVITIES_ASSIGNED_TO' => 'Usuario Asignado',

    //Analytics labels for the marketing pivot
    'LBL_AN_MARKETING_STATUS' => 'Estado',
    'LBL_AN_MARKETING_TYPE' => 'Tipo',
    'LBL_AN_MARKETING_BUDGET' => 'Presupuesto',
    'LBL_AN_MARKETING_EXPECTED_COST' => 'Costo esperado',
    'LBL_AN_MARKETING_EXPECTED_REVENUE' => 'Ingresos Esperados',
    'LBL_AN_MARKETING_OPPORTUNITY_NAME' => 'Nombre de la oportunidad',
    'LBL_AN_MARKETING_OPPORTUNITY_AMOUNT' => 'Monto de la Oportunidad',
    'LBL_AN_MARKETING_OPPORTUNITY_SALES_STAGE' => 'Etapa de venta de oportunidad',
    'LBL_AN_MARKETING_OPPORTUNITY_ASSIGNED_TO' => 'Oportunidad asignada a',
    'LBL_AN_MARKETING_ACCOUNT_NAME' => 'Nombre de Cuenta',

    //Analytics labels for the marketing activities pivot
    'LBL_AN_MARKETINGACTIVITY_CAMPAIGN_NAME' => 'Nombre de Campaña',
    'LBL_AN_MARKETINGACTIVITY_ACTIVITY_DATE' => 'Fecha de Actividad',
    'LBL_AN_MARKETINGACTIVITY_ACTIVITY_TYPE' => 'Tipo de Actividad',
    'LBL_AN_MARKETINGACTIVITY_RELATED_TYPE' => 'Tipo Relacionado',
    'LBL_AN_MARKETINGACTIVITY_RELATED_ID' => 'Id Relacionado',

    //Analytics labels for the quotes pivot
    'LBL_AN_QUOTES_OPPORTUNITY_NAME' => 'Nombre de la oportunidad',
    'LBL_AN_QUOTES_OPPORTUNITY_TYPE' => 'Tipo de oportunidad',
    'LBL_AN_QUOTES_OPPORTUNITY_LEAD_SOURCE' => 'Toma de contacto de la oportunidad',
    'LBL_AN_QUOTES_OPPORTUNITY_SALES_STAGE' => 'Etapa de venta de oportunidad',
    'LBL_AN_QUOTES_ACCOUNT_NAME' => 'Nombre de Cuenta',
    'LBL_AN_QUOTES_CONTACT_NAME' => 'Nombre Contacto',
    'LBL_AN_QUOTES_ITEM_NAME' => 'Nombre del Elemento',
    'LBL_AN_QUOTES_ITEM_TYPE' => 'Tipo de elemento',
    'LBL_AN_QUOTES_ITEM_CATEGORY' => 'Categoría de artículo',
    'LBL_AN_QUOTES_ITEM_QTY' => 'Cantidad de Artículos',
    'LBL_AN_QUOTES_ITEM_LIST_PRICE' => 'Precio de lista del artículo',
    'LBL_AN_QUOTES_ITEM_SALE_PRICE' => 'Precio de venta del artículo',
    'LBL_AN_QUOTES_ITEM_COST_PRICE' => 'Precio de costo del artículo',
    'LBL_AN_QUOTES_ITEM_DISCOUNT_PRICE' => 'Precio de descuento de artículo',
    'LBL_AN_QUOTES_ITEM_DISCOUNT_AMOUNT' => 'Cantidad del Descuento',
    'LBL_AN_QUOTES_ITEM_TOTAL' => 'Total de Artículo',
    'LBL_AN_QUOTES_GRAND_TOTAL' => 'Gran Total',
    'LBL_AN_QUOTES_ASSIGNED_TO' => 'Usuario Asignado',
    'LBL_AN_QUOTES_DATE_CREATED' => 'Fecha de Creación',
    'LBL_AN_QUOTES_DAY_CREATED' => 'Día de creación',
    'LBL_AN_QUOTES_WEEK_CREATED' => 'Semana Creada',
    'LBL_AN_QUOTES_MONTH_CREATED' => 'Mes creado',
    'LBL_AN_QUOTES_QUARTER_CREATED' => 'Trimestre Creado',
    'LBL_AN_QUOTES_YEAR_CREATED' => 'Año creada',

    //Error message when there are multiple values for the label
    'LBL_AN_DUPLICATE_LABEL_FOR_SUBAREA' => 'Error para determinar la etiqueta de la zona pivote',
);
