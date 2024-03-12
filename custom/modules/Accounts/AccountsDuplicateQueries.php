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
// Extension of class ListViewSmarty to allow MassUpdate of field types not allowed by default by SugarCRM

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class AccountsDuplicateQueries{

    /**
     * getDuplicateQuery
     *
     * This function returns the SQL String used for initial duplicate Accounts check
     *
     * @param $bean sugarbean
     * @param $prefix String value of prefix that may be present in $_POST variables
     * @return SQL String of the query that should be used for the initial duplicate lookup check
     */
    public static function getDuplicateQuery($bean, $prefix='') {
        if(file_exists("custom/modules/Accounts/customAccountsDuplicateQueries.php")) {
            require_once("custom/modules/Accounts/customAccountsDuplicateQueries.php");
            if(method_exists("customAccountsDuplicateQueries", "getDuplicateQuery")) {
                return customAccountsDuplicateQueries::getDuplicateQuery($focus, $prefix);
            }
        }

        $dbManager = DBManagerFactory::getInstance();

        $query = '';

        $name = !empty($_POST[$prefix . 'name']) ? $_POST[$prefix . 'name'] : '';
        $shippingAddressCity = !empty($_POST[$prefix . 'shipping_address_city']) ? $_POST[$prefix . 'shipping_address_city'] : '';
        $billingAddressCity = !empty($_POST[$prefix . 'billing_address_city']) ? $_POST[$prefix . 'billing_address_city'] : '';

        $baseQuery = 'SELECT id, name, website, billing_address_city FROM accounts WHERE deleted != 1 AND ';

        if (!empty($name)) {
            $nameQuoted = $dbManager->quote($name . '%');
            $query = $baseQuery ." name LIKE " . $nameQuoted;
        }

        if (!empty($billingAddressCity) || !empty($shippingAddressCity)) {
            $tempQuery = '';

            if (!empty($billingAddressCity)) {
                $billingAddressCityQuoted = $dbManager->quote($billingAddressCity . '%');
                $tempQuery .= "billing_address_city LIKE " . $billingAddressCityQuoted;
            }

            if (!empty($shippingAddressCity)) {
                $shippingAddressCityQuoted = $dbManager->quote($shippingAddressCity . '%');
                $tempQuery .= (empty($tempQuery)) ?: ' OR ';
                $tempQuery .= "shipping_address_city LIKE " . $shippingAddressCityQuoted;
            }

            $query .= (empty($query)) ? $baseQuery : ' AND ';
            $query .=   ' ('. $tempQuery . ' ) ';
        }
        return $query;
    }

    public static function getShowDuplicateQuery() {
        if(file_exists("custom/modules/Accounts/customAccountsDuplicateQueries.php")) {
            require_once("custom/modules/Accounts/customAccountsDuplicateQueries.php");
            if(method_exists("customAccountsDuplicateQueries", "getShowDuplicateQuery")) {
                return customAccountsDuplicateQueries::getShowDuplicateQuery($focus, $prefix);
            }
        }

        $query = 'select id, name, website, billing_address_city  from accounts where deleted=0 ';
        $duplicates = $_POST['duplicate'];
        $count = count($duplicates);
        $db = DBManagerFactory::getInstance();
        if ($count > 0) {
            $query .= "and (";
            $first = true;
            foreach ($duplicates as $duplicate_id) {
                if (!$first) {
                    $query .= ' OR ';
                }
                $first = false;
                $duplicateIdQuoted = $db->quote($duplicate_id);
                $query .= "id='$duplicateIdQuoted' ";
            }
            $query .= ')';
        }
        
        return $query;
    }
}