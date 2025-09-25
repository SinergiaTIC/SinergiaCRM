<?php

/**
 * Retrieves fields and relationships of given Module to given SuiteCRM modules.
 * Result: [name, text, textSingular, inStudio, icon, fields:[Field], relationships:[Relationship]]
 *   Field: {
 *     name, text, type, required, options, inViews
 *   }
 *   Relationship: {
 *     name, text, module_orig, field_orig, relationship, module_dest
 *   }
 */
function getModuleInformation($moduleName, $availableModules){
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
    foreach($moduleScanList as $moduleScan) {
        foreach($moduleScan['availableOrig'] as $moduleOrigName => $moduleOrigInfo) {
            $objectOrig = BeanFactory::getObjectName($moduleOrigName);
            VardefManager::loadVardef($moduleOrigName, $objectOrig);
            $fieldDefs = $dictionary[$objectOrig]['fields'] ?? [];
            foreach ($fieldDefs as $fieldName => $arr) {
                if ($moduleOrigName == $moduleName && isset($result['fields'][$fieldName])) {
                    continue;
                }
                if (!isset($arr['type'])) {
                    continue;
                }
                // Set link defs to process later
                if ($arr['type'] == 'link') {
                    $link_defs[$fieldName] = $arr;
                    continue;
                }

                // Exclude ID type fields
                if ($arr['type'] == 'id' || (isset($arr['dbType']) && strtolower($arr['dbType']) == 'id')) {
                    continue;
                }
                // Exclude currency, date_entered, date_modified, modified_user, created_by, deleted fields
                if ($fieldName === 'currency_name' || $fieldName === 'currency_symbol' ||
                    $fieldName === 'date_entered' || $fieldName === 'date_modified' ||
                    $fieldName === 'modified_user_id' || $fieldName === 'modified_by_name' ||
                    $fieldName === 'created_by' || $fieldName === 'created_by_name' ||
                    $fieldName === 'deleted') {
                    continue;
                }
                // Exclude non procesable field types
                // Tipus especials o widgets UI: html, iframe, image, file, phone, email, url, address, name, fullname, …
                if ($arr['type'] == "html" || $arr['type'] == "iframe" || $arr['type'] == "image" || 
                    $arr['type'] == "file" || $arr['type'] == "attachment" || $arr['type'] == "address" || $arr['type'] == "wysiwyg" || 
                    $arr['type'] == "parent" || $arr['type'] == "parent_type" || 
                    $arr['type'] == "team_id" || $arr['type'] == "team_set_id" || $arr['type'] == "team_list" || $arr['type'] == "team_count" || 
                    $arr['type'] == "wysiwyg") {
                    continue;
                }

                // In source Module: Set fields information
                if ($moduleOrigName == $moduleName) {
                    // Add field information
                    // name, text, type, required, options, inViews
                    $result['fields'][$fieldName] = [
                        'name' => $fieldName,
                        'text' => rtrim(trim(translate($arr['vname'] ?? '', $moduleOrigName)), ":"),
                        'type' => $arr['type'],
                        'required' => isset($arr['required']) && $arr['required'],
                        'options' => $arr['options'] ?? '',
                        'inViews' => false
                    ];

                }

                // Relationships: type:'relate' with 'non-db' and 'link' and is set 'module' as available (dest) module
                if ($arr['type'] != 'relate' || 
                    !isset($arr['source']) || $arr['source'] != 'non-db' || !isset($arr['link']) ||
                    !isset($arr['module']) || !isset($arr['module']) || !isset($moduleScan['availableDest'][$arr['module']])) {
                    continue;
                }
                // Ignore EmailAddress relationships
                if ($arr['module'] == 'EmailAddress') {
                    continue;
                }

                // Add relationship information (if not set yet)
                if (!isset($result['relationships'][$arr['link']])) {
                    // { name, text, module_orig, field_orig, relationship, module_dest }
                    $result['relationships'][$arr['link']] = [
                        'name' => $arr['link'],
                        'text' => '',
                        'module_orig' => $moduleOrigName,
                        'field_orig' => $fieldName,
                        'relationship' => '',
                        'module_dest' => $arr['module']
                    ];
                }
            }
        }
    }

    // Complete Relationship information finding Links
    foreach ($result['relationships'] as $linkName => $arr) {
        if (!isset($link_defs[$linkName])) {
            continue;
        }
        $link_def = $link_defs[$linkName];

        // { name, text, module_orig, field_orig, relationship, module_dest }
        $module_orig = $result['relationships'][$linkName]['module_orig'];
        $module_dest = $result['relationships'][$linkName]['module_dest'];

        // Set relationship information
        $label = $link_def['vname'] ?? '';
        $rel_text = rtrim(trim(translate($label, $module_orig)), ":");
        if ($label == $rel_text) {
            $rel_text = rtrim(trim(translate($label, $module_dest)), ":");
        }
        // Fix relationship text
        $module_text = trim(translate($moduleName));
        $module_singularText = $module_text;
        if(isset($app_list_strings['moduleListSingular'][$moduleName])) {
            $module_singularText = trim($app_list_strings['moduleListSingular'][$moduleName]);
        }
        $otherModule = ($moduleName == $module_orig) ? $module_dest : $module_orig;
        $otherModule_text = trim(translate($otherModule));
        $otherModule_singularText = $otherModule_text; 
        if(isset($app_list_strings['moduleListSingular'][$otherModule])) {
            $otherModule_singularText = trim($app_list_strings['moduleListSingular'][$otherModule]);
        }
        if (strtolower(($rel_text)) == strtolower($module_text) ||
            strtolower(($rel_text)) == strtolower($module_singularText) ||
            strtolower(($rel_text)) == strtolower($otherModule_text) ) {
            $rel_text = $otherModule_singularText;
        }
       
        $result['relationships'][$linkName]['text'] = $rel_text;
        $result['relationships'][$linkName]['relationship'] = $link_def['relationship'] ?? '';

        if ($module_orig == $moduleName) {
            // Set field information: Options with the linkName
            $field_name = $result['relationships'][$linkName]['field_orig'];
            $result['fields'][$field_name]['options'] = $linkName;
        }
    }

    // Remove relation fields to unavailable modules
    $fieldsToRemove = [];
    foreach ($result['fields'] as $fieldName => $arr) {
        if ($arr['type'] == 'relate' && !isset($link_defs[$arr['options']])) {
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
 * Retrieves the relationships of given Module to given SuiteCRM modules.
 * Result: [Relationship]
 * Relationship: {
 *   name, text, module_orig, field_orig, relationship, module_dest
 * }
 */
function getRelationships($moduleName, $availableModules){
    global $beanList;

    $result = [];
    $moduleScanList = [
        // All relations from module to availableModules
        ['availableOrig' => [$moduleName => $moduleName], 'availableDest' => $availableModules],
        // All relations from availableModules to module
        ['availableOrig' => $availableModules, 'availableDest' => [$moduleName => $moduleName]]
    ];

    $link_defs = [];
    foreach($moduleScanList as $moduleScan) {
        foreach($moduleScan['availableOrig'] as $moduleOrigName => $moduleOrigInfo) {
            if (!isset($beanList[$moduleOrigName])) {
                continue;
            }
            $moduleOrig = new $beanList[$moduleOrigName]();
            foreach ($moduleOrig->field_defs as $fieldName => $arr) {
                if (!isset($arr['type'])) {
                    continue;
                }
                // Set link defs to process later
                if ($arr['type'] == 'link') {
                    $link_defs[$fieldName] = $arr;
                    continue;
                }
                // Relationships: type:'relate' with 'non-db' and 'link' and is set 'module' as available (dest) module
                if ($arr['type'] != 'relate' || 
                    !isset($arr['source']) || $arr['source'] != 'non-db' || !isset($arr['link']) ||
                    !isset($arr['module']) || !isset($arr['module']) || !isset($moduleScan['availableDest'][$arr['module']])) {
                    continue;
                }
                // Ignore EmailAddress relationships
                if ($arr['module'] == 'EmailAddress') {
                    continue;
                }

                // Add relationship information (if not set yet)
                if (!isset($result[$arr['link']])) {
                    // { name, text, module_orig, field_orig, relationship, module_dest }
                    $result[$arr['link']] = [
                        'name' => $arr['link'],
                        'text' => '',
                        'module_orig' => $moduleOrigName,
                        'field_orig' => $fieldName,
                        'relationship' => '',
                        'module_dest' => $arr['module']
                    ];
                }
            }
        }
    }
    // Complete Relationship information finding Links
    foreach ($result as $linkName => $arr) {
        if (!isset($link_defs[$linkName])) {
            continue;
        }
        $link_def = $link_defs[$linkName];
        // Set relationship information
        // { name, text, module_orig, field_orig, relationship, module_dest }
        $module_orig = $result[$linkName]['module_orig'];
        $result[$linkName]['text'] = trim(translate($link_def['vname'] ?? '', $module_orig));
        $result[$linkName]['text'] = trim(translate($link_def['vname'] ?? '', $moduleName));
        $result[$linkName]['relationship'] = $link_def['relationship'] ?? '';
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
function getRelationshipsBetween($availableModules)
{
    global $beanList;

    $result = [];
    foreach($availableModules as $moduleName => $moduleInfo) {
        if (!isset($beanList[$moduleName])) {
            continue;
        }
        $module = new $beanList[$moduleName]();
        $link_defs = [];
        foreach ($module->field_defs as $fieldName => $arr) {
            if (!isset($arr['type'])) {
                continue;
            }
    
            // Set link defs to process later
            if ($arr['type'] == 'link') {
                $link_defs[$fieldName] = $arr;
                continue;
            }
         
            // Relationships: type:'relate' with 'non-db' and 'link' and is set 'module' as available module
            if ($arr['type'] != 'relate' || 
                !isset($arr['source']) || $arr['source'] != 'non-db' || !isset($arr['link']) ||
                !isset($arr['module']) || !isset($arr['module']) || !isset($availableModules[$arr['module']])) {
                continue;
            }

            // Ignore EmailAddress relationships
            if ($arr['module'] == 'EmailAddress') {
                continue;
            }
    
            // Add relationship information
            // { name, text, module_orig, field_orig, relationship, module_dest }
            $result[$arr['link']] = [
                'name' => $arr['link'],
                'text' => '',
                'module_orig' => $moduleName,
                'field_orig' => $fieldName,
                'relationship' => '',
                'module_dest' => $arr['module']
            ];
        }
        // Complete relationship information: Field
        foreach ($result as $linkName => $arr) {
            if (!isset($link_defs[$linkName])) {
                continue;
            }
            $link_def = $link_defs[$linkName];
            $result[$linkName]['text'] = trim(translate($link_def['vname'] ?? '', $moduleName));
            $result[$linkName]['relationship'] = $link_def['relationship'] ?? '';
        }
    }

    // Sort relationships by text
    uasort($result, function($a, $b) {
        return strcmp($a['text'], $b['text']);
    });

    return $result;
}

/**
 * Get all modules enabled in Administration
 * Result: [EnabedModule]
 * EnabledModule: {
 *   name, text, textSingular, inStudio, icon
 * }
 */
function getEnabledModules()
{
    global $app_list_strings;

    // Get Enabled Modules
    require_once("modules/MySettings/TabController.php");
    $controller = new TabController();
    $tabs = $controller->get_tabs_system();
    
    $enabled = [];
    foreach ($tabs[0] as $key=>$value) {
        $text = translate($key);
        $textSingular = $app_list_strings['moduleListSingular'][$key] ?? $text;
        $enabled[$key] = ["name" => $key, "text" => $text, "textSingular" => $textSingular, "inStudio" => false, "icon" => ""];
    }

    if (!isset($enabled["Users"])) {
        $key = "Users";
        $text = translate($key);
        $textSingular = $app_list_strings['moduleListSingular'][$key] ?? $text;
        $enabled[$key] = ["name" => $key, "text" => $text, "textSingular" => $textSingular, "inStudio" => false, "icon" => ""];
    }

    // Fill inStudio information
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

