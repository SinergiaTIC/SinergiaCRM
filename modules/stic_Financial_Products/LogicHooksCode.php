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

class stic_Financial_ProductsLogicHooks {

    /**
     * Executed after saving a financial product
     * Recalculates the balance based on linked transactions
     */
    public function after_save(&$bean, $event, $arguments)
    {
        // Skip this hook during Norma 43 import process
        if (!empty($_SESSION['norma43_importing']) && $_SESSION['norma43_importing'] === true) {
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Skipping after_save hook during Norma 43 import");
            return;
        }
        
        $productId = $bean->id;
        
        if (!empty($productId)) {
            $GLOBALS['log']->debug( __METHOD__ . '(' . __LINE__ . ") >> Product {$productId} saved. Recalculating balance based on linked transactions."
            );
            
            // Recalculate product balance
            require_once 'modules/stic_Transactions/Utils.php';
            try {
                stic_TransactionsUtils::recalculateProductBalance($productId);
                $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Balance recalculated for product {$productId}");
            } catch (Exception $e) {
                $GLOBALS['log']->error(
                    "Error recalculating balance in after_save: " . $e->getMessage()
                );
            }
        }
    }

    /**
     * Executed after any relationship change on the product
     * Executed from the product side (when adding/removing from subpanel)
     */
    public function after_relationship_add($bean, $event, $arguments)
    {
        // Skip this hook during Norma 43 import process
        if (!empty($_SESSION['norma43_importing']) && $_SESSION['norma43_importing'] === true) {
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Skipping after_relationship_add hook during Norma 43 import");
            return;
        }
        
        // Only process relationships with transactions
        if (empty($arguments['related_module']) || $arguments['related_module'] !== 'stic_Transactions') {
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Ignoring relationship with module " . ($arguments['related_module'] ?? 'unknown'));
            return;
        }

        $productId = $bean->id;
        $transactionId = $arguments['related_id'];

        if (empty($productId) || empty($transactionId)) {
            $GLOBALS['log']->error(
                "Error: Product or Transaction empty. " .
                "Product: {$productId}, Transaction: {$transactionId}, " .
                "RelationshipName: " . ($arguments['relationship'] ?? 'unknown')
            );
            return;
        }

        $GLOBALS['log']->debug(
            __METHOD__ . '(' . __LINE__ . ") >> Transaction {$transactionId} added to Product {$productId} " .
            "Relationship: " . ($arguments['relationship'] ?? 'unknown')
        );

        // Check if this transaction is a duplicate within the product
        require_once 'modules/stic_Transactions/Utils.php';
        $transaction = BeanFactory::getBean('stic_Transactions', $transactionId);
        if ($transaction) {
            $isDuplicate = stic_TransactionsUtils::isTransactionDuplicateInProduct(
                $productId,
                $transaction->transaction_date ?? '',
                $transaction->amount ?? 0,
                $transaction->description ?? $transaction->document_name ?? ''
            );

            if ($isDuplicate) {
                $errorMsg = "Duplicate transaction detected! A transaction with the same date ({$transaction->transaction_date}), " .
                "amount ({$transaction->amount}), and description is already linked to this product. " .
                "The transaction will not be added.";
            
                $GLOBALS['log']->warn(
                    __METHOD__ . '(' . __LINE__ . ") >> {$errorMsg} " .
                    "Transaction {$transactionId} with date={$transaction->transaction_date}, " .
                        "amount={$transaction->amount} is already linked to product {$productId}"
                );
                
                // Throw exception to prevent the relationship from being added
                throw new Exception($errorMsg);
            }
        }

        // Check if product has no start_date and if there are linked transactions
        if (empty($bean->start_date) || $bean->start_date === '0000-00-00' || $bean->start_date === null) {
            global $db;
            
            // Get the oldest transaction linked to this product
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
                $bean->start_date = $oldestRow['transaction_date'];
                
                // If initial_balance is empty or 0, set it to the amount of the oldest transaction
                if (empty($bean->initial_balance) || $bean->initial_balance == 0) {
                    $bean->initial_balance = (float)($oldestRow['amount'] ?? 0);
                    
                    $GLOBALS['log']->debug(
                        __METHOD__ . '(' . __LINE__ . ") >> Product {$productId} has no initial_balance. " .
                        "Setting initial_balance to {$bean->initial_balance} from oldest transaction {$oldestRow['id']}"
                    );
                }
                
                $bean->save();
                
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
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Balance updated for product {$productId} after adding transaction from subpanel");
        } catch (Exception $e) {
            $GLOBALS['log']->error(
                "Error recalculating balance after adding transaction: " . $e->getMessage()
            );
        }

        // Update the transaction hash, generate it now that product is associated
        try {
            stic_TransactionsUtils::updateTransactionHash($transactionId);
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Transaction hash generated for transaction {$transactionId} after adding to product subpanel");
        } catch (Exception $e) {
            $GLOBALS['log']->error(
                "Error updating transaction hash after adding relationship: " . $e->getMessage()
            );
        }
    }

    /**
     * Executed after deleting a relationship
     * Executed from the product side (when removing from subpanel)
     */
    public function after_relationship_delete($bean, $event, $arguments)
    {
        // Skip this hook during Norma 43 import process
        if (!empty($_SESSION['norma43_importing']) && $_SESSION['norma43_importing'] === true) {
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Skipping after_relationship_delete hook during Norma 43 import");
            return;
        }
        
        // Only process relationships with transactions
        if (empty($arguments['related_module']) || $arguments['related_module'] !== 'stic_Transactions') {
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Ignoring relationship with module " . 
                ($arguments['related_module'] ?? 'unknown')
            );
            return;
        }

        $productId = $bean->id;
        $transactionId = $arguments['related_id'];

        if (empty($productId) || empty($transactionId)) {
            $GLOBALS['log']->error(
                "Error: Product empty. " .
                "Product: {$productId}, Transaction: {$transactionId}, " .
                "RelationshipName: " . ($arguments['relationship'] ?? 'unknown')
            );
            return;
        }

        $GLOBALS['log']->debug(
            __METHOD__ . '(' . __LINE__ . ") >> Transaction {$transactionId} removed from Product {$productId} " .
            "Relationship: " . ($arguments['relationship'] ?? 'unknown')
        );

        // Recalculate product balance
        require_once 'modules/stic_Transactions/Utils.php';
        try {
            stic_TransactionsUtils::recalculateProductBalance($productId);
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Balance updated for product {$productId} after removing transaction from subpanel");
        } catch (Exception $e) {
            $GLOBALS['log']->error(
                "Error recalculating balance after removing transaction: " . $e->getMessage()
            );
        }

        // Clear the transaction hash if product is no longer associated
        try {
            stic_TransactionsUtils::updateTransactionHash($transactionId);
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Transaction hash cleared for transaction {$transactionId} after removing from product subpanel");
        } catch (Exception $e) {
            $GLOBALS['log']->error(
                "Error updating transaction hash after removing relationship: " . $e->getMessage()
            );
        }
    }

}
