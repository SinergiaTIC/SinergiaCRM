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
        global $db;
        
        $productId = $bean->id;
        
        if (!empty($productId)) {
            $GLOBALS['log']->debug( __METHOD__ . '(' . __LINE__ . ") >> Product {$productId} saved. Recalculating balance based on linked transactions."
            );
            
            // Recalculate product balance
            require_once 'modules/stic_Transactions/importNorma43.php';
            try {
                Norma43::updateProductBalance($productId, []);
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
        global $db;
        
        // Only process relationships with transactions
        if (empty($arguments['related_module']) || $arguments['related_module'] !== 'stic_Transactions') {
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> gnoring relationship with module " . ($arguments['related_module'] ?? 'unknown'));
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

        // Recalculate product balance
        require_once 'modules/stic_Transactions/importNorma43.php';
        try {
            Norma43::updateProductBalance($productId, []);
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Balance updated for product {$productId} after adding transaction from subpanel");
        } catch (Exception $e) {
            $GLOBALS['log']->error(
                "Error recalculating balance after adding transaction: " . $e->getMessage()
            );
        }
    }

    /**
     * Executed after deleting a relationship
     * Executed from the product side (when removing from subpanel)
     */
    public function after_relationship_delete($bean, $event, $arguments)
    {
        global $db;
        
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
        require_once 'modules/stic_Transactions/importNorma43.php';
        try {
            Norma43::updateProductBalance($productId, []);
            $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Balance updated for product {$productId} after removing transaction from subpanel");
        } catch (Exception $e) {
            $GLOBALS['log']->error(
                "Error recalculating balance after removing transaction: " . $e->getMessage()
            );
        }
    }

}
