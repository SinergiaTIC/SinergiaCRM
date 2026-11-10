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

class stic_WhistleblowingLogicHooks {

    /**
     * Assigns the next sequential number (stic_code) to new whistleblowing records.
     * Registered as a before_save logic hook in custom/Extension/modules/stic_Whistleblowing/Ext/LogicHooks/SticLogicHooksCall.php
     *
     * @param object $bean The stic_Whistleblowing bean being saved
     * @param string $event The current event
     * @param array $arguments Additional hook arguments
     * @return void
     */
    public function incremental(&$bean, $event, $arguments)
    {
        // Only for new records without an assigned code
        $isNew = empty($bean->fetched_row['id']);
        if (!$isNew || !empty($bean->stic_code)) {
            return;
        }

        $db = DBManagerFactory::getInstance();
        // The MAX() must not filter by deleted: soft-deleted complaints keep their sequential
        // number reserved, so its number is not assigned to a new complaint
        $query = 'SELECT MAX(CAST(stic_code AS UNSIGNED)) AS max_val FROM stic_whistleblowing';
        $row = $db->fetchOne($query);
        $nextNumber = (int) ($row['max_val'] ?? 0) + 1;
        $bean->stic_code = (string) $nextNumber;
    }
}
