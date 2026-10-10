<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
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
 * with this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

#[\AllowDynamicProperties]
class SticRecycleBinHookCode
{
    /**
     * Captures metadata and relationships (M2M and 1:M) of a record being deleted.
     * Registered as before_delete application-level LogicHook.
     *
     * @param SugarBean $bean The bean being deleted
     * @param string $event Event name
     * @param array $arguments Event arguments
     * @return void
     */
    public function captureDeletedRecord($bean, $event, $arguments)
    {
        global $log;

        if (!$bean || empty($bean->id) || !self::isValidId($bean->id)) {
            return;
        }

        if ($bean->module_dir === 'stic_Recycle_Bin') {
            return;
        }

        $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': capturing deleted record: module=' . $bean->module_dir . ' id=' . $bean->id);

        global $db, $current_user;

        $module = $bean->module_dir;
        $recordId = $bean->id;
        $recordName = $bean->name ?? '';
        $dateDeleted = $GLOBALS['timedate']->nowDb();
        $userId = $current_user->id ?? ($bean->modified_user_id ?? '1');
        $createdById = $current_user->id ?? '1';

        $recycleBinId = create_guid();
        // Direct SQL INSERT (no beans) so no after_save logic hooks / workflows are triggered.
        $assignedUserId = $bean->assigned_user_id ?? null;
        // Snapshot of the pre-delete audit data so the restore can leave the
        // original record exactly as it was before the deletion.
        $originalModifiedUserId = $bean->modified_user_id ?? null;
        $originalDateModified = $bean->date_modified ?? null;
        // Deletion source: merge, mass action, detail view or other.
        // The merged flag is kept in sync for backward compatibility with
        // entries captured before the deletion_source field existed.
        $deletionSource = self::getDeletionSource($bean);
        $merged = $deletionSource === 'merge' ? 1 : 0;
        $mergedIntoId = $merged ? self::getMergeMasterId($bean) : null;
        $mergedIntoName = $merged && !empty($mergedIntoId) ? self::getMergeMasterName($bean, $mergedIntoId, $db) : '';
        $sql = 'INSERT INTO stic_recycle_bin (
                    id, name, date_entered, date_modified, modified_user_id, created_by,
                    deleted, assigned_user_id, original_assigned_user_id,
                    original_modified_user_id, original_date_modified,
                    record_module, record_id, record_name, date_deleted, user_deleted_id,
                    restored, merged, deletion_source, merged_into_id, merged_into_name
                ) VALUES (
                    ' . $db->quoted($recycleBinId) . ',
                    ' . $db->quoted($recordName) . ',
                    ' . $db->quoted($dateDeleted) . ',
                    ' . $db->quoted($dateDeleted) . ',
                    ' . $db->quoted($userId) . ',
                    ' . $db->quoted($createdById) . ',
                    0,
                    ' . (empty($assignedUserId) ? 'NULL' : $db->quoted($assignedUserId)) . ',
                    ' . (empty($assignedUserId) ? 'NULL' : $db->quoted($assignedUserId)) . ',
                    ' . (empty($originalModifiedUserId) ? 'NULL' : $db->quoted($originalModifiedUserId)) . ',
                    ' . (empty($originalDateModified) ? 'NULL' : $db->quoted($originalDateModified)) . ',
                    ' . $db->quoted($module) . ',
                    ' . $db->quoted($recordId) . ',
                    ' . $db->quoted($recordName) . ',
                    ' . $db->quoted($dateDeleted) . ',
                    ' . $db->quoted($userId) . ',
                    0,
                    ' . $merged . ',
                    ' . $db->quoted($deletionSource) . ',
                    ' . (empty($mergedIntoId) ? 'NULL' : $db->quoted($mergedIntoId)) . ',
                    ' . $db->quoted($mergedIntoName) . '
                )';
        $result = $db->query($sql);
        if ($result === false) {
            $log->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': recycle bin INSERT failed: ' . $db->lastError());
            return;
        }

        $this->captureRelationships($bean, $recycleBinId, $db);
    }

    /**
     * Iterates all link-type fields in the bean to capture relationships (M2M and 1:M,
     * either side of a 1:M link). Excludes system-only links.
     *
     * @param SugarBean $bean Parent bean
     * @param string $recycleBinId Recycle bin entry ID
     * @param object $db Database instance
     * @return void
     */
    private function captureRelationships($bean, $recycleBinId, $db)
    {
        $excludedLinks = array(
            'assigned_user_link',
            'created_by_link',
            'modified_user_link',
            'securitygroup',
        );

        foreach ($bean->field_defs as $fieldName => $def) {
            if (($def['type'] ?? '') !== 'link' || empty($def['relationship'])) {
                continue;
            }
            if (in_array($fieldName, $excludedLinks, true)) {
                continue;
            }
            if (!$bean->load_relationship($fieldName)) {
                continue;
            }

            $link = $bean->$fieldName;
            $relObj = $link->getRelationshipObject();
            if (!$relObj) {
                continue;
            }

            $relDef = $relObj->def;
            $relatedIds = $link->get();
            if (empty($relatedIds) || !is_array($relatedIds)) {
                continue;
            }

            $joinTable = $relDef['join_table'] ?? '';
            $lhsKey = $relDef['join_key_lhs'] ?? $this->resolveLhsKey($bean, $fieldName, $def, $relDef);
            $rhsKey = $relDef['join_key_rhs'] ?? $relDef['rhs_key'] ?? 'id';

            $lhsModule = $relDef['lhs_module'] ?? '';
            $rhsModule = $relDef['rhs_module'] ?? '';
            $relatedModule = ($lhsModule === $bean->module_dir) ? $rhsModule : $lhsModule;
            if (!$relatedModule) {
                continue;
            }
            $relatedSeed = BeanFactory::newBean($relatedModule);
            if (!$relatedSeed) {
                continue;
            }
            $relatedTable = $relatedSeed->table_name;

            $quotedIds = array();
            foreach ($relatedIds as $rid) {
                if (self::isValidId($rid)) {
                    $quotedIds[] = $db->quoted($rid);
                }
            }
            if (empty($quotedIds)) {
                continue;
            }

            $selectSql = 'SELECT * FROM `' . $relatedTable . '` WHERE id IN (' . implode(',', $quotedIds) . ') AND deleted = 0';
            $selectResult = $db->query($selectSql);

            while ($row = $db->fetchByAssoc($selectResult)) {
                $relatedBean = new stdClass();
                $relatedBean->id = $row['id'];
                $relatedBean->name = $row['name'] ?? $row['email_address'] ?? $row['filename'] ?? $row['recipient_name'] ?? '';
                $relatedBean->module_dir = $relatedModule;
                $relatedBean->deleted = 0;

                $this->insertRelationshipRow(
                    $bean, $fieldName, $relatedBean, $recycleBinId, $db,
                    $joinTable
                );
            }
        }
    }

    /**
     * Resolves the LHS join key for a 1:M link where the vardef lacks join_key_lhs.
     * For self-referencing 1:M relationships (e.g. Account.member_of/members), the
     * capture may happen on either side: the RHS side has a relate field with link =
     * $fieldName (whose id_name is the FK column), while the LHS side has no such
     * relate field — in that case the FK column on the RHS row is the relationship's
     * rhs_key (e.g. 'parent_id' for member_accounts).
     *
     * @param SugarBean $bean Parent bean
     * @param string $fieldName Link field name on the bean
     * @param array $linkDef The link field vardef
     * @param array $relDef The relationship definition from the rel object
     * @return string Resolved LHS join key
     */
    private function resolveLhsKey($bean, $fieldName, $linkDef, $relDef)
    {
        if (!empty($linkDef['id_name']) && self::isValidIdentifier($linkDef['id_name'])) {
            return $linkDef['id_name'];
        }
        foreach ($bean->field_defs as $fName => $fDef) {
            if (($fDef['type'] ?? '') === 'relate' && ($fDef['link'] ?? '') === $fieldName) {
                $candidate = $fDef['id_name'] ?? $fName;
                if (self::isValidIdentifier($candidate)) {
                    return $candidate;
                }
            }
        }
        if (!empty($relDef['rhs_key']) && self::isValidIdentifier($relDef['rhs_key'])) {
            return $relDef['rhs_key'];
        }
        if (!empty($relDef['lhs_key']) && self::isValidIdentifier($relDef['lhs_key'])) {
            return $relDef['lhs_key'];
        }
        return 'parent_id';
    }

    /**
     * Inserts a row in stic_recycle_bin_relationships for one captured relationship.
     *
     * @param SugarBean $bean Parent bean
     * @param string $fieldName Link field name
     * @param SugarBean $relatedBean The related bean captured
     * @param string $recycleBinId Recycle bin entry ID
     * @param object $db Database instance
     * @param string $joinTable M2M join table or empty for 1:M
     * @return void
     */
    private function insertRelationshipRow($bean, $fieldName, $relatedBean, $recycleBinId, $db, $joinTable)
    {
        if (!self::isValidIdentifier($fieldName)) {
            return;
        }

        $relId = create_guid();
        $relRecordName = $db->quoted($relatedBean->name ?? '');
        $binId = $db->quoted($recycleBinId);
        $userIdQ = $db->quoted($bean->modified_user_id ?? '1');
        $relNameQ = $db->quoted($fieldName);
        $joinTableQ = $db->quoted($joinTable);
        $relModule = $db->quoted($relatedBean->module_dir);
        $relRecordId = $db->quoted($relatedBean->id);

        $sql = "INSERT INTO stic_recycle_bin_relationships (
                    id, name, date_entered, date_modified, created_by,
                    stic_recycle_bin_id, relationship_name,
                    join_table, related_module,
                    related_record_id, related_record_name, skip_reason
                ) VALUES (
                    " . $db->quoted($relId) . ",
                    " . $relRecordName . ",
                    NOW(), NOW(),
                    " . $userIdQ . ",
                    " . $binId . ",
                    " . $relNameQ . ",
                    " . $joinTableQ . ",
                    " . $relModule . ",
                    " . $relRecordId . ",
                    " . $relRecordName . ",
                    ''
                )";
        $db->query($sql);
    }

    /**
     * Validates a SugarCRM-style UUID.
     *
     * @param string $id ID to validate
     * @return bool true if the value matches the UUID pattern
     */
    private static function isValidId($id)
    {
        return is_string($id) && preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $id) === 1;
    }

    /**
     * Determines the deletion source for the captured record.
     *
     * @param SugarBean $bean The bean being deleted
     * @return string One of 'merge', 'mass', 'detail', 'other'
     */
    private static function getDeletionSource($bean)
    {
        if (self::isMergeDelete($bean)) {
            return 'merge';
        }
        if (self::isMassDelete($bean)) {
            return 'mass';
        }
        if (self::isDetailDelete($bean)) {
            return 'detail';
        }
        return 'other';
    }

    /**
     * Detects deletions triggered from the list view mass action.
     * MassUpdate.php retrieves each id from $_POST['mass'] and calls
     * mark_deleted() when $_POST['Delete'] is set.
     *
     * @param SugarBean $bean The bean being deleted
     * @return bool true if the deletion comes from a mass delete action
     */
    private static function isMassDelete($bean)
    {
        $deleteRequested = !empty($_POST['Delete']) || !empty($_REQUEST['Delete']);
        if (!$deleteRequested) {
            return false;
        }
        foreach (array('mass', 'uid', 'selected_ids') as $key) {
            if (!empty($_REQUEST[$key]) && is_array($_REQUEST[$key])) {
                if (in_array($bean->id, $_REQUEST[$key], true)) {
                    return true;
                }
            }
            if (!empty($_POST[$key]) && is_array($_POST[$key])) {
                if (in_array($bean->id, $_POST[$key], true)) {
                    return true;
                }
            }
        }
        if (!empty($_REQUEST['action']) && strtolower((string)$_REQUEST['action']) === 'massupdate') {
            return true;
        }
        if (!empty($_REQUEST['massupdate']) && (string)$_REQUEST['massupdate'] === 'true') {
            return true;
        }
        return false;
    }

    /**
     * Detects deletions triggered from a record's detail view (single delete).
     *
     * @param SugarBean $bean The bean being deleted
     * @return bool true if the deletion comes from the detail view delete action
     */
    private static function isDetailDelete($bean)
    {
        if (empty($_REQUEST['action']) || strtolower((string)$_REQUEST['action']) !== 'delete') {
            return false;
        }
        if (!empty($_REQUEST['module']) && $_REQUEST['module'] !== $bean->module_dir) {
            return false;
        }
        if (!empty($_REQUEST['record']) && $_REQUEST['record'] !== $bean->id) {
            return false;
        }
        return true;
    }

    /**
     * Detects whether the current deletion comes from a record merge.
     * MergeRecords/SaveMerge.php deletes the losing records with
     * $mergeSource->mark_deleted(), which fires this same before_delete hook.
     * In that request $_REQUEST['merged_ids'] holds the ids being merged.
     *
     * @param SugarBean $bean The bean being deleted
     * @return bool true if the deletion is the result of a merge
     */
    private static function isMergeDelete($bean)
    {
        if (!empty($_REQUEST['merged_ids']) && is_array($_REQUEST['merged_ids'])) {
            if (in_array($bean->id, $_REQUEST['merged_ids'], true)) {
                return true;
            }
        }
        if (!empty($_REQUEST['module']) && $_REQUEST['module'] === 'MergeRecords'
            && !empty($_REQUEST['action']) && $_REQUEST['action'] === 'SaveMerge'
            && !empty($_REQUEST['merge_module']) && $_REQUEST['merge_module'] === $bean->module_dir
        ) {
            return true;
        }
        return false;
    }

    /**
     * Returns the id of the surviving (master) record when the deletion comes
     * from a merge. In MergeRecords/SaveMerge.php, $_REQUEST['record'] holds
     * the master record while $_REQUEST['merged_ids'] holds the losers.
     *
     * @param SugarBean $bean The bean being deleted
     * @return string|null Master record id or null if it cannot be determined
     */
    private static function getMergeMasterId($bean)
    {
        if (!empty($_REQUEST['record']) && self::isValidId($_REQUEST['record'])
            && $_REQUEST['record'] !== $bean->id
        ) {
            return $_REQUEST['record'];
        }
        return null;
    }

    /**
     * Returns the name of the surviving (master) record at merge time. The merge
     * happens within a single module, so the master's table is the bean's table.
     *
     * @param SugarBean $bean The bean being deleted
     * @param string $masterId Surviving record id
     * @param object $db Database instance
     * @return string Master record name or empty string
     */
    private static function getMergeMasterName($bean, $masterId, $db)
    {
        $table = $bean->table_name ?? '';
        if (!self::isValidIdentifier($table) || !self::isValidId($masterId)) {
            return '';
        }
        $result = $db->query(
            'SELECT name FROM `' . $table . '`'
            . ' WHERE id = ' . $db->quoted($masterId) . ' AND deleted = 0 LIMIT 1'
        );
        if ($result && $row = $db->fetchByAssoc($result)) {
            return $row['name'] ?? '';
        }
        return '';
    }

    /**
     * Validates an identifier (table/column name).
     *
     * @param string $name Identifier to validate
     * @return bool true if the value is a safe identifier
     */
    private static function isValidIdentifier($name)
    {
        return is_string($name) && preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $name) === 1;
    }
}
