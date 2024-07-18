<?php
/**
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
 */

class ExternalReporting
{
    // Default language, it will be used if the instance language cannot be obtained from the settings.
    private $lang = 'es';

    // Variable to store debug messages
    private $info = '';

    // It is important during the development phase to not use previously used versions in order to avoid breaking existing reports.
    private $versionPrefix = 'sda';

    // Fields that we always exclude in our operations
    private $evenExcludedFields = ['template_ddown_c', 'currency_name', 'assigned_user_id', 'parent_name', 'deleted', 'created_by', 'created_by_name', 'created_by_link', 'modified_user_link', 'modified_by_name', 'jjwg_maps_address_c', 'jjwg_maps_geocode_status_c', 'jjwg_maps_lat_c', 'jjwg_maps_lng_c'];

    // Modules that we always included, although they are hidden in the CRM
    private $evenIncludedModules = [];

    // Modules that we always exclude
    private $evenExcludedModules = [
        'Administration',
        'AM_ProjectTemplates',
        'AOBH_BusinessHours',
        'AOK_Knowledge_Base_Categories',
        'AOK_KnowledgeBase',
        'AOR_Reports',
        'AOR_Scheduled_Reports',
        'AOS_PDF_Templates',
        'AOW_WorkFlow',
        'Bugs',
        'Calendar',
        'DHA_PlantillasDocumentos',
        'Documents', // Excluida por STIC#578
        'EmailTemplates',
        'FP_events',
        'Home',
        'jjwg_Address_Cache',
        'jjwg_Areas',
        'jjwg_Maps',
        'jjwg_Markers',
        'KReports',
        'ProspectLists',
        'Prospects',
        'ResourceCalendar',
        'SecurityGroups',
        'Spots',
        'stic_Bookings_Calendar',
        'stic_Sepe_Files',
        'stic_Settings',
        'stic_Validation_Actions',
        'stic_Web_Forms',
        'stic_Incorpora_Locations',
        'stic_Validation_Results',
        'stic_Custom_Views',
    ];

    public function __construct()
    {
        global $sugar_config;

        $this->hostName = $sugar_config['host_name'];
        $this->baseHostname = explode('.', $this->hostName)[0];

        // Retrieve the settings related to SinergiaDA
        require_once 'modules/stic_Settings/Utils.php';
        $this->sdaSettings = stic_SettingsUtils::getSettingsByType('SINERGIADA');
        $this->sdaSettings['publishAsTable'] = $sugar_config['stic_sinergiada']['publish_as_table'] ?? [];

        //  Check if certain parameters are present in the request and set the corresponding
        // instance variables accordingly.
        if (!isset($_REQUEST['do']) || empty($_REQUEST['do'])) {
            // If the 'do' parameter is not present or is empty, set the instance variables to true.
            $this->doCreateViews = true;
            $this->doCreateMetadata = true;
            $this->doCreateSecurity = true;
        } else {
            // If the 'do' parameter is present and not empty, set the instance variables based on the presence of certain substrings.
            $do = $_REQUEST['do'];
            $this->doCreateViews = strpos($do, 'createViews') !== false ? true : false;
            $this->doCreateMetadata = strpos($do, 'createMetadata') !== false ? true : false;
            $this->doCreateSecurity = strpos($do, 'createSecurity') !== false ? true : false;
        }

        // If a specific language is not provided, the language defined for the instance will be used.
        if (!empty($_REQUEST['lang'])) {
            $this->lang = $_REQUEST['lang'];
            switch ($_REQUEST['lang']) {
                case 'ca':
                    $this->langCode = 'ca_ES';
                    break;
                case 'en':
                    $this->langCode = 'en_US';
                    break;
                case 'gl':
                    $this->langCode = 'gl_ES';
                    break;
                default:
                    $this->langCode = 'es_ES';
                    break;
            }
        } else {
            $this->langCode = $sugar_config['default_language'];
            $this->lang = explode('_', $this->langCode)[0];
        }

        $this->viewPrefix = "{$this->versionPrefix}";
        $this->listViewPrefix = "{$this->viewPrefix}_l";
    }
    /**
     * Main function responsible for creating and managing MariaDB views and tables (as specified in stic_Settings) based on CRM modules.
     *
     * Here's a breakdown of its operations:
     * 1) For each enabled CRM module (excluding specified ones), it creates a MariaDB view or table. These are derived from the module's primary table and, if present, the _cstm table, excluding records marked as deleted.
     * 2) For every 1:n or n:1 relationship where the current module represents the "n" side, a column is added to the respective view or table.
     * 3) Generates essential exchange tables and views (prefixed with "sda_def_") to facilitate EDA's data interpretation and management.
     *
     * @param Boolean $callUpdateModel Indicates whether to call updateModel (true) or not (false).
     * @param String $rebuildFilter Specifies the rebuild scope: 'views' for views only, 'tables' for tables only, and 'all' or any other value for both tables and views.
     * @return String Provides debug information in HTML format, displayed only when $_REQUEST['print_debug'] equals 1.
     */

