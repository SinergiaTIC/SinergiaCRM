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
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class stic_AWF_FormsUtils {

    private static $relationshipsCache = [];

    /**
     * Retrieves fields and relationships of given Module to given SuiteCRM modules.
     * Result: [name, text, textSingular, inStudio, icon, fields:[Field], relationships:[Relationship]]
     *   Field: {
     *     name, text, type, required, options, inViews
     *   }
     *   Relationship: {
     *     name, text, module_orig, field_orig, relationship, module_dest
     *   }
     * 
     */
    public static function getModuleInformation($moduleName, $availableModules) {
        global $app_list_strings, $dictionary;

        $result = [
            'name' => $moduleName,
            'text' => '',
            'textSingular' => '',
            'inStudio' => false,
            'icon' => '',
            'fields' => [],
            'relationships' => []
        ];
        // Set Text
        $result['text'] = translate($moduleName);
        $result['textSingular'] = $app_list_strings['moduleListSingular'][$moduleName] ?? $result['text'];

        // Fill Studio information
        require_once 'modules/ModuleBuilder/Module/StudioBrowser.php';
        $sb = new StudioBrowser();
        $nodes = $sb->getNodes();
        foreach ($nodes as $node) {
            if ($node['module'] == $moduleName) {
                $result['inStudio'] = true;
                $result['icon'] = $node['icon'];
                break;
            }
        }

        // Discover relationships from $dictionary (canonical source + link field fallback)
        $result['relationships'] = self::getRelationshipsFromDictionary($moduleName, $availableModules);

        // Discover fields for the module
        try {
            $objectOrig = BeanFactory::getObjectName($moduleName);
            VardefManager::loadVardef($moduleName, $objectOrig);
        } catch (\Exception $e) {
            $GLOBALS['log']->warn(__METHOD__ . ": Error loading vardefs for '{$moduleName}': " . $e->getMessage());
            return $result;
        }
        $fieldDefs = $dictionary[$objectOrig]['fields'] ?? [];

        // Collect link field relationship names for later relate-field cleanup
        $validRelNames = array_keys($result['relationships']);

        foreach ($fieldDefs as $fieldName => $arr) {
            if (isset($result['fields'][$fieldName])) continue;
            if (!isset($arr['type'])) continue;
            if ($arr['type'] == 'link') continue;

            // Exclude non Studio editable fields
            if (isset($arr['studio'])) {
                if (is_array($arr['studio']) && isset($arr['studio']['editview']) && $arr['studio']['editview'] === false) continue;
                if ($arr['studio'] === false || $arr['studio'] === 'false') continue;
            }

            // Exclude ID type fields
            if ($arr['type'] == 'id' || (isset($arr['dbType']) && strtolower($arr['dbType']) == 'id')) continue;

            // Exclude system fields
            $excludedFields = ['currency_name', 'currency_symbol', 'date_entered', 'date_modified',
                'modified_user_id', 'modified_by_name', 'created_by', 'created_by_name', 'deleted'];
            if (in_array($fieldName, $excludedFields)) continue;

            // Exclude non procesable field types
            $excludedTypes = ['html', 'iframe', 'image', 'file', 'attachment', 'address', 'wysiwyg',
                'parent', 'parent_type', 'team_id', 'team_set_id', 'team_list', 'team_count'];
            if (in_array($arr['type'], $excludedTypes)) continue;

            $isEmail = self::isEmailField($arr, $fieldName);
            $merge_filter = $isEmail ? 'enabled' : ($arr['merge_filter'] ?? '');

            $result['fields'][$fieldName] = [
                'name' => $fieldName,
                'text' => rtrim(trim(translate($arr['vname'] ?? '', $moduleName)), ":"),
                'type' => $arr['type'],
                'required' => isset($arr['required']) && $arr['required'],
                'default' => $arr['default'] ?? null,
                'options' => $arr['options'] ?? '',
                'module' => $arr['module'] ?? '',
                'merge_filter' => $merge_filter,
                'inViews' => false,
            ];

            // For relate fields, link options to their relationship name if found
            if ($arr['type'] === 'relate' && isset($arr['link'])) {
                $linkName = $arr['link'];
                $linkRelName = $fieldDefs[$linkName]['relationship'] ?? '';
                if (!empty($linkRelName) && isset($result['relationships'][$linkRelName])) {
                    $result['fields'][$fieldName]['options'] = $linkRelName;
                    $result['fields'][$fieldName]['link_name'] = $linkName;
                }
            }
        }

        // Remove relate fields whose relationship is not available
        $fieldsToRemove = [];
        foreach ($result['fields'] as $fieldName => $arr) {
            if ($arr['type'] == 'relate' && !empty($arr['options']) && !in_array($arr['options'], $validRelNames)) {
                $fieldsToRemove[] = $fieldName;
            }
        }
        foreach ($fieldsToRemove as $fieldName) {
            unset($result['fields'][$fieldName]);
        }

        // Complete field info with inViews (is in detailview or editview)
        if($result['inStudio']) {
            require_once 'modules/ModuleBuilder/parsers/ParserFactory.php';
            $views = ['detailview', 'editview'];
            foreach($views as $view) {
                $parser = ParserFactory::getParser($view, $moduleName, null);
                foreach ($parser->_viewdefs['panels'] as $panel) {
                    foreach ($panel as $row) {
                        foreach ($row as $field) {
                            if (isset($result['fields'][$field])) {
                                $result['fields'][$field]['inViews'] = true;
                            }
                        }
                    }
                }
            }
        }

        // Sort fields by text
        uasort($result['fields'], function($a, $b) {
            return strcmp($a['text'], $b['text']);
        });

        return $result;
    }

    /**
     * Returns relationships for a module using $dictionary['relationships'] (canonical source)
     * with fallback to link-type fields from $dictionary['fields'].
     * Each relationship entry includes: name, text, module_orig, module_dest, relationship, link_name
     */
    private static function getRelationshipsFromDictionary(string $moduleName, array $availableModules): array {
        global $dictionary;

        $cacheKey = $moduleName . '_' . md5(serialize(array_keys($availableModules)));
        if (isset(self::$relationshipsCache[$cacheKey])) {
            return self::$relationshipsCache[$cacheKey];
        }

        $result = [];
        $bean = BeanFactory::newBean($moduleName);
        if (!$bean) {
            return $result;
        }

        try {
            $objectName = BeanFactory::getObjectName($moduleName);
            VardefManager::loadVardef($moduleName, $objectName);
        } catch (\Exception $e) {
            $GLOBALS['log']->warn(__METHOD__ . ": Error loading vardefs for '{$moduleName}': " . $e->getMessage());
            return $result;
        }

        $fields = $dictionary[$objectName]['fields'] ?? [];
        $relDefs = $dictionary[$objectName]['relationships'] ?? [];

        $processed = [];
        $sources = [
            'relationships' => $relDefs,
            'link_fields'   => array_filter($fields, function($f) { return ($f['type'] ?? '') === 'link' && !empty($f['relationship']); }),
        ];

        foreach ($sources as $sourceType => $entries) {
            foreach ($entries as $key => $def) {
                if ($sourceType === 'relationships') {
                    $relName = $key;
                    $lhs = $def['lhs_module'] ?? '';
                    $rhs = $def['rhs_module'] ?? '';
                    $vname = $def['vname'] ?? '';
                    $linkFieldName = '';
                    $relType = $def['relationship_type'] ?? 'many-to-many';
                } else {
                    $relName = $def['relationship'];
                    $lhs = $moduleName;
                    $rhs = $def['module'] ?? '';
                    $vname = $def['vname'] ?? '';
                    $linkFieldName = $def['name'] ?? $key;
                    $relType = $def['relationship_type'] ?? 'many-to-many';

                    if (empty($rhs)) {
                        try {
                            if ($bean->load_relationship($linkFieldName)) {
                                $rhs = $bean->$linkFieldName->getRelatedModuleName();
                            }
                        } catch (\Exception $e) {
                            continue;
                        }
                    }
                }

                if (isset($processed[$relName])) continue;
                if (empty($rhs) || $rhs === 'EmailAddress') continue;

                // Determine which side is the current module
                $otherModule = null;
                $isOrig = false;
                if ($lhs === $moduleName && isset($availableModules[$rhs])) {
                    $otherModule = $rhs;
                    $isOrig = true;
                } elseif ($rhs === $moduleName && isset($availableModules[$lhs])) {
                    $otherModule = $lhs;
                    $isOrig = false;
                } else {
                    continue;
                }

                $processed[$relName] = true;

                // Text: translate vname, with fallbacks
                $text = '';
                if (!empty($vname)) {
                    $text = translate($vname, $moduleName);
                    if ($text === $vname) {
                        $text = translate($vname, $otherModule);
                    }
                    $text = rtrim(trim($text), ':');
                }
                if (empty($text) || $text === $vname) {
                    // Fallback 1: try relate field's vname (more descriptive for 1-N)
                    $fieldVname = '';
                    foreach ($fields as $fName => $fDef) {
                        if (($fDef['type'] ?? '') !== 'relate') continue;
                        $match = (!empty($linkFieldName) && ($fDef['link'] ?? '') === $linkFieldName)
                              || (($fDef['module'] ?? '') === $otherModule);
                        if ($match) {
                            $fieldVname = $fDef['vname'] ?? '';
                            break;
                        }
                    }
                    if (!empty($fieldVname)) {
                        $text = translate($fieldVname, $moduleName);
                        $text = rtrim(trim($text), ':');
                    }
                    // Fallback 2: destination module name
                    if (empty($text) || $text === $fieldVname) {
                        $text = translate($otherModule) ?: $relName;
                    }
                }

                $result[$relName] = [
                    'name'              => $relName,
                    'text'              => $text,
                    'module_orig'       => $isOrig ? $moduleName : $otherModule,
                    'module_dest'       => $isOrig ? $otherModule : $moduleName,
                    'relationship'      => $relName,
                    'link_name'         => $linkFieldName,
                    'relationship_type' => $relType,
                ];
            }
        }

        // Deduplicate by target module: if multiple relationships point to the same module_dest,
        // keep only the first one (avoids duplicate Notes/Tasks entries in module selectors)
        $seenDest = [];
        foreach ($result as $relName => $relData) {
            $dest = $relData['module_dest'];
            if (isset($seenDest[$dest])) {
                unset($result[$relName]);
            } else {
                $seenDest[$dest] = true;
            }
        }

        return self::$relationshipsCache[$cacheKey] = $result;
    }

    /**
     * Determines if a given field definition corresponds to a CRM Email field.
     */
    public static function isEmailField($fieldDef, $fieldName) 
    {
        if (isset($fieldDef['type']) && $fieldDef['type'] === 'email') {
            return true;
        }
        if (isset($fieldDef['type']) && $fieldDef['type'] === 'varchar' && 
            isset($fieldDef['source']) && $fieldDef['source'] === 'non-db' &&
            strpos($fieldName, 'email') !== false) {
            return true;
        }
        return false;
    }

    /**
     * Retrieves the relationships of given Module to given SuiteCRM modules.
     * Result: [Relationship]
     * Relationship: {
     *   name, text, module_orig, field_orig, relationship, module_dest
     * }
     */
    public static function getRelationships($moduleName, $availableModules) {
        $result = self::getRelationshipsFromDictionary($moduleName, $availableModules);

        uasort($result, function($a, $b) {
            return strcmp($a['text'], $b['text']);
        });

        return $result;
    }


    /**
     * Retrieves the relationships between given SuiteCRM modules.
     * Result: [Relationship]
     * Relationship: {
     *   name, text, module_orig, field_orig, relationship, module_dest
     * }
     */
    public static function getRelationshipsBetween($availableModules) {
        $result = [];
        foreach ($availableModules as $moduleName => $moduleInfo) {
            $rels = self::getRelationshipsFromDictionary($moduleName, $availableModules);
            foreach ($rels as $relName => $relData) {
                if (!isset($result[$relName])) {
                    $result[$relName] = $relData;
                }
            }
        }

        uasort($result, function($a, $b) {
            return strcmp($a['text'], $b['text']);
        });

        return $result;
    }

    /**
     * Get all modules enabled in Administration with a valid Bean
     * Result: [EnabedModule]
     * EnabledModule: {
     *   name, text, textSingular, inStudio, icon
     * }
     */
    public static function getEnabledModules() {
        global $app_list_strings, $beanList;

        $blackList = [
            'Home',
            'AOW_WorkFlow',
            'AOR_Reports', 'AOR_Scheduled_Reports',
            'KReports',
            'AOS_PDF_Templates',
            'DHA_PlantillasDocumentos',
            'AM_ProjectTemplates',
            // 'Documents',
            'Emails', 'EmailTemplates',
            'jjwg_Maps', 'jjwg_Markers', 'jjwg_Areas', 'jjwg_Address_Cache',
            'ProspectLists',
            'SecurityGroups', 'Roles', 'stic_Security_Groups_Rules',
            'SavedSearch', 
            'Spots',
            'Schedulers', 'SchedulersJobs',
            'Surveys', 'SurveyQuestions', 'SurveyResponses', 'SurveyQuestionOptions', 'SurveyQuestionResponses',
            'stic_Sepe_Actions', 'stic_Sepe_Files', 'stic_Sepe_Incidents',
            'stic_Signatures', 'stic_Signature_Log', 'stic_Signers',
            'stic_Messages', 'stic_Message_Marketing', 'stic_MessagesMan', 'stic_Conversations',
            'stic_Validation_Actions', 'stic_Validation_Results',
            'stic_AWF_Forms', 'stic_AWF_Responses', 'stic_AWF_Response_Details', 'stic_AWF_Links', 'stic_AWF_Deferred_Tickets', 'stic_AWF_Incoming_Events',
            'stic_Web_Forms',
            'stic_Settings',
            'Calendar', 'ResourceCalendar', 'stic_Bookings_Calendar', 'stic_Bookings_Places_Calendar', 'Reminders', 'Reminders_Invitees',
            'AOBH_BusinessHours',
            'AOK_KnowledgeBase', 'AOK_Knowledge_Base_Categories',
            'stic_Incorpora_Locations',
            'FP_Event_Locations'
        ];

        // Get Enabled Modules
        require_once("modules/MySettings/TabController.php");
        $controller = new TabController();
        $tabs = $controller->get_tabs_system();
        
        $enabled = [];
        foreach ($tabs[0] as $key=>$value) {
            if (!isset($beanList[$key]) || in_array($key, $blackList)) {
                continue;
            }
            $text = translate($key);
            $textSingular = $app_list_strings['moduleListSingular'][$key] ?? $text;
            $enabled[$key] = ["name" => $key, "text" => $text, "textSingular" => $textSingular, "inStudio" => false, "icon" => ""];
        }

        // Complete information from Studio
        require_once 'modules/ModuleBuilder/Module/StudioBrowser.php';
        $sb = new StudioBrowser();
        $nodes = $sb->getNodes();
        foreach ($nodes as $module) {
            if(isset($enabled[$module['module']])) {
                $enabled[$module['module']]['inStudio'] = true;
                $enabled[$module['module']]['icon'] = $module['icon'];
            }
        }

        // Sort modules by text
        uasort($enabled, function($a, $b) {
            return strcmp($a['text'], $b['text']);
        });

        return $enabled;
    }

    /**
     * Retrieves the Id and text of required records
     * Results: [{ id, text }]
     */
    public static function getRecordsTextById($module, $ids = []) {
        $results = [];
        if (empty($module) || empty($ids)) {
            return $results;
        }

        foreach ($ids as $id) {
            $bean = BeanFactory::getBean($module, $id);

            if (empty($bean) || empty($bean->id)) {
                continue;
            }

            $displayField = self::detectDisplayField($bean);
            $text = isset($bean->$displayField) ? $bean->$displayField : $bean->id;

            $results[] = [
                'id' => $bean->id,
                'text' => $text,
            ];
        }

        return $results;
    }

    /**
     * Gets the field name for the text of a record
     */
    public static function detectDisplayField($bean) {
        $fields = $bean->field_defs;

        $priorityFields = ['name', 'document_name', 'subject', 'full_name', 'first_name', 'last_name', 'title'];
        foreach ($priorityFields as $f) {
            if (isset($fields[$f])) {
                return $f;
            }
        }
        return 'id';
    }

    public static function getCustomBaseColor() {
        // From SticInclude/SticCustomScss.php
        $db = DBManagerFactory::getInstance();
        $color = $db->getOne("select value from stic_settings where name='GENERAL_CUSTOM_THEME_COLOR' and deleted=0");

        if (!preg_match('/#([a-fA-F0-9]{3}){1,2}\b/m', $color)) {
            $color = '';
        }

        if (!empty($color)){
            return $color;
        } else {
            return '#b5bc31';
        }
    }
}
