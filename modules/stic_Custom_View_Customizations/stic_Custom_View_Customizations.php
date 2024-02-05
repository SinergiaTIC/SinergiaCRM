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

class stic_Custom_View_Customizations extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_Custom_View_Customizations';
    public $object_name = 'stic_Custom_View_Customizations';
    public $table_name = 'stic_custom_view_customizations';
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
    public $SecurityGroups;
    public $customization_order;
    public $init;
	
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

    }

    public function save($check_notify = false)
    {
        require_once("modules/stic_Custom_View_Customizations/Utils.php");

        $return_id = parent::save($check_notify);
        $customizationBean = BeanFactory::getBean('stic_Custom_View_Customizations', $this->id);
        $viewBean = getCustomView($customizationBean);

        require_once('modules/stic_Custom_View_Conditions/stic_Custom_View_Conditions.php');
        $condition = BeanFactory::newBean('stic_Custom_View_Conditions');
        $condition->save_lines($_POST, $viewBean->view_module, $this, 'sticCustomView_Condition');

        require_once('modules/stic_Custom_View_Actions/stic_Custom_View_Actions.php');
        $action = BeanFactory::newBean('stic_Custom_View_Actions');
        $action->save_lines($_POST, $viewBean->view_module, $this, 'sticCustomView_Action');

        // Set Conditions field
        $conditionBeanArray = SticUtils::getRelatedBeanObjectArray($customizationBean, 'stic_custom_view_customizations_stic_custom_view_conditions');
        $conditions = array();
        foreach ($conditionBeanArray as $conditionBean) {
            $conditions[] = $conditionBean->field . " " . $conditionBean->operator . " " . $conditionBean->value;
        //     // if ($customizationBean->id != $bean->id && 
        //     //     $customizationBean->init == $bean->init &&
        //     //     $customizationBean->customization_order == $bean->customization_order) {
        //     //         $customizationBean->customization_order = $customizationBean->customization_order + 1;
        //     //         $customizationBean->save();
        //     //     }
        }

        // Set Actions field

        return $return_id;
    }

}