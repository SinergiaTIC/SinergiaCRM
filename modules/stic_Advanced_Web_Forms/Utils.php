<?php
/**
 * Retrieves the relationships and fields for a given SuiteCRM module.
 * This function fetches a module's own fields and also identifies and lists fields 
 *  Result: [
 *      name, 
 *      text, 
 *      fields: [name, text, type, required, inViews],
 *      relationships: [name, text, fieldName, relationship, moduleName, moduleText]
 *  ]
 * @param string $moduleName The name of the module (e.g., 'Accounts', 'Contacts').
 * @return array An associative array or JSON string containing module relationships and field options.
 */
function getModuleInformation($moduleName)
{
    global $beanList, $app_list_strings;

    /*
        Result: name => [ name, text, 
                          fields: name => [name, text, type, required, options, inViews],
                          relationships: name => [name, text, fieldName, relationship, moduleName, moduleText]
                        ]
    */
    $result_array = [
        'name' => $moduleName,
        'text' => '',
        'fields' => [],
        'relationships' => [],
    ];

    // Validate module existence
    if (!isset($beanList[$moduleName]) || !isset($app_list_strings['moduleList'][$moduleName])) {
        return $result_array;
    }
    
    $result_array['text']= $app_list_strings['moduleList'][$moduleName];
    $module = new $beanList[$moduleName]();
    $link_defs = [];
    foreach ($module->field_defs as $name => $arr) {
        if (!isset($arr['type'])) {
            continue;
        }

        // Set link defs to process later
        if ($arr['type'] == 'link') {
            $link_defs[$name] = $arr;
            continue;
        }

        // Exclude ID type fields
        if ($arr['type'] == 'id' || (isset($arr['dbType']) && strtolower($arr['dbType']) == 'id')) {
            continue;
        }
 
        // Exclude currency, date_entered, date_modified, modified_user, created_by, deleted fields
        if ($name === 'currency_name' || $name === 'currency_symbol' ||
            $name === 'date_entered' || $name === 'date_modified' ||
            $name === 'modified_user_id' || $name === 'modified_by_name' ||
            $name === 'created_by' || $name === 'created_by_name' ||
            $name === 'deleted') {
            continue;
        }

        // Exclude non procesable field types
        if ($arr['type'] == "wysiwyg" || $arr['type'] == "iframe" || $arr['type'] == "image" || $arr['type'] == "parent") {
            continue;
        }

        // Add field information
        $result_array['fields'][$name] = [
            'name' => $name,
            'text' => rtrim(translate($arr['vname'] ?? '', $moduleName)),
            'type' => $arr['type'],
            'required' => isset($arr['required']) && $arr['required'],
            'options' => $arr['options'] ?? '',
            'inViews' => false
        ];

        // Relationships: type:'relate' with 'non-db' and exists 'module' and 'link'
        if (!$arr['type'] == 'relate' || 
            !isset($arr['source']) || $arr['source'] != 'non-db' ||
            !isset($arr['module']) || !isset($arr['module']) ||
            !isset($arr['link'])) {
            continue;
        }

        $relate_module_name = $arr['module'];
        // Validate related module existence
        if (!isset($beanList[$relate_module_name]) || !isset($app_list_strings['moduleList'][$relate_module_name])) {
            continue;
        }

        // Ignore EmailAddress relationships
        if ($relate_module_name == 'EmailAddress') {
            continue;
        }

        // Add relationship information        
        $result_array['relationships'][$arr['link']] = [
            'name' => $arr['link'],
            'text' => '',
            'fieldName' => $name,
            'relationship' => '',
            'moduleName' => $relate_module_name,
            'moduleText' => $app_list_strings['moduleList'][$relate_module_name],
            // 'subpanelName' => '',
            // 'subpanelText' => '',
        ];
    }

    // Complete relationship information: Field
    foreach ($result_array['relationships'] as $rel_name => $rel_arr) {
        if (!isset($link_defs[$rel_name])) {
            continue;
        }
        $link_def = $link_defs[$rel_name];
        $result_array['relationships'][$rel_name]['text'] = rtrim(translate($link_def['vname'] ?? '', $moduleName));
        $result_array['relationships'][$rel_name]['relationship'] = $link_def['relationship'] ?? '';

        // Set retationship name as options in field
        $field_name = $result_array['relationships'][$rel_name]['fieldName'];
        $result_array['fields'][$field_name]['options'] = $result_array['relationships'][$rel_name]['name'];
    }

    // // Complete relationship information: Subpanel (in other module)
    // foreach ($result_array['relationships'] as $rel_name => $rel_arr) {
    //     $destModuleName = $rel_arr['moduleName'];

    //     // IEPA!! Peta amb stic_sessions!
    //     if (!file_exists("modules/$destModuleName/metadata/subpaneldefs.php")){
    //         continue;
    //     }
    //     // Load Subpanel definition (module destination)
    //     require_once "modules/$destModuleName/metadata/subpaneldefs.php";

    //     // Load vardefs (module destination)
    //     $destModule = new $beanList[$destModuleName]();
    //     // $destLink_defs = [];
    //     // foreach ($destModule->field_defs as $name => $arr) {

    //     if (!empty($layout_defs[$destModuleName]['subpanel_setup'])) {
    //         foreach ($layout_defs[$destModuleName]['subpanel_setup'] as $subpanelKey => $subpanelDef) {
    //             if (($subpanelDef['module'] ?? '') == $moduleName) {
    //                 $linkField = $subpanelDef['get_subpanel_data'] ?? null;
    //                 if ($linkField && !empty($destModule->field_defs[$linkField])) {
    //                     $result_array['relationships'][$rel_name]['subpanelName'] = $destModule->field_defs[$linkField]['name'];
    //                     $result_array['relationships'][$rel_name]['subpanelText'] = rtrim(translate($destModule->field_defs[$linkField]['vname'] ?? '', $destModuleName));                        
    //                 }
    //             }
    //         }
    //     }      
    // }


    // Complete field info with inViews (is in detailview or editview)
    require_once 'modules/ModuleBuilder/parsers/ParserFactory.php';
    $views = ['detailview', 'editview'];
    foreach($views as $view) {
        $parser = ParserFactory::getParser($view, $moduleName, null);
        foreach ($parser->_viewdefs['panels'] as $panel) {
            foreach ($panel as $row) {
                foreach ($row as $field) {
                    if (isset($result_array['fields'][$field])) {
                        $result_array['fields'][$field]['inViews'] = true;
                    }
                }
            }
        }
    }

    // Sort fields by text
    uasort($result_array['fields'], function($a, $b) {
        return strcmp($a['text'], $b['text']);
    });

    // Sort relationships by text
    uasort($result_array['relationships'], function($a, $b) {
        return strcmp($a['text'], $b['text']);
    });

    return $result_array;
}

/**
 * Get all modules enabled in Administration
 * Result: [name: ModuleName, text: TranslatedModuleName]
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
        $enabled[$key] = ["name" => $key, "text" => $text, "textSingular" => $textSingular];
    }

    if (!isset($enabled["Users"])) {
        $key = "Users";
        $text = translate($key);
        $textSingular = $app_list_strings['moduleListSingular'][$key] ?? $text;
        $enabled[$key] = ["name" => $key, "text" => $text, "textSingular" => $textSingular];
    }

    // Sort modules by text
    uasort($enabled, function($a, $b) {
        return strcmp($a['text'], $b['text']);
    });

    return $enabled;
}

/**
 * Get all Modules that: Are enabled (in Administration) and are visible in Studio
 * Result: [moduleName: [name: ModuleName, icon: StudioIcon, text: TranslatedModuleName]]
 */
function getInitialModules()
{
    global $app_list_strings;

    // Get Enabled Modules
    $enabled = getEnabledModules();

    // Get Modules in Studio
    require_once 'modules/ModuleBuilder/Module/StudioBrowser.php';
    $sb = new StudioBrowser();
    $nodes = $sb->getNodes();
    $modules = [];

    foreach ($nodes as $module) {
        if(isset($enabled[$module['module']])) {
            $name = $module['module'];
            $icon = $module['icon'];
            $text = $module['name'];
            $textSingular = $app_list_strings['moduleListSingular'][$name] ?? $text;

            $modules[$module['module']] = [
                'name' => $name,
                'icon' => $icon,
                'text' => $text,
                'textSingular' => $textSingular,
            ];
        }
    }
    
    // Sort modules by text
    uasort($modules, function($a, $b) {
        return strcmp($a['text'], $b['text']);
    });
    return $modules;
}

