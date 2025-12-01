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

class stic_Justifications extends Basic
{
    public $module_dir = 'stic_Justifications';
    public $object_name = 'stic_Justifications';
    public $table_name = 'stic_justifications';
    public $new_schema = true;
    
    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $created_by;
    public $description;
    public $deleted;
    public $assigned_user_id;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function save($check_notify = false)
    {
        global $current_language;
        $justificationsModStrings = return_module_language($current_language, 'stic_Justifications'); // can not be $mod_strings because of different contexts (specially inline edition)     

        $this->fillName();

        // Save the bean
        parent::save($check_notify);

    }

        /**
         * Fill the name field with a concatenation of other fields values
         */
    private function fillName()
    {
        // get Allocation
        $allocation = BeanFactory::getBean('stic_Allocations', $this->stic_alloc8c71cations_ida);
        if ($allocation) {
            $this->name = $allocation->name;
        } else {
            $this->name = $this->date_entered;
        }
    }


}