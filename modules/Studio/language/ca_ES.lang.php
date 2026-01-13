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
    'LBL_EDIT_LAYOUT' => 'Edita el disseny',
    'LBL_EDIT_FIELDS' => 'Edita els camps',
    'LBL_SELECT_FILE' => 'Selecciona un arxiu',
    'LBL_MODULE_TITLE' => 'Estudi',
    'LBL_TOOLBOX' => "Caixa d'eines",
    'LBL_SUITE_FIELDS_STAGE' => "Camps de SuiteCRM (feu clic als elements per afegir-los a l'àrea de disseny)",
    'LBL_VIEW_SUITE_FIELDS' => 'Mostra els camps de SuiteCRM',
    'LBL_FAILED_TO_SAVE' => 'Error al desar',
    'LBL_CONFIRM_UNSAVE' => "Els canvis no s'han desat i es perdran. Segur que voleu continuar?",
    'LBL_PUBLISHING' => 'Publicant...',
    'LBL_PUBLISHED' => 'Publicat',
    'LBL_FAILED_PUBLISHED' => 'Error al publicar',
    'LBL_DROP_HERE' => '[Deixeu-ho anar aquí]',

//CUSTOM FIELDS
    'LBL_NAME' => 'Nom',
    'LBL_LABEL' => 'Etiqueta',
    'LBL_MASS_UPDATE' => 'Actualització massiva',
    'LBL_DEFAULT_VALUE' => 'Valor per defecte',
    'LBL_REQUIRED' => 'Requerit',
    'LBL_DATA_TYPE' => 'Tipus',


    'LBL_HISTORY' => 'Historial',

//WIZARDS

//STUDIO WIZARD
    'LBL_SW_WELCOME' => "<h2>Benvingut a l'Estudi!</h2><br> Què voleu fer avui? Seleccioneu una de les següents opcions.</b>",
    'LBL_SW_EDIT_MODULE' => 'Editar un mòdul',
    'LBL_SW_EDIT_DROPDOWNS' => 'Editar les llistes desplegables',
    'LBL_SW_EDIT_TABS' => 'Configurar les pestanyes',
    'LBL_SW_RENAME_TABS' => 'Canviar el nom de pestanyes',
    'LBL_SW_EDIT_GROUPTABS' => 'Configurar els grups de pestanyes',
    'LBL_SW_EDIT_PORTAL' => 'Editar el portal',
    'LBL_SW_REPAIR_CUSTOMFIELDS' => 'Reparar els camps personalitzats',
    'LBL_SW_MIGRATE_CUSTOMFIELDS' => 'Migrar els camps personalitzats',

//Manager Backups History
    'LBL_MB_DELETE' => 'Esborra',

//EDIT DROP DOWNS
    'LBL_ED_CREATE_DROPDOWN' => 'Crea una llista desplegable',
    'LBL_DROPDOWN_NAME' => 'Nom de la llista desplegable:',
    'LBL_DROPDOWN_LANGUAGE' => 'Idioma de la llista desplegable:',
    'LBL_TABGROUP_LANGUAGE' => 'Idioma:',

//END WIZARDS

//DROP DOWN EDITOR
    'LBL_DD_DISPALYVALUE' => 'Valor de visualització',
    'LBL_DD_DATABASEVALUE' => 'Valor de base de dades',
    'LBL_DD_ALL' => 'Tot',

//BUTTONS
    'LBL_BTN_SAVE' => 'Desa',
    'LBL_BTN_CANCEL' => 'Cancel·la',
    'LBL_BTN_SAVEPUBLISH' => 'Desa i publica',
    'LBL_BTN_HISTORY' => 'Historial',
    'LBL_BTN_ADDROWS' => 'Afegeix files',
    'LBL_BTN_UNDO' => 'Desfés',
    'LBL_BTN_REDO' => 'Repeteix',
    'LBL_BTN_ADDCUSTOMFIELD' => 'Afegeix un camp personalitzat',
    'LBL_BTN_TABINDEX' => "Edita l'ordre de les pestanyes",

//TABS
    'LBL_MODULES' => 'Mòduls',
    'LBL_MODULE_NAME' => 'Administració',
    'LBL_CONFIGURE_GROUP_TABS' => 'Configurar el mòdul de menú de filtres',
    'LBL_GROUP_TAB_WELCOME' => 'El disseny dels Grups de Pestanyes s\'usarà sempre que un usuari elegeixi utilitzar Grups de Pestanyes en lloc de les Pestanyes de Mòduls habituals en Dc. Compte>Opcions de Presentació.',
    'LBL_RENAME_TAB_WELCOME' => 'Faci clic en el Valor de Visualització de qualsevol pestanya de la següent taula per rebatejar la pestanya.',
    'LBL_DELETE_MODULE' => 'Eliminar&nbsp;mòdul<br />del&nbsp;filtre',
    'LBL_TAB_GROUP_LANGUAGE_HELP' => 'Seleccioneu un dels idiomes disponibles, editeu les etiquetes del grup i feu clic a desa i desplega per aplicar-les.',
    'LBL_ADD_GROUP' => 'Afegeix un filtre',
    'LBL_NEW_GROUP' => 'Nou grup',
    'LBL_RENAME_TABS' => 'Canvia el nom de les pestanyes',

//ERRORS
    'ERROR_INVALID_KEY_VALUE' => "Error: El valor de la clau no és vàlid: [']",

//SUGAR PORTAL
    'LBL_SAVE' => 'Desa' /*for 508 compliance fix*/,
    'LBL_UNDO' => 'Desfés' /*for 508 compliance fix*/,
    'LBL_REDO' => 'Repeteix' /*for 508 compliance fix*/,
    'LBL_INLINE' => 'de línia' /*for 508 compliance fix*/,
    'LBL_DELETE' => 'Elimina' /*for 508 compliance fix*/,
    'LBL_ADD_FIELD' => 'Afegeix un camp' /*for 508 compliance fix*/,
    'LBL_MAXIMIZE' => 'Maximitza' /*for 508 compliance fix*/,
    'LBL_MINIMIZE' => 'Minimitza' /*for 508 compliance fix*/,
    'LBL_PUBLISH' => 'Publica' /*for 508 compliance fix*/,
    'LBL_ADDROWS' => 'Afegeix files' /*for 508 compliance fix*/,
    'LBL_ADDFIELD' => 'Afegeix un camp' /*for 508 compliance fix*/,
    'LBL_EDIT' => 'Edita' /*for 508 compliance fix*/,

    'LBL_LANGUAGE_TOOLTIP' => "Seleccioneu l'idioma que voleu editar.",
    'LBL_SINGULAR' => 'Etiqueta del singular',
    'LBL_PLURAL' => 'Etiqueta del plural',
    'LBL_RENAME_MOD_SAVE_HELP' => 'Feu clic a <b>Desa</b> per aplicar els canvis.'

);