    public function createViews($callUpdateModel = true, $rebuildFilter = 'all')
    {

        $startTime = microtime(true);
        $GLOBALS['log']->stic('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "SinergiaDA rebuild script starts!");

        global $app_list_strings;

        $archivo = __FILE__; // Ruta del archivo actual
        $fechaModificacion = filemtime($archivo);
        $fechaFormateada = date('Y-m-d H:i:s', $fechaModificacion);

        $this->info = "<strong>La fecha de modificación del archivo es: " . $fechaFormateada . "</strong><br>";

        $this->info .= '<link rel="stylesheet" type="text/css" href="cache/themes/SuiteP/css/Stic/style.css" />';

        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Running Createviews() function');

        $db = DBManagerFactory::getInstance();

        // Before create any view, delete previous old views
        $this->deleteOldViews();

        $this->info .= "<div><a href='index.php?module=Administration&action=createReportingMySQLViews&print_debug=1'>All modules</a> </div>";

        // Reset general metadata tables
        $this->resetMetadataTables();

        // Reset user metadata views
        $this->resetMetadataViews();

        $this->info .= "<h2>Creating MySQL/MariaDB views & tables</h2>";

        // For test & debug mode
        $onlyIncludeModule = isset($_REQUEST['only']) ? $_REQUEST['only'] : '';

        // Get module list in current lang
        $langAppListStrings = return_app_list_strings_language($this->langCode);

        // Filter only allowed modules in CRM
        include_once 'modules/MySettings/TabController.php';
        $controller = new TabController();
        $visibleModules = $controller->get_system_tabs();

        $modulesList = array_diff($visibleModules, $this->evenExcludedModules);

        // The modules that should always be included are added
        $modulesList = array_merge($modulesList, $this->evenIncludedModules);

        $modulesList = array_unique($modulesList);

        // We force add the users module, since, otherwise, it will never appear on the list of visible modules
        $modulesList['Users'] = 'Users';

        // If the Project module is enabled, we directly include the project tasks module,
        // since otherwise it would never be included, because it is not included directly in the application menu
        if (in_array('Project', $modulesList)) {
            $modulesList['ProjectTask'] = 'ProjectTask';
        }

        // If Surveys module is enabled, we automatically activate the related Surveys modules
        if (in_array('Surveys', $modulesList)) {
            $modulesList['SurveyResponses'] = 'SurveyResponses';
            $modulesList['SurveyQuestions'] = 'SurveyQuestions';
            $modulesList['SurveyQuestionOptions'] = 'SurveyQuestionOptions';
            $modulesList['SurveyQuestionResponses'] = 'SurveyQuestionResponses';
        }

        natsort($modulesList);

        // Get & populate users ACL metadata (must run after $modulesList is created)
        $this->getAndSaveUserACL($modulesList);
        foreach ($modulesList as $moduleName) {
            // Reset module index list
            unset($indexesToCreate);
            $moduleStart = microtime(true);
            // If $onlyIncludeModule is set, only this module will be processed and others will be ignored for debugging purposes).
            if ($moduleName != $onlyIncludeModule && !empty($onlyIncludeModule)) {
                continue;
            };

            // The module is processed or omitted, depending on the received parameter
            switch ($rebuildFilter) {
                case 'tables':
                    if (!in_array($moduleName, $this->sdaSettings['publishAsTable'])
                        && $this->sdaSettings['publishAsTable'][0] != '0'
                    ) {
                        $this->info .= "<li>{$moduleName} (Omitted)</li>";
                        continue 2;
                    }
                    break;
                case 'views':
                    if (in_array($moduleName, $this->sdaSettings['publishAsTable'])
                        || $this->sdaSettings['publishAsTable'][0] == '1'
                    ) {
                        $this->info .= "<li>{$moduleName} (Omitted)</li>";
                        continue 2;
                    }
                    break;
                default:

                    break;
            }

            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Processing module {$moduleName}");

            // Get only allowed fields in detail view
            $detailViewVisibleFields = $this->detailViewVisibleFields($moduleName);

            // Create array to save lists used in module
            $listNames = array();

            // Fix when table name & module name are not equal.
            switch ($moduleName) {
                case 'CampaignLog':
                    $tableName = 'campaign_log';
                    break;
                case 'ProjectTask':
                    $tableName = 'project_task';
                    break;

                default:
                    $tableName = strtolower($moduleName);
                    break;
            }

            if (!$this->checkSdaTables($tableName)) {
                continue;
            }

            // Get translated module name
            $txModuleName = $langAppListStrings['moduleList'][$moduleName];
            $this->info .= "<li module='{$moduleName}'><a href='#'>	{$txModuleName} ({$moduleName})</a></li><div id='{$moduleName}' style='display:none;'>";

            // Sanitize text
            $viewName = $this->sanitizeText("{$this->viewPrefix}_{$tableName}");

            // Get module bean for use later
            $moduleBean = BeanFactory::getBean($moduleName);

            // recover module labels
            $modStrings = return_module_language($this->langCode, $moduleName);

            // Initialize a variable to concatenate the different Left Join corresponding to Relationships fields
            $leftJoins = '';

            $fieldList = [];

            // We save here the aliases we are using to avoid duplicates when there is more than one relationship with the same module
            $usedAlias = [];
            // Process the content of the field according to the type
            foreach ($moduleBean->getFieldDefinitions() as $fieldK => $fieldV) {
                // We reset certain variables to avoid errors
                unset($fieldSrc, $relatedModuleName, $secureName, $edaAggregations, $sdaHiddenField, $excludeColumnFromMetadada);

                // To avoid exceptional cases where the table name is defined in uppercase
                // (like in the relationship between Contacts and Cases) we convert the table name to lowercase
                $fieldV['table'] = strtolower($fieldV['table']);

                $fieldName = $fieldV['name'];

                $fieldPrefix = $fieldV['source'] == 'custom_fields' ? 'c' : 'm';

                // If the field is excluded, skip it
                if (in_array($fieldV['name'], $this->evenExcludedFields)) {
                    continue;
                }

                // Conditionally controls the visibility of fields in the detail view:
                // * Shows fields present in the detail view.
                // * Always shows the "full_name" & "id" field, regardless of its presence in the detail view.
                if (in_array($fieldV['name'], $detailViewVisibleFields) || in_array($fieldV['name'], ['id', 'full_name'])) {
                    $sdaHiddenField = false;
                } else {
                    $sdaHiddenField = true;
                }

                $fieldV['label'] = $this->sanitizeText($modStrings[$fieldV['vname']]);

                // Attempts to assign a translated label to the field.
                // If no translation is found, it tries to translate it directly.
                // The field is skipped if no translation is obtained.
                if (empty($fieldV['label']) && $fieldV['name'] != 'id') {
                    $directTranslate = translate($fieldV['vname'], $fieldV['module']);
                    if (!empty($directTranslate)) {
                        $fieldV['label'] = $this->sanitizeText($directTranslate);
                    } else {
                        continue;
                    }
                }

                // There are some exceptions that must be applied in specific modules that have not been seen how to solve otherwise
                switch ($moduleName) {
                    case 'Cases':
                        // exclude this field because there is another with same name
                        if ($fieldV['id_name'] == 'account_id') {
                            continue 2;
                        }
                        break;
                    default:
                        break;
                }

                // Let's establish specific actions according to the field type
                switch ($fieldV['type']) {
                    case 'id':
                        if ($fieldV['name'] == 'id') {
                            // It is the normal ID field of a module
                            $fieldV['alias'] = 'id';
                            $fieldV['label'] = "ID $txModuleName";

                            $fieldSrc = "m.{$fieldV['name']} AS id";

                            $indexesToCreate[] = 'id';

                        } else {
                            // It is a related field of the ID type and we will not process it
                            continue 2;
                        }
                        break;
                    case 'function':
                    case 'assigned_user_name':
                    case 'file':
                    case 'parent_type':
                    case 'parent_name':
                    case 'parent':
                    case 'kreporter':
                    case 'image':
                    case 'link':
                    case 'mailchimprating':
                    case 'Signature':
                    case 'Esignature':
                    case 'wysiwyg':
                    case 'time':
                    case 'iframe':
                    case 'currency_id':
                    case 'emailbody':
                    case 'none':
                        continue 2;
                        break;
                    case 'relate':
                        if (
                            (in_array($fieldV['module'], $this->evenExcludedModules) && $fieldV['name'] != 'assigned_user_name')

                        ) {
                            continue 2;
                        } else {

                            $relatedModuleName = "{$app_list_strings['moduleList'][$fieldV['module']]}";

                            // The relationship between contacts and accounts does not have the 'link' property,
                            // which is necessary for retrieval of the relationship values, so we add it directly.
                            if ($fieldName == 'account_id' && $moduleName == 'Contacts') {
                                $fieldV['link'] = 'accounts_contacts';
                            }

                            if (isset($fieldV['link']) && !empty($fieldV['link']) && $fieldV['name'] != 'assigned_user_name') {

                                // Build and obtain the translated value from the other side of the relationship so it can be properly displayed in SinergiaDA
                                $joinModuleRelLabel = 'LBL_' . strtoupper($fieldV['link']) . '_FROM_' . strtoupper($moduleName) . '_TITLE';
                                $joinLabel = translate($joinModuleRelLabel, $fieldV['module']);
                                $joinLabel = empty($joinLabel) || $joinLabel == $joinModuleRelLabel ? $txModuleName : $joinLabel;

                                $res = $this->createRelateLeftJoin($fieldV, $tableName, $joinLabel);

                                if (empty($res)) {
                                    continue 2;
                                }

                                $fieldSrc = " IFNULL({$res['field']},'') ";
                                $leftJoins .= "\n\t{$res['leftJoin']} ";

                                $fieldV['alias'] = substr($fieldV['id_name'], 0, 64);

                                if (in_array($fieldV['alias'], $usedAlias)) {
                                    $relName = $fieldV['link'];
                                    $fieldV['alias'] = $this->sanitizeText("{$fieldV['alias']}_{$relName}");
                                }
                                $usedAlias[] = $fieldV['alias'];

                                // add column to index list
                                $indexesToCreate[] = $fieldV['alias'];

                                if (!empty($fieldSrc)) {
                                    $fieldList['related'][$fieldK] = $fieldSrc . " AS {$fieldV['alias']}";
                                } else {
                                    $fieldList['failedRelations'][$fieldK] = $fieldSrc . " AS {$fieldV['alias']}";
                                }

                            } else {
                                /**
                                 * Management of 'relate' type fields:
                                 *
                                 * 1) Creation of a column with the ID of the record in the related module (e.g., contacts_id_c).
                                 *    This column establishes a complete relationship with the other module, allowing the use
                                 *    of any column from the related module. This column is not created in case the relationship
                                 *    is with another record of the same module (autorelationship).
                                 *
                                 * 2) Creation of a column with the name of the related record, or 'first_name' + 'last_name'
                                 *    if it's a 'Person' type module.
                                 *    This column allows for easy use of the related record's name, without the
                                 *    need for establishing additional relationships.
                                 */

                                $secureName = preg_replace('([^A-Za-z0-9])', '_', $app_list_strings['moduleList'][$fieldV['module']]) . ' (' . $fieldV['label'] . ')';

                                // It is necessary to detect whether or not the field is custom, and in this case, we obtain it looking at the property of the field containing the ID of the Relationship Table ("...id_c")
                                $fieldV['source'] = $moduleBean->getFieldDefinitions()[$fieldV['id_name']]['source'] ?: $fieldV['source'];

                                $fieldPrefix = $fieldV['source'] == 'custom_fields' ? 'c' : 'm';

                                $fieldV['alias'] = $fieldV['id_name'];

                                // In the case of the Users module, the 'module' value may appear empty, if so, we force it.
                                $fieldV['module'] = empty($fieldV['module']) && $fieldV['table'] == 'users' ? 'Users' : $fieldV['module'];
                                $relatedTableName = strtolower($fieldV['module']);
                                $leftJoinAlias = "{$fieldV['name']}_{$relatedTableName}";

                                // Add id field
                                $fieldSrc = " IFNULL({$fieldPrefix}.{$fieldV['id_name']},'') AS {$fieldV['alias']}, ";

                                // add column to index list
                                $indexesToCreate[] = "{$fieldV['id_name']}";

                                //Add relate record name 
                                if (in_array($fieldV['module'], ['Contacts', 'Leads'])) {
                                    $relatedName = " concat_ws(' ', {$leftJoinAlias}.first_name, {$leftJoinAlias}.last_name) ";
                                } elseif ($fieldV['module'] == 'Users') {
                                    $relatedName = "{$leftJoinAlias}.user_name";
                                } else { $relatedName = "{$leftJoinAlias}.name";}

                                $fieldSrc .= " IFNULL($relatedName,'') AS {$fieldV['name']}";

                                $leftJoins .= " \n\tLEFT JOIN {$relatedTableName} AS {$leftJoinAlias} ON {$leftJoinAlias}.id = {$fieldPrefix}.{$fieldV['id_name']} AND {$leftJoinAlias}.deleted=0";

                                // Related fields are treated as normal fields. Since the Source property is indicated Non-DB,
                                // If is a base fields, we directly include them into "base" array to prevent subsequently excluding.
                                if ($fieldPrefix == 'm') {
                                    $fieldList['base'][$fieldK] = $fieldSrc;
                                }

                                $fieldV['targetModule'] = strtolower($fieldV['module']);

                                // Añadimos directamente los metadatos en sda_def_columns para la columna que contiene el nombre del regitro relacionado
                                // al mismo tiempo nos aseguramos de que no se añade a sda_def_columns la columna que contiene el id
                                $excludeColumnFromMetadada = true;
                                $this->addMetadataRecord(
                                    'sda_def_columns',
                                    [
                                        'table' => "{$this->viewPrefix}_{$tableName}",
                                        'column' => $fieldV['name'],
                                        'type' => 'text',
                                        'aggregations' => 'count,count_distinct,none',
                                        'label' => $fieldV['label'],
                                        'description' => addslashes($fieldV['label']),
                                        'sda_hidden' => 0,
                                        'stic_type' => $fieldV['type'] . '-name',
                                    ]
                                );

                                // Add metadata record for relate fields
                                $this->addMetadataRecord(
                                    'sda_def_relationships',
                                    [
                                        'id' => "{$tableName}_{$fieldV['name']}",
                                        'source_table' => "{$this->viewPrefix}_{$tableName}",
                                        'source_column' => $fieldV['id_name'],
                                        'target_table' => "{$this->viewPrefix}_{$fieldV['targetModule']}",
                                        'target_column' => 'id',
                                        'info' => 'relate',
                                        'label' => "{$fieldV['label']}|{$txModuleName}",
                                    ]
                                );
                            }
                        }
                        break;

                    case 'multienum':

                        // Create listViewName for use in metadata & view creation
                        $listViewName = substr(join('_', [$tableName, $fieldV['name'], $fieldV['options']]), 0, 58);

                        $createdListView = $this->createEnumView($fieldV['options'], $listViewName);

                        // If there is a valid drop-down list we continue, otherwise we move on to the next column
                        if (!empty($createdListView)) {
                            $listNames[] = $createdListView;
                        } else {
                            continue 2;
                        }

                        $fieldV['alias'] = $fieldV['name'];
                        $fieldV['bridgeTableName'] = mb_strcut("{$this->viewPrefix}_{$tableName}__{$fieldV['name']}", 0, 64);
                        $fieldSrc = "IFNULL({$fieldPrefix}.{$fieldV['name']},'') AS {$fieldName}";

                        $this->addMetadataRecord(
                            'sda_def_enumerations',
                            [
                                'source_table' => "{$this->viewPrefix}_{$tableName}",
                                'source_column' => $fieldV['name'],
                                'master_table' => "{$this->listViewPrefix}_{$listViewName}",
                                'bridge_table' => $fieldV['bridgeTableName'],
                                'source_bridge' => 'id',
                                'target_bridge' => 'code',
                                'info' => 'enum_list',
                                'stic_type' => $fieldV['type'],
                            ]
                        );
                        break;
                    case 'enum':
                    case 'dynamicenum':
                    case 'bool':
                    case 'radioenum':

                        $fieldV['alias'] = $fieldV['name'];

                        $listName = $fieldV['type'] == 'bool' ? 'stic_boolean_list' : $fieldV['options'];

                        // Create listViewName for use in metadata & view creation
                        $listViewName = substr(join('_', [$tableName, $fieldV['name'], $listName]), 0, 58);

                        $fieldSrc = " IFNULL(CAST({$fieldPrefix}.{$fieldV['name']} AS CHAR),'') AS {$fieldName}";

                        $createdListView = $this->createEnumView($listName, $listViewName);

                        // If there is a valid drop-down list or if it corresponds to that of a boolean field
                        // we continue, otherwise we move on to the next column
                        if (!empty($createdListView) || $listName == 'stic_boolean_list') {
                            $listNames[] = $createdListView;
                        } else {
                            continue 2;
                        }

                        $this->addMetadataRecord(
                            'sda_def_enumerations',
                            [
                                'source_table' => "{$this->viewPrefix}_{$tableName}",
                                'source_column' => $fieldV['name'],
                                'master_table' => "{$this->listViewPrefix}_{$listViewName}",
                                'info' => 'enum_list',
                                'stic_type' => $fieldV['type'],
                            ]
                        );
                        break;
                    case 'datetime':
                    case 'datetimecombo':
                        $fieldV['alias'] = $fieldV['name'];
                        $tzDateValue = "CONVERT_TZ({$fieldPrefix}.{$fieldV['name']}, 'UTC', 'Europe/Madrid')";
                        $fieldSrc = "IFNULL({$tzDateValue},'') AS {$fieldName}";
                        break;

                    case 'date':
                    case 'varchar':
                    case 'phone':
                    case 'text':
                    case 'readonly':
                    case 'fullname':
                    case 'name':
                    case 'url':
                    case 'decimal':
                    case 'int':
                    case 'html':
                    case 'currency':
                    case 'float':
                    case 'user_name':
                    case 'ColorPicker':
                    case 'email':
                        $fieldV['alias'] = $fieldV['name'];
                        if ($fieldV['name'] == 'email1' && $fieldV['type'] == 'varchar' && $fieldV['source'] == 'non-db') {
                            // Special field for main email
                            $fieldSrc = "IFNULL(ea.email_address,'') AS {$fieldV['name']}";

                            // add left join for email field
                            $leftJoins .= " LEFT JOIN email_addr_bean_rel eabr ON m.id = eabr.bean_id AND eabr.bean_module = '{$moduleName}' AND eabr.deleted = 0 AND eabr.primary_address = 1 LEFT JOIN email_addresses ea ON eabr.email_address_id = ea.id AND ea.deleted = 0 ";
                        } elseif ($fieldV['name'] == 'full_name') {
                            // special query for full_name
                            $fieldSrc = "IFNULL(CONCAT_WS(' ',m.first_name, m.last_name),'') as {$fieldV['name']}";
                        } else {
                            $fieldSrc = "IFNULL({$fieldPrefix}.{$fieldV['name']},'') AS {$fieldName}";
                        }
                        break;

                    default:
                        $this->info .= "<div class='error' style='color:red;'>ERROR: [FATAL: Unprocessed field type. {$fieldV['type']} | Módule: {$moduleName} - Field: {$fieldV['name']}] </div>";
                        $this->info .= "[FATAL: Unprocessed field type. {$fieldV['type']} | Módule: {$moduleName} - Field: {$fieldV['name']}]";
                        $this->info .= print_r($fieldV, true);

                        break;
                }

                // Map to appropriate eda type
                switch ($fieldV['type']) {
                    case 'decimal':
                    case 'int':
                    case 'currency':
                    case 'float':
                        $edaType = 'numeric';
                        $edaPrecision = $fieldV['type'] == 'currency' ? 2 : 0;
                        $edaPrecision = $fieldV['precision'] ? $fieldV['precision'] : $edaPrecision;
                        break;
                    case 'date':
                    case 'datetime':
                    case 'datetimecombo':
                        $edaType = 'date';
                        break;
                    case 'enum':
                    case 'multienum':
                    case 'dynamicenum':
                    case 'bool':
                    case 'radioenum':
                        $edaType = 'enumeration';
                        break;
                    default:
                        $edaType = 'text';
                        break;
                }

                //  set aggregation according EDA field type if it has not been previously defined
                if (empty($edaAggregations)) {
                    switch ($edaType) {
                        case 'numeric':
                            $edaAggregations = 'sum,avg,max,min,count,count_distinct,none';
                            break;
                        case 'text':
                        case 'enumeration':
                        case 'date':
                            $edaAggregations = 'count,count_distinct,none';
                            break;

                        default:
                            $edaAggregations = 'none';
                            break;
                    }
                }

                // We add the values calculated to Array $fieldv to use them later in other methods
                $fieldV['edaType'] = $edaType;

                if (isset($fieldSrc)) {
                    // Add to the array of normal base and custom fields
                    if ($fieldV['source'] == 'custom_fields') {
                        $fieldList['custom'][$fieldK] = $fieldSrc;
                        $addColumnToMetadata = 1;
                    } else if ($fieldV['source'] == 'non-db' && $fieldV['name'] != 'full_name' && $fieldV['name'] != 'email1') {
                        // This source is not processed, so we are moving them away
                        $fieldList['non-db'][$fieldK] = $fieldSrc;
                        $addColumnToMetadata = 0;
                    } else {
                        if ($fieldV['type'] != 'relate') {
                            $fieldList['base'][$fieldK] = $fieldSrc;
                            $addColumnToMetadata = 1;
                        }
                    }

                    if ($excludeColumnFromMetadada == true) {
                        $addColumnToMetadata = 0;
                    }
                    // Only the columns that are really going to be added to the views are added to the SDA_def_Columns table
                    if ($addColumnToMetadata == 1) {
                        // Add field metadata
                        $this->addMetadataRecord(
                            'sda_def_columns',
                            [
                                'table' => "{$this->viewPrefix}_{$tableName}",
                                'column' => $fieldV['alias'],
                                'type' => $edaType,
                                'decimals' => $edaPrecision,
                                'aggregations' => empty($edaAggregations) ? 'none' : $edaAggregations,
                                'label' => html_entity_decode($fieldV['label'], ENT_QUOTES),
                                'description' => addslashes($fieldV['label']),
                                'sda_hidden' => $sdaHiddenField ?: 0,
                                'stic_type' => $fieldV['type'],
                            ]
                        );
                    }
                }

                unset($edaPrecision);
            }

            // VIRTUAL FIELDS
            // Include existing files in modules and custom/modules/ to create virtual fields in SinergiaCRM
            $sourceFiles = [
                "modules/$moduleName/SDAVardefs.php",
                "custom/modules/$moduleName/Ext/SDAVardefs/SDAVardefs.ext.php",
            ];

            foreach ($sourceFiles as $file) {
                if (file_exists($file)) {
                    require_once $file;

                    $fieldsToProcess = [];

                    if (basename($file) === 'SDAVardefs.ext.php') {
                        // For custom files, use $dictionary[$moduleName]['SDAVirtualFields']
                        $fieldsToProcess = $dictionary[$moduleName]['SDAVirtualFields'] ?? [];
                    } else {
                        // For standard files, use $SDAVirtualFields
                        $fieldsToProcess = $SDAVirtualFields ?? [];
                    }

                    if (!empty($fieldsToProcess) && is_array($fieldsToProcess)) {
                        foreach ($fieldsToProcess as $fieldName => $fieldData) {
                            // Get the translated label or use the original if not available
                            $virtualFieldLabel = $modStrings[$fieldData['label']] ?? $fieldData['label'];

                            // Check if the virtual field label is empty
                            if (empty($virtualFieldLabel)) {
                                $this->info .= "<div style='color:red;'>VIRTUAL FIELD ERROR: <b>[{$file}]</b> - The virtual field was not processed because there is no translation available for {$this->langCode}</div>";
                                $this->info .= "[FATAL: Virtual Field without label $viewName - $file]";
                                continue;
                            }

                            // Get the translated description or use the original if not available
                            $virtualFieldDescription = $modStrings[$fieldData['description']] ?? $fieldData['description'];

                            // Add the virtual field to the fieldList array
                            $fieldList['virtual'][$fieldName] = " {$fieldData['expression']} AS '{$fieldName}'";

                            // Add metadata record for the virtual field
                            $this->addMetadataRecord(
                                'sda_def_columns',
                                [
                                    'table' => "{$this->viewPrefix}_{$tableName}",
                                    'column' => $fieldName,
                                    'type' => $fieldData['type'],
                                    'decimals' => $fieldData['precision'] ?? 0,
                                    'aggregations' => $fieldData['aggregations'] ?? 'none',
                                    'label' => html_entity_decode($virtualFieldLabel, ENT_QUOTES),
                                    'description' => addslashes($virtualFieldDescription),
                                    'sda_hidden' => $fieldData['hidden'] ?? 0,
                                    'stic_type' => 'virtual',
                                ]
                            );
                        }
                    } else {
                        $this->info .= "<div style='color:orange;'>WARNING: The file {$file} does not contain a valid array of virtual fields.</div>";
                    }

                    // Clear the variables after processing to avoid conflicts with the next file
                    unset($SDAVirtualFields, $dictionary);
                }
            }
            // END VIRTUAL FIELDS

            // Add module metadata
            $this->addMetadataRecord(
                'sda_def_tables',
                [
                    'table' => "{$this->viewPrefix}_{$tableName}",
                    'label' => $txModuleName,
                    'description' => addslashes($txModuleName),
                ]
            );

            // Sql Header. Depending on the value of the SDA_MODE_MODE setting we create tables or views mysql
            if (
                in_array($moduleName, $this->sdaSettings['publishAsTable'])
                || $this->sdaSettings['publishAsTable'][0] == '1'
            ) {
                $tableMode = 'table';
                $createViewQueryHeader = " CREATE OR REPLACE TABLE {$viewName} ENGINE=MyISAM AS SELECT ";
            } else {
                $tableMode = 'view';
                $createViewQueryHeader = " CREATE OR REPLACE VIEW {$viewName} AS SELECT ";
            }

            // Add base table fields
            $createViewQueryFields = ''; // important
            foreach ($fieldList['base'] as $bKey => $bValue) {
                $createViewQueryFields .= " {$bValue}, ";
            }

            // Add custom table fields
            if (!empty($fieldList['custom'])) {
                foreach ($fieldList['custom'] as $cKey => $cValue) {
                    $createViewQueryFields .= " {$cValue}, ";
                }
            }

            // Add virtual fields
            if (!empty($fieldList['virtual'])) {
                foreach ($fieldList['virtual'] as $vKey => $vValue) {
                    $createViewQueryFields .= " {$vValue}, ";
                }
            }

            // Special fields to allow count records
            $createViewQueryFields .= " 1 as N, "; // N field for counters

            // Add N column to metadata
            $this->addMetadataRecord(
                'sda_def_columns',
                [
                    'table' => "{$this->viewPrefix}_{$tableName}",
                    'column' => 'N',
                    'type' => 'numeric',
                    'aggregations' => 'sum,avg,max,min,count,count_distinct,none',
                    'decimals' => 0,
                    'label' => "(n) {$txModuleName}",
                    'description' => 'Special field allow count',
                    'stic_type' => 'none',
                ]
            );

            // Relations
            if (!empty($fieldList['related'])) {
                foreach ($fieldList['related'] as $cValue) {
                    $createViewQueryFields .= " {$cValue}, ";
                }
            }
            $createViewQueryFields = rtrim($createViewQueryFields, ', ');

            // Create FROM
            unset($createViewQueryFrom);
            if (isset($fieldList['custom'])) {
                $createViewQueryFrom .= " FROM  {$tableName} m LEFT JOIN {$tableName}_cstm c ON m.id=c.id_c ";
            } else {
                $createViewQueryFrom .= " FROM  {$tableName} AS m ";
            }

            // Create left joins
            $createViewQueryLeftJoins = " {$leftJoins} ";

            // Create WHERE
            $createViewQueryWhere = " WHERE m.deleted = 0 ";

            // We create the SQL instruction with the pieces created above
            $createViewQuery = "{$createViewQueryHeader}  {$createViewQueryFields} {$createViewQueryFrom} {$createViewQueryLeftJoins} {$createViewQueryWhere}";

            if (!$db->query($createViewQuery)) {
                $lastSQLError = array_pop(explode(':', $db->last_error));

                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Error has occurred: [{$lastSQLError}] running Query: [{$createViewQuery}]");

                $this->info .= "<div class='error' style='color:red;'>ERROR: <textarea style='width:100%;height:300px;border:1px solid red;'> {$createViewQuery} </textarea>({$lastSQLError})</div>";
                $this->info .= "[FATAL: Unable to create view $viewName]";

            } else {
                $this->info .= '<div style="color:green;">OK: <textarea style="width:100%;height:300px;border:1px solid green;">' . $createViewQuery . '</textarea>  </div>';
                $this->info .= '<div style="font-size:80%"><b>Listas creadas:</b> ' . join(' | ', array_unique($listNames)) . '</div>';
            };

            // If we are in table mode, we must add the corresponding indices to the table
            // if ($this->sdaSettings['SDA_TABLE_MODE'] == '1') {
            //     foreach ($indexesToCreate as $indexToCreate) {
            //         $createIndexQuery = "ALTER TABLE {$viewName} ADD INDEX ($indexToCreate);";
            //         if (!$db->query($createIndexQuery)) {
            //             $lastSQLError = array_pop(explode(':', $db->last_error));
            //             $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Error has occurred: [{$lastSQLError}] running Query: [{$createIndexQuery}]");
            //             $this->info .= "<div class='error' style='color:red;'>ERROR: <textarea style='width:100%;height:300px;border:1px solid red;'> {$createIndexQuery} </textarea>({$lastSQLError})</div>";
            //             $this->info .= "[FATAL: Unable to create index $indexToCreate]";
            //         } else {
            //             $this->info .= '<div style="font-size:80%"><b>Índice creado OK:</b> ' . $indexToCreate . '</div>';
            //         }

            //     }
            // }

            $this->info .= "<h2>Base fields</h2>";
            $this->info .= print_r($fieldList['base'], true);
            $this->info .= "<h2>Custom fields</h2>";
            $this->info .= print_r($fieldList['custom'], true);
            $this->info .= "<h2>Virtual Fields</h2>";
            $this->info .= print_r($fieldList['virtual'], true);

            $this->info .= "</div>";
            $isTable = $tableMode == 'table' ? ' <b style=color:orange>[Table]</b> ' : ' <b style=color:green>[View]</b> ';
            $moduleTotalTime = round(microtime(true) - $moduleStart, 2);
            $this->info .= "<script>document.querySelectorAll('[module={$moduleName}] a').forEach(function(element) {
            element.textContent += ' ({$moduleTotalTime} s.)';
            element.innerHTML += '{$isTable}';
            element.style.color='blue';
            });</script>";
        }

