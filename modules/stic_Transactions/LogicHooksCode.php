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

class stic_TransactionsLogicHooks {

    /**
     * Executed after saving a transaction
     * Detects if the transaction is linked to a financial product
     * and recalculates the balance (covers cases where subpanel add does not trigger after_relationship_add)
     */
    public function after_save(&$bean, $event, $arguments)
    {
        global $db;
        
        // Skip this hook during Norma 43 import process - multiple checks for safety
        if (!empty($_SESSION['norma43_importing']) && $_SESSION['norma43_importing'] === true) {
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Skipping after_save hook during Norma 43 import for transaction " . $bean->id);
            return;
        }
        
        // Also check if the bean itself is marked as a Norma 43 import transaction
        if (!empty($bean->norma43_import_flag) && $bean->norma43_import_flag === true) {
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Skipping after_save hook - transaction marked as Norma 43 import");
            return;
        }
        
        $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Executing after_save hook for transaction " . $bean->id . " (NOT during Norma 43 import)");
        
        // Wait a bit for the relationship to be created in the database
        usleep(100000);
        
        // Get the associated product ID directly from the database
        $query = "
            SELECT DISTINCT stic_trans4a5broducts_ida as product_id
            FROM stic_transactions_stic_financial_products_c
            WHERE stic_transactions_stic_financial_productsstic_transactions_idb = " . $db->quoted($bean->id) . "
            AND deleted = 0
            LIMIT 1
        ";
        
        $result = $db->query($query);
        if ($result) {
            $row = $db->fetchByAssoc($result);
            if ($row && !empty($row['product_id'])) {
                $productId = $row['product_id'];
                
                $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Transaction {$bean->id} saved. Detected linked product: {$productId}");
                
                // Recalculate product balance (manual/subpanel only, not Norma 43)
                require_once 'modules/stic_Transactions/Utils.php';
                try {
                    stic_TransactionsUtils::recalculateProductBalance($productId);
                    $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Balance recalculated for product {$productId} after saving transaction");
                } catch (Exception $e) {
                    $GLOBALS['log']->error(
                        "Error recalculating balance in after_save: " . $e->getMessage()
                    );
                }
            }
        }
    }

