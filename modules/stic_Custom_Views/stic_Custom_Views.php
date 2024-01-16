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
    public $disable_row_level_security = true;

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
     */
    public function __construct()
    {
        parent::__construct();
        require_once('modules/stic_Custom_Views/Utils.php');

        $this->fill_stic_custom_views_moduleList();
        $this->fill_stic_custom_views_roleList();
        $this->fill_stic_custom_views_securityGroupList();
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
    public function fill_stic_custom_views_moduleList()
    {
        if(isset($GLOBALS ['app_list_strings']['stic_custom_views_moduleList'])) {
            return;
        }

        // Remove studio file to prevent StackOverflow (StudioBrowser will crete a new instance of every module with studio.php)
        $renamed_own_studio_file = false;
        if (file_exists('modules/' . $this->module_dir . '/metadata/studio.php')){
            rename('modules/' . $this->module_dir . '/metadata/studio.php', 'modules/' . $this->module_dir . '/metadata/studio.php.bck');
            $renamed_own_studio_file = true;
        }

        require_once('modules/ModuleBuilder/Module/StudioBrowser.php') ;
        $sb = new StudioBrowser();
        $nodes = $sb->getNodes();
        
        $stic_custom_views_moduleList = array();
        foreach ($nodes as $module) {
            $stic_custom_views_moduleList[$module['module']] = $module['name'];
        }
        $GLOBALS ['app_list_strings']['stic_custom_views_moduleList'] = $stic_custom_views_moduleList;

        // Restore studio file
        if($renamed_own_studio_file) {
            rename('modules/' . $this->module_dir . '/metadata/studio.php.bck', 'modules/' . $this->module_dir . '/metadata/studio.php');
        }
    }

    public function fill_stic_custom_views_roleList()
    {
        if(isset($GLOBALS ['app_list_strings']['stic_custom_views_roleList'])) {
            return;
        }
        
        $rolFocus = BeanFactory::newBean('ACLRoles');
        $roles = $rolFocus->get_list("name", "", 0, -99, -99);
        
        $stic_custom_views_roleList = array("" => "");
        foreach ($roles['list'] as $role) {
            $stic_custom_views_roleList[$role->id] = $role->name;
        }

        $GLOBALS ['app_list_strings']['stic_custom_views_roleList'] = $stic_custom_views_roleList;
    }

    public function fill_stic_custom_views_securityGroupList()
    {
        if(isset($GLOBALS ['app_list_strings']['stic_custom_views_securityGroupList'])) {
            return;
        }
        
        $groupFocus = BeanFactory::newBean('SecurityGroups');
        $groups = $groupFocus->get_list("name", "", 0, -99, -99);
        
        $stic_custom_views_securityGroupList = array("" => "");
        foreach ($groups['list'] as $group) {
            $stic_custom_views_securityGroupList[$group->id] = $group->name;
        }

        $GLOBALS ['app_list_strings']['stic_custom_views_securityGroupList'] = $stic_custom_views_securityGroupList;
    }
}
