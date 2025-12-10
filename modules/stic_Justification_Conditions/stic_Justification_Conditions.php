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

class stic_Justification_Conditions extends Basic
{
    public $module_dir = 'stic_Justification_Conditions';
    public $object_name = 'stic_Justification_Conditions';
    public $table_name = 'stic_justification_conditions';
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
        $allocationsModStrings = return_module_language($current_language, 'stic_Allocations'); // can not be $mod_strings because of different contexts (specially inline edition)


        $this->fillName();

        $tempFetchedRow = $this->fetched_row ?? null;

        // Save the bean
        parent::save($check_notify);
    }

        /**
     * Auto-generate the name field based on allocation type and date
     */
    protected function fillName() {
        // Auto name - concatenate allocation type and date
        if (empty($this->name)) {
            global $app_list_strings;

            $nameParts = array();

            // Add related opportunity name if available
            $opportunityBean = BeanFactory::getBean('Opportunities', $this->opportunit378funities_ida);
            if ($opportunityBean){
                $nameParts[] = $opportunityBean->name;
            }

            // Add ledger group if available
            if (!empty($this->ledger_group) && isset($app_list_strings['stic_ledger_groups_list'][$this->ledger_group])) {
                $nameParts[] = $app_list_strings['stic_ledger_groups_list'][$this->ledger_group];
            }

            // Add subgroup if available
            if (!empty($this->subgroup) && isset($app_list_strings['stic_ledger_subgroups_list'][$this->subgroup])) {
                $nameParts[] = $app_list_strings['stic_ledger_subgroups_list'][$this->subgroup];
            }

            // Add allocation type if available
            if (!empty($this->allocation_type) && isset($app_list_strings['stic_allocations_types_list'][$this->allocation_type])) {
                $nameParts[] = $app_list_strings['stic_allocations_types_list'][$this->allocation_type];
            }

            // Set the name by joining parts with a hyphen
            $this->name = implode(' - ', $nameParts);
        }
    }


}