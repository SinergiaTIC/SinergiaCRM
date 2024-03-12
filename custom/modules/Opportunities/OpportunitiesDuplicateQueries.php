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

class OpportunitiesDuplicateQueries{

    /**
     * getDuplicateQuery
     *
     * This function returns the SQL String used for initial duplicate Opportunities check
     *
     * @param $bean sugarbean
     * @param $prefix String value of prefix that may be present in $_POST variables
     * @return SQL String of the query that should be used for the initial duplicate lookup check
     */
    public static function getDuplicateQuery($bean, $prefix='') {
        if(file_exists("custom/modules/Opportunities/customOpportunitiesDuplicateQueries.php")) {
            require_once("custom/modules/Opportunities/customOpportunitiesDuplicateQueries.php");
            if(method_exists("customOpportunitiesDuplicateQueries", "getDuplicateQuery")) {
                return customOpportunitiesDuplicateQueries::getDuplicateQuery($focus, $prefix);
            }
        }
        $query = '';
        $baseQuery = 'select id, name, sales_stage,amount, date_closed  from opportunities where deleted!=1 and (';

        if (isset($_POST[$prefix.'name']) && !empty($_POST[$prefix.'name'])) {
            $query = $baseQuery ."  name like '%".$_POST[$prefix.'name']."%'";
            $query .= getLikeForEachWord('name', $_POST[$prefix.'name']);
        }

        return $query;
    }

}