        // We create the views Join Multienum, right now that we already have all the views and the complete metadata table.
        $this->createMultiEnumJoinViews();

        $this->checkSdaColumns();
        $this->checkSdaTablesInViews();

        if ($callUpdateModel) {
            $this->updateModelCall();
        }

        $this->info .= '<script>
        // Selecciona todos los elementos li con el atributo module
        var liElements = document.querySelectorAll("li[module]");

        // Recorre todos los elementos li seleccionados
        for (var i = 0; i < liElements.length; i++) {
          var li = liElements[i];

          // Obtiene el valor del atributo module
          var moduleValue = li.getAttribute("module");

          // Busca el div con id igual al valor de module
          var targetDiv = document.getElementById(moduleValue);


          // Si se encuentra el div
          if (targetDiv) {
            // Comprueba si el div contiene un div con la clase error
            var errorDivs = targetDiv.getElementsByClassName("error");

            // Selecciona el elemento a dentro del li
            var aElement = li.querySelector("a");

            // Si hay elementos con la clase error dentro del div
            if (errorDivs.length > 0) {
              // Aplica los estilos al elemento a
              aElement.style.backgroundColor = "red";
              aElement.style.color = "white";
            }

            // Agrega el evento de clic al elemento li
            li.addEventListener("click", function () {
              var targetId = this.getAttribute("module");
              var targetDiv = document.getElementById(targetId);
              if (targetDiv.style.display === "none") {
                targetDiv.style.display = "block";
              } else {
                targetDiv.style.display = "none";
              }

              // Obtiene el primer campo de entrada dentro del div
              var inputField = targetDiv.querySelector("input");

              // Si se encuentra el campo de entrada, le da el foco
              if (inputField) {
                inputField.focus();
              }
            });
          }
        }
        </script>';

