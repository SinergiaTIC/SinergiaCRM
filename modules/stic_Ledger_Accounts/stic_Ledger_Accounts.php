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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

#[\AllowDynamicProperties]
class stic_Ledger_Accounts extends Basic
{
    public $module_dir = 'stic_Ledger_Accounts';
    public $object_name = 'stic_Ledger_Accounts';
    public $table_name = 'stic_ledger_accounts';
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

    /**
     * Override the bean's save function to auto-generate the name field
     *
     * @param boolean $check_notify
     * @return void
     */
    public function save($check_notify = false)
    {
        $this->fillName();

        // Save the bean
        parent::save($check_notify);
    }

    /**
     * Auto-generate the name field based on dropdown selections
     */
    protected function fillName()
    {
        // Auto name - concatenate dropdown values with hyphens
        if (empty($this->name)) {
            global $app_list_strings;
            
            $nameParts = array();
            
            // // Add group if available
            // if (!empty($this->group) && isset($app_list_strings['stic_ledger_groups_list'][$this->group])) {
            //     $nameParts[] = $app_list_strings['stic_ledger_groups_list'][$this->group];
            // }
            
            // // Add subgroup if available
            // if (!empty($this->subgroup) && isset($app_list_strings['stic_ledger_subgroups_list'][$this->subgroup])) {
            //     $nameParts[] = $app_list_strings['stic_ledger_subgroups_list'][$this->subgroup];
            // }
            
            // // Add account if available
            // if (!empty($this->account) && isset($app_list_strings['stic_ledger_accounts_list'][$this->account])) {
            //     $nameParts[] = $app_list_strings['stic_ledger_accounts_list'][$this->account];
            // }
            
            // Add subaccount if available
            if (!empty($this->subaccount) && isset($app_list_strings['stic_ledger_subaccounts_list'][$this->subaccount])) {
                $nameParts[] = $app_list_strings['stic_ledger_subaccounts_list'][$this->subaccount];
            }
            else {
                $nameParts[] = $app_list_strings['stic_ledger_accounts_list'][$this->account];
            }
            
            // Join with hyphens if we have parts, otherwise use a default name
            if (!empty($nameParts)) {
                $this->name = implode(' - ', $nameParts);
            } else {
                $this->name = 'Ledger Account';
            }
        }
    }
}