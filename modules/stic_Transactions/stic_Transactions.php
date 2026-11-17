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
    public function save($check_notify = false)
    {
        include_once 'SticInclude/Utils.php';
        global $app_list_strings, $db;

        // Capture the original amount from user input before any normalization
        $originalAmountString = (string)$this->amount;

        // Normalize the sign of the amount according to the type
        if (!empty($this->type) && isset($this->amount)) {
            $amount = trim($this->amount);

            // If there is a value
            if ($amount !== '') {
                // If there is a comma in the amount
                if (strpos($amount, ',') !== false) {
                    // Remove all dots (thousand separators)
                    $amount = str_replace('.', '', $amount);
                    // Replace comma with dot (decimal separator)
                    $amount = str_replace(',', '.', $amount);
                }

                // If expense is negative
                if ($this->type === 'expense') {
                    // Apply the sign without converting to float
                    if (strpos($amount, '-') === false && $amount !== '0') {
                        $amount = '-' . $amount;
                    }
                }
                // If income is positive
                elseif ($this->type === 'income') {
                    // Remove negative sign if present
                    $amount = ltrim($amount, '-');
                }

                $this->amount = $amount;
            }
        }

        // Build the document_name if not provided
        if (empty($this->description)) {
            // Use the amount value directly without formatting to preserve all decimals
            $amountFormatted = SticUtils::formatDecimalInConfigSettings($this->amount, true);
            if ($this->type === 'expense') {
                $this->document_name = $app_list_strings['stic_payments_transaction_types_list'][$this->type]
                    . ' - ' . $this->transaction_date . ' - (' . $amountFormatted . '€)';
            } else {
                $this->document_name = $app_list_strings['stic_payments_transaction_types_list'][$this->type]
                    . ' - ' . $this->transaction_date . ' - ' . $amountFormatted . '€';
            }
        } elseif (!empty($this->description) && empty($this->document_name)) {
            $this->document_name = $this->description;
        }

        $this->name = $this->document_name;

        // The amount is now normalized
        $amountForDatabase = (string)$this->amount;

        // Save the transaction normally
        parent::save($check_notify);
        
        // Check if this is a Norma 43 import
        $isNorma43Import = (!empty($_SESSION['norma43_importing']) && $_SESSION['norma43_importing'] === true) || (!empty($this->norma43_import_flag) && $this->norma43_import_flag === true);
        
        // After save, restore the full-precision amount directly in the database
        if (!empty($amountForDatabase) && !empty($this->id)) {
            global $db;
            
            // Pass the amount with point decimal to the database
            $updateQuery = "UPDATE " . $this->table_name . 
                          " SET amount = " . $db->quoted($amountForDatabase) .
                          " WHERE id = " . $db->quoted($this->id);
            $db->query($updateQuery);
            
            // Reload the value from DB to verify it was saved correctly
            $selectQuery = "SELECT amount FROM " . $this->table_name . " WHERE id = " . $db->quoted($this->id);
            $result = $db->query($selectQuery);
            if ($result) {
                $row = $db->fetchByAssoc($result);
                $savedAmount = (float)$row['amount'];
                $originalAmountFloat = (float)$amountForDatabase;
                
                if (abs($savedAmount - $originalAmountFloat) > 0.001) {
                    $GLOBALS['log']->error(
                        "Error: Amount truncation detected! Original input: " . $originalAmountString . 
                        " Requested: " . $amountForDatabase . 
                        " (" . $originalAmountFloat . ") Saved: " . $savedAmount . 
                        " for transaction " . $this->id
                    );
                } else {
                    $GLOBALS['log']->debug(
                        __METHOD__ . '(' . __LINE__ . ") >> Amount saved correctly! Original input: " . $originalAmountString . 
                        " Stored in DB: " . $amountForDatabase . 
                        " for transaction " . $this->id
                    );
                }
                // Update in-memory value
                $this->amount = $savedAmount;
            }
        }
        
        // If this is a Norma 43 import, skip hash and balance updates (already handled during import)
        if ($isNorma43Import) {
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Skipping hash and balance updates during Norma 43 import for transaction {$this->id}");
            return;
        }
        
        // After save, update the transaction hash
        if (!empty($this->id)) {
            require_once 'modules/stic_Transactions/Utils.php';
            stic_TransactionsUtils::updateTransactionHash($this->id);
        }
        
        // After saving, check if there's an associated financial product
        $productId = $this->getAssociatedProductId();
        
        // If there's an associated product, update its balance
        if (!empty($productId)) {
            require_once 'modules/stic_Transactions/Utils.php';
            
            // Count how many transactions exist for this product before this save
            // If this is the first one, we log it specially
            $countQuery = "
                SELECT COUNT(*) as total
                FROM stic_transactions_stic_financial_products_c
                WHERE stic_trans4a5broducts_ida = " . $db->quoted($productId) . "
                AND deleted = 0
            ";
            $countResult = $db->query($countQuery);
            $countRow = $db->fetchByAssoc($countResult);
            $transactionCount = (int)$countRow['total'];
            $isFirstTransaction = ($transactionCount <= 1);
            
            try {
                // If this is the first transaction, also set the initial_balance
                if ($isFirstTransaction) {
                    $product = BeanFactory::getBean('stic_Financial_Products', $productId);
                    if ($product) {
                        // Set initial_balance to the amount of this first transaction
                        $product->initial_balance = $this->amount;
                        $GLOBALS['log']->debug(
                            __METHOD__ . '(' . __LINE__ . ") >> First Transaction. Setting initial_balance to " . $originalAmountString . 
                            " for product {$productId} | Transaction: {$this->id}"
                        );
                    }
                }
                
                // Always update the current_balance
                stic_TransactionsUtils::recalculateProductBalance($productId);
                
                if ($isFirstTransaction) {
                    $GLOBALS['log']->debug(
                        __METHOD__ . '(' . __LINE__ . ") >> First Transaction. Balance initialized for product {$productId}. " .
                        "Original input: " . $originalAmountString . 
                        " | Transaction: {$this->id}"
                    );
                } else {
                    $GLOBALS['log']->debug(
                        __METHOD__ . '(' . __LINE__ . ") >> Balance updated for product {$productId} (Transaction #{$transactionCount}). " .
                        "Original input: " . $originalAmountString . 
                        " | Transaction: {$this->id}"
                    );
                }
            } catch (Exception $e) {
                $GLOBALS['log']->error("Error updating balance after save transaction: " . $e->getMessage());
            }
        }
    }

    /**
     * Get the associated financial product ID for this transaction from the relationship table
     * @return string|null The product ID or null if no product is associated
     */
    public function getAssociatedProductId()
    {
        global $db;
        
        $query = "
            SELECT DISTINCT stic_trans4a5broducts_ida as product_id
            FROM stic_transactions_stic_financial_products_c
            WHERE stic_transactions_stic_financial_productsstic_transactions_idb = " . $db->quoted($this->id) . "
            AND deleted = 0
            LIMIT 1
        ";
        
        $result = $db->query($query);
        if ($result) {
            $row = $db->fetchByAssoc($result);
            if ($row && !empty($row['product_id'])) {
                return $row['product_id'];
            }
        }
        
        return null;
    }

    /**
     * Override the mark_deleted method to recalculate the balance of the associated financial product
     * after deleting a transaction.
     * @param string $id The ID of the transaction to delete.
     * @return void
     */
    public function mark_deleted($id)
    {
        global $db;
        $productId = null;

        // Try to get the associated financial product before deleting
        $query = "
            SELECT rel.stic_trans4a5broducts_ida AS product_id
            FROM stic_transactions_stic_financial_products_c rel
            WHERE rel.stic_transactions_stic_financial_productsstic_transactions_idb = " . $db->quoted($id) . "
            AND rel.deleted = 0
            LIMIT 1
        ";
        $result = $db->query($query);
        $row = $db->fetchByAssoc($result);
        if ($row && !empty($row['product_id'])) {
            $productId = $row['product_id'];
        }

        // Delete the transaction
        parent::mark_deleted($id);

        // Recalculate the balance if there is an associated product
        if ($productId) {
            require_once 'modules/stic_Transactions/Utils.php';
            try {
                stic_TransactionsUtils::recalculateProductBalance($productId);
            } catch (Exception $e) {
                $GLOBALS['log']->error("Error updating balance after deleting transaction: " . $e->getMessage());
            }
        } else {
            $GLOBALS['log']->warn("No associated product found to the transaction $id for update balance");
        }
    }
	
}
