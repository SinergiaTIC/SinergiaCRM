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

/**
 * Utility class for manual operations (transaction edit, subpanel add/remove)
 * Provides functions for duplicate detection and balance calculation for manual flows
 * Complements Norma43 class which handles N43 import-specific operations
 */
class stic_TransactionsUtils
{
    /**
     * Get the associated product ID for a transaction from the relationship table
     * @param string $transactionId The transaction ID
     * @return string|null The product ID or null if not found
     */
    private static function getTransactionProductId($transactionId)
    {
        global $db;
        
        $query = "
            SELECT DISTINCT stic_trans4a5broducts_ida as product_id
            FROM stic_transactions_stic_financial_products_c
            WHERE stic_transactions_stic_financial_productsstic_transactions_idb = " . $db->quoted($transactionId) . "
            AND deleted = 0
            LIMIT 1
        ";
        $result = $db->query($query);
        $row = $db->fetchByAssoc($result);
        return ($row && !empty($row['product_id'])) ? $row['product_id'] : null;
    }

    /**
     * Generate a transaction hash using productId, date, amount, and name
     * Used for manual operations (edit/subpanel) to check for duplicates within a product
     * @param string $productId The product ID
     * @param string $date The transaction date
     * @param float $amount The transaction amount
     * @param string $name The transaction name/description
     * @return string The generated MD5 hash
     */
    public static function generateTransactionHashByProduct($productId, $date, $amount, $name)
    {
        $productId = trim(strtolower($productId ?? ''));
        $date = trim($date ?? '');
        $amount = number_format((float)$amount, 2, '.', '');
        $name = mb_strtolower(trim($name ?? ''), 'UTF-8');

        // Generate the hash
        return md5("{$productId}|{$date}|{$amount}|{$name}");
    }

    /**
     * Check if a transaction is a duplicate within a product
     * Compares directly: productId + date + amount + name
     * @param string $productId The product ID
     * @param string $transactionDate The transaction date
     * @param float $amount The transaction amount
     * @param string $name The transaction name/description
     * @return bool True if duplicate exists, false otherwise
     */
    public static function isTransactionDuplicateInProduct($productId, $transactionDate, $amount, $name)
    {
        global $db;

        // If no product or no date, can't check for duplicates
        if (empty($productId) || empty($transactionDate)) {
            return false;
        }

        // Normalize values for comparison
        $normalizedAmount = number_format((float)$amount, 2, '.', '');
        $normalizedName = mb_strtolower(trim($name ?? ''), 'UTF-8');

        // Search for duplicates in the database linked to this product
        $query = "
            SELECT t.id
            FROM stic_transactions t
            INNER JOIN stic_transactions_stic_financial_products_c rel
                ON rel.stic_transactions_stic_financial_productsstic_transactions_idb = t.id
                AND rel.deleted = 0
            WHERE rel.stic_trans4a5broducts_ida = " . $db->quoted($productId) . "
            AND t.deleted = 0
            AND t.transaction_date = " . $db->quoted($transactionDate) . "
            AND ABS(CAST(t.amount AS DECIMAL(10,2)) - " . $db->quoted($normalizedAmount) . ") < 0.01
            AND LOWER(TRIM(COALESCE(t.description, t.document_name, ''))) = " . $db->quoted($normalizedName) . "
            LIMIT 1
        ";
        $result = $db->query($query);
        return $db->fetchByAssoc($result) !== false;
    }

