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
    public $importable = true;

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
    public $start_date;
    public $initial_balance;
    public $current_balance;
    public $balance_error;
    public $type;
    public $entity;
    public $holders;
	
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
        global $app_list_strings, $mod_strings;

        // Skipped - Norma 43 import in progress
        if (!empty($_SESSION['norma43_importing'])) {
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Norma 43 import in progress");
            return parent::save($check_notify);
        }

        // Normalize decimal values to prevent truncation
        foreach (['initial_balance', 'current_balance'] as $field) {
            if (isset($this->$field) && !empty($this->$field)) {
                $value = $this->$field;
                if (is_string($value)) {
                    // Replace comma with point for decimal conversion
                    $value = str_replace(',', '.', $value);
                    // Convert to float to preserve precision
                    $this->$field = (float)$value;
                } elseif (!is_float($this->$field) && !is_int($this->$field)) {
                    // Ensure it's a numeric type
                    $this->$field = (float)$value;
                }
            }
        }

        // Check if iban or type have changed
        $ibanChanged = !isset($this->fetched_row['iban']) || ($this->fetched_row['iban'] !== $this->iban);
        $typeChanged = !isset($this->fetched_row['type']) || ($this->fetched_row['type'] !== $this->type);
        
        // Create the name if it's empty or if type or iban have changed
        if (empty($this->name) || $ibanChanged  || $typeChanged){
            $this->name = $app_list_strings['stic_financial_products_types_list'][$this->type] . ' - ' . $this->iban;

            // If IBAN is empty, do not include it in the name but include assigned user
            if (empty($this->iban)) {
            $this->name = $app_list_strings['stic_financial_products_types_list'][$this->type]. ' - ' . $mod_strings['LBL_ASSIGNED_TO_NAME'] . ' "' . $this->assigned_user_name . '"';
            }
        }

        // Call the generic save() function from the SugarBean class
        parent::save();
    }
	
}