        $endTime = microtime(true);
        $totalTime = round($endTime - $startTime, 2);
        $GLOBALS['log']->stic('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "SinergiaDA rebuild script finished in {$totalTime} seconds.");
        $this->info .= "<b>Rebuild ejecutado en {$totalTime} segundos. </b>";

        //Adding config values
        $this->addMetadataRecord('sda_def_config', ['key' => 'rebuild_file_date', 'value' => $fechaFormateada]);
        $this->addMetadataRecord('sda_def_config', ['key' => 'rebuild_duration', 'value' => $totalTime]);

        // Add other config values that do not depend on the variables of this function
        $this->addConfigValues();

        // The text output is shown only if REQUEST print_debug exists
        if (isset($_REQUEST['print_debug'])) {
            echo $this->info;
            die();
        }

        return $this->info;

    }

    /**
     * Updates the model from SinergiaDA instance fetching content from a specified URL.
     *
     * @return void
     */
    private function updateModelCall()
    {
        global $sugar_config;

        $this->info .= '<div style="background-color:#eee;padding:5px;font-style:helvetica, arial;"><h2>Update Model Call</h2>';

        $seedString = $sugar_config['stic_sinergiada']['seed_string'] ?? '';

        $token = gmdate('Y') . $seedString . intVal(gmdate('d')) . intVal(gmdate('H'));

        $this->info .= "<li>Token source: $token";
        $token = md5($token);
        $this->info .= "<li>Token md5: $token";

        // Builds the URL to be called to execute the updateModel method in SinergiaDA,
        // depending on whether a specific URL has been indicated or if a standard location will be used.
        if ($sugar_config['stic_sinergiada_public']['url'] ?? null) {
            $url = "{$sugar_config['stic_sinergiada_public']['url']}/edapi/updatemodel/update?tks=$token";
        } else {
            $url = "https://{$this->baseHostname}.sinergiada.org/edapi/updatemodel/update?tks=$token";
        }

        $link = "<a href='$url' target='_blank'>$url</a>";
        $link2 = addslashes("Retry <a href='$url' target='_blank'>&#9842;</a>");

        $this->info .= "<li>URL: {$link}";

        // Use curl to get url content
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $contenido = curl_exec($ch);
        curl_close($ch);

        // Check if the content is empty.
        if (!empty($contenido)) {
            // If the content is not empty, display it.
            $this->info .= "<li>Response: <strong>{$contenido}</strong>";

            if (preg_match('/\bstatus\b.*\bok\b/i', $contenido)) {
                $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . ' The Updatemodel method has returned state OK');
                // break; // Exit the loop if the status is "ok".
            } else {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . ' The Updatemodel method has returned state Error');
            }
        } else {
            // If the content is empty, display an error message.
            $this->info .= "[FATAL: The Updatemodel method has not been executed {$link2}]";
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . 'The Updatemodel method in the SDA instance has not been executed');
        }
        $this->info .= "</div>";

    }

    /**
     * Sanitizes a text by removing unnecessary characters
     * @param string $text The text to be sanitized
     * @return string The sanitized text
     */
    private function sanitizeText($text)
    {
        $text = trim($text, ' _:');
        return $text;
    }

    /**
     * Creates a LEFT JOIN for a relationship field, handling both standard and special cases.
     *
     * This function retrieves information about a relationship based on the field's `link` property.
     * If the relationship uses a join table (`join_table`, `join_key_lhs`, and `join_key_rhs` are defined),
     * it creates a LEFT JOIN based on whether the current module is the left or right side of the relationship.
     * If no join table is used, it checks if the relationship is a one-to-many relationship for the
     * current table and builds the join accordingly. If no suitable relationship is found, it does not return a join.
     *
     * @param array $field The field array containing information about the current field
     * @param string $tableName The name of the table being processed
     * @param string $tableLabel The label of the other side relationships
     *
     * @return array|null An array containing the 'field' and 'leftJoin' information, or null if no join is created
     */

    private function createRelateLeftJoin($field, $tableName, $tableLabel)
    {
        $db = DBManagerFactory::getInstance();

        $tableLabel = empty($tableLabel) ? '-' : $tableLabel;
        // **Retrieve relationship information:**
        $rel = $db->fetchOne("select * from relationships where relationship_name='{$field['link']}'");

        // **Check if necessary information is present for standard join:**
        if (!empty($rel['join_table']) && !empty($rel['join_key_lhs']) && !empty($rel['join_key_rhs'])) {
            // Standard join using join table

            // **Determine join side based on current module:**
            if ($rel['lhs_module'] == $field['module']) {
                // Current module is on the left side

                // Add metadata record
                $this->addMetadataRecord(
                    'sda_def_relationships',
                    [
                        'id' => $field['link'],
                        'source_table' => "{$this->viewPrefix}_{$rel['rhs_table']}",
                        'source_column' => $field['id_name'],
                        'target_table' => "{$this->viewPrefix}_{$field['table']}",
                        'target_column' => 'id',
                        'info' => 'link_lhs',
                        'label' => "{$field['label']}|{$tableLabel}",
                    ]
                );

                return [
                    'field' => "{$rel['join_table']}.{$rel['join_key_lhs']}",
                    'leftJoin' => " LEFT JOIN {$rel['join_table']} ON {$rel['join_table']}.{$rel['join_key_rhs']}=m.id AND {$rel['join_table']}.deleted=0 ",
                ];
            } elseif ($rel['rhs_module'] == $field['module']) {
                // Current module is on the right side

                // Add metadata record
                $this->addMetadataRecord(
                    'sda_def_relationships',
                    [
                        'id' => $field['link'],
                        'source_table' => "{$this->viewPrefix}_{$rel['lhs_table']}",
                        'source_column' => $field['id_name'],
                        'target_table' => "{$this->viewPrefix}_{$field['table']}",
                        'target_column' => 'id',
                        'info' => 'link_rhs',
                        'label' => "{$field['label']}|{$tableLabel}",
                    ]
                );

                return [
                    'field' => "{$rel['join_table']}.{$rel['join_key_rhs']} ",
                    'leftJoin' => " LEFT JOIN {$rel['join_table']} ON {$rel['join_table']}.{$rel['join_key_lhs']}=m.id AND {$rel['join_table']}.deleted=0 ",
                ];
            }
        } else {
            // **Handle cases where no join table is used:**

            // Check for one-to-many relationship with the current table
            $sql = "SELECT * FROM relationships WHERE (lhs_table='{$tableName}' OR rhs_table='{$tableName}') AND (lhs_table='{$field['table']}' OR rhs_table='{$field['table']}') AND relationship_type='one-to-many'";
            $rel = $db->fetchOne($sql);

            if ($rel) {
                // One-to-many relationship - use direct join
                $res['field'] = "m.{$field['id_name']}";
                $res['leftJoin'] = " LEFT JOIN {$field['table']} ON {$field['table']}.id=m.{$field['id_name']} AND {$field['table']}.deleted=0 ";

                // Add metadata record
                $this->addMetadataRecord(
                    'sda_def_relationships',
                    [
                        'id' => $field['link'],
                        'source_table' => "{$this->viewPrefix}_{$tableName}",
                        'source_column' => $field['id_name'],
                        'target_table' => "{$this->viewPrefix}_{$field['table']}",
                        'target_column' => 'id',
                        'info' => 'no-join-table-relationship ',
                        'label' => "{$field['label']}|{$tableLabel}",
                    ]
                );

                return $res;

            }

            return;
        }
    }

    /**
     * Retrieves the fields that are visible in the detail view for a given module
     *
     * This function retrieves the fields that are visible in the detail view for a given module by including the relevant metadata files and
     * parsing through the view definition panels. It first checks for the existence of the file "modules/{$moduleName}/metadata/detailviewdefs.php"
     * and then checks for the existence of "custom/modules/{$moduleName}/metadata/detailviewdefs.php". It then iterates through the
     * view definition panels and collects the fields that are visible in the detail view.
     *
     * @param string $moduleName The name of the module for which to retrieve the visible fields
     *
     * @return array An array of the fields that are visible in the detail view
     */
    private function detailViewVisibleFields($moduleName)
    {
        // 1º) Check for the presence of the file "modules/{$moduleName}/metadata/detailviewdefs.php"
        if (file_exists("modules/{$moduleName}/metadata/detailviewdefs.php")) {
            include_once "modules/{$moduleName}/metadata/detailviewdefs.php";
        }

        // 2º) Check for the presence of the file "custom/modules/{$moduleName}/metadata/detailviewdefs.php"
        if (file_exists("custom/modules/{$moduleName}/metadata/detailviewdefs.php")) {
            include_once "custom/modules/{$moduleName}/metadata/detailviewdefs.php";
        }

        $fieldsAvailable = array();
        //Iterate through the view definition panels
        foreach ($viewdefs[$moduleName]['DetailView']['panels'] as $panel) {
            foreach ($panel as $row) {
                foreach ($row as $fieldArray) {
                    if (isset($fieldArray['name'])) {
                        //collect the fields that are visible in the detail view
                        $fieldsAvailable[] = $fieldArray['name'];
                    } else {
                        if (!empty($fieldArray)) {
                            $fieldsAvailable[] = $fieldArray;
                        }
                    }
                }
            }
        }

        return $fieldsAvailable;
    }
    /**
     * Common function for insert values in metadata tables.
     *
     * @param String $table Table name
     * @param Array $values Array with keys & values to insert in $table
     * @return void
     */
    private function addMetadataRecord($table, $values)
    {
        $db = DBManagerFactory::getInstance();

        // All values to be inserted in MySQL escape to avoid syntax errors
        foreach ($values as $key => $value) {
            $values[$key] = addslashes($value);
        }

        $columnKeys = '`' . implode('`,`', array_keys($values)) . '`';

        $columnValues = "'" . implode("','", array_values($values)) . "'";

        $insertSQL = "INSERT INTO `{$table}` ";
        $insertSQL .= " ($columnKeys ) ";
        $insertSQL .= " VALUES ($columnValues)";

        if (!$db->query($insertSQL)) {
            die("<div style=color:red;>" . $db->last_error . "</div>");
        };
    }

    /**
     * Reconstruye las vistas que forman parte de los metadatos
     *
     * @return void
     */
    private function resetMetadataViews()
    {
        global $sugar_config;

        $db = DBManagerFactory::getInstance();

        $sqlMetadata = [];
        // 1) eda_def_users
        // All users are added, indicating whether or not they are active, in the "Active" field, to
        // that EDA can handle the user synchronization process
        $sqlMetadata[] = "CREATE or REPLACE VIEW `sda_def_users` AS
                          SELECT
                            id,
                            user_name AS user_name,
                            CONCAT_WS(' ', first_name, last_name) as name,
                            (
                                SELECT
                                    email_address
                                FROM
                                    email_addresses ea
                                    JOIN email_addr_bean_rel eab ON ea.id = eab.email_address_id
                                WHERE
                                    eab.bean_id = u.id
                                    AND primary_address = 1
                                    AND eab.bean_module = 'Users'
                                    AND ea.deleted = 0
                                    AND eab.deleted = 0
                                LIMIT
                                    1
                            ) email,
                            u.user_hash AS password,
                            if(u.status='Active' AND uc.sda_allowed_c=1 ,1,0) as 'active'
                        FROM
                            users u
                            INNER JOIN users_cstm uc on u.id =uc.id_c
                        WHERE
                            deleted = 0;";

        // 2) eda_def_groups
        $sqlMetadata[] = "CREATE or REPLACE VIEW `sda_def_groups` AS
                                  SELECT CONCAT('SDA_',name) as name FROM securitygroups WHERE deleted=0
                                  UNION SELECT 'EDA_ADMIN'
                                  UNION SELECT 'NO_SINERGIACRM_USERS'
                                  ;";
        // 3) eda_def_users_groups
        $sqlMetadata[] = "CREATE or REPLACE VIEW `sda_def_user_groups` AS
                            -- Normal users are assigned to their own security groups.
                            SELECT
                                user_name,
                                CONCAT('SDA_',s.name) as name
                            FROM
                                users u
                            JOIN securitygroups_users su ON
                                u.id = su.user_id
                            JOIN securitygroups s ON
                                s.id = su.securitygroup_id
                            WHERE
                                u.is_admin = 0
                                AND u.deleted = 0
                                AND su.deleted = 0
                                AND s.deleted = 0
                            UNION
                            -- Administrator users should always belong to the EDA_ADMIN group.
                            SELECT
                                user_name,
                                'EDA_ADMIN'
                            FROM
                                users u
                            WHERE
                                u.is_admin = 1
                                AND u.deleted = 0;";

        // 4) eda_def_security_group_records

        // Set a switch to determine whether to populate the sda_def_security_group_records view based
        // on the value of $sugar_config['stic_sinergiada']['group_permissions_enabled']
        if (($sugar_config['stic_sinergiada']['group_permissions_enabled'] ?? null) != true) {
            $limitQueryClause = ' limit 0 ';
        } else {
            $limitQueryClause = '';
        }

        $sqlMetadata[] = "CREATE or REPLACE VIEW `sda_def_security_group_records` AS
                            SELECT
                                CONCAT('{$this->viewPrefix}_', LCASE(module)) as `table`,
                                record_id,
                                CONCAT('SDA_',s.name) as `group`
                            FROM
                                securitygroups_records sr
                                JOIN securitygroups s on sr.securitygroup_id=s.id
                            WHERE sr.deleted=0
                            {$limitQueryClause};
                            ";

        // run sql queries
        foreach ($sqlMetadata as $value) {
            if (!$db->query($value)) {
                die("<div style=color:red;>Error running  {$sqlMetadata} <br>" . $db->last_error . "</div>");
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Error has occurred: [{$db->last_error}] running Query: [{$sqlMetadata}]");
            };
        }

        // foreach ($sqlMetadata as $value) {
        //     if (!$db->query($value)) {
        //         die("<div style=color:red;>Error running  {$sqlMetadata} <br>" . $db->last_error . "</div>");
        //     };
        // }
    }

    /**
     * Drop and create metadata tables
     *
     * @return void
     */
    private function resetMetadataTables()
    {
        $db = DBManagerFactory::getInstance();

        $sqlMetadata = [];

        // 1) eda_def_tables
        $sqlMetadata[] = 'DROP TABLE IF EXISTS `sda_def_tables`';
        $sqlMetadata[] = 'CREATE TABLE IF NOT EXISTS `sda_def_tables` (
                            `table` VARCHAR(64) NOT NULL,
                            `label` VARCHAR(100) NOT NULL,
                            `description` VARCHAR(255) NOT NULL,
                            `visible` BIT DEFAULT 1
                        ) ENGINE = MyISAM;';

        // 2) eda_def_columns
        $sqlMetadata[] = 'DROP TABLE IF EXISTS `sda_def_columns`';
        $sqlMetadata[] = 'CREATE TABLE IF NOT EXISTS `sda_def_columns` (
                            `table` VARCHAR(64) NOT NULL,
                            `column` VARCHAR(64) NOT NULL,
                            `type` VARCHAR(25) NOT NULL,
                            `label` VARCHAR(100) NOT NULL,
                            `description` VARCHAR(255) NOT NULL,
                            `decimals` INT NOT NULL,
                            `aggregations` VARCHAR(100) DEFAULT \'none\',
                            `visible` BIT DEFAULT 1,
                            `sda_hidden` INT DEFAULT 0,
                            `stic_type` VARCHAR(20)
                        ) ENGINE = MyISAM;';
        // 3) eda_def_relationships
        $sqlMetadata[] = 'DROP TABLE IF EXISTS `sda_def_relationships`';
        $sqlMetadata[] = 'CREATE TABLE IF NOT EXISTS `sda_def_relationships` (
                            `id` VARCHAR(64) NOT NULL,
                            `source_table` VARCHAR(64) NOT NULL,
                            `source_column` VARCHAR(64) NOT NULL,
                            `target_table` VARCHAR(64) NOT NULL,
                            `target_column` VARCHAR(64) NOT NULL,
                            `label` VARCHAR(255) NOT NULL,
                            `info` VARCHAR(255) NOT NULL
                        ) ENGINE = MyISAM;';

        // 4) eda_def_enumerations
        $sqlMetadata[] = 'DROP TABLE IF EXISTS `sda_def_enumerations`';
        $sqlMetadata[] = 'CREATE TABLE IF NOT EXISTS `sda_def_enumerations` (
                            -- `id` CHAR(64) NOT NULL,
                            `source_table` VARCHAR(64) NOT NULL,
                            `source_column` VARCHAR(64) NOT NULL,
                            `master_table` VARCHAR(64) NOT NULL,
                            `master_id` VARCHAR(64) NOT NULL DEFAULT "code",
                            `master_column` VARCHAR(64) NOT NULL DEFAULT "value",
                            `bridge_table` VARCHAR(64) NOT NULL,
                            `source_bridge` VARCHAR(64) NOT NULL,
                            `target_bridge` VARCHAR(64) NOT NULL,
                            `stic_type` VARCHAR(20) NOT NULL,
                            `info` VARCHAR(255) NOT NULL
                        ) ENGINE = MyISAM;';

        // 5) eda_def_permissions
        $sqlMetadata[] = 'DROP TABLE IF EXISTS `sda_def_permissions`';
        $sqlMetadata[] = 'CREATE TABLE IF NOT EXISTS `sda_def_permissions` (
                            `user_name` VARCHAR(64) NOT NULL,
                            `group` VARCHAR(64) NOT NULL,
                            `table` VARCHAR(64) NOT NULL,
                            `column` VARCHAR(64) NOT NULL,
                            `global` VARCHAR(64) NOT NULL,
                            `stic_permission_source` VARCHAR (20) NOT NULL
                        ) ENGINE = MyISAM;';

        // 6) sda_def_config
        $sqlMetadata[] = 'DROP TABLE IF EXISTS `sda_def_config`';
        $sqlMetadata[] = 'CREATE TABLE IF NOT EXISTS `sda_def_config` (
                            `key` VARCHAR(64) NOT NULL,
                            `value` VARCHAR(64) NOT NULL
                        ) ENGINE = MyISAM;';

        foreach ($sqlMetadata as $key => $value) {
            if (!$db->query($value)) {
                die("<div style=color:red;>Error running  {$sqlMetadata} <br>" . $db->last_error . "</div>");
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Error has occurred: [{$db->last_error}] running Query: [{$sqlMetadata}]");

            };
        }
    }

    /**
     * Adds configuration values to the sda_def_config table.
     * This function aggregates different types of configuration values,
     * including CRM version, current datetime, and custom settings from $sugar_config['stic_sinergiada'].
     */
    public function addConfigValues()
    {
        global $sugar_config;
        $tmpConfigValues = [];

        // Add the CRM version to the config values
        $tmpConfigValues['sinergiacrm_version'] = $sugar_config['sinergiacrm_version'];

        // Add the current date and time to the config values
        $tmpConfigValues['last_rebuild'] = date('Y-m-d H:i:s');

        // Iterate over 'stic_sinergiada' settings and add them to the config values
        foreach ($sugar_config['stic_sinergiada'] as $key => $value) {
            if (is_array($value)) {
                // If the setting is an array, add each sub-element with a composite key
                foreach ($value as $key2 => $value2) {
                    $tmpConfigValues["sda_{$key}_{$key2}"] = $value2;
                }
            } else {
                // If the setting is not an array, add it directly with a simple key
                $tmpConfigValues["sda_{$key}"] = $value;
            }
        }

        // Add each gathered config value to the metadata record
        foreach ($tmpConfigValues as $key => $value) {
            $this->addMetadataRecord('sda_def_config', [
                'key' => $key,
                'value' => $value,
            ]);
        }
    }

