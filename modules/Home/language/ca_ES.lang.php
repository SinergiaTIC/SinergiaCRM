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
    'LBL_MODULE_NAME' => 'Inici',
    'LBL_NEW_FORM_TITLE' => 'Nou Contacte',
    'LBL_FIRST_NAME' => 'Nom:',
    'LBL_LAST_NAME' => 'Cognoms:',
    'LBL_LIST_LAST_NAME' => 'Cognoms',
    'LBL_PHONE' => 'Telèfon:',
    'LBL_EMAIL_ADDRESS' => 'Adreça de correu electrònic:',
    'LBL_MY_PIPELINE_FORM_TITLE' => 'El meu Objectiu',
    'LBL_PIPELINE_FORM_TITLE' => 'Objectiu per Etapa de Vendes',
    'LBL_RGraph_PIPELINE_FORM_TITLE' => 'Objectiu Per Etapa de Vendes',
    'LNK_NEW_CONTACT' => 'Nou Contacte',
    'LNK_NEW_ACCOUNT' => 'Nou Compte',
    'LNK_NEW_OPPORTUNITY' => 'Nova Oportunitat',
    'LNK_NEW_LEAD' => 'Nou Client Potencial',
    'LNK_NEW_CASE' => 'Nou Cas',
    'LNK_NEW_NOTE' => 'Nova Nota o Arxiu Adjunt',
    'LNK_NEW_CALL' => 'Programar Trucada',
    'LNK_NEW_EMAIL' => 'Arxivar correu electrònic',
    'LNK_NEW_MEETING' => 'Programar Reunió',
    'LNK_NEW_TASK' => 'Nova Tasca',
    'LNK_NEW_BUG' => 'Informar d\'Incidència',
    'LNK_NEW_SEND_EMAIL' => 'Redactar correu electrònic',
    'LBL_NO_ACCESS' => 'No té accés a aquesta zona. Contacti amb l\'administrador per a obtenir accés.',
    'LBL_NO_RESULTS_IN_MODULE' => '-- Sense Resultats --',
    'LBL_NO_RESULTS' => '<h2>No s\'han trobat resultats. Si us plau, realitzi una nova cerca.</h2><br>',
    'LBL_NO_RESULTS_TIPS' => '<h3>Trucs per a la Cerca:</h3><ul><li>Asseguris que ha seleccionat les categories adequades més a dalt.</li><li>Ampliï els seus criteris de recerca.</li><li>Si tot i així no obté resultats, provi amb l\'opció de cerca avançada.</li></ul >',

    'LBL_ADD_DASHLETS' => 'Afegir Dashlets',
    'LBL_WEBSITE_TITLE' => 'Lloc Web',
    'LBL_RSS_TITLE' => 'Font de noticies',
    'LBL_CLOSE_DASHLETS' => 'Tancar',
    'LBL_OPTIONS' => 'Opcions',
    // dashlet search fields
    'LBL_TODAY' => 'Avui',
    'LBL_YESTERDAY' => 'Ahir',
    'LBL_TOMORROW' => 'Demà',
    'LBL_NEXT_WEEK' => 'La Próxima Setmana',
    'LBL_LAST_7_DAYS' => 'Últims 7 Dies',
    'LBL_NEXT_7_DAYS' => 'Següents 7 Dies',
    'LBL_LAST_MONTH' => 'Últim Mes',
    'LBL_NEXT_MONTH' => 'Següent Mes',
    'LBL_LAST_YEAR' => 'Últim Any',
    'LBL_NEXT_YEAR' => 'Proper Any',
    'LBL_LAST_30_DAYS' => 'Últims 30 Dies',
    'LBL_NEXT_30_DAYS' => 'Pròxims 30 dies',
    'LBL_THIS_MONTH' => 'Aquest Mes',
    'LBL_THIS_YEAR' => 'Aquest Any',

    'LBL_MODULES' => 'Mòduls',
    'LBL_CHARTS' => 'Gràfics',
    'LBL_TOOLS' => 'Eines',
    'LBL_WEB' => 'Web',
    'LBL_SEARCH_RESULTS' => 'Resultats de la Cerca',

    // Dashlet Categories
    'dashlet_categories_dom' => array(
        'Module Views' => 'Vistes del Mòdul',
        'Portal' => 'Portal',
        'Charts' => 'Gràfics',
        'Tools' => 'Eines',
        'Miscellaneous' => 'Altres'
    ),
    'LBL_ADDING_DASHLET' => 'Afegint Dashlet de SuiteCRM...',
    'LBL_ADDED_DASHLET' => 'Dashlet de SuiteCRM afegit',
    'LBL_REMOVE_DASHLET_CONFIRM' => 'Està segur que vol eliminar el Dashlet de SuiteCRM?',
    'LBL_REMOVING_DASHLET' => 'Eliminant Dashlet de SuiteCRM...',
    'LBL_REMOVED_DASHLET' => 'Dashlet de SuiteCRM eliminat',
    'LBL_DASHLET_CONFIGURE_GENERAL' => 'General',
    'LBL_DASHLET_CONFIGURE_FILTERS' => 'Filtres',
    'LBL_DASHLET_CONFIGURE_MY_ITEMS_ONLY' => 'Només els Meus Elements',
    'LBL_DASHLET_CONFIGURE_TITLE' => 'Títol',
    'LBL_DASHLET_CONFIGURE_DISPLAY_ROWS' => 'Mostrar Files',

    'LBL_DASHLET_DELETE' => 'Eliminar Dashlet de SuiteCRM',
    'LBL_DASHLET_REFRESH' => 'Refrescar Dashlet de SuiteCRM',
    'LBL_DASHLET_EDIT' => 'Editar Dashlet de SuiteCRM',

    // Default out-of-box names for tabs
    'LBL_HOME_PAGE_1_NAME' => 'El meu CRM',
    'LBL_CLOSE_SITEMAP' => 'Tancar',

    'LBL_SEARCH' => 'Cercar',
    'LBL_CLEAR' => 'Netejar',

    'LBL_BASIC_CHARTS' => 'Gràfics Bàsics',

    'LBL_DASHLET_SEARCH' => 'Cercar Dashlet de SuiteCRM',

