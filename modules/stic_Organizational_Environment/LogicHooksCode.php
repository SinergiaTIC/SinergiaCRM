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
// Prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class stic_Organizational_EnvironmentLogicHooks
{
    public function before_save(&$bean, $event, $arguments)
    {
        if (empty($bean->name)) {
            global $app_list_strings;
            include_once 'SticInclude/Utils.php';

            // Environment contact or organization (1st part).
            $environment = '';
            if (!empty($bean->stic_organizational_environment_accountsaccounts_ida)) {
                $related = SticUtils::getRelatedBeanObject($bean, 'stic_organizational_environment_accounts');
                if ($related) {
                    $environment = $related->name;
                }
            } elseif (!empty($bean->stic_organizational_environment_contactscontacts_ida)) {
                $related = SticUtils::getRelatedBeanObject($bean, 'stic_organizational_environment_contacts');
                if ($related) {
                    $environment = trim($related->first_name . ' ' . $related->last_name);
                }
            }

            // Base organization.
            $base = '';
            if (!empty($bean->stic_organizational_environment_accounts_1accounts_ida)) {
                $related = SticUtils::getRelatedBeanObject($bean, 'stic_organizational_environment_accounts_1');
                if ($related) {
                    $base = $related->name;
                }
            }

            // Relationship type.
            $list  = 'stic_organizational_environment_relationships_list';
            $type  = $app_list_strings[$list][$bean->relationship_type] ?? '';

            $bean->name = $environment . " - " . $type . " - " . $base;
        }
    }
}