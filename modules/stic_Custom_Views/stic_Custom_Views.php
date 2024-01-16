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

class stic_Custom_Views extends Basic {
    public $new_schema = true;
    public $module_dir = 'stic_Custom_Views';
    public $object_name = 'stic_Custom_Views';
    public $table_name = 'stic_custom_views';
    public $importable = false;

    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $SecurityGroups;
    public $user_type;
    public $user_profile;
    public $roles;
    public $security_groups;
    public $module;

    public function bean_implements($interface) {
        switch ($interface) {
        case 'ACL':
            return true;
        }

        return false;
    }

    /**
     * stic_Custom_Views constructor.
     * @param bool $init
     */
    public function __construct($init = true)
    {
        parent::__construct();
        if ($init) {
            //$this->fill_moduleList();
            require_once('modules/stic_Custom_Views/Utils.php');
        }
    }
    // public function __construct() {
    //     parent::__construct();

    //     // Overwrite the list of functions to make it available
    //     global $app_list_strings;

    //     $optionList = $this->field_name_map['function']['options'];

    //     // Load the function list
    //     $app_list_strings[$optionList] = DataCheckFunctionFactory::getFunctionListStrings();

    // }

    /**
     * Fills the module list for the module: stic_custom_views_moduleList
     */
    public function fill_moduleList()
    {
        if(isset($app_list_strings['stic_custom_views_moduleList']) || empty($app_list_strings['moduleList'])) {
            return;
        }

        require_once('modules/ModuleBuilder/Module/StudioBrowser.php') ;
        $sb = new StudioBrowser();
        $nodes = $sb->getNodes();
        $app_list_strings['stic_custom_views_moduleList'] = array();

        foreach ($nodes as $module) {
            $app_list_strings['stic_custom_views_moduleList'][$module['module']] = $module['name'];
        }
        //asort($app_list_strings['stic_custom_views_moduleList']);
    }
}
