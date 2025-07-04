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

    public function action_getRelationships()
    {

        var_dump($this->getModuleRelationships($_REQUEST['getmodule']));
        die();
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
     * @return array An associative array containing module relationships and field options.
     */
    public function getModuleRelationships($moduleName)
    {
        global $beanList;

        $options_array = ['' => ''];
        $mod_options_array = [];

        // Validate module existence
        if (!isset($beanList[$moduleName])) {
            return [];
        }

        // Instantiate the module
        $module = new $beanList[$moduleName]();

        // Process main module's fields
        foreach ($module->field_defs as $name => $arr) {
            if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || (isset($arr['type']) && $arr['type'] == 'id') || (isset($arr['type']) && $arr['type'] == 'link'))) {
                if (!isset($arr['reportable']) || $arr['reportable']) {
                    $options_array['$' . $module->table_name . ':' . $name] = translate($arr['vname'] ?? '', $module->module_dir);
                }
            }
        }

        $options = $options_array;
        $mod_options_array[$module->module_dir] = translate('LBL_MODULE_NAME', $module->module_dir);
        $firstOptions = $options;

        $fmod_options_array = [];

        // Process related module fields
        foreach ($module->field_defs as $module_name => $module_arr) {
            if (isset($module_arr['type']) && $module_arr['type'] == 'relate' && isset($module_arr['source']) && $module_arr['source'] == 'non-db') {
                $options_array = ['' => ''];

                if (isset($module_arr['module']) && $module_arr['module'] != '' && $module_arr['module'] != 'EmailAddress') {
                    $relate_module_class_name = $beanList[$module_arr['module']];

                    if (!is_null($relate_module_class_name)) {
                        $relate_module = new $relate_module_class_name();

                        foreach ($relate_module->field_defs as $relate_name => $relate_arr) {
                            if (!((isset($relate_arr['dbType']) && strtolower($relate_arr['dbType']) == 'id') || $relate_arr['type'] == 'id' || $relate_arr['type'] == 'link')) {
                                if ((!isset($relate_arr['reportable']) || $relate_arr['reportable']) && isset($relate_arr['vname'])) {
                                    $options_array['$' . $module_arr['name'] . ':' . $relate_name] = translate($relate_arr['vname'], $relate_module->module_dir);
                                }
                            }
                        }
                    }

                    $options = $options_array;

                    if ($module_arr['vname'] != 'LBL_DELETED') {
                        $options_array['$' . $module->table_name . ':' . $name] = translate($module_arr['vname'], $module->module_dir);
                        $fmod_options_array[$module_arr['vname']] = translate($relate_module->module_dir) . ' : ' . translate($module_arr['vname'], $module->module_dir);
                    }
                }
            }
        }

        // Finalize and sort module options
        array_multisort($fmod_options_array, SORT_ASC, $fmod_options_array);
        $mod_options_array = array_merge($mod_options_array, $fmod_options_array);

        $module_options = $mod_options_array;

        // Prepare the final output structure
        $moduleOptions[$moduleName] = ["module" => $module_options, "option" => $firstOptions];

        return $moduleOptions;
    }
}
