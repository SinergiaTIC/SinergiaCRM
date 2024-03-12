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

class LeadsDuplicateQueries{

    /**
     * getDuplicateQuery
     *
     * This function returns the SQL String used for initial duplicate Leads check
     *
     * @see checkForDuplicates (method), ContactFormBase.php, LeadFormBase.php, ProspectFormBase.php
     * @param $bean sugarbean
     * @param $prefix String value of prefix that may be present in $_POST variables
     * @return SQL String of the query that should be used for the initial duplicate lookup check
     */
    public static function getDuplicateQuery($bean, $prefix='') {
        if(file_exists("custom/modules/Leads/customLeadsDuplicateQueries.php")) {
            require_once("custom/modules/Leads/customLeadsDuplicateQueries.php");
            if(method_exists("customLeadsDuplicateQueries", "getDuplicateQuery")) {
                return customLeadsDuplicateQueries::getDuplicateQuery($focus, $prefix);
            }
        }

        $query = "SELECT id, first_name, last_name, account_name, title FROM leads ";

        // Bug #46427 : Records from other Teams shown on Potential Duplicate Contacts screen during Lead Conversion
        // add team security

        $query .= " WHERE deleted != 1 AND (status <> 'Converted' OR status IS NULL) AND ";

        $dbManager = DBManagerFactory::getInstance();
        //Use the first and last name from the $_POST to filter.  If only last name supplied use that
        if (isset($_POST[$prefix.'first_name']) && strlen($_POST[$prefix.'first_name']) != 0 && isset($_POST[$prefix.'last_name']) && strlen($_POST[$prefix.'last_name']) != 0) {
            $firstName = $dbManager->quote($_POST[$prefix.'first_name' ?? '']);
            $lastName = $dbManager->quote($_POST[$prefix.'last_name' ?? '']);
            $query .= " (first_name='". $firstName . "' AND last_name = '". $lastName ."')";
        } else {
            $lastName = $dbManager->quote($_POST[$prefix.'last_name' ?? '']);
            $query .= " last_name = '". $lastName ."'";
        }
        return $query;
    }

    public static function getShowDuplicateQuery() {
        if(file_exists("custom/modules/Leads/customLeadsDuplicateQueries.php")) {
            require_once("custom/modules/Leads/customLeadsDuplicateQueries.php");
            if(method_exists("customLeadsDuplicateQueries", "getShowDuplicateQuery")) {
                return customLeadsDuplicateQueries::getShowDuplicateQuery($focus, $prefix);
            }
        }

        $query = 'SELECT id, first_name, last_name, title FROM leads WHERE deleted=0 ';
        return $query;
    }
}