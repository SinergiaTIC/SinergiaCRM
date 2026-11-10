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

 class stic_Whistleblowing extends Basic
 {
     public $new_schema = true;
     public $module_dir = 'stic_Whistleblowing';
     public $object_name = 'stic_Whistleblowing';
     public $table_name = 'stic_whistleblowing';
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
     public $securitygroups_name;
     public $stic_category;
     public $stic_start_date;
     public $stic_code;
     public $stic_status;
     public $stic_status_detail;
     

     public function __construct() 
     {
         parent::__construct();
         if (class_exists('TrackerManager')) {
             TrackerManager::getInstance()->pause();
         }
     }

     public function bean_implements($interface)
     {
         switch($interface)
         {
             case 'ACL':
                 return true;
         }
 
         return false;
     }
 

     public function ACLAccess($view, $is_owner = 'not_set', $in_group = 'not_set') 
     {
         $view = strtolower($view);
 
         // Bloquegem l'edició i la importació si el registre ja té ID (ja existeix)
         if ($view == 'edit' || $view == 'import') {
             if (!empty($this->id)) {
                //  global $current_user;
                //  if ($current_user->is_admin) {
                //      return true;
                //  }
                 return false;
             }
         }
 
         return parent::ACLAccess($view, $is_owner, $in_group);
     }
 }