    /**
     * Executed before saving a transaction
     * Intercepts and normalizes the amount to prevent truncation
     */
    public function before_save(&$bean, $event, $arguments)
    {
        // Skip this hook during Norma 43 import process
        if (!empty($_SESSION['norma43_importing']) && $_SESSION['norma43_importing'] === true) {
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Skipping before_save hook during Norma 43 import");
            return;
        }
        
        // Log the original amount value
        $originalAmount = $bean->amount;
        
        // Normalize the amount if necessary
        if (isset($bean->amount) && !empty($bean->amount)) {
            $amount = $bean->amount;
            
            // Log what we received
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Original amount: " . var_export($originalAmount, true) . " | Type: " . gettype($originalAmount));
            
            // If it's string
            if (is_string($amount)) {
                // Replace comma with point for decimal
                $amount = str_replace(',', '.', $amount);
                // Convert to float to preserve precision
                $amount = (float)$amount;
                // Save again so SugarCRM processes it correctly
                $bean->amount = $amount;
                
                $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Amount normalized from: " . $originalAmount . " to: " . $amount . " (float)"
                );
            }
        }
    }

    /**
     * Executed after adding a relationship
     * Executed from the transaction side (when linking from transaction edit view or from subpanel)
     * Not executed during Norma 43 import
     */
    public function after_relationship_add($bean, $event, $arguments)
    {
        global $db;
        
        // Skip during Norma 43 import
        if (!empty($_SESSION['norma43_importing']) && $_SESSION['norma43_importing'] === true) {
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Skipping after_relationship_add hook during Norma 43 import");
            return;
        }

        // Only process relationships with financial products
        if (empty($arguments['related_module']) || $arguments['related_module'] !== 'stic_Financial_Products') {
            $GLOBALS['log']->debug(
                __METHOD__ . '(' . __LINE__ . ") >> Ignoring relationship with module " . 
                ($arguments['related_module'] ?? 'unknown')
            );
            return;
        }

        $transactionId = $bean->id;
        $productId = $arguments['related_id'];

        // Both transaction and product must exist
        if (empty($transactionId) || empty($productId)) {
            $GLOBALS['log']->error(
                "Error: Transaction or Product empty. " .
                "Transaction: {$transactionId}, Product: {$productId}, " .
                "RelationshipName: " . ($arguments['relationship'] ?? 'unknown')
            );
            return;
        }

        $GLOBALS['log']->debug( __METHOD__ . '(' . __LINE__ . ") >> Transaction {$transactionId} linked to Product {$productId} " ."Relationship: " . ($arguments['relationship'] ?? 'unknown')
        );

        // Check if this transaction is a duplicate within the product
        require_once 'modules/stic_Transactions/Utils.php';
        $isDuplicate = stic_TransactionsUtils::isTransactionDuplicateInProduct(
            $productId,
            $bean->transaction_date ?? '',
            $bean->amount ?? 0,
            $bean->description ?? $bean->document_name ?? ''
        );

        if ($isDuplicate) {
            $errorMsg = "Duplicate transaction detected! A transaction with the same date ({$bean->transaction_date}), " .
                "amount ({$bean->amount}), and description is already linked to this product. " .
                "The transaction will not be added.";
            
            $GLOBALS['log']->warn(
                __METHOD__ . '(' . __LINE__ . ") >> {$errorMsg} " .
                "Transaction {$transactionId}, Product {$productId}"
            );
            
            // Throw exception to prevent the relationship from being added
            throw new Exception($errorMsg);
        }

        // Check if product has no start_date and if there are linked transactions
        $product = BeanFactory::getBean('stic_Financial_Products', $productId);
        if ($product && (empty($product->start_date) || $product->start_date === '0000-00-00' || $product->start_date === null)) {
            // Get the OLDEST transaction linked to this product
            $oldestQuery = "
                SELECT t.id, t.transaction_date, t.amount
                FROM stic_transactions t
                INNER JOIN stic_transactions_stic_financial_products_c rel
                    ON rel.stic_transactions_stic_financial_productsstic_transactions_idb = t.id
                    AND rel.deleted = 0
                WHERE rel.stic_trans4a5broducts_ida = " . $db->quoted($productId) . "
                AND t.deleted = 0
                ORDER BY t.transaction_date ASC
                LIMIT 1
            ";
            $oldestResult = $db->query($oldestQuery);
            $oldestRow = $db->fetchByAssoc($oldestResult);
            
            if ($oldestRow && !empty($oldestRow['transaction_date'])) {
                $product->start_date = $oldestRow['transaction_date'];
                
                // If initial_balance is empty or 0, set it to the amount of the oldest transaction
                if (empty($product->initial_balance) || $product->initial_balance == 0) {
                    $product->initial_balance = (float)($oldestRow['amount'] ?? 0);
                    
                    $GLOBALS['log']->debug(
                        __METHOD__ . '(' . __LINE__ . ") >> Product {$productId} has no initial_balance. " .
                        "Setting initial_balance to {$product->initial_balance} from oldest transaction {$oldestRow['id']}"
                    );
                }
                
                $product->save();
                
                $GLOBALS['log']->debug(
                    __METHOD__ . '(' . __LINE__ . ") >> Product {$productId} has no start_date. " .
                    "Setting start_date to {$oldestRow['transaction_date']} from oldest transaction {$oldestRow['id']}"
                );
            }
        }

        // Recalculate product balance
        require_once 'modules/stic_Transactions/Utils.php';
        try {
            stic_TransactionsUtils::recalculateProductBalance($productId);
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Balance updated for product {$productId} after adding transaction");
        } catch (Exception $e) {
            $GLOBALS['log']->error(
                "Error recalculating balance after adding transaction: " . $e->getMessage()
            );
        }

        // Update the transaction hash, generate it now that product is associated
        try {
            stic_TransactionsUtils::updateTransactionHash($transactionId);
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Transaction hash updated for transaction {$transactionId} after associating product");
        } catch (Exception $e) {
            $GLOBALS['log']->error(
                "Error updating transaction hash after adding relationship: " . $e->getMessage()
            );
        }
    }

    /**
     * Executed after deleting a relationship
     * Executed from the transaction side (when unlinking from transaction edit view or from subpanel)
     * Not executed during Norma 43 import
     */
    public function after_relationship_delete($bean, $event, $arguments)
    {
        // Skip during Norma 43 import
        if (!empty($_SESSION['norma43_importing']) && $_SESSION['norma43_importing'] === true) {
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Skipping after_relationship_delete hook during Norma 43 import");
            return;
        }
        
        // Only process relationships with financial products
        if (empty($arguments['related_module']) || $arguments['related_module'] !== 'stic_Financial_Products') {
            $GLOBALS['log']->debug(
                __METHOD__ . '(' . __LINE__ . ") >> Ignoring relationship with module " . 
                ($arguments['related_module'] ?? 'unknown')
            );
            return;
        }

        $transactionId = $bean->id;
        $productId = $arguments['related_id'];

        // If no product, nothing to do
        if (empty($productId)) {
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> No product associated, skipping balance recalculation");
            return;
        }

        $GLOBALS['log']->debug( __METHOD__ . '(' . __LINE__ . ") >> Transaction {$transactionId} unlinked from Product {$productId} " ."Relationship: " . ($arguments['relationship'] ?? 'unknown'));

        // Recalculate product balance
        require_once 'modules/stic_Transactions/Utils.php';
        try {
            stic_TransactionsUtils::recalculateProductBalance($productId);
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Balance updated for product {$productId} after removing transaction");
        } catch (Exception $e) {
            $GLOBALS['log']->error(
                "Error recalculating balance after removing transaction: " . $e->getMessage()
            );
        }

        // Clear the transaction hash, product is no longer associated
        try {
            stic_TransactionsUtils::updateTransactionHash($transactionId);
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Transaction hash cleared for transaction {$transactionId} after removing product association");
        } catch (Exception $e) {
            $GLOBALS['log']->error(
                "Error updating transaction hash after removing relationship: " . $e->getMessage()
            );
        }
    }

}
