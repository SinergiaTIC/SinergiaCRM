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
     * Not executed during Norma 43 import (checks $_SESSION['norma43_importing'])
     */
    public function after_save(&$bean, $event, $arguments)
    {
        global $db;
        
        // Skip during Norma 43 import to avoid interference
        if (!empty($_SESSION['norma43_importing'])) {
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Norma 43 import in progress");
            return;
        }
        
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
                require_once 'modules/stic_Transactions/importNorma43.php';
                try {
                    Norma43::recalculateProductBalance($productId);
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
        global $db;
        
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
        if (!empty($_SESSION['norma43_importing'])) {
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Norma 43 import in progress");
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

        // Recalculate product balance
        require_once 'modules/stic_Transactions/importNorma43.php';
        try {
            Norma43::recalculateProductBalance($productId);
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Balance updated for product {$productId} after adding transaction");
        } catch (Exception $e) {
            $GLOBALS['log']->error(
                "Error recalculating balance after adding transaction: " . $e->getMessage()
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
        global $db;
        
        // Skip during Norma 43 import
        if (!empty($_SESSION['norma43_importing'])) {
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Norma 43 import in progress");
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

        if (empty($productId)) {
            $GLOBALS['log']->error(
                "Error: Product empty. " .
                "Transaction: {$transactionId}, Product: {$productId}, " .
                "RelationshipName: " . ($arguments['relationship'] ?? 'unknown')
            );
            return;
        }

        $GLOBALS['log']->debug( __METHOD__ . '(' . __LINE__ . ") >> Transaction {$transactionId} unlinked from Product {$productId} " ."Relationship: " . ($arguments['relationship'] ?? 'unknown'));

        // Recalculate product balance
        require_once 'modules/stic_Transactions/importNorma43.php';
        try {
            Norma43::recalculateProductBalance($productId);
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Balance updated for product {$productId} after removing transaction");
        } catch (Exception $e) {
            $GLOBALS['log']->error(
                "Error recalculating balance after removing transaction: " . $e->getMessage()
            );
        }
    }

}
