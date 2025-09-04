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


 #[\AllowDynamicProperties] 
class stic_Financial_Products extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_Financial_Products';
    public $object_name = 'stic_Financial_Products';
    public $table_name = 'stic_financial_products';
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
    public $active;
    public $iban;
    public $opening_date;
    public $initial_balance;
    public $current_balance;
    public $balance_error;
    public $product_type;
    public $bank_entity;
    public $bank_account_holders;
	
    public function bean_implements($interface)
    {
        switch($interface)
        {
            case 'ACL':
                return true;
        }

        return false;
    }

    /**
     * Overriding SugarBean save function to insert additional logic:
     * Build the name of the financial product using the type, the iban and the bank
     *
     * @param boolean $check_notify
     * @return void
     */
    public function save($check_notify = false) {
        
        include_once 'SticInclude/Utils.php';
        global $app_list_strings;

        // Create name even when some of the required fields change
        $this->name = $app_list_strings['stic_financial_products_product_types_list'][$this->product_type] . ' - ' . $this->iban . ' - ' . $this->bank_entity;
        
        // Call the generic save() function from the SugarBean class
        parent::save();
    }
	
}
