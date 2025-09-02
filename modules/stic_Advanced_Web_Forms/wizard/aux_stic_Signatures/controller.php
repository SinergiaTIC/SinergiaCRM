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
class stic_SignaturesController extends SugarController
{

    /**
     * Handles the 'getRelationships' action to retrieve module relationships and fields.
     */
    public function action_getRelationships()
    {
        // Ensure no debug output interferes with JSON response.
        // The die() is handled within getModuleRelationships for JSON format.
        $this->getModuleRelationships($_REQUEST['getmodule'], $_REQUEST['format'] ?? 'json'); 
    }

    /**
     * Retrieves the relationships and fields for a given SuiteCRM module.
     *
     * This function fetches a module's own reportable fields and also
     * identifies and lists reportable fields from related modules.
     * The field names are formatted as '$[table_name]:[field_name]' or
     * '$[relationship_name]:[field_name]' for clarity in reporting or display.
     *
     * @param string $moduleName The name of the module (e.g., 'Accounts', 'Contacts').
     * @param string $format The format of the output, either 'raw' or 'json'.
     * @return array|string An associative array or JSON string containing module relationships and field options.
     */
    public function getModuleRelationships($moduleName, $format = 'raw')
    {
        global $beanList;

        // Initialize arrays for options
        $options_array = ['' => '']; // For module's own fields
        $mod_options_array = []; // For modules, including the main module and direct relations
        $fmod_options_array = []; // For related modules (children in tree)

        // Validate module existence
        if (!isset($beanList[$moduleName])) {
            if ($format == 'json') {
                echo json_encode(["module" => [], "option" => []]);
                die(); // Terminate execution after JSON output
            }
            return []; 
        }

        // Instantiate the module bean
        $module = new $beanList[$moduleName]();

        // Process main module's own reportable fields
        foreach ($module->field_defs as $name => $arr) {
            // Exclude ID and link type fields
            if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || (isset($arr['type']) && $arr['type'] == 'id') || (isset($arr['type']) && $arr['type'] == 'link'))) {
                // Include only reportable fields (or if reportable is not set, assume true)
                if (!isset($arr['reportable']) || $arr['reportable']) {
                    $options_array['$' . $module->table_name . ':' . $name] = translate($arr['vname'] ?? '', $module->module_dir);
                }
            }
        }

        // Assign processed fields as main module options
        $firstOptions = $options_array; 

        // Add the main module itself to the module list (if required for selection/display)
        // $mod_options_array[$module->module_dir] = translate('LBL_MODULE_NAME', $module->module_dir); 
        
        // Process related modules' fields (for hierarchical display in JS tree)
        foreach ($module->field_defs as $field_name => $field_def) { // Renamed $module_name to $field_name for clarity
            // Check for 'relate' type fields with 'non-db' source, which represent relationships
            if (isset($field_def['type']) && $field_def['type'] == 'relate' && isset($field_def['source']) && $field_def['source'] == 'non-db') {
                // Ensure the related module exists and is not 'EmailAddress'
                if (isset($field_def['module']) && $field_def['module'] != '' && $field_def['module'] != 'EmailAddress') {
                    $relate_module_class_name = $beanList[$field_def['module']];

                    if (!is_null($relate_module_class_name)) {
                        $relate_module = new $relate_module_class_name();

                        // Construct the module path for the related module (e.g., 'Accounts:contact_id')
                        $module_path = $field_def['module'] . ':' . ($field_def['link'] ?? $field_def['id_name']). ':' .time();
                        // Translate the related module's display name
                        $fmod_options_array[$module_path] = translate($relate_module->module_dir) . ' : ' . translate($field_def['vname'], $module->module_dir);
                    }
                }
            }
        }

        // Sort the related module options alphabetically
        array_multisort($fmod_options_array, SORT_ASC, $fmod_options_array);
        
        // Merge main module and related modules into a single list
        // This array will be used for the 'module' key in the JSON response
        // Note: $mod_options_array initially contains only the main module itself.
        // It will now contain the main module AND its direct relations.
        $mod_options_array = array_merge($mod_options_array, $fmod_options_array); 

        $module_options = $mod_options_array; 

        // Prepare the final output structure as an associative array
        $moduleOptions = ["module" => $module_options, "option" => $firstOptions];

        // Output as JSON or return raw array based on format request
        if ($format == 'json') {
            echo json_encode($moduleOptions);
            die(); // Terminate execution after JSON output
        } else {
            return $moduleOptions;
        }
    }
}