/**
 * Function to create an MariaDB table for a specific $app_list_strings
 * @param string $listName The name of the SuiteCRM list ($app_mod_string)
 * @param string $listViewName then name of the MariaDB table to create.
 * @return mixed The name of the list if the table is successfully created, void otherwise
 */
    private function createEnumTable($listName, $listViewName)
    {
        // Get the global variable $app_list_strings
        global $app_list_strings;

        // Get instance of DBManagerFactory
        $db = DBManagerFactory::getInstance();

        // Get the current list
        $currentList = $app_list_strings[$listName];

        // Drop the table if it already exists
        $dropTableCommand = "DROP TABLE IF EXISTS {$this->listViewPrefix}_{$listViewName}";
        $db->query($dropTableCommand);

        // Start building the SQL command to create the table
        $sqlCommand = "CREATE TABLE {$this->listViewPrefix}_{$listViewName} (code VARCHAR(100), value VARCHAR(100)) AS ";
        $isFirst = false;

        // Loop through the current list
        foreach ($currentList as $key => $value) {
            // If it's the first iteration, add SELECT statement
            if ($isFirst == false) {
                $sqlCommand .= "SELECT '{$key}' as 'code', '{$db->quote($value)}' as 'value' ";
                $isFirst = true;
            } else {
                // Add UNION SELECT statement for the rest of the iterations
                $sqlCommand .= "UNION SELECT '{$key}', '{$db->quote($value)}' ";
            }
        }

        // Execute the SQL command
        if (!$db->query($sqlCommand)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Error has occurred: [{$db->last_error}] running Query: [{$sqlCommand}]");
            return;
        }

        // Create an index on the 'value' column
        $indexCommand = "CREATE INDEX value_index ON {$this->listViewPrefix}_{$listViewName} (value)";

        if (!$db->query($indexCommand)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Error has occurred: [{$db->last_error}] creating Index on: [{$this->listViewPrefix}_{$listViewName}]");
            return;
        }

        return $listViewName;
    }

    /**
     * Function to create an MariaDB view for a specific $app_list_strings
     * @param string $listName The name of the SuiteCRM list ($app_mod_string)
     * @param string $listViewName then name of the MariaDB view to create.
     * @return mixed The name of the list if the view is successfully created, void otherwise
     */
    private function createEnumView($listName, $listViewName)
    {

        // return $this->createEnumTable($listName, $listViewName);

        // Get the global variable $app_list_strings
        global $app_list_strings;

        // Get instance of DBManagerFactory
        $db = DBManagerFactory::getInstance();

        // Get the current list
        $currentList = $app_list_strings[$listName];

        // Start building the SQL command
        $sqlCommand = "CREATE OR REPLACE VIEW {$this->listViewPrefix}_{$listViewName} AS ";
        $isFirst = false;

        // Loop through the current list
        foreach ($currentList as $key => $value) {
            // If it's the first iteration, add SELECT statement
            if ($isFirst == false) {
                $sqlCommand .= "SELECT '{$key}' as 'code', '{$db->quote($value)}' as 'value' ";
                $isFirst = true;
            }
            // Add UNION SELECT statement for the rest of the iterations
            $sqlCommand .= "UNION SELECT '{$key}', '{$db->quote($value)}' ";
        }

        // Execute the SQL command
        if (!$db->query($sqlCommand)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Error has occurred: [{$db->last_error}] running Query: [{$sqlCommand}]");
            // $this->info .= "[FATAL: No se ha podido crear la vista {$this->listViewPrefix}_{$listViewName}]";
        } else {
            return $listViewName;
        };
    }

    /**
     * Function to delete old views from the database
     * @return void
     */
    public function deleteOldViews()
    {
        // List of prefixes to be deleted
        $prefixesToDelete = ['sda_'];

        // Get instance of DBManagerFactory
        $db = DBManagerFactory::getInstance();
        $counterView = 0;
        $counterTable = 0;

        // Loop through the prefixes
        foreach ($prefixesToDelete as $prefix) {

            // Get all the views with matching prefixes
            $res = $db->query("select table_name from information_schema.views where table_name like '{$prefix}%'");
            // Loop through the views
            while ($view = $db->fetchByAssoc($res, false)) {
                // Delete the view
                if ($db->query("DROP view {$view['table_name']}")) {
                    $counterView++;
                }
            }

            // Get all the tables with matching prefixes
            $res = $db->query("select table_name from information_schema.tables where table_name like '{$prefix}%'");
            // Loop through the views
            while ($view = $db->fetchByAssoc($res, false)) {
                // Delete the view
                if ($db->query("DROP TABLE {$view['table_name']}")) {
                    $counterTable++;
                }
            }

        }
        $this->info .= "Eliminando {$counterView} vistas y {$counterTable} tablas obsoletas.";
    }

    /**
     * Function to create join views for multi-enum fields in SuiteCRM
     *
     * This function retrieves all multi-enum fields from the 'sda_def_enumerations' table, and for each field,
     * it retrieves the corresponding list of values from the 'master_table' specified in the field's record.
     * For each value in the list, it finds the ids of the records in the 'source_table' that contain the value in the 'source_column'
     * of the multi-enum field, and creates a UNION SELECT statement for each id. These statements are then concatenated
     * and used to create a join view with the name "{$multiField['source_table']}__{$multiField['source_column']}"
     * The function will print if the view was created successfully or if there was an error while creating it.
     *
     * @return bool true if bridge view has created, false if not
     */
    public function createMultiEnumJoinViews()
    {
        $this->info .= "<h2>Creating enum join tables</h2>";

        // Get instance of DBManagerFactory
        $db = DBManagerFactory::getInstance();

        // Get all the multi-enum fields
        $res = $db->query("SELECT * FROM sda_def_enumerations WHERE stic_type='multienum' ");
        while ($multiField = $db->fetchByAssoc($res, false)) {

            $list = $multiField['master_table'];
            $this->info .= "<li>{$list}</li>";

            $unionSelectPiece = array();
            $resList = $db->query("select * from {$list}");

            // Loop through the elements in the list
            while ($listElement = $db->fetchByAssoc($resList, false)) {

                // Skip empty values
                if (empty($db->quote($listElement['value']))) {
                    continue;
                }

                // Add the ids that contain the value of the list
                $unionSelectPiece[] = "SELECT
                 id as id,
                 '{$listElement['code']}' as 'code'
                 FROM
                    {$multiField['source_table']}
                 WHERE ({$multiField['source_column']} LIKE '%^{$listElement['code']}^%' OR {$multiField['source_column']} = '{$listElement['code']}')
                 ";
            }

            $multienumJoinTableSQL = implode(' UNION ', $unionSelectPiece);

            // Create the join view. It's important to use the same method as when processing each individual multienum field
            $multienumBridgeTableName = mb_strcut("{$multiField['source_table']}__{$multiField['source_column']}", 0, 64);

            $sqlCommand = "CREATE OR REPLACE VIEW `$multienumBridgeTableName` AS {$multienumJoinTableSQL}";

            if (!$db->query($sqlCommand)) {

                $this->info .= ("<div style=color:red;>Error al crear la vista {$multienumBridgeTableName}:" . $sqlCommand . '<hr>' . array_pop(explode(':', $db->last_error)) . "</div>");
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Error has occurred: [{$db->last_error}] running Query: [{$sqlCommand}]");
                $this->info .= "[FATAL: Unable to create view {$multienumBridgeTableName}]";
            } else {
                $this->info .= ("<div style=color:green;> - OK: {$multienumBridgeTableName}  </div>");
            };
        }
    }

    /**
     * Function to get and save user access control list (ACL) for specified modules in SuiteCRM
     *
     * @param array $modules The modules for which the user's ACL should be retrieved and saved
     *
     * This function retrieves the list of active users from the 'users' table, and for each user,
     * it retrieves their ACL for the specified modules using the 'ACLAction::getUserActions' method.
     * Then it processes the ACL for each module and saves metadata for the user's access level and source of access,
     * such as 'ACL_ALLOW_GROUP' or 'ACL_ALLOW_OWNER' in the 'sda_def_permissions' table.
     * It also saves the user's access level for each module in the 'aclList' array.
     *
     * @return void
     */
    public function getAndSaveUserACL($modules)
    {
        global $sugar_config;

        $db = DBManagerFactory::getInstance();
        include_once 'modules/ACLActions/ACLAction.php';

        // List of ACL sources
        $aclSourcesList = [
            100 => 'ACL_ALLOW_ADMIN_DEV',
            99 => 'ACL_ALLOW_ADMIN',
            90 => 'ACL_ALLOW_ALL',
            89 => 'ACL_ALLOW_ENABLED',
            80 => 'ACL_ALLOW_GROUP',
            75 => 'ACL_ALLOW_OWNER',
            1 => 'ACL_ALLOW_NORMAL',
            0 => 'ACL_ALLOW_DEFAULT',
        ];

        // Get list of active users
        $res = $db->query("SELECT id,user_name, is_admin FROM users WHERE status='Active' AND deleted=0;");
        while ($u = $db->fetchByAssoc($res, false)) {

            $allModulesACL = array_intersect_key(ACLAction::getUserActions($u['id'], true), $modules);
            foreach ($allModulesACL as $key => $value) {
                unset($aclSource);
                // Access to the users module is allowed only for administrator users
                if ($u['is_admin'] == 0 && $key == 'Users') {
                    continue;
                }

                $aclSource = $aclSourcesList[$value['module']['view']['aclaccess']];

                // Fix for special cases when the module name is different from the table name
                $key = $key == 'ProjectTask' ? 'Project_Task' : $key;
                $key = $key == 'CampaignLog' ? 'Campaign_Log' : $key;

                $currentTable = $this->viewPrefix . '_' . strtolower($key);
                if ($u['is_admin'] == 1) {
                    $userModuleAccessMode["{$aclSource}_{$u['user_name']}_{$currentTable}"] = [
                        'user_name' => $u['user_name'],
                        'table' => $currentTable,
                        'column' => 'users_id',
                        'stic_permission_source' => 'ACL_ALLOW_ALL',
                        'global' => 1,
                    ];
                } elseif ($value['module']['access']['aclaccess'] >= 0 && $value['module']['view']['aclaccess'] >= 0) {

                    // Determine the metadata to be saved based on the type of permissions,
                    // first we'll add them to the $userModuleAccessMode array with a unique key to avoid duplicates
                    switch ($value['module']['view']['aclaccess']) {
                        case '80': // Security groups

                            // If $sugar_config['stic_sinergiada']['group_permissions_enabled'] is disabled, access is also disabled to
                            // modules where the user has restricted access to their group's records.
                            if (($sugar_config['stic_sinergiada']['group_permissions_enabled'] ?? null) != true) {
                                continue 2;
                            }

                            // In the case of Security Groups we add a unique entry for each of the groups the user belongs to,
                            // ensuring that it does not exist previously for each module.
                            $userGroupsRes = $db->query("SELECT distinct(name) as 'group' FROM sda_def_user_groups ug WHERE user_name='{$u['user_name']}';");

                            while ($userGroups = $db->fetchByAssoc($userGroupsRes, false)) {

                                $crmGroupName = explode('SDA_', $userGroups['group'])[1];

                                // Verify whether or not the group has access to the module for their roles
                                $groupHasAccessToModule = groupHasAccess($crmGroupName, $key, 'view');

                                if ($groupHasAccessToModule) {

                                    $userModuleAccessMode["{$u['user_name']}_{$aclSource}_{$userGroups['group']}_{$currentTable}"] = [
                                        'user_name' => null,
                                        'group' => $userGroups['group'],
                                        'table' => $currentTable,
                                        'column' => 'id',
                                        'stic_permission_source' => $aclSource,
                                        'global' => 0,
                                    ];

                                    // Additionally we insert a record that allows each user's access to the records in which coinicide
                                    // the user_name with the assigned_user_name field content in each module in which the user has group permission
                                    $userModuleAccessMode["{$u['user_name']}_{$aclSource}_{$userGroups['group']}_private_{$currentTable}"] = [
                                        'user_name' => $u['user_name'],
                                        'group' => null,
                                        'table' => $currentTable,
                                        'column' => 'assigned_user_name',
                                        'stic_permission_source' => "{$aclSource}_private",
                                        'global' => 0,
                                    ];
                                }
                            }

                            break;

                        case '75': // Owner case
                            // Modules where the user has restricted access to their own/assigned records .

                            $userModuleAccessMode["{$aclSource}_{$u['user_name']}_{$currentTable}"] = [
                                'user_name' => $u['user_name'],
                                'table' => $currentTable,
                                'column' => 'assigned_user_name',
                                'stic_permission_source' => $aclSource,
                                'global' => 0,
                            ];
                            break;

                        default: // Other (normal) cases
                            // add metadata record for normal cases
                            $userModuleAccessMode["{$aclSource}_{$u['user_name']}_{$currentTable}"] = [
                                'user_name' => $u['user_name'],
                                'table' => $currentTable,
                                'column' => 'users_id',
                                'stic_permission_source' => $aclSource,
                                'global' => 1,
                            ];
                            break;
                    }
                    $aclList[$u['user_name']][$key] = $value['module']['view']['aclaccess'];
                }
            }
            unset($allModulesACL);
        }

        // Add the permissions with the values determined in the previous switch case to the metadata table, based on the case.
        foreach (array_unique($userModuleAccessMode, SORT_REGULAR) as $key => $value) {
            $this->addMetadataRecord(
                'sda_def_permissions',
                [
                    'user_name' => $value['user_name'],
                    'group' => $value['group'],
                    'table' => $value['table'],
                    'column' => $value['column'],
                    'global' => $value['global'],
                    'stic_permission_source' => $value['stic_permission_source'],
                ]
            );
        }
    }

    /**
     * Check the columns in the sda_def_columns table against the columns in the views.
     * Display the columns that do not exist in the views.
     *
     * @return void
     */
    public function checkSdaColumns()
    {
        $this->info .= "<b>SDA_DEF_COLUMNS columns that do not exist in the views</b>";
        // Get an instance of the DBManager
        $db = DBManagerFactory::getInstance();
        // Query to get all the rows from the sda_def_columns table
        $query = "SELECT `table`, `column` FROM sda_def_columns WHERE stic_type != 'virtual'";
        $result = $db->query($query);

        // Loop through each row
        while ($row = $db->fetchByAssoc($result)) {
            // Query to check if the column exists in the view
            $columnExists = $db->getOne("SELECT count(*)
        FROM information_schema.columns
        WHERE table_name = '{$row["table"]}'
        AND column_name='{$row["column"]}'");
            // If the column does not exist, display a message
            if ($columnExists == 0) {
                $this->info .= "[FATAL: Table: " . $row["table"] . " - column: " . $row["column"] . " does not exist]";

            }
        }
    }
    public function checkSdaTables($tableToCheck)
    {
        // Get an instance of the DBManager
        $db = DBManagerFactory::getInstance();
        // Query to get all the rows from the sda_def_columns table
        $query = "SELECT table_name
        FROM information_schema.tables
        WHERE table_schema = DATABASE();";
        $result = $db->query($query);
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['table_name'] == $tableToCheck) {
                return true;
            }
        }
        return false;
    }