//ABOUT page
    'LBL_VERSION' => 'Versió',
    'LBL_BUILD' => 'Construir',

    'LBL_SOURCE_SUGAR' => 'SugarCRM Inc - proveïdors de CE framework',

    'LBL_DASHLET_TITLE' => 'Portal',
    'LBL_DASHLET_OPT_TITLE' => 'Títol',
    'LBL_DASHLET_INCORRECT_URL' => 'La ubicació especificada de la web es incorrecta',
    'LBL_DASHLET_OPT_URL' => 'Adreça del lloc web',
    'LBL_DASHLET_OPT_HEIGHT' => 'Altura del Dashlet (en píxels)',
    'LBL_DASHLET_SUITE_NEWS' => 'Noticies sobre SuiteCRM',
    'LBL_DASHLET_DISCOVER_SUITE' => 'Descobrir SuiteCRM',
    'LBL_BASIC_SEARCH' => 'Filtre ràpid' /*for 508 compliance fix*/,
    'LBL_ADVANCED_SEARCH' => 'Filtre avançat' /*for 508 compliance fix*/,
    'LBL_TOUR_HOME' => 'Icona de la pàgina d\'inici',
    'LBL_TOUR_HOME_DESCRIPTION' => 'Torni a la seva pàgina d\'inici en un sol clic.',
    'LBL_TOUR_MODULES' => 'Mòdul',
    'LBL_TOUR_MODULES_DESCRIPTION' => 'Tots els seus mòduls importants estan aquí.',
    'LBL_TOUR_MORE' => 'Més mòduls',
    'LBL_TOUR_MORE_DESCRIPTION' => 'La resta dels seus mòduls estan aquí.',
    'LBL_TOUR_SEARCH' => 'Cerca de text complet',
    'LBL_TOUR_SEARCH_DESCRIPTION' => 'La cerca ara es molt millor.',
    'LBL_TOUR_NOTIFICATIONS' => 'Notificacions',
    'LBL_TOUR_NOTIFICATIONS_DESCRIPTION' => 'Les notificacions de l\'aplicació SuiteCRM aniran aquí.',
    'LBL_TOUR_PROFILE' => 'Perfil',
    'LBL_TOUR_PROFILE_DESCRIPTION' => 'Accedir al perfil, la configuració i finalitzar sessió.',
    'LBL_TOUR_QUICKCREATE' => 'Creació Ràpida',
    'LBL_TOUR_QUICKCREATE_DESCRIPTION' => 'Creació de registres ràpida sense perdre posició.',
    'LBL_TOUR_FOOTER' => 'Peu de pàgina plegable',
    'LBL_TOUR_FOOTER_DESCRIPTION' => 'Plegui i desplegui el peu de pàgina facilment.',
    'LBL_TOUR_CUSTOM' => 'Apps personalitzades',
    'LBL_TOUR_CUSTOM_DESCRIPTION' => 'integracions personalitzades hauríen d\'anar aquí.',
    'LBL_TOUR_BRAND' => 'La teva marca',
    'LBL_TOUR_BRAND_DESCRIPTION' => 'El seu logo va aquí. Passi el ratolí per sobre per a més informació.',
    'LBL_TOUR_WELCOME' => 'Benvingut a SuiteCRM',
    'LBL_TOUR_WATCH' => 'Veure les novetats de SuiteCRM',
    'LBL_TOUR_FEATURES' => '<ul style=""><li class="icon-ok">Nova barra de navegació simplificada</li><li class="icon-ok">Nova peu plegable</li><li class="icon-ok">Millora de la cerca</li><li class="icon-ok">Actualitzat menú d\'accions</li></ul><p>I molt més!</p>',
    'LBL_TOUR_VISIT' => 'Per a més informació visiti la nostra aplicació',
    'LBL_TOUR_DONE' => 'Ja està!',
    'LBL_TOUR_REFERENCE_1' => 'sempre pots referenciar al nostre ',
    'LBL_TOUR_REFERENCE_2' => 'a través del link de soport a sota de la pestanya del perfil.',
    'LNK_TOUR_DOCUMENTATION' => 'documentació',
    'LBL_TOUR_CALENDAR_URL_1' => 'Compartiu el vostre calendari SuiteCRM amb aplicacions de 3a, com Microsoft, Outlook o l\'Exchange? Si és així, vostè té una adreça URL nova. Aquesta nova URL, més segura inclou una clau personal que evitarà la publicació no autoritzada de calendari.',
    'LBL_TOUR_CALENDAR_URL_2' => 'Recuperar el teu nou calendari compartit URL',
    'LBL_CONTRIBUTORS' => 'Colaboradors',
    'LBL_ABOUT_SUITE' => 'Sobre SuiteCRM',
    'LBL_PARTNERS' => 'Socis',
    'LBL_FEATURING' => 'Els mòduls AOS, AOW, AOR, AOP, AOE i Replanificació són de SalesAgility.',

    'LBL_CONTRIBUTOR_SUITECRM' => 'SuiteCRM - CRM de codi obert per a tot el món',
    'LBL_CONTRIBUTOR_SECURITY_SUITE' => 'SecuritySuite per Jason Eggers',
    'LBL_CONTRIBUTOR_JJW_GMAPS' => 'JJWDesign Google Maps per Jeffrey J.Walters',
    'LBL_CONTRIBUTOR_CONSCIOUS' => 'SuiteCRM LOGO proporcionat per Conscious Solutions',
    'LBL_CONTRIBUTOR_RESPONSETAP' => 'Contribució a SuiteCRM 7.3 publicada per ResponseTap',
    'LBL_CONTRIBUTOR_GMBH' => 'Camps calculats del flux de treball aportat per diligent technology & business consulting GmbH',

    'LBL_LANGUAGE_ABOUT' => 'Sobre traduccions SuiteCRM',
    'LBL_LANGUAGE_COMMUNITY_ABOUT' => 'Traducció col·laborativa per la comunitat SuiteCRM',
    'LBL_LANGUAGE_COMMUNITY_PACKS' => 'Traducció creada utilitzant Crowdin',

    'LBL_ABOUT_SUITE_2' => 'SuiteCRM està publicat sota la llicència de codi obert - GPL3',
    'LBL_ABOUT_SUITE_4' => 'Tot el codi desenvolupat i administrat pel projecte SuiteCRM serà publicat com a codi obert - GPL3',
    'LBL_ABOUT_SUITE_5' => 'El suport de SuiteCRM està disponible tant de forma gratuita com de pagament',

    'LBL_SUITE_PARTNERS' => 'Tenim socis lleials de SuiteCRM que són apassionats del codi obert. Per veure la nostra llista completa de socis, visiteu la nostra pàgina web.',

    'LBL_SAVE_BUTTON' => 'Desa',
    'LBL_DELETE_BUTTON' => 'Elimina',
    'LBL_APPLY_BUTTON' => 'Aplica',
    'LBL_SEND_INVITES' => 'Envia Invitacions',
    'LBL_CANCEL_BUTTON' => 'Cancel·la',
    'LBL_CLOSE_BUTTON' => 'Tanca',

    'LBL_CREATE_NEW_RECORD' => 'Crea una activitat',
    'LBL_CREATE_CALL' => 'Programa una Trucada',
    'LBL_CREATE_MEETING' => 'Programa una Reunió',

    'LBL_GENERAL_TAB' => 'Detalls',
    'LBL_PARTICIPANTS_TAB' => 'Assistents',
    'LBL_REPEAT_TAB' => 'Repetició',

    'LBL_REPEAT_TYPE' => 'Repeteix',
    'LBL_REPEAT_INTERVAL' => 'Cada',
    'LBL_REPEAT_END' => 'Últim',
    'LBL_REPEAT_END_AFTER' => 'Després de',
    'LBL_REPEAT_OCCURRENCES' => 'recurrències',
    'LBL_REPEAT_END_BY' => 'Per',
    'LBL_REPEAT_DOW' => 'En',
    'LBL_REPEAT_UNTIL' => 'Repetir fins',
    'LBL_REPEAT_COUNT' => 'Nombre de recurrències',
    'LBL_REPEAT_LIMIT_ERROR' => 'La vostra sol·licitud anava a crear més de $limit reunions.',

    //Events
    'LNK_EVENT' => 'Esdeveniment',
    'LNK_EVENT_VIEW' => 'Veure esdeveniment',
    'LBL_DATE' => 'Data: ',
    'LBL_DURATION' => 'Durada: ',
    'LBL_NAME' => 'Títol',
    'LBL_HOUR_ABBREV' => 'hora',
    'LBL_HOURS_ABBREV' => 'hores',
    'LBL_MINSS_ABBREV' => 'minuts',
    'LBL_LOCATION' => 'Lloc:',
    'LBL_STATUS' => 'Estat:',
    'LBL_DESCRIPTION' => 'Descripció: ',
    //End Events

    'LBL_ELASTIC_SEARCH_EXCEPTION_SEARCH_INVALID_REQUEST' => 'S\'ha produït un error mentre es realitzava la cerca. La sintaxi de la consulta podria no ser vàlida.',
    'LBL_ELASTIC_SEARCH_EXCEPTION_SEARCH_ENGINE_NOT_FOUND' => 'No es pot trobar el motor de cerca sol·licitat. Intenta realitzar la cerca de nou.',
    'LBL_ELASTIC_SEARCH_EXCEPTION_NO_NODES_AVAILABLE' => 'No s\'ha pogut connectar al servidor Elasticsearch.',
    'LBL_ELASTIC_SEARCH_EXCEPTION_SEARCH' => 'S\'ha produït un error intern a la recerca.',
    'LBL_ELASTIC_SEARCH_EXCEPTION_DEFAULT' => 'S\'ha produït un error desconegut mentre es realitzava la cerca.',
    'LBL_ELASTIC_SEARCH_EXCEPTION_END_MESSAGE' => 'Si el problema persisteix, contacti amb un administrador. Més informació en els registres.'
);
