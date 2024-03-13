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

class stic_Custom_Views extends Basic
{
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
    public $view_module;
    public $user_type;
    public $roles;
    public $security_groups;

    public $show_SubPanelTopButtonListView = false;
	
    public function bean_implements($interface)
    {
        switch($interface)
        {
            case 'ACL':
                return true;
        }

        return false;
    }
	

    public function __construct()
    {
        parent::__construct();

        require_once('modules/stic_Custom_Views/Utils.php');
        fillDynamicGenericLists();
    }

    // public function save($check_notify = false){
    //     if (($_POST["duplicateSave"] && $_POST["duplicateSave"]="true")) {
    //          //IEPA!!
    //          // Veure modules/Surveys/Surveys.php
    //     }
    // }

    // private function saveOptions(array $options, array $ids, array $deleted, $questionId) {
    //     if (($_POST["duplicateSave"] && $_POST["duplicateSave"]="true"))
    //     {
    //         unset($_REQUEST['survey_questions_ids']);
    //     }
    // }
}