/**
 * Checks the tables in the different definition tables against the existing views in the database.
 * Prevents attempts to generate tables that do not exist in the views.
 *
 * @return void
 */
    public function checkSdaTablesInViews()
    {
        $this->info .= "<br><b>SDA_DEF_COLUMNS tables that do not exist in the views</b><br>";

        $db = DBManagerFactory::getInstance();
        $missingTables = "SELECT DISTINCT * FROM (
            SELECT `table`, 'sda_def_columns', 'table' AS column_name  FROM sda_def_columns
            UNION SELECT `table`,'sda_def_tables', 'table' FROM sda_def_tables
            UNION SELECT source_table,'sda_def_enumerations','source_table' FROM sda_def_enumerations
            UNION SELECT master_table,'sda_def_enumerations', 'master_table' FROM sda_def_enumerations
            UNION SELECT `table`, 'sda_def_permissions','table' FROM sda_def_permissions
            UNION SELECT source_table,'sda_def_relationships','source_table' FROM sda_def_relationships
            UNION SELECT target_table,'sda_def_relationships','target_table' FROM sda_def_relationships)
            AS source WHERE (
                    `table` NOT IN ( SELECT table_name FROM information_schema.views WHERE table_schema = DATABASE())
                AND `table` NOT IN ( SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE())
                );";

        $result = $db->query($missingTables);
        if ($result !== false) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $queryDelete = "DELETE FROM {$row['sda_def_columns']} WHERE {$row['column_name']} = '{$row['table']}';";

                    $deleteResult = $db->query($queryDelete);

                    if ($deleteResult !== false) {
                        $this->info .= "<br>Registro {$row['table']} ha sido eliminado de {$row['sda_def_columns']}<br>";
                        $GLOBALS['log']->debug('Línea ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Registro {$row['table']} ha sido eliminado de {$row['sda_def_columns']}");
                    } else {
                        $this->info .= "<br>Error al eliminar el registro {$row['table']} de {$row['sda_def_columns']}.<br>";
                        $GLOBALS['log']->debug('Línea ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Error al eliminar el registro {$row['table']} de {$row['sda_def_columns']}");
                    }
                }
            } else {
                $this->info .= "No existen tablas sin relaciones en las vistas.";
            }
        } else {
            $this->info .= "Error al ejecutar la consulta.";
            $GLOBALS['log']->debug('Línea ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Error al ejecutar la consulta: $missingTables");
        }
    }

}