    /**
     * Recalculate product balance for manual operations (subpanel/edit)
     * Calculates: sum of all linked transactions
     * @param string $productId The product ID
     * @return void
     */
    public static function recalculateProductBalance($productId)
    {
        global $db;
        
        // If no product ID, nothing to do
        if (empty($productId)) {
            return;
        }
        
        // Load the product
        $product = BeanFactory::getBean('stic_Financial_Products', $productId);
        if (!$product) {
            $GLOBALS['log']->error("Error: Product not found: {$productId}");
            return;
        }

        // Get all transactions linked to this product
        $query = "
            SELECT t.id, t.amount, t.date_entered
            FROM stic_transactions t
            INNER JOIN stic_transactions_stic_financial_products_c rel
                ON rel.stic_transactions_stic_financial_productsstic_transactions_idb = t.id
                AND rel.deleted = 0
            WHERE rel.stic_trans4a5broducts_ida = " . $db->quoted($productId) . "
            AND t.deleted = 0
            ORDER BY t.date_entered ASC
        ";

        $result = $db->query($query);
        $transactions = [];
        
        while ($row = $db->fetchByAssoc($result)) {
            $transactions[] = [
                'id' => $row['id'],
                'amount' => (float)$row['amount'],
                'date_entered' => $row['date_entered']
            ];
        }

        // Calculate balances
        if (!empty($transactions)) {
            // Preserve the existing initial_balance
            $initialBalance = (float)$product->initial_balance;
            
            // Sum of all transactions
            $totalAmount = 0;
            foreach ($transactions as $trans) {
                $totalAmount += $trans['amount'];
            }
            
            // current_balance = initial_balance + sum of transactions
            $product->current_balance = $initialBalance + $totalAmount;
            
            $GLOBALS['log']->debug(__METHOD__ . __LINE__ . " >> ({$productId}): " .
                "Found " . count($transactions) . " transactions. " .
                "initial_balance = " . $initialBalance . 
                ", transactions_sum = {$totalAmount}" .
                ", current_balance = " . $product->current_balance
            );
        } else {
            // No transactions - current_balance equals initial_balance
            $product->current_balance = (float)$product->initial_balance;
            
            $GLOBALS['log']->debug(__METHOD__ . __LINE__ . " >>  ({$productId}): No transactions found, current_balance set to initial_balance (" . $product->initial_balance . ")");
        }

        // Save without triggering after_save hook again
        $product->save();
    }


    /**
     * Update or clear the transaction hash based on whether it has an associated product
     * If product exists: generate hash. If not: clear hash.
     * Only updates if the hash is different from the current one.
     * @param string $transactionId The transaction ID
     * @return void
     */
    public static function updateTransactionHash($transactionId)
    {
        global $db;
        
        if (empty($transactionId)) {
            return;
        }
        
        // Get the transaction
        $transaction = BeanFactory::getBean('stic_Transactions', $transactionId);
        if (!$transaction) {
            $GLOBALS['log']->error("Error: Transaction not found: {$transactionId}");
            return;
        }
        
        // Get the current hash from DB
        $currentHashQuery = "SELECT transaction_hash FROM stic_transactions WHERE id = " . $db->quoted($transactionId);
        $currentHashResult = $db->query($currentHashQuery);
        $currentHashRow = $db->fetchByAssoc($currentHashResult);
        $currentHash = $currentHashRow ? ($currentHashRow['transaction_hash'] ?? '') : '';
        
        // Get the associated product ID using consolidated method
        $productId = self::getTransactionProductId($transactionId);
        
        $newHash = '';
        
        // If there's a product, generate the hash
        if (!empty($productId)) {
            $newHash = self::generateTransactionHashByProduct(
                $productId,
                $transaction->transaction_date ?? '',
                $transaction->amount ?? 0,
                $transaction->description ?? $transaction->document_name ?? ''
            );
        }
        
        // Only update if the hash is different
        if ($newHash !== $currentHash) {
            // Update the hash in the database
            $updateQuery = "UPDATE stic_transactions " .
                          "SET transaction_hash = " . $db->quoted($newHash) .
                          " WHERE id = " . $db->quoted($transactionId);
            $db->query($updateQuery);
            
            // Update in-memory value
            $transaction->transaction_hash = $newHash;
            
            if (!empty($productId)) {
                $GLOBALS['log']->debug(
                    __METHOD__ . '(' . __LINE__ . ") >> Transaction hash updated for {$transactionId}. " .
                    "Old: '{$currentHash}' → New: '{$newHash}' (Product: {$productId})"
                );
            } else {
                $GLOBALS['log']->debug(
                    __METHOD__ . '(' . __LINE__ . ") >> Transaction hash cleared for {$transactionId}. " .
                    "Old: '{$currentHash}' → New: (empty)"
                );
            }
        } else {
            $GLOBALS['log']->debug(
                __METHOD__ . '(' . __LINE__ . ") >> Transaction hash unchanged for {$transactionId}. " .
                "Current hash: '{$currentHash}' (Product: " . ($productId ?? 'none') . ")"
            );
        }
    }

}

