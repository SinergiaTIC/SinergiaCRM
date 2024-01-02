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
    'LBL_ASSIGNED_TO_ID' => 'Asignado a',
    'LBL_ASSIGNED_TO_NAME' => 'Asignado a',
    'LBL_ASSIGNED_TO' => 'Asignado a',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Asignado a',
    'LBL_LIST_ASSIGNED_USER' => 'Asignado a',
    'LBL_CREATED' => 'Creado por',
    'LBL_CREATED_USER' => 'Creado por',
    'LBL_CREATED_ID' => 'Creado por',
    'LBL_MODIFIED' => 'Modificado por',
    'LBL_MODIFIED_NAME' => 'Modificado por',
    'LBL_MODIFIED_USER' => 'Modificado por',
    'LBL_MODIFIED_ID' => 'Modificado por',
    'LBL_SECURITYGROUPS' => 'Grupos de seguridade',
    'LBL_SECURITYGROUPS_SUBPANEL_TITLE' => 'Grupos de seguridade',
    'LBL_ID' => 'ID',
    'LBL_DATE_ENTERED' => 'Data de Creación',
    'LBL_DATE_MODIFIED' => 'Data de Modificación',
    'LBL_DESCRIPTION' => 'Descrición',
    'LBL_DELETED' => 'Eliminado',
    'LBL_NAME' => 'Nome',
    'LBL_LIST_NAME' => 'Nome',
    'LBL_EDIT_BUTTON' => 'Editar',
    'LBL_REMOVE' => 'Quitar',
    'LBL_LIST_FORM_TITLE' => 'Lista de Ficheiros XML SEPE',
    'LBL_MODULE_NAME' => 'Xerador XML SEPE',
    'LBL_MODULE_TITLE' => 'Xerador XML SEPE',
    'LBL_HOMEPAGE_TITLE' => 'Os meus Ficheiros XML SEPE',
    'LNK_NEW_RECORD' => 'Crear un Ficheiro XML SEPE',
    'LNK_LIST' => 'Ver Ficheiros XML SEPE',
    'LNK_IMPORT_STIC_SEPE_FILES' => 'Importar Ficheiros XML SEPE',
    'LBL_SEARCH_FORM_TITLE' => 'Buscar Ficheiros XML SEPE',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'Historial',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Actividades',
    'LBL_STIC_SEPE_FILES_SUBPANEL_TITLE' => 'Xerador XML SEPE',
    'LBL_NEW_FORM_TITLE' => 'Novo Ficheiro XML SEPE',
    'LBL_STATUS' => 'Estado',
    'LBL_TYPE' => 'Tipo de ficheiro',
    'LBL_REPORTED_MONTH' => 'Mes/ano reportado',
    'LBL_LOG' => 'Rexistro de erros',
    'LBL_AGREEMENT' => 'Código de convenio',
    'LBL_GENERATE_XML_FILE' => 'Xerar ficheiro XML',
    'LBL_SEPE_LOG_TITLE' => 'O ficheiro foi xerado por última vez por: ',
    'LBL_SEPE_GENERATED_WITHOUT_ERRORS' => 'Ficheiro XML xerado sen erros',
    'LBL_SEPE_XML_HAS_ERRORS' => 'O arquivo XML non se xerou porque existen erros que deben ser corrixidos.',
    'LBL_ERROR_DATE_FUTURE' => 'O valor do campo Mes reportado ten que ser igual ou inferior ao mes e ano actual.',
    'LBL_ERROR_SEPE_CODIGO_AGENCIA' => 'O valor do parámetro SEPE_CODIGO_AGENCIA non é correcto. Debería ser unha cadea numérica de 10 dixitos.',
    'LBL_ERROR_SEPE_AGREEMENT' => 'Para ficheiros do tipo ACCD ou ACCI o Código de Convenio é obrigatorio.',
    'LBL_ERROR_PATTERN_SEPE_AGREEMENT' => 'O Codigo de Convenio non é correcto. Ten que ser unha cadea de 10 caracteres composta de números e letras.',
    'LBL_SEPE_ERRORS_TITLE' => 'O ficheiro non se xerou debido aos seguintes erros:',
    'LBL_ERROR_SEPE_REQUIRED' => 'O seguinte campo (do módulo) é obrigatorio: ',
    'LBL_ERROR_SEPE_PATTERN' => 'O seguinte campo (do módulo) non ten o formato correcto: ',
    'LBL_ERROR_SEPE_NOT_IN_LIST' => 'O valor do seguinte campo (do módulo) non pertence ás listas SEPE: ',
    'LBL_ERROR_SEPE_LENGTH_EXCEEDED' => 'O valor do seguinte campo (do módulo) é demasiado longo: ',
    'LBL_ERRORS_SEPE_CHECK' => 'Revisar',
    'LBL_SEPE_WARNINGS_TITLE' => 'Avisos (rexistros que poderían engadirse ao ficheiro pero que necesitan modificacións previas)',
    'LBL_WARNING_JOB_OFFER_NOT_SEPE_ACTIVATED' => 'Unha persoa dada de alta no SEPE está vinculada a unha oferta que non ten data de activación SEPE (ou a data de activación da oferta é posterior á data de alta da persoa): ',
    'LBL_WARNING_JOB_OFFER_COVERED_NOT_ACTIVATED' => 'Unha oferta con data de oferta cuberta SEPE non ten data de activación SEPE ou a data de activación é posterior: ',
    'LBL_WARNING_CONTACT_NOT_SEPE_ACTIVATED' => 'Unha persoa vinculada a unha oferta SEPE non está dada de alta coa acción de Alta SEPE (ou a data de alta é posterior a datas da candidatura):',
    'LBL_WARNING_CONTACT_AND_JOB_OFFER_NOT_SEPE_ACTIVATED' => "Unha candidatura ten polo menos unha data anterior á data de alta SEPE da persoa ou á data de activación da oferta: ",
    'LBL_WARNING_ACCD_ACTION_SEPE_NOT_ACTIVATED' => 'Unha persoa ten accións SEPE pero non está dada de alta coa acción de Alta SEPE (ou a data de alta é posterior a datas das accións): ',
    'LBL_WARNING_ACCI_ACTION_SEPE_NOT_ACTIVATED' => 'Unha persoa ten incidencias SEPE, pero non está dada de alta coa acción de Alta SEPE (ou a data de alta é posterior a datas das incidencias): ',
    'LBL_PANEL_RECORD_DETAILS' => 'Detalles do rexistro',
    'LBL_DEFAULT_PANEL' => 'Datos xerais',
    'LBL_REPORTED_MONTH_HELP' => 'Seleccione calquera día no mes ou no ano para o que queira xerar o ficheiro. Por exemplo, se desexa xerar o ficheiro de setembro pode seleccionar calquera día entre o 1 e o 30 dese mes. Se desexa xerar o ficheiro do ano 2019 pode seleccionar calquera día entre o 1 de xaneiro e o 31 de decembro dese ano.',
    'LBL_AGREEMENT_HELP' => "O campo Código de Convenio se activa e é obrigatorio para xerar ficheiros de tipo ACCD ou ACCI. Se poden xestionar os convenios editando a lista 'stic_sepe_agreement_list' non Editor de listas despregables.",
    'LBL_AGREEMENT_EMPTY_ERROR' => "Se o campo Tipo contén un valor de tipo ACCD ou ACCI o campo Convenio é obrigatorio.",
    'LBL_AGREEMENT_ERROR' => "Se o campo Tipo non contén un valor de tipo ACCD ou ACCI, o campo Convenio debe de estar baleiro.",
);