/**
 * Checks if a security group has access to a specific action in a given module.
 *
 * This function determines whether a security group, identified by its name, has the necessary
 * permissions to perform a specific action in a given module. It looks up the roles associated
 * with the group and checks the highest access levels available for those roles.
 *
 * @param string $group_name The name of the security group to check.
 * @param string $category The name of the module or category (e.g., 'Accounts', 'Contacts').
 * @param string $action The specific action to check (e.g., 'view', 'edit', 'delete').
 * @param string $type The type of ACL, defaults to 'module'.
 *
 * @return bool Returns true if the group has access, false otherwise.
 *
 * @global object $db SuiteCRM's global database object.
 *
 * @throws SQLException If there's an error in executing the SQL queries.
 */
function groupHasAccess($group_name, $category, $action, $type = 'module')
{
    global $db;

    // Escape the group name to prevent SQL injection
    $group_name = $db->quote($group_name);

    // Get the group ID based on the name
    $query = "SELECT id FROM securitygroups WHERE name = '$group_name' AND deleted = 0";
    $result = $db->query($query);
    $row = $db->fetchByAssoc($result);

    if (empty($row)) {
        return false; // The group doesn't exist
    }

    $group_id = $row['id'];

    // Get the roles associated with this security group
    $query = "SELECT role_id FROM securitygroups_acl_roles
              WHERE securitygroup_id = '$group_id' AND deleted = 0";
    $result = $db->query($query);

    $roles = array();
    while ($row = $db->fetchByAssoc($result)) {
        $roles[] = $row['role_id'];
    }

    if (empty($roles)) {
        return false; // If there are no roles, there's no access
    }

    // Check permissions for these roles
    $roleIds = implode("','", $roles);
    $query = "SELECT acl_actions.*, acl_roles_actions.access_override
              FROM acl_actions
              LEFT JOIN acl_roles_actions ON acl_roles_actions.action_id = acl_actions.id
                  AND acl_roles_actions.role_id IN ('$roleIds')
              WHERE acl_actions.category = '$category'
                AND acl_actions.name = '$action'
                AND acl_actions.acltype = '$type'
                AND acl_actions.deleted = 0";

    $result = $db->query($query);

    $highestAccess = -1;
    while ($row = $db->fetchByAssoc($result)) {
        // Use access_override if set, otherwise use the default aclaccess
        $access = $row['access_override'] ?? $row['aclaccess'];
        $highestAccess = max($highestAccess, $access);
    }

    // Determine if the access is sufficient
    // ACL_ALLOW_GROUP should be defined elsewhere in the system
    return $highestAccess >= ACL_ALLOW_GROUP;
}
