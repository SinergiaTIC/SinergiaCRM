<?php

/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
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
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
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
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/

$mod_strings = array(
    'LBL_ASSIGNED_TO_ID' => 'Asignado a (ID)',
    'LBL_ASSIGNED_TO_NAME' => 'Asignado a',
    'LBL_ASSIGNED_TO' => 'Asignado a',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Asignado a',
    'LBL_LIST_ASSIGNED_USER' => 'Asignado a',
    'LBL_CREATED' => 'Creado por',
    'LBL_CREATED_USER' => 'Creado por',
    'LBL_CREATED_ID' => 'Creado por (ID)',
    'LBL_MODIFIED' => 'Modificado por',
    'LBL_MODIFIED_NAME' => 'Modificado por',
    'LBL_MODIFIED_USER' => 'Modificado por',
    'LBL_MODIFIED_ID' => 'Modificado por (ID)',
    'LBL_SECURITYGROUPS' => 'Grupos de seguridad',
    'LBL_SECURITYGROUPS_SUBPANEL_TITLE' => 'Grupos de seguridad',
    'LBL_ID' => 'ID',
    'LBL_DATE_ENTERED' => 'Fecha de Creación',
    'LBL_DATE_MODIFIED' => 'Fecha de Modificación',
    'LBL_DESCRIPTION' => 'Descripción',
    'LBL_DELETED' => 'Eliminado',
    'LBL_NAME' => 'Nombre',
    'LBL_LIST_NAME' => 'Nombre',
    'LBL_EDIT_BUTTON' => 'Editar',
    'LBL_REMOVE' => 'Quitar',
    'LBL_LIST_FORM_TITLE' => 'Lista de Ficheros XML SEPE',
    'LBL_MODULE_NAME' => 'Generador XML SEPE',
    'LBL_MODULE_TITLE' => 'Generador XML SEPE',
    'LBL_HOMEPAGE_TITLE' => 'Mis Ficheros XML SEPE',
    'LNK_NEW_RECORD' => 'Crear un Fichero XML SEPE',
    'LNK_LIST' => 'Ver Ficheros XML SEPE',
    'LNK_IMPORT_STIC_SEPE_FILES' => 'Importar Ficheros XML SEPE',
    'LBL_SEARCH_FORM_TITLE' => 'Buscar Ficheros XML SEPE',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'Historial',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Actividades',
    'LBL_STIC_SEPE_FILES_SUBPANEL_TITLE' => 'Generador XML SEPE',
    'LBL_NEW_FORM_TITLE' => 'Nuevo Fichero XML SEPE',
    'LBL_STATUS' => 'Estado',
    'LBL_TYPE' => 'Tipo de fichero',
    'LBL_REPORTED_MONTH' => 'Mes/año reportado',
    'LBL_LOG' => 'Registro de errores',
    'LBL_AGREEMENT' => 'Código de convenio',
    'LBL_GENERATE_XML_FILE' => 'Generar fichero XML',
    'LBL_SEPE_LOG_TITLE' => 'El fichero fue generado por última vez por: ',
    'LBL_SEPE_GENERATED_WITHOUT_ERRORS' => 'Fichero XML generado sin errores',
    'LBL_SEPE_XML_HAS_ERRORS' => 'El archivo XML no se ha generado porque existen errores que deben ser corregidos.',
    'LBL_ERROR_DATE_FUTURE' => 'El valor del campo Mes reportado tiene que ser igual o inferior al mes y año actual.',
    'LBL_ERROR_SEPE_CODIGO_AGENCIA' => 'El valor del parámetro SEPE_CODIGO_AGENCIA no es correcto. Debería ser una cadena numérica de 10 digitos.',
    'LBL_ERROR_SEPE_AGREEMENT' => 'Para ficheros del tipo ACCD o ACCI el Código de Convenio es obligatorio.',
    'LBL_ERROR_PATTERN_SEPE_AGREEMENT' => 'El Codigo de Convenio no es correcto. Tiene que ser una cadena de 10 caracteres compuesta de números y letras.',
    'LBL_SEPE_ERRORS_TITLE' => 'El fichero no se ha generado debido a los siguientes errores:',
    'LBL_ERROR_SEPE_REQUIRED' => 'El siguiente campo (del módulo) es obligatorio: ',
    'LBL_ERROR_SEPE_PATTERN' => 'El siguiente campo (del módulo) no tiene el formato correcto: ',
    'LBL_ERROR_SEPE_NOT_IN_LIST' => 'El valor del siguiente campo (del módulo) no pertenece a las listas SEPE: ',
    'LBL_ERROR_SEPE_LENGTH_EXCEEDED' => 'El valor del siguiente campo (del módulo) es demasiado largo: ',
    'LBL_ERRORS_SEPE_CHECK' => 'Revisar',
    'LBL_SEPE_WARNINGS_TITLE' => 'Avisos (registros que podrían añadirse al fichero pero que necesitan modificaciones previas)',
    'LBL_WARNING_JOB_OFFER_NOT_SEPE_ACTIVATED' => 'Una persona dada de alta en el SEPE está vinculada a una oferta que no tiene fecha de activación SEPE (o la fecha de activación de la oferta es posterior a la fecha de alta de la persona): ',
    'LBL_WARNING_JOB_OFFER_COVERED_NOT_ACTIVATED' => 'Una oferta con fecha de oferta cubierta SEPE no tiene fecha de activación SEPE o la fecha de activación es posterior: ',
    'LBL_WARNING_CONTACT_NOT_SEPE_ACTIVATED' => 'Una persona vinculada a una oferta SEPE no está dada de alta con la acción de Alta SEPE (o la fecha de alta es posterior a fechas de la candidatura):',
    'LBL_WARNING_CONTACT_AND_JOB_OFFER_NOT_SEPE_ACTIVATED' => "Una candidatura tiene por lo menos una fecha anterior a la fecha de alta SEPE de la persona o a la fecha de activación de la oferta: ",
    'LBL_WARNING_ACCD_ACTION_SEPE_NOT_ACTIVATED' => 'Una persona tiene acciones SEPE pero no está dada de alta con la acción de Alta SEPE (o la fecha de alta es posterior a fechas de las acciones): ',
    'LBL_WARNING_ACCI_ACTION_SEPE_NOT_ACTIVATED' => 'Una persona tiene incidencias SEPE, pero no está dada de alta con la acción de Alta SEPE (o la fecha de alta es posterior a fechas de las incidencias): ',
    'LBL_PANEL_RECORD_DETAILS' => 'Detalles del registro',
    'LBL_DEFAULT_PANEL' => 'Datos generales',
    'LBL_REPORTED_MONTH_HELP' => 'Seleccione cualquier día en el mes o en el año para el que quiera generar el fichero. Por ejemplo, si desea generar el fichero de septiembre puede seleccionar cualquier día entre el 1 y el 30 de ese mes. Si desea generar el fichero del año 2019 puede seleccionar cualquier día entre el 1 de enero y el 31 de diciembre de ese año.',
    'LBL_AGREEMENT_HELP' => "El campo Código de Convenio se activa y es obligatorio para generar ficheros de tipo ACCD o ACCI. Se pueden gestionar los convenios editando la lista 'stic_sepe_agreement_list' en el Editor de listas desplegables.",
    'LBL_AGREEMENT_EMPTY_ERROR' => "Si el campo Tipo contiene un valor de tipo ACCD o ACCI el campo Convenio es obligatorio.",
    'LBL_AGREEMENT_ERROR' => "Si el campo Tipo no contiene un valor de tipo ACCD o ACCI, el campo Convenio debe de estar vacío.",
);
