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

        // Prepare where to find relationships
        $moduleScanList = [
            // All relations from module to availableModules
            ['availableOrig' => [$moduleName => $moduleName], 'availableDest' => $availableModules],
            // All relations from availableModules to module
            ['availableOrig' => $availableModules, 'availableDest' => [$moduleName => $moduleName]]
        ];

        $link_defs = [];
        $seen_relationships = [];

        foreach($moduleScanList as $moduleScan) {
            foreach($moduleScan['availableOrig'] as $moduleOrigName => $moduleOrigInfo) {
                $beanOrig = BeanFactory::newBean($moduleOrigName);
                if (!$beanOrig) continue;
                
                foreach ($beanOrig->field_defs as $fieldName => $arr) {
                    if (!isset($arr['type'])) continue;

                    // Process LINK fields as relationships, but only if they point to available modules (and are not EmailAddress, which is a special module not usable in AWF)
                    if ($arr['type'] == 'link') {
                        $relName = $arr['relationship'] ?? '';
                        // If it's the reverse part of a bidirectional relationship already seen, skip it
                        if (!empty($relName) && isset($seen_relationships[$relName])) continue;

                        $link_defs[$fieldName] = $arr;
                        
                        $destModule = $arr['module'] ?? '';
                        if (empty($destModule) && $beanOrig->load_relationship($fieldName)) {
                            $destModule = $beanOrig->$fieldName->getRelatedModuleName();
                        }

                        if (!empty($destModule) && isset($moduleScan['availableDest'][$destModule]) && $destModule !== 'EmailAddress') {
                            if (!isset($result['relationships'][$fieldName])) {
                                if (!empty($relName)) $seen_relationships[$relName] = true; // Mark as seen
                                
                                $result['relationships'][$fieldName] = [
                                    'name' => $fieldName,
                                    'text' => '',
                                    'module_orig' => $moduleOrigName,
                                    'field_orig' => $fieldName,
                                    'relationship' => $relName,
                                    'module_dest' => $destModule
                                ];
                            }
                        }
                        continue; 
                    }

                    // Exclude fields not desired for "fields" normals
                    if ($moduleOrigName == $moduleName && isset($result['fields'][$fieldName])) continue;
                    if (isset($arr['studio']) && ($arr['studio'] === false || $arr['studio'] === 'false' || (is_array($arr['studio']) && isset($arr['studio']['editview']) && $arr['studio']['editview'] === false))) continue;
                    if ($arr['type'] == 'id' || (isset($arr['dbType']) && strtolower($arr['dbType']) == 'id')) continue;
                    $excludedFields = ['currency_name', 'currency_symbol', 'date_entered', 'date_modified', 'modified_user_id', 'modified_by_name', 'created_by', 'created_by_name', 'deleted'];
                    if (in_array($fieldName, $excludedFields)) continue;
                    $excludedTypes = ['html', 'iframe', 'image', 'file', 'attachment', 'address', 'wysiwyg', 'parent', 'parent_type', 'team_id', 'team_set_id', 'team_list', 'team_count'];
                    if (in_array($arr['type'], $excludedTypes)) continue;

                    if ($moduleOrigName == $moduleName) {
                        // Add field information
                        // name, text, type, required, default, options, inViews
                        $result['fields'][$fieldName] = [
                            'name' => $fieldName,
                            'text' => rtrim(trim(translate($arr['vname'] ?? '', $moduleOrigName)), ":"),
                            'type' => $arr['type'],
                            'required' => isset($arr['required']) && $arr['required'],
                            'default' => $arr['default'] ?? null,
                            'options' => $arr['options'] ?? '',
                            'module' => $arr['module'] ?? '',
                            'merge_filter' => $isEmail ? 'enabled' : ($arr['merge_filter'] ?? ''), // 'enabled', 'disabled', 'selected', ''
                            'inViews' => false,
                        ];

                    }

                    // Process relate fields pointing to links as relationships, but only if they point to available modules (and are not EmailAddress, which is a special module not usable in AWF)
                    if ($arr['type'] == 'relate' && isset($arr['link'])) {
                        $linkName = $arr['link'];
                        $relName = $beanOrig->field_defs[$linkName]['relationship'] ?? '';

                        // If we have already added the link to the results list, update the source field (UI)
                        if (isset($result['relationships'][$linkName])) {
                            $result['relationships'][$linkName]['field_orig'] = $fieldName;
                            continue;
                        }

                        // If it's the reverse part of a bidirectional relationship already seen, skip it
                        if (!empty($relName) && isset($seen_relationships[$relName])) continue;

                        $destModule = $arr['module'] ?? '';
                        if (empty($destModule) && isset($beanOrig->field_defs[$linkName]['module'])) {
                            $destModule = $beanOrig->field_defs[$linkName]['module'];
                        } elseif (empty($destModule) && $beanOrig->load_relationship($linkName)) {
                            $destModule = $beanOrig->$linkName->getRelatedModuleName();
                        }

                        if (empty($destModule) || $destModule == 'EmailAddress' || !isset($moduleScan['availableDest'][$destModule])) continue;

                        if (!isset($result['relationships'][$linkName])) {
                            if (!empty($relName)) $seen_relationships[$relName] = true;
                            
                            $result['relationships'][$linkName] = [
                                'name' => $linkName,
                                'text' => '',
                                'module_orig' => $moduleOrigName,
                                'field_orig' => $fieldName,
                                'relationship' => $relName, 
                                'module_dest' => $destModule
                            ];
                        }
                    }
                }
            }
        }

        // Virtualize fields and format text
        foreach ($result['relationships'] as $linkName => $arr) {
            $module_orig = $arr['module_orig'];
            $module_dest = $arr['module_dest'];
            $link_def = $link_defs[$linkName] ?? [];

            $label = $link_def['vname'] ?? '';
            $rel_text = rtrim(trim(translate($label, $module_orig)), ":");
            if (empty($rel_text) || $label == $rel_text) {
                $rel_text = rtrim(trim(translate($label, $module_dest)), ":");
            }

            $module_text = trim(translate($moduleName));
            $module_singularText = isset($app_list_strings['moduleListSingular'][$moduleName]) ? trim($app_list_strings['moduleListSingular'][$moduleName]) : $module_text;
            $otherModule = ($moduleName == $module_orig) ? $module_dest : $module_orig;
            $otherModule_text = trim(translate($otherModule));
            $otherModule_singularText = isset($app_list_strings['moduleListSingular'][$otherModule]) ? trim($app_list_strings['moduleListSingular'][$otherModule]) : $otherModule_text; 
            
            if (strtolower($rel_text) == strtolower($module_text) || strtolower($rel_text) == strtolower($module_singularText) || strtolower($rel_text) == strtolower($otherModule_text)) {
                $rel_text = $otherModule_singularText;
            }
        
            $result['relationships'][$linkName]['text'] = $rel_text;

            if ($module_orig == $moduleName) {
                $field_name = $arr['field_orig'];
                
                // If the field does not exist, create a virtual one of type 'link' to be able to use it in the UI as a relationship field.
                if (!isset($result['fields'][$field_name])) {
                    $result['fields'][$field_name] = [
                        'name' => $field_name,
                        'text' => $rel_text,
                        'type' => 'link',
                        'required' => false,
                        'default' => null,
                        'options' => $linkName,
                        'module' => $module_dest,
                        'merge_filter' => '',
                        'inViews' => false
                    ];
                } else {
                    $result['fields'][$field_name]['options'] = $linkName;
                }
            }
        }

        // Clean fields that are links but do not have a valid relationship (not pointing to an available module or EmailAddress)
        $fieldsToRemove = [];
        foreach ($result['fields'] as $fieldName => $arr) {
            // If it's a link field, but the relationship is not valid (not pointing to an available module or EmailAddress), 
            // remove it from the fields list (it will not be usable in the UI and can cause errors)
            if ($arr['type'] == 'relate' && !isset($link_defs[$arr['options']])) {
                $fieldsToRemove[] = $fieldName;
            }
        }
        foreach ($fieldsToRemove as $fieldName) { unset($result['fields'][$fieldName]); }

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
        $result = [];
        $moduleScanList = [
            // All relations from module to availableModules
            ['availableOrig' => [$moduleName => $moduleName], 'availableDest' => $availableModules],
            // All relations from availableModules to module
            ['availableOrig' => $availableModules, 'availableDest' => [$moduleName => $moduleName]]
        ];

        $link_defs = [];
        $seen_relationships = [];

        foreach($moduleScanList as $moduleScan) {
            foreach($moduleScan['availableOrig'] as $moduleOrigName => $moduleOrigInfo) {
                $moduleOrig = BeanFactory::newBean($moduleOrigName);
                if (!$moduleOrig) continue;
                
                foreach ($moduleOrig->field_defs as $fieldName => $arr) {
                    if (!isset($arr['type'])) continue;

                    if ($arr['type'] == 'link') {
                        $relName = $arr['relationship'] ?? '';
                        if (!empty($relName) && isset($seen_relationships[$relName])) continue;

                        $link_defs[$fieldName] = $arr;
                        $destModule = $arr['module'] ?? '';
                        
                        if (empty($destModule) && $moduleOrig->load_relationship($fieldName)) {
                            $destModule = $moduleOrig->$fieldName->getRelatedModuleName();
                        }

                        if (!empty($destModule) && isset($moduleScan['availableDest'][$destModule]) && $destModule !== 'EmailAddress') {
                            if (!isset($result[$fieldName])) {
                                if (!empty($relName)) $seen_relationships[$relName] = true;
                                $result[$fieldName] = [
                                    'name' => $fieldName,
                                    'text' => '',
                                    'module_orig' => $moduleOrigName,
                                    'field_orig' => $fieldName,
                                    'relationship' => $relName,
                                    'module_dest' => $destModule
                                ];
                            }
                        }
                        continue;
                    }

                    if ($arr['type'] == 'relate' && isset($arr['link'])) {
                        $linkName = $arr['link'];
                        $relName = $moduleOrig->field_defs[$linkName]['relationship'] ?? '';

                        if (isset($result[$linkName])) {
                            $result[$linkName]['field_orig'] = $fieldName;
                            continue;
                        }

                        if (!empty($relName) && isset($seen_relationships[$relName])) continue;

                        $destModule = $arr['module'] ?? '';
                        if (empty($destModule) && isset($moduleOrig->field_defs[$linkName]['module'])) {
                            $destModule = $moduleOrig->field_defs[$linkName]['module'];
                        } elseif (empty($destModule) && $moduleOrig->load_relationship($linkName)) {
                            $destModule = $moduleOrig->$linkName->getRelatedModuleName();
                        }

                        if (empty($destModule) || $destModule == 'EmailAddress' || !isset($moduleScan['availableDest'][$destModule])) continue;
                        
                        if (!isset($result[$linkName])) {
                            if (!empty($relName)) $seen_relationships[$relName] = true;
                            $result[$linkName] = [
                                'name' => $linkName,
                                'text' => '',
                                'module_orig' => $moduleOrigName,
                                'field_orig' => $fieldName,
                                'relationship' => $relName,
                                'module_dest' => $destModule
                            ];
                        }
                    }
                }
            }
        }

        foreach ($result as $linkName => $arr) {
            $module_orig = $arr['module_orig'];
            $link_def = $link_defs[$linkName] ?? [];
            
            $label = $link_def['vname'] ?? '';
            $rel_text = trim(translate($label, $module_orig));
            if ($label == $rel_text || empty($rel_text)) {
                $rel_text = trim(translate($label, $moduleName));
            }
            $result[$linkName]['text'] = $rel_text;
        }  

        // Sort relationships by text
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
        $seen_relationships = [];
        
        foreach($availableModules as $moduleName => $moduleInfo) {
            $module = BeanFactory::newBean($moduleName);
            if (!$module) continue;
            
            $link_defs = [];
            
            foreach ($module->field_defs as $fieldName => $arr) {
                if (!isset($arr['type'])) continue;

                if ($arr['type'] == 'link') {
                    $relName = $arr['relationship'] ?? '';
                    if (!empty($relName) && isset($seen_relationships[$relName])) continue;

                    $link_defs[$fieldName] = $arr;
                    $destModule = $arr['module'] ?? '';
                    
                    if (empty($destModule) && $module->load_relationship($fieldName)) {
                        $destModule = $module->$fieldName->getRelatedModuleName();
                    }

                    if (!empty($destModule) && isset($availableModules[$destModule]) && $destModule !== 'EmailAddress') {
                        if (!isset($result[$fieldName])) {
                            if (!empty($relName)) $seen_relationships[$relName] = true;
                            $result[$fieldName] = [
                                'name' => $fieldName,
                                'text' => '',
                                'module_orig' => $moduleName,
                                'field_orig' => $fieldName,
                                'relationship' => $relName,
                                'module_dest' => $destModule
                            ];
                        }
                    }
                    continue;
                }
            
                if ($arr['type'] == 'relate' && isset($arr['link'])) {
                    $linkName = $arr['link'];
                    $relName = $module->field_defs[$linkName]['relationship'] ?? '';

                    if (isset($result[$linkName])) {
                        $result[$linkName]['field_orig'] = $fieldName;
                        continue;
                    }

                    if (!empty($relName) && isset($seen_relationships[$relName])) continue;

                    $destModule = $arr['module'] ?? '';
                    if (empty($destModule) && isset($module->field_defs[$linkName]['module'])) {
                        $destModule = $module->field_defs[$linkName]['module'];
                    } elseif (empty($destModule) && $module->load_relationship($linkName)) {
                        $destModule = $module->$linkName->getRelatedModuleName();
                    }

                    if (empty($destModule) || $destModule == 'EmailAddress' || !isset($availableModules[$destModule])) continue;
            
                    if (!isset($result[$linkName])) {
                        if (!empty($relName)) $seen_relationships[$relName] = true;
                        $result[$linkName] = [
                            'name' => $linkName,
                            'text' => '',
                            'module_orig' => $moduleName,
                            'field_orig' => $fieldName,
                            'relationship' => $relName,
                            'module_dest' => $destModule
                        ];
                    }
                }
            }
            
            foreach ($result as $linkName => $arr) {
                if (!isset($link_defs[$linkName]) || $arr['module_orig'] !== $moduleName) continue;
                $link_def = $link_defs[$linkName];
                $result[$linkName]['text'] = trim(translate($link_def['vname'] ?? '', $moduleName));
            }
        }

        // Sort relationships by text
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
        global $app_list_strings;

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
            'SecurityGroups', 'stic_Security_Groups_Rules',
            'Spots',
            'Surveys',
            'stic_Sepe_Actions', 'stic_Sepe_Files', 'stic_Sepe_Incidents',
            'stic_Signatures', 'stic_Signature_Log', 'stic_Signers',
            'stic_Messages',
            'stic_Validation_Actions', 'stic_Validation_Results',
            'stic_AWF_Forms',
            'stic_Settings',
        ];

        // Get Enabled Modules
        require_once("modules/MySettings/TabController.php");
        $controller = new TabController();
        $tabs = $controller->get_tabs_system();
        
        $enabled = [];
        foreach ($tabs[0] as $key=>$value) {
            if (in_array($key, $blackList)) {
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
