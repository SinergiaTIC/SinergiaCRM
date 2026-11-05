<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2026 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along
 * with this program; if not, see http://www.gnu.org/licenses or write to the
 * Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// STIC-Custom 20261022 DEV - Related records panel
// Filters union rows of subpanels by record ACL and Security Groups membership,
// replicating the checks of ListViewSubPanel::process_dynamic_listview_rows()
// but with ONE aggregated query per module instead of one query per row
// (SecurityGroup::groupHasAccess() is not cached and would cause a heavy N+1).
// See stic-ai-handbook: output/features/related-records-panel

require_once 'modules/SecurityGroups/SecurityGroup.php';

class SticRelatedPanelSecurity
{
    /**
     * Returns only the rows the current user is allowed to see in a record detail view.
     *
     * @param array $rows List of union-row beans as returned by SugarBean::get_union_related_list()
     * @return array Filtered list (values reindexed)
     */
    public function filterVisibleRows(array $rows)
    {
        global $current_user;

        if (empty($rows) || !is_object($current_user)) {
            return array();
        }

        // Administrators bypass group checks (same as core rendering).
        $isAdmin = is_admin($current_user);

        // Group row ids by module: one aggregated query per module.
        $idsByModule = array();
        foreach ($rows as $row) {
            if (!is_object($row) || empty($row->id) || empty($row->module_dir)) {
                continue;
            }
            $idsByModule[$row->module_dir][$row->id] = true;
        }

        $inGroup = array();
        if (!$isAdmin) {
            $db = DBManagerFactory::getInstance();
            foreach ($idsByModule as $moduleName => $moduleIds) {
                $ids = array();
                foreach (array_keys($moduleIds) as $id) {
                    $ids[] = $db->quote($id);
                }
                if (empty($ids)) {
                    continue;
                }
                $moduleQuoted = $db->quote($moduleName);
                $query =
                    'SELECT securitygroups_records.record_id AS record_id '
                    . 'FROM securitygroups_records '
                    . 'INNER JOIN securitygroups_users '
                    . 'ON securitygroups_users.securitygroup_id = securitygroups_records.securitygroup_id '
                    . 'AND securitygroups_users.deleted = 0 '
                    . "AND securitygroups_users.user_id = '" . $db->quote($current_user->id) . "' "
                    . 'WHERE securitygroups_records.deleted = 0 '
                    . "AND securitygroups_records.module = '" . $moduleQuoted . "' "
                    . 'AND securitygroups_records.record_id IN (' . implode(',', $ids) . ')';
                $result = $db->query($query);
                while ($record = $db->fetchByAssoc($result)) {
                    // Keyed "moduleId" so modules with equal record ids cannot collide.
                    $inGroup[$moduleName . '|' . $record['record_id']] = true;
                }
            }
        }

        $visible = array();
        foreach ($rows as $row) {
            if (!is_object($row) || empty($row->id) || empty($row->module_dir)) {
                continue;
            }

            if ($isAdmin) {
                $isOwner = true;
                $inGroupFlag = false;
            } else {
                $isOwner = method_exists($row, 'isOwner') ? $row->isOwner($current_user->id) : false;
                $inGroupFlag = isset($inGroup[$row->module_dir . '|' . $row->id]);
            }

            // Same ACL evaluation as the subpanel list rendering.
            if ($row instanceof SugarBean && $row->ACLAccess('DetailView', $isOwner, $inGroupFlag)) {
                $visible[] = $row;
            }
        }

        return $visible;
    }
}
