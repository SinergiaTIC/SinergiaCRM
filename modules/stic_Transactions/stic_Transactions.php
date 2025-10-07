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

require_once('include/SugarObjects/templates/file/File.php');

class stic_Transactions extends File
{
    public $new_schema = true;
    public $module_dir = 'stic_Transactions';
    public $object_name = 'stic_Transactions';
    public $table_name = 'stic_transactions';
    public $importable = true;

    public $id;
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
    public $document_name;
    public $filename;
    public $file_ext;
    public $file_mime_type;
    public $uploadfile;
    public $active_date;
    public $exp_date;
    public $category_id;
    public $subcategory_id;
    public $status_id;
    public $status;
    public $show_preview;
    public $transaction_date;
    public $type;
    public $amount;
    public $payment_method;
    public $destination_account;
    public $accounting_account;
    public $category;
    public $subcategory;
	
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
     * Build the name of the transaction using the type, the date and the destination account
     *
     * @param boolean $check_notify
     * @return void
     */
    public function save($check_notify = false) {
        
        include_once 'SticInclude/Utils.php';
        global $app_list_strings;

        if(empty($this->description)) {
            $this->document_name = $app_list_strings['stic_payments_transaction_types_list'][$this->type] . ' - ' . $this->transaction_date . ' - ' . $this->amount;
        } elseif (!empty($this->description) && empty($this->name)) {
            $this->document_name = $this->description;
        }

        $this->name = $this->document_name;
        
        // Call the generic save() function from the SugarBean class
        parent::save();
    }
	
}
