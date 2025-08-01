<?php
/**
 * This file is part of KReporter. KReporter is an enhancement developed
 * by Christian Knoll. All rights are (c) 2012 by Christian Knoll
 *
 * This file has been modified by SinergiaTIC in SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE
 * along with this program; if not, see http://www.gnu.org/licenses
 * or write to the Free Software Foundation,Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA 02110-1301  USA
 *
 * You can contact Christian Knoll at info@kreporter.org
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$mod_strings = array(
    'LBL_SAVE_BUTTON_LABEL' => 'Desa',
    'LBL_CANCEL_BUTTON_LABEL' => 'Cancel·la',
    'LBL_REPORT_NAME_LABEL' => 'Nom',
    'LBL_LOADMASK' => 'Carregant dades...',
    'LBL_ASSIGNED_USER_LABEL' => 'Usuari',
    'LBL_ASSIGNED_TEAM_LABEL' => 'Equip',
    'LBL_KORGOBJECTS_LABEL' => 'Territori',
    'LBL_REPORT_OPTIONS' => 'Opcions',
    'LBL_DEFAULT_NAME' => 'Nou informe',
    'LBL_SEARCHING' => 'Cercant...',
    'LBL_LONGTEXT_LABEL' => 'Descripció',
    'LBL_DEFAULT_NAME' => 'Nou informe',
    'LBL_CHART_NODATA' => 'No hi ha dades per mostrar',
    'LBL_REPORT_RELOAD' => 'Aplica els filtres',
    'LBL_LIST_LISTTYPE' => 'Llista de tipus',
    'LBL_LIST_CHART_LAYOUT' => 'Disseny del gràfic',
    'LBL_LIST_DATEENTERED' => 'Data de Creació',
    'LBL_LIST_DATEMODIFIED' => 'Data de Modificació',
    'LBL_AUTH_CHECK' => 'Validació d&#39;autorització',
    'LBL_AUTH_FULL' => 'A tots els nodes',
    'LBL_AUTH_TOP' => 'Només al node superior',
    'LBL_AUTH_NONE' => 'Desactivat',
    'LBL_SHOW_DELETED' => 'Mostra els eliminats',
    'LBL_FOLDED_PANELS' => 'Panells desplegables',
    'LBL_DYNOPTIONS' => 'Opcions dinàmiques',
    'LBL_RESULTS' => 'Resultats agrupats',
    'LBL_PANEL_OPEN' => 'Obre',
    'LBL_PANEL_COLLAPSED' => 'Agrupat',
    'LBL_OPTIONS_MENUITEMS' => 'Elements de la barra de tasques',
    'LBL_ADVANCEDOPTIONS_MENUITEMS' => 'Opcions avançades',
    'LBL_AOP_EXPORTTOPLANNING' => 'Exportar a nodes de planificació',
    'LBL_TOOLBARITEMS_FS' => 'Elements de la barra de tasques',
    'LBL_TOOLBARITEMS_SHOW' => 'Mostra',
    'LBL_SHOW_EXPORT' => 'Exporta',
    'LBL_SHOW_SNAPSHOTS' => 'Foto',
    'LBL_SHOW_TOOLS' => 'Eines',
    'LBL_DATA_UPDATE' => 'Data d&#39;actualització',
    'LBL_UPDATE_ON_REQUEST' => 'a petició de l&#39;Usuari',
    'LBL_MODULE_NAME' => 'Informes',
    'LBL_REPORT_STATUS' => 'Estat',
    'LBL_REPORT_SEGMENTATION' => 'Segmentació',
    'LBL_MODULE_TITLE' => 'Informes',
    'LBL_SEARCH_FORM_TITLE' => 'Cerca Informes',
    'LBL_LIST_FORM_TITLE' => 'Llista d&#39;Informes',
    'LBL_NEW_FORM_TITLE' => 'Nou Informe',
    'LBL_LIST_CLOSE' => 'Tanca',
    'LBL_LIST_SUBJECT' => 'Títol',
    'LBL_DESCRIPTION' => 'Descripció:',
    'LNK_NEW_REPORT' => 'Crea un Informe',
    'LNK_REPORT_LIST' => 'Mostra els Informes',
    'LBL_UNIONTREE' => 'Uneix mòduls',
    'LBL_UNIONLISTFIELDS' => 'Llistes de camps de la unió',
    'LBL_UNIONFIELDDISPLAYPATH' => 'Esquema de la unió',
    'LBL_UNIONFIELDNAME' => 'Nom de camp de la unió',
    'LBL_SELECT_MODULE' => 'Seleccioneu un mòdul',
    'LBL_SELECT_TAB' => 'Seleccioneu una etiqueta',
    'LBL_ENTER_SEARCH_TERM' => 'Entreu el terme de cerca',
    'LBL_LIST_MODULE' => 'Mòdul',
    'LBL_LIST_ASSIGNED_USER_NAME' => 'Usuari assignat',
    'LBL_DEFINITIONS' => "Definició de l'informe",
    'LBL_MODULES' => 'Mòduls',
    'LBL_LISTFIELDS' => 'manipula',
    'LBL_PRESENTATION' => 'mostra',
    'LBL_CHARTDEFINITION' => 'Detalls del gràfic',
    'LBL_TARGETLIST_NAME' => 'Nom de la Llista de Públic Objectiu',
    'LBL_TARGETLIST_PROMPT' => 'Nom de la nova Llista de Públic Objectiu',
    'LBL_DUPLICATE_NAME' => 'Nom del nou informe',
    'LBL_DUPLICATE_PROMPT' => 'Indiqueu el nom del nou informe',
    'LBL_DYNAMIC_OPTIONS' => 'Criteris de cerca/filtre',
    // Grid headers
    'LBL_FIELDNAME' => 'Nom de camp',
    'LBL_NAME' => 'Nom',
    'LBL_OPERATOR' => 'Operació',
    'LBL_VALUE_FROM' => 'Igual a/Des de',
    'LBL_VALUE_TO' => 'Fins a',
    'LBL_JOIN_TYPE' => 'Requerit',
    'LBL_TYPE' => 'Tipus',
    'LBL_WIDTH' => 'Amplada',
    'LBL_SORTPRIORITY' => 'Seqüència ordre',
    'LBL_SORTSEQUENCE' => 'Ordena',
    'LBL_EXPORTPDF' => 'mostra en PDF',
    'LBL_DISPLAY' => 'Mostra',
    'LBL_OVERRIDETYPE' => 'Anul·la el tipus',
    'LBL_LINK' => 'Vincle',
    'LBL_WIDGET' => 'Giny',
    'LBL_FIXEDVALUE' => 'Valor fixat',
    'LBL_ASSIGNTOVALUE' => 'Desa',
    'LBL_FORMULAVALUE' => 'Fórmula',
    'LBL_FORMULASEQUENCE' => 'Ordre',
    'LBL_PATH' => 'Ruta',
    'LBL_FULLPATH' => 'Procés tècnic',
    'LBL_SEQUENCE' => 'Ordre',
    'LBL_GROUPBY' => 'Agrupa per',
    'LBL_SQLFUNCTION' => 'Funció',
    'LBL_CUSTOMSQLFUNCTION' => 'Funció personalitzada',
    'LBL_VALUETYPE' => 'Tipus de valor',
    'LBL_DISPLAYFUNCTION' => 'Mostra la funció',
    'LBL_USEREDITABLE' => 'Permet l&#39;edició',
    'LBL_DASHLETEDITABLE' => 'Opcions del dashlet',
    'LBL_QUERYCONTEXT' => 'Context',
    'LBL_QUERYREFERENCE' => 'Referència',
    'LBL_UEOPTION_YES' => 'sí',
    'LBL_UEOPTION_NO' => 'no',
    'LBL_UEOPTION_YFO' => 'només valor',
    'LBL_UEOPTION_YO1' => 'on/(off)',
    'LBL_UEOPTION_YO2' => '(on)/off',
    'LBL_DEOPTION_YES' => 'sí',
    'LBL_DEOPTION_NO' => 'no',
    'LBL_ONOFF_YO1' => 'sí',
    'LBL_ONOFF_YO2' => 'no',
    'LBL_ONOFF_COLUMN' => 's/n',
    // Title and Headers for Multiselect Popup
    'LBL_MUTLISELECT_POPUP_TITLE' => 'Selecciona els valors',
    'LBL_MULTISELECT_VALUE_HEADER' => 'ID',
    'LBL_MULTISELECT_TEXT_HEADER' => 'Valor',
    'LBL_MUTLISELECT_CLOSE_BUTTON' => 'Actualitza',
    'LBL_MUTLISELECT_CANCEL_BUTTON' => 'Cancel·la',
    // Title and Headers for Datetimepicker Popup
    'LBL_DATETIMEPICKER_POPUP_TITLE' => 'Selecciona Data/Hora',
    'LBL_DATETIMEPICKER_CLOSE_BUTTON' => 'Actualitza',
    'LBL_DATETIMEPICKER_CANCEL_BUTTON' => 'Cancel·la',
    'LBL_DATETIMEPICKER_DATE' => 'Data',
    // for the Snapshot Comaprison
    'LBL_SNAPSHOTCOMPARISON_POPUP_TITLE' => 'Gràfic a gràfic',
    'LBL_SNAPSHOTTRENDANALYSIS_POPUP_TITLE' => 'Anàlisi de tendència',
    'LBL_SNAPSHOTCOMPARISON_SNAPHOT_HEADER' => 'Foto',
    'LBL_SNAPSHOTCOMPARISON_DESCRIPTION_HEADER' => 'Descripció',
    'LBL_SNAPSHOTCOMPARISON_SELECT_CHART' => 'Selecciona el gràfic:',
    'LBL_SNAPSHOTCOMPARISON_SELECT_LEFT' => 'Selecciona origen d&#39;esquerra:',
    'LBL_SNAPSHOTCOMPARISON_SELECT_RIGHT' => 'Selecciona origen de dreta:',
    'LBL_SNAPSHOTCOMPARISON_DATASERIES' => 'Data',
    'LBL_SNAPSHOTCOMPARISON_DATADIMENSION' => 'Dimensió',
    'LBL_SNAPSHOTCOMPARISON_CHARTTYPE' => 'Tipus de gràfic',
    'LBL_BASIC_TRENDLINE_BUTTON_LABEL' => 'Anàlisi de Tendència',
    'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSLINE' => 'Línia',
    'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_STACKEDAREA2D' => 'Àrea',
    'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSBAR2D' => 'Barres 2D',
    'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSBAR3D' => 'Barres 3D',
    'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSCOLUMN2D' => 'Columnes 2D',
    'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSCOLUMN3D' => 'Columnes 3D',
    'LBL_SNAPSHOTCOMPARISON_LOADINGCHARTMSG' => 'cercant gràfic',
    // Operator Names
    'LBL_OP_IGNORE' => 'ignora',
    'LBL_OP_EQUALS' => '=',
    'LBL_OP_AUTOCOMPLETE' => 'autocompleta el nom',
    'LBL_OP_SOUNDSLIKE' => 'semblant a',
    'LBL_OP_NOTEQUAL' => '≠',
    'LBL_OP_STARTS' => 'comença per',
    'LBL_OP_CONTAINS' => 'conté',
    'LBL_OP_NOTSTARTS' => 'no comença per',
    'LBL_OP_NOTCONTAINS' => 'no conté',
    'LBL_OP_BETWEEN' => 'es troba entre',
    'LBL_OP_ISEMPTY' => 'està buit',
    'LBL_OP_ISEMPTYORNULL' => 'està buit o és nul',
    'LBL_OP_ISNULL' => 'és nul',
    'LBL_OP_ISNOTEMPTY' => 'no està buit',
    'LBL_OP_FIRSTDAYOFMONTH' => 'el dia 1 d&#39;aquest mes',
    'LBL_OP_FIRSTDAYNEXTMONTH' => 'el dia 1 del mes que ve',
    'LBL_OP_NTHDAYOFMONTH' => 'el dia n d&#39;aquest mes',
    'LBL_OP_THISMONTH' => 'en el mes actual',
    'LBL_OP_NOTTHISMONTH' => 'no en el mes actual',
    'LBL_OP_THISWEEK' => 'aquesta setmana',
    'LBL_OP_NEXTNMONTH' => 'en els propers n mesos',
    'LBL_OP_NEXT3MONTH' => 'en els propers 3 mesos',
    'LBL_OP_NEXT3MONTHDAILY' => 'en els propers 3 mesos diàriament', 
    'LBL_OP_NEXT6MONTH' => 'en els propers 6 mesos', 
    'LBL_OP_NEXT6MONTHDAILY' => 'en els propers 6 mesos diàriament',
    'LBL_OP_LAST3MONTHDAILY' => 'en els darrers 3 mesos diàriament', 
    'LBL_OP_LAST6MONTH' => 'en els darrers 6 mesos', 
    'LBL_OP_LAST6MONTHDAILY' => 'en els darrers 6 mesos diàriament',
    'LBL_OP_LASTNFMONTH' => 'en els darrers n mesos sencers',
    'LBL_OP_TODAY' => 'avui',
    'LBL_OP_PAST' => 'en el passat',
    'LBL_OP_FUTURE' => 'en el futur',
    'LBL_OP_BEFORENDAYS' => 'abans de n dies (comptador)',
    'LBL_OP_LASTNDAYS' => 'en els darrers n dies (comptador)',
    'LBL_OP_LASTNFDAYS' => 'en els darrers n dies complerts (comptador)',
    'LBL_OP_LASTNDDAYS' => 'en els darrers n dies (data)',
    'LBL_OP_LASTNWEEKS' => 'en les darreres n setmanes',
    'LBL_OP_NOTLASTNWEEKS' => 'no en les darreres n setmanes',
    'LBL_OP_LASTNFWEEKS' => 'en les darreres n setmanes complertes',
    'LBL_OP_AFTERNDAYS' => 'després de n dies (comptador)',
    'LBL_OP_NEXTNDAYS' => 'en els propers n dies (comptador)',
    'LBL_OP_NEXTNDDAYS' => 'en els propers n dies (data)',
    'LBL_OP_NEXTNWEEKS' => 'en les properes n setmanes',
    'LBL_OP_NOTNEXTNWEEKS' => 'no en les properes n setmanes',
    'LBL_OP_BETWNDAYS' => 'entre n dies (comptador)',
    'LBL_OP_BETWNDDAYS' => 'entre n dies (data)',
    'LBL_OP_BEFORE' => 'abans de',
    'LBL_OP_AFTER' => 'després de',
    'LBL_OP_LASTMONTH' => 'en el darrer mes',
    'LBL_OP_LAST3MONTH' => 'en els darrers 3 mesos',
    'LBL_OP_THISYEAR' => 'aquest any',
    'LBL_OP_LASTYEAR' => 'l&#39;any passat',
    'LBL_OP_TYYTD' => 'aquest any fins avui',
    'LBL_OP_LYYTD' => 'l&#39;any passat fins "avui"',
    'LBL_OP_GREATER' => '>',
    'LBL_OP_LESS' => '<',
    'LBL_OP_GREATEREQUAL' => '>=',
    'LBL_OP_LESSEQUAL' => '<=',
    'LBL_OP_ONEOF' => 'és un de',
    'LBL_OP_ONEOFNOT' => 'no és un de',
    'LBL_OP_ONEOFNOTORNULL' => 'no és un de o és NULL',
    'LBL_OP_PARENT_ASSIGN' => 'assigna al nivell superior',
    'LBL_OP_FUNCTION' => 'funció',
    'LBL_OP_REFERENCE' => 'referència',
    'LBL_BOOL_0' => 'fals',
    'LBL_BOOL_1' => 'cert',
    // for the List view Menu
    'LBL_LISTVIEW_OPTIONS' => 'Opcions de llista',
    // List Limits
    'LBL_LI_TOP10' => 'els primers 10',
    'LBL_LI_TOP20' => 'els primers 20',
    'LBL_LI_TOP50' => 'els primers 50',
    'LBL_LI_TOP250' => 'els primers 250',
    'LBL_LI_BOTTOM50' => 'els darrers 50',
    'LBL_LI_BOTTOM10' => 'els darrers 10',
    'LBL_LI_NOLIMIT' => 'sense límit',

    // buttons
    'LBL_CHANGE_GROUP_NAME' => 'Canvia el nom del Grup',
    'LBL_CHANGE_GROUP_NAME_PROMPT' => 'Nom :',
    'LBL_ADD_GROUP_NAME' => 'Crea un nou Grup',

    'LBL_SELECTION_CLAUSE' => 'Seleccioneu la condició: ',
    'LBL_SELECTION_LIMIT' => 'Limita la llista a:',
    'LBL_RECORDS' => 'Registres', 
    'LBL_PERCENTAGE' => '%',
    'LBL_EDIT_BUTTON_LABEL' => 'Edita',
    'LBL_DELETE_BUTTON_LABEL' => 'Esborra',
    'LBL_ADD_BUTTON_LABEL' => 'Afegeix',
    'LBL_ADDEMTPY_BUTTON_LABEL' => 'Afegeix valor fixat',
    'LBL_DOWN_BUTTON_LABEL' => '',
    'LBL_UP_BUTTON_LABEL' => '',
    'LBL_SNAPSHOT_BUTTON_LABEL' => 'Fes una Foto',
    'LBL_CURRENT_SNAPSHOT' => 'actual',
    'LBL_SNAPSHOTMENU_BUTTON_LABEL' => 'Fotos',
    'LBL_TOOLSMENU_BUTTON_LABEL' => 'Eines',
    'LBL_EXPORTMENU_BUTTON_LABEL' => 'Exporta',
    'LBL_COMPARE_SNAPSHOTS_BUTTON_LABEL' => 'Comparació gràfic a gràfic',
    'LBL_EXPORT_TO_EXCEL_BUTTON_LABEL' => 'Excel',
    'LBL_EXPORT_TO_KLM_BUTTON_LABEL' => 'Google Earth KML',
    'LBL_EXPORT_TO_PDF_BUTTON_LABEL' => 'PDF',
    'LBL_EXPORT_TO_PDFWCHART_BUTTON_LABEL' => 'PDF w. Chart',
    'LBL_EXPORT_TO_TARGETLIST_BUTTON_LABEL' => 'Llista de Públic Objectiu',
    'LBL_SQL_BUTTON_LABEL' => 'SQL',
    'LBL_DUPLICATE_REPORT_BUTTON_LABEL' => 'Duplica',
    'LBL_LISTTYPE' => 'Tipus de Llista',
    'LBL_CHART_LAYOUTS' => 'Aparença',
    'LBL_CHART_TYPE' => 'Tipus',
    'LBL_CHART_DIMENSION' => 'Dimensió',
    'LBL_CHART_INDEX_LABEL' => 'Índex de gràfics',
    'LBL_CHART_INDEX_EMPTY_TEXT' => 'Seleccioneu un ID de gràfic',
    'LBL_CHART_LABEL' => 'Gràfic',
    'LBL_CHART_HEIGHT_LABEL' => 'Alçada del gràfic',
     
    // Dropdown Values
    'LBL_DD_1' => 'sí',
    'LBL_DD_0' => 'no',

    // DropDownValues
    'LBL_DD_SEQ_YES' => 'Sí',
    'LBL_DD_SEQ_NO' => 'No',
    'LBL_DD_SEQ_PRIMARY' => '1',
    'LBL_DD_SEQ_2' => '2',
    'LBL_DD_SEQ_3' => '3',
    'LBL_DD_SEQ_4' => '4',
    'LBL_DD_SEQ_5' => '5',
    // Panel Titles
    'LBL_WHERRE_CLAUSE_TITLE' => 'selecciona',
    //Confirm Dialog
    'LBL_DIALOG_CONFIRM' => 'Confirma',
    'LBL_DIALOG_DELETE_YN' => 'Segur que voleu esborrar aquest informe?',

    // for the views options
    'LBL_RESET_BUTTON' => 'Reinicia',
    'LBL_TREESTRCUTUREGRID_TITLE' => 'Jerarquia d&#39;arbre',
    'LBL_REPOSITORYGRID_TITLE' => 'Camps disponibles',
    'LBL_CANCEL_BUTTON' => 'Cancel·la',
    'LBL_CLOSE_BUTTON' => 'Tanca',
    'LBL_LISTTYPEPROPERTIES' => 'Propietats',
    'LBL_XAXIS_TITLE' => 'Camps Eix X',
    'LBL_YAXIS_TITLE' => 'Camps Eix Y',
    'LBL_VALUES_TITLE' => 'Camps Valor',
    'LBL_SUMMARIZATION_TITLE' => 'Camps de Sumarització',
    'LBL_FUNCTION' => 'Funció',
    'LBL_FUNCTION_SUM' => 'Suma',
    'LBL_FUNCTION_CUMSUM' => 'Suma acumulada',
    'LBL_FUNCTION_COUNT' => 'Comptador',
    'LBL_FUNCTION_COUNT_DISTINCT' => 'Comptador Distinct',
    'LBL_FUNCTION_AVG' => 'Mitjana',
    'LBL_FUNCTION_MIN' => 'Mínim',
    'LBL_FUNCTION_MAX' => 'Màxim',
    'LBL_FUNCTION_GROUP_CONCAT' => 'Concatena el Grup',
    //2013-03-01 Sort function for Group Concat
    'LBL_FUNCTION_GROUP_CONASC' => 'Concatena el Grup (asc)',
    'LBL_FUNCTION_GROUP_CONDSC' => 'Concatena el Grup (desc)',
    // Value Types
    'LBL_VALUETYPE_TOFSUM' => 'mostra la Suma',
    'LBL_VALUETYPE_POFSUM' => '% de la Suma',
    'LBL_VALUETYPE_POFCOUNT' => '% del Comptador',
    'LBL_VALUETYPE_POFAVG' => '% de la Mitjana',
    'LBL_VALUETYPE_DOFSUM' => 'Δ de la Suma',
    'LBL_VALUETYPE_DOFCOUNT' => 'Δ del Comptador',
    'LBL_VALUETYPE_DOFAVG' => 'Δ de la Mitjana',
    'LBL_VALUETYPE_C' => 'Acumulat',
    // panel title
    'LBL_STANDARDGRIDPANELTITLE' => 'Resultat de l&#39;informe',
    'LBL_STANDRDGRIDPANEL_FOOTERWCOUNT' => 'Mostrant els registres {0} - {1} de {2}',
    'LBL_STANDRDGRIDPANEL_FOOTERWOCOUNT' => 'Mostrant els registres {0} - {1}',
    'LBL_STANDARDGRIDPROPERTIES_COUNT' => 'Nombre de processos',
    'LBL_STANDARDGRIDPROPERTIES_SYNCHRONOUSCOUNT' => 'síncron',
    'LBL_STANDARDGRIDPROPERTIES_ASYNCHRONOUSCOUNT' => 'asíncron',
    'LBL_STANDARDGRIDPROPERTIES_NOCOUNT' => 'no comptabilitzar',
    'LBL_STANDARDGRIDENTRIES_COUNT' => 'registres per pàgina',
    // General Labels
    'LBL_YES' => 'sí',
    'LBL_NO' => 'no',
    'LBL_HID' => 'ocult',
    'LBL_SORT_ASC' => 'asc.',
    'LBL_SORT_DESC' => 'desc.',
    'LBL_SORT_SORTABLE' => 'ordenable',
    'LBL_JT_OPTIONAL' => 'opcional',
    'LBL_JT_REQUIRED' => 'requerit',
    //Trendlines
    'LBL_TRENDLINE_STARTVALUE' => 'Valor inicial',
    'LBL_TRENDLINE_ENDVALUE' => 'Valor final',
    'LBL_ADD_TRENDLINE' => 'afegeix línia de tendència',
    'LBL_DELETE_TRENDLINE' => 'esborra línia de tendència',
    'LBL_TRENDLINE_MIN' => 'Mínim',
    'LBL_TRENDLINE_MAX' => 'Màxim',
    'LBL_TRENDLINE_AVG' => 'Mitjana',
    'LBL_TRENDLINE_AMM' => 'Àrea Min/Max',
    'LBL_TRENDLINE_LRG' => 'Regressió lineal',
    'LBL_TRENDLINE_CST' => 'Personalitza',
    'LBL_STANDARDTYPE' => 'Tipus',
    'LBL_TRENDLINE_STYLE' => 'Estil',
    'LBL_TRENDLINE_VAL' => 'Valor',
    'LBL_TRENDLINE_TXT' => 'Nom',
    'LBL_TRENDLINE_NOT' => '-',
    'LBL_TRENDLINE_DISPLAY' => 'Info',
    // for report publishing
    'LBL_PUBLISH_OPTION' => 'publicar Informe',
    'LBL_PUBLISHPOPUP_TITLE' => 'Publicar Opcions d&#39;Informe',
    'LBL_PUBLISHPOPUP_SUBPANEL' => 'Subpanell',
    'LBL_PUBLISHPOPUP_DASHLET' => 'Dashlet',
    'LBL_PUBLISHPOPUP_GRID' => 'publicar quadrícula',
    'LBL_PUBLISHPOPUP_CHART' => 'publicar Informe',
    'LBL_PUBLISHPOPUP_SUBPANELORDER' => 'Ordenar Subpanell',
    'LBL_PUBLISHPOPUP_CLOSE' => 'Tancar',
    'LBL_PUBLISHPOPUP_MENU' => 'publicar com a element de Menú',
    // for Export to Planning
    'LBL_EXPORTTOPLANINGPOPUP_TITLE' => 'Exportar a Configuració de Nodes',
    // for the pdf
    'LBL_PDF_DATE_LEADIN' => ' creat a ',
    'LBL_PDF_DATE_LEADOUT' => '',
    'LBL_PDF_PAGE_LEADIN' => 'Pàgina ',
    'LBL_PDF_PAGE_SEPARATOR' => ' de ',
    // for the targetlist Export
    'LBL_TARGETLISTEXPORTPOPUP_TITLE' => 'Exporta a Llista de Públic Objectiu',
    'LBL_TARGETLISTPOUPFIELDSET_LABEL' => 'Opcions d&#39;Exportació',
    'LBL_TGLLISTPOPUP_CLOSE' => 'Tanca',
    'LBL_TGLLISTPOPUP_EXEC' => 'Executa',
    'LBL_TARGETLISTPOUP_OPTIONS' => 'Acció',
    'LBL_TGLEXP_NEW' => 'crear nou',
    'LBL_TGLEXP_UPD' => 'actualitzar existent',
    'LBL_TARGETLISTPOUPNEWFIELDSET_LABEL' => 'Nova Llista de Públic Objectiu',
    'LBL_TARGETLISTPERFSETTINGS_LABEL' => 'Configuració de rendiment',
    'LBL_TARGETLISTPERFCHECKBOX_LABEL' => 'crear directe',
    'LBL_TARGETLISTPOUP_NEWNAME' => 'Nom de Llista de Públic Objectiu',
    'LBL_TARGETLISTPOUPCHANGEFIELDSET_LABEL' => 'Actualitza la Llista de Públic Objectiu',
    'LBL_TARGETLISTPOUP_LISTS' => 'Llistes de Públic Objectiu',
    'LBL_TARGETLISTPOUP_ACTIONS' => 'Acció',
    'LBL_TGLACT_REP' => 'actualitza',
    'LBL_TGLACT_ADD' => 'afegeix',
    'LBL_TGLACT_SUB' => 'treu',
    'LBL_TARGETLISTPOUP_CAMPAIGNS' => 'afegeix a campanya',
    'LBL_LAST_DAY_OF_MONTH' => 'darrer dia del mes',
    'LBL_EXPORT_TO_PLANNER_BUTTON_LABEL' => 'Exporta a KPlanner',
    'LBL_PLANNEREXPORTPOPUP_TITLE' => 'Exporta a KPlanner',
    'LBL_EXPORTPOPUP_CLOSE' => 'Cancel·la',
    'LBL_EXPORTPOPUP_EXEC' => 'Exporta a KPlanner',
    'LBL_PLANNEREXPORTPOPUP_SCOPESETS' => 'Abast',
    'LBL_PLANNINCHARACTERISTICSGRID_TITLE' => 'Planifica les característiques',
    'LBL_CHARFIELDVALUE' => 'Valor característic',
    'LBL_CHARFIELDNAME' => 'Nom característic',
    'LBL_CHARFIXEDVALUE' => 'Valor fixat',
    'LBL_PLANNEREXPORTPOPUP_NODENAME' => 'Nom del node',
    
    // for the Visualization
    'LBL_VISUALIZATION' => 'visualitza',
    'LBL_VISUALIZATIONPLUGIN' => 'tipus',
    'LBL_VISUALIZATIONTOOLBAR_LAYOUT' => 'Aparença',
    'LBL_VISUALIZATION_HEIGHT' => 'alçada (px)',
    'LBL_GOOGLECHARTS' => 'Google Charts', 
    'LBL_CHARTFS_DATA' => 'Dades del gràfic',
    'LBL_CHARTFS_SERIES' => 'Sèries de dades', 
    'LBL_CHARTFS_VALUES' => 'Valors', 
    'LBL_DIMENSIONS' => 'Dimensions',
    'LBL_DIMENSION_111' => '1 dimensió (sèries)',
    'LBL_DIMENSION_10N' => '1 dimensió (valors)',
    'LBL_DIMENSION_220' => '2 dimensions (sense valors)',
    'LBL_DIMENSION_221' => '2 dimensions (sèries)',
    'LBL_DIMENSION_21N' => '2 dimensions (valors)',
    'LBL_DIMENSION_331' => '3 dimensions (sèries)',
    'LBL_DIMENSION_32N' => '3 dimensions (valors)',
    'LBL_CHARTTYPE_DIMENSION1' => 'Dimensió 1',
    'LBL_CHARTTYPE_DIMENSION2' => 'Dimensió 2',
    'LBL_CHARTTYPE_DIMENSION3' => 'Dimensió 3',
    'LBL_CHARTTYPE_MULTIPLIER' => 'Multiplicador',
    'LBL_CHARTTYPE_COLORS' => 'Colors',
    'LBL_CHARTTYPE_DATASERIES' => 'Sèries de dades',
    'LBL_CHARTTYPES' => 'Tipus',
    'LBL_CHARTTYPE_AREA' => 'Gràfic d&#39;àrea', 
    'LBL_CHARTTYPE_STEPPEDAREA' => 'Gràfic d&#39;àrea esglaonat', 
    'LBL_CHARTTYPE_BAR' => 'Gràfic de barres',
    'LBL_CHARTTYPE_BUBBLE' => 'Gràfic de bombolles',
    'LBL_CHARTTYPE_COLUMN' => 'Gràfic de columnes',
    'LBL_CHARTTYPE_GAUGE' => 'Indicadors',
    'LBL_CHARTTYPE_PIE' => 'Gràfic circular', 
    'LBL_CHARTTYPE_LINE' => 'Gràfic de línia', 
    'LBL_CHARTTYPE_SCATTER' => 'Gràfic de dispersió', 
    'LBL_CHARTTYPE_COMBO' => 'Gràfic combinat',
    'LBL_CHARTTYPE_CANDLESTICK' => 'Candlestick', 
    'LBL_CHARTFUNCTION' => 'Funció', 
    'LBL_MEANING' => 'Significat', 
    'LBL_COLOR' => 'Color',
    'LBL_AXIS' => 'Eix',
    'LBL_CHARTAXIS_P' => 'Primari',
    'LBL_CHARTAXIS_S' => 'Secundari',
    'LBL_RENDERER' => 'representar com', 
    'LBL_CHARTRENDER_DEFAULT' => 'Per defecte',
    'LBL_CHARTRENDER_BARS' => 'Barres',
    'LBL_CHARTRENDER_COLUMN' => 'Columna',
    'LBL_CHARTRENDER_LINE' => 'Línia',
    'LBL_CHARTRENDER_AREA' => 'Àrea',
    'LBL_CHARTRENDER_STEPPEDAREA' => 'Àrea esglaonat',
    'LBL_CHARTOPTIONS_FS' => 'Opcions de gràfic',
    'LBL_CHARTOPTIONS_TITLE' => 'Títol',
    'LBL_CHARTOPTIONS_CONTEXT' => 'Context',
    'LBL_CHARTOPTIONS_VMINMAX' => 'Eix V Min/Max', 
    'LBL_CHARTOPTIONS_HMINMAX' => 'Eix H Min/Max', 
    'LBL_CHARTOPTIONS_GREEN' => 'Verd des de/fins a',
    'LBL_CHARTOPTIONS_YELLOW' => 'Groc des de/fins a',
    'LBL_CHARTOPTIONS_RED' => 'Vermell des de/fins a',
    'LBL_CHARTOPTIONS_LEGEND' => 'mostrar Llegenda', 
    'LBL_CHARTOPTIONS_EMTPY' => 'Valors buits',
    'LBL_CHARTOPTIONS_NOVLABLES' => 'amagar etiquetes Eix V',
    'LBL_CHARTOPTIONS_NOHLABLES' => 'amagar etiquetes Eix H',
    'LBL_CHARTOPTIONS_LOGV' => 'escala logarítmica V',
    'LBL_CHARTOPTIONS_LOGH' => 'escala logarítimica H',
    'LBL_CHARTOPTIONS_3D' => '3D', 
    'LBL_CHARTOPTIONS_STACKED' => 'Sèries apilades', 
    'LBL_CHARTOPTIONS_REVERSED' => 'Sèries invertides', 
    'LBL_CHARTOPTIONS_CTFUNCTION' => 'Línia suavitzada', 
    'LBL_CHARTOPTIONS_POINTS' => 'Mostra els punts',
    
    // for Fusion Charts ... needs to be moved
    'LBL_CHARTTYPE_COLUMN2D' => 'Columna 2D',
    'LBL_CHARTTYPE_COLUMN3D' => 'Columna 3D',
    'LBL_CHARTTYPE_PIE2D' => 'Circular 2D',
    'LBL_CHARTTYPE_PIE3D' => 'Circular 3D',
    'LBL_CHARTTYPE_DOUGNUT2D' => 'Anell 2D',
    'LBL_CHARTTYPE_DOUGNUT3D' => 'Anell 3D',
    'LBL_CHARTTYPE_BAR2D' => 'Barra 2D',
    'LBL_CHARTTYPE_AREA2D' => 'Àrea 2D', 
    'LBL_CHARTTYPE_STACKEDAREA2D' => 'Àrea apilat 2D',
    'LBL_CHARTTYPE_PARETO2D' => 'Pareto 2D', 
    'LBL_CHARTTYPE_PARETO3D' => 'Pareto 3D', 
    'LBL_CHARTTYPE_STACKEDCOLUMN2D' => 'Columna apilat 2D',
    'LBL_CHARTTYPE_STACKEDCOLUMN3D' => 'Columna apilat 3D',
    'LBL_CHARTTYPE_MSCOLUMN2D' => 'Columna multisèries 2D',
    'LBL_CHARTTYPE_MSCOLUMN3D' => 'Columna multisèries 3D',
    'LBL_CHARTTYPE_MSBAR2D' => 'Barra multisèries 2D',
    'LBL_CHARTTYPE_MSBAR3D' => 'Barra multisèries 3D',
    'LBL_CHARTTYPE_STACKEDBAR2D' => 'Barra apilat 2D',
    'LBL_CHARTTYPE_STACKEDBAR3D' => 'Barra apilat 3D',
    'LBL_CHARTTYPE_MARIMEKKO' => 'Gràfic Marimekko',
    'LBL_CHARTTYPE_MSLINE' => 'Línia multisèries',
    'LBL_CHARTTYPE_MSAREA' => 'Àrea multisèries',
    'LBL_CHARTTYPE_MSCOMBIDY2D' => 'Combinació dual multisèries',
    'LBL_CHARTOPTIONS_ROUNDEDGES' => 'Vores arrodonides', 
    'LBL_CHARTOPTIONS_HIDELABELS' => 'amagar Etiquetes', 
    'LBL_CHARTOPTIONS_HIDEVALUES' => 'amagar Valors', 
    'LBL_CHARTOPTIONS_FORMATNUMBERSCALE' => 'Valors d&#39;escala',
    'LBL_CHARTOPTIONS_ROTATEVALUES' => 'Valors de rotació', 
    'LBL_CHARTOPTIONS_PLACEVALUESINSIDE' => 'situar Valors dintre',
    'LBL_CHARTOPTIONS_SHOWSHADOE' => 'mostrar Ombra',
    'LBL_CHARTOPTIONS_LPOS' => 'Llegenda',
    'LBL_LPOS_NONE' => 'cap', 
    'LBL_LPOS_RIGHT' => 'dreta', 
    'LBL_LPOS_LEFT' => 'esquerra', 
    'LBL_LPOS_BOTTOM' => 'a sota',
    'LBL_LPOS_TOP' => 'a sobre',
    
    
    'LBL_STANDARDPLUGIN' => 'Vista estàndard',
    
    
    // for the Google Maps
    'LBL_GOOGLEMAPSFS_GEOCODEBY' => 'Ubicat per',
    'LBL_GOOGLEMAPSFS_GEOCODELATLONG' => 'Lat/Long',
    'LBL_GOOGLEMAPSFS_GEOCODEADDRESS' => 'Adreça',
    'LBL_GOOGLEMAPS_LONGITUDE' => 'Longitud', 
    'LBL_GOOGLEMAPS_LATITUDE' => 'Latitud', 
    'LBL_GOOGLEMAPSFS_LATLONG' => 'Geocoordenades', 
    'LBL_GOOGLEMAPS_STREET' => 'Carrer',
    'LBL_GOOGLEMAPS_CITY' => 'Ciutat',
    'LBL_GOOGLEMAPS_PC' => 'Codi Postal',
    'LBL_GOOGLEMAPS_COUNTRY' => 'Pais',
    'LBL_GOOGLEMAPS_ADDRESS' => 'Adreça', 
    'LBL_GOOGLEMAPSFS_TITLE' => 'Info d&#39;Ubicació', 
    'LBL_GOOGLEMAPS_TITLE' => 'Títol',
    'LBL_GOOGLEMAPS_CLUSTER' => 'Ubicació de Segment',
    
    // for the Plugins
    'LBL_PRESENTATION_PLUGIN' => 'Plugin',
    'LBL_PRESENTATION_PARAMS' => 'Paràmetres de Presentació',
    'LBL_DEFAULT_GROUPBY' => 'Agrupació per defecte',
    'LBL_INTEGRATION' => 'exportar',
    'LBL_INTEGRATION_PLUGINNAME' => 'Plugin',
    'LBL_CSV_EXPORT' => 'Exportar a CSV', 
    'LBL_EXCEL_EXPORT' => 'Exportar a Excel',
    'LBL_TARGETLIST_EXPORT' => 'Exportar a Llista de Públic Objectiu', 
    'LBL_SNAPSHOT_EXPORT' => 'fer Foto',
    'LBL_QUERY_ANALIZER' => 'Anàlisi de Query',
    'LBL_SCHEDULE_REPORT' => 'planificador d&#39;Informes', 
    'LBL_PUBLISH_REPORT' => 'publicar Informe', 
    'LBL_PUBLISH_DASHLET' => 'Publicar com a Dashlet',
    'LBL_PUBLISH_DASHLETREPORT' => 'Seleccionar Informe',
    'LBL_PUBLISH_DASHLETTITLE' => 'Títol de Dashlet',
    'LBL_PUBLISH_DASHLET_PRESENTATION' => 'Presentació',
    'LBL_PUBLISH_DASHLET_PRESENTATION_VISUALIZATION' => 'Visualització', 
    'LBL_PUBLISH_SUBPANEL_SEQUENCE' => 'Seqüència',
    'LBL_PUBLISH_SUBPANEL_MODULE' => 'Mòdul',
    'LBL_PUBLISH_SUBPANEL_TAB' => 'Etiqueta',
    
    // PDF Export Option
    'LBL_PDF_EXPORT' => 'Exportar a PDF', 
    'LBL_PDF_EXPORTOPTIONS_GENERAL' => 'General', 
    'LBL_PDF_LAYOUT' => 'Aparença de PDF',
    'LBL_PDF_FORMAT' => 'Format',
    'LBL_PDFFORMAT_LTR' => 'Carta',
    'LBL_PDFFORMAT_LGL' => 'Legal',
    'LBL_PDFFORMAT_A4' => 'A4',
    'LBL_PDFFORMAT_A5' => 'A5', 
    'LBL_PDF_ORIENTATION' => 'Orientació',
    'LBL_PDF_MULTILINE' => 'Multilínia',
    'LBL_PDFORIENT_P' => 'Retrat',
    'LBL_PDFORIENT_L' => 'Paisatge', 
    'LBL_PDF_PALIGNMENT' => 'Alineació de dades', 
    'LBL_PDFPALIGNMENT_L' => 'Esquerra',
    'LBL_PDFPALIGNMENT_R' => 'Dreta',
    'LBL_PDFPALIGNMENT_C' => 'Centre',
    'LBL_PDFPALIGNMENT_S' => 'Estendre', 
    'LBL_PDF_NEWPAGEPERGROUP' => 'Nova pàgina per grup',
    'LBL_PDF_HEADERPERPAGE' => 'Capçalera a cada pàgina',
    
    // Pivot Plugin ... to be moved later
    'LBL_PIVOT_SETTINGS' => 'Opcions de taula de pivot', 
    'LBL_PIVOT_ADVANCED' => 'Opcions avançades',
    'LBL_PIVOT_REPOSITORY' => 'Camps disponibles', 
    'LBL_PIVOT_COLUMNS' => 'Columnes', 
    'LBL_PIVOT_ROWS' => 'Files',
    'LBL_PIVOT_ADDROWINFO' => 'Informació addicional de fila',
    'LBL_PIVOT_VALUES' => 'Valors', 
    'LBL_PIVOT_FUNCTiON' => 'Funció', 
    'LBL_PIVOT_TOTALS' => 'Mostra els totals', 
    'LBL_PIVOT_SUMS' => 'Mostra la suma',
    'LBL_PIVOT_ROTATEHEADERS' => 'Rota les capçaleres',
    'LBL_PIVOT_EMPTYCOLUMNS' => 'Mostra les columnes buides', 
    'LBL_PIVOT_ADJUSTCOLUMNS' => 'Ajusta l&#39;amplada de columna',
    'LBL_PIVOT_SORTCOLUMNS' => 'Ordena les columnes',
    'LBL_PIVOT_LBLPIVOTDATA' => 'Dades de Pivot',
    'LBL_PIVOT_NAMECOLUMNWIDTH' => 'Ample d&#39;element de columna', 
    'LBL_PIVOT_MINCOLUMNWIDTH' => 'Ample mínim de columna',
    
    // the field renderer
    'LBL_RENDERER_CURRENCY' => 'Moneda', 
    'LBL_RENDERER_PERCENTAGE' => 'Percentatge', 
    'LBL_RENDERER_NUMBER' => 'Nombre', 
    'LBL_RENDERER_INT' => 'Enter', 
    'LBL_RENDERER_DATE' => 'Data', 
    'LBL_RENDERER_DATETIME' => 'Data/hora',
    'LBL_RENDERER_DATETUTC' => 'Data/hora (UTC)',
    'LBL_RENDERER_FLOAT' => 'Decimal',
    'LBL_RENDERER_BOOL' => 'Booleà',
    'LBL_RENDERER_TEXT' => 'Text',
    'LBL_RENDERER_NONE' => 'Sense format', 
    
    // override Alignment
    'LBL_OVERRIDEALIGNMENT' => 'Alineació',
    'LBL_ALIGNMENT_LEFT' => 'esquerra',
    'LBL_ALIGNMENT_RIGHT' => 'dreta',
    'LBL_ALIGNMENT_CENTER' => 'centre',

    'LBL_REPORTTIMEOUT' => 'Timeout',
    'LBL_RT30' => '30 segons',
    'LBL_RT60' => '1 minut',
    'LBL_RT120' => '2 minuts',
    'LBL_RT240' => '3 minuts',
    'LBL_RT300' => '4 minuts',
    
    'LBL_KSNAPSHOTS' => 'Instantànies',
    'LBL_KSNAPSHOT' => 'Instantània', 
    'LBL_TAKING_SNAPSHOT' => 'Creant una instantània...',

    // STIC-Custom 20230710 AAM - Improving export to LPO
    // STIC#1010
    'LBL_TARGETLISTEXPORT_SELECTION' => 'Seleccioneu una llista de públic objectiu a la finestra emergent',
    'LBL_TARGETLISTEXPORT_LOADING' => "Espereu mentre s'exporten les dades a la llista de públic objectiu...",
    'LBL_TARGETLISTEXPORT_ALERT_TITLE' => "Seleccioneu un mode d'exportació:",
    'LBL_TARGETLISTEXPORT_ALERT_DESCRIPTION' => "1) Acumulatiu: Es mantindrà el contingut original de la llista i s'hi afegiran els registres de l'informe que actualment no hi són.<br><br>2) Substitutiu: Es buidarà la llista existent i s'omplirà amb els registres de l'informe.<br><br>Nota: l'elecció del mode d'exportació només genera efectes en cas que el destí sigui una llista pre-existent. Si al pas següent escolliu crear una llista nova, el resultat serà el mateix independentment del mètode triat.",
    'LBL_TARGETLISTEXPORT_ALERT_CUMULATIVE' => 'Acumulatiu',
    'LBL_TARGETLISTEXPORT_ALERT_REPLACEMENT' => 'Substitutiu',
    'LBL_TARGETLISTEXPORT_ALERT_CANCEL' => 'Cancel·la',
    // END STIC-Custom
);
