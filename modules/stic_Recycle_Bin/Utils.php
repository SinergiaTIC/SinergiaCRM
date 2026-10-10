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
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

#[\AllowDynamicProperties]
class stic_Recycle_BinUtils
{
    /**
     * Restores a single deleted record and all its relationships.
     *
     * @param string $recycleBinId ID of the stic_Recycle_Bin entry
     * @return array Result with success flag, message, and counters
     */
    public static function restoreRecord($recycleBinId)
    {
        global $db, $current_user, $log;

        if (!self::isValidId($recycleBinId)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': invalid recycleBinId: ' . $recycleBinId);
            return [
                'success' => false,
                'message' => translate('LBL_RESTORE_INVALID_ID', 'stic_Recycle_Bin'),
            ];
        }

        $binBean = BeanFactory::getBean('stic_Recycle_Bin', $recycleBinId);
        if (!$binBean || empty($binBean->record_id)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': recycle bin entry not found: ' . $recycleBinId);
            return [
                'success' => false,
                'message' => translate('LBL_RESTORE_NOT_FOUND', 'stic_Recycle_Bin'),
            ];
        }

        if (!self::isValidId($binBean->record_id)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': invalid record_id: ' . $binBean->record_id);
            return [
                'success' => false,
                'message' => translate('LBL_RESTORE_INVALID_ID', 'stic_Recycle_Bin'),
            ];
        }

        if (!empty($binBean->restored)) {
            return [
                'success' => false,
                'message' => translate('LBL_RESTORE_ALREADY', 'stic_Recycle_Bin'),
            ];
        }

        $module = $binBean->record_module;
        $recordId = $binBean->record_id;

        $table = self::getTableForModule($module);
        if (!$table) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': cannot resolve table for module: ' . $module);
            return [
                'success' => false,
                'message' => translate('LBL_RESTORE_NO_TABLE', 'stic_Recycle_Bin'),
            ];
        }

        $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': restoring record: module=' . $module . ' id=' . $recordId);

        $db->query('UPDATE ' . self::quoteIdentifier($table) . ' SET deleted = 0 WHERE id = ' . $db->quoted($recordId));

        $relationsRestored = 0;
        $relationsSkipped = 0;

        $relsResult = $db->query(
            'SELECT * FROM stic_recycle_bin_relationships
             WHERE stic_recycle_bin_id = ' . $db->quoted($recycleBinId) . '
             AND deleted = 0 AND restored = 0'
        );

        while ($rel = $db->fetchByAssoc($relsResult)) {
            $restored = self::reinsertRelationshipRow($rel, $module, $recordId, $db, $binBean->date_deleted ?? null);
            if ($restored) {
                $relationsRestored++;
            } else {
                $relationsSkipped++;
            }
        }

        $bean = BeanFactory::getBean($module, $recordId, array(), true);
        if ($bean && !empty($bean->id)) {
            $bean->mark_undeleted($recordId);
        }

        self::replayRelationshipAddHooks($bean, $recycleBinId, $db);

        // Leave the original record with its pre-delete audit data instead of
        // the restore datetime/user. Only applies when the snapshot was captured
        // (entries created before it fall back to the standard behavior).
        $auditSet = array();
        if (!empty($binBean->original_date_modified)) {
            $auditSet[] = 'date_modified = ' . $db->quoted($binBean->original_date_modified);
        }
        if (!empty($binBean->original_modified_user_id)) {
            $auditSet[] = 'modified_user_id = ' . $db->quoted($binBean->original_modified_user_id);
        }
        if (!empty($auditSet)) {
            $db->query(
                'UPDATE ' . self::quoteIdentifier($table)
                . ' SET ' . implode(', ', $auditSet)
                . ' WHERE id = ' . $db->quoted($recordId)
            );
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': pre-delete audit data reapplied: ' . $recordId);
        }

        $nowDb = $GLOBALS['timedate']->nowDb();
        $currentUserId = $current_user->id ?? '1';

        $recycleBean = BeanFactory::getBean('stic_Recycle_Bin', $recycleBinId);
        if ($recycleBean && !empty($recycleBean->id)) {
            $recycleBean->date_restored = $nowDb;
            $recycleBean->user_restored_id = $currentUserId;
            $recycleBean->restored = 1;
            $recycleBean->save(true);
        }

        $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': record restored: ' . $relationsRestored . ' relations, ' . $relationsSkipped . ' skipped');

        return [
            'success' => true,
            'relations_restored' => $relationsRestored,
            'relations_skipped' => $relationsSkipped,
        ];
    }

    /**
     * Restores multiple records.
     *
     * @param array $ids Array of stic_Recycle_Bin IDs
     * @return array Summary with counters
     */
    public static function massRestoreRecords($ids)
    {
        global $log;

        $success = 0;
        $failed = 0;

        foreach ($ids as $id) {
            $result = self::restoreRecord($id);
            if ($result['success']) {
                $success++;
            } else {
                $failed++;
                $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': mass restore failed for id ' . $id . ': ' . ($result['message'] ?? ''));
            }
        }

        return [
            'success' => $success,
            'failed' => $failed,
        ];
    }

    /**
     * Replays the framework's after_relationship_add event for every relationship
     * restored with the given record.
     *
     * @param SugarBean $bean Restored record bean
     * @param string $recycleBinId Recycle bin entry ID
     * @param object $db Database instance
     * @return void
     */
    private static function replayRelationshipAddHooks($bean, $recycleBinId, $db)
    {
        global $log;

        $relRows = $db->query(
            'SELECT relationship_name, related_module, related_record_id
             FROM stic_recycle_bin_relationships
             WHERE stic_recycle_bin_id = ' . $db->quoted($recycleBinId) . '
             AND deleted = 0 AND restored = 1'
        );

        while ($rel = $db->fetchByAssoc($relRows)) {
            $linkName = $rel['relationship_name'];
            if (empty($linkName) || !self::isValidModule($rel['related_module']) || !self::isValidId($rel['related_record_id'])) {
                continue;
            }

            $relatedBean = BeanFactory::getBean($rel['related_module'], $rel['related_record_id']);
            if (!$relatedBean || empty($relatedBean->id)) {
                continue;
            }

            $relationshipName = $bean->field_defs[$linkName]['relationship'] ?? '';

            try {
                $bean->call_custom_logic('after_relationship_add', array(
                    'id' => $bean->id,
                    'related_id' => $relatedBean->id,
                    'module' => $bean->module_dir,
                    'related_module' => $relatedBean->module_dir,
                    'related_bean' => $relatedBean,
                    'link' => $linkName,
                    'relationship' => $relationshipName,
                ));
            } catch (Exception $e) {
                $log->warn(__METHOD__ . ': after_relationship_add replay failed for ' . $bean->module_dir . ' / ' . $bean->id . ': ' . $e->getMessage());
            }

            try {
                $relatedBean->call_custom_logic('after_relationship_add', array(
                    'id' => $relatedBean->id,
                    'related_id' => $bean->id,
                    'module' => $relatedBean->module_dir,
                    'related_module' => $bean->module_dir,
                    'related_bean' => $bean,
                    'link' => '',
                    'relationship' => $relationshipName,
                ));
            } catch (Exception $e) {
                $log->warn(__METHOD__ . ': after_relationship_add replay failed for ' . $relatedBean->module_dir . ' / ' . $relatedBean->id . ': ' . $e->getMessage());
            }
        }
    }

    /**
     * Reinserts a relationship row for a restored record. Dispatches by relationship
     * cardinality: many-to-many (join table) or one-to-many (column on the related row).
     *
     * @param array $rel Relationship row from stic_recycle_bin_relationships
     * @param string $module Parent module being restored
     * @param string $recordId Parent record ID
     * @param object $db Database instance
     * @param string|null $dateDeleted Deletion datetime of the bin entry (Y-m-d H:i:s)
     * @return bool true if restored, false if skipped
     */
    private static function reinsertRelationshipRow($rel, $module, $recordId, $db, $dateDeleted = null)
    {
        global $log;

        $linkName = $rel['relationship_name'];
        $joinTable = $rel['join_table'];

        if (empty($linkName)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': empty linkName, skipping');
            return false;
        }

        if (!self::isValidModule($rel['related_module'])) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': invalid related module: ' . $rel['related_module']);
            return false;
        }
        if (!self::isValidId($rel['related_record_id'])) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': invalid related id: ' . $rel['related_record_id']);
            return false;
        }

        $relatedBean = BeanFactory::getBean($rel['related_module'], $rel['related_record_id'], [], true);
        if (!$relatedBean || !empty($relatedBean->deleted) || empty($relatedBean->id)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': related record not available (soft-deleted or missing): ' . $rel['related_module'] . ' / ' . $rel['related_record_id']);
            self::setSkipReason($db, $rel['id'] ?? null, 'related_deleted');
            return false;
        }

        $bean = BeanFactory::newBean($module);
        if (!$bean || !$bean->load_relationship($linkName)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': cannot load relationship: ' . $module . ' / ' . $linkName);
            return false;
        }

        $link = $bean->$linkName;
        $relObj = $link->getRelationshipObject();
        if (!$relObj) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': cannot get relationship object: ' . $linkName);
            return false;
        }

        $relDef = $relObj->def;
        $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': restoring relationship: link=' . $linkName . ' relatedModule=' . $rel['related_module'] . ' relatedId=' . $rel['related_record_id'] . ' joinTable=' . $joinTable);

        $restored = false;
        if (empty($joinTable)) {
            $restored = self::restoreOneToMany($bean, $linkName, $recordId, $relatedBean, $relDef, $db, $rel['id'] ?? null);
        } else {
            $restored = self::restoreManyToMany($module, $linkName, $recordId, $relatedBean, $relDef, $joinTable, $db, $dateDeleted, $rel['id'] ?? null);
        }

        if ($restored) {
            $db->query(
                'UPDATE stic_recycle_bin_relationships SET restored = 1, skip_reason = \'\''
                . ' WHERE id = ' . $db->quoted($rel['id'])
            );
            return true;
        }

        $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': relationship UPDATE affected 0 rows or failed: link=' . $linkName . ' relatedId=' . $rel['related_record_id']);
        return false;
    }

    /**
     * Restores a 1:M relationship by setting the appropriate column on the row that
     * stores the reference. For self-referencing relationships, the column is on the
     * side that has a relate field pointing back to this link.
     *
     * For a 1:M relationship, the FK column lives on the related (RHS) row and points
     * back to the parent (LHS) row. So we UPDATE the related row to set its FK column
     * to the parent record id.
     *
     * @return bool true if the UPDATE affected at least one row
     */
    private static function restoreOneToMany($bean, $linkName, $recordId, $relatedBean, $relDef, $db, $relId = null)
    {
        global $log;

        $rhsTable = !empty($relDef['rhs_table']) ? $relDef['rhs_table'] : (!empty($relDef['lhs_table']) ? $relDef['lhs_table'] : $relatedBean->table_name);
        if (!self::isValidIdentifier($rhsTable)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': invalid rhsTable: ' . $rhsTable);
            return false;
        }

        $ourColumn = null;
        foreach ($bean->field_defs as $fName => $fDef) {
            if (($fDef['type'] ?? '') === 'relate' && ($fDef['link'] ?? '') === $linkName) {
                $ourColumn = $fDef['id_name'] ?? $fName;
                break;
            }
        }
        if (empty($ourColumn)) {
            $ourColumn = !empty($relDef['rhs_key']) ? $relDef['rhs_key'] : (!empty($relDef['join_key_lhs']) ? $relDef['join_key_lhs'] : 'parent_id');
        }
        if (!self::isValidIdentifier($ourColumn)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': invalid ourColumn: ' . $ourColumn);
            return false;
        }

        $tableQ = self::quoteIdentifier($rhsTable);
        $columnQ = self::quoteIdentifier($ourColumn);
        $valueQ = $db->quoted($recordId);
        $whereQ = $db->quoted($relatedBean->id);

        // A 1:M child can only point to one parent: if it has been reassigned
        // to another record since the deletion, it is no longer orphan and must
        // not be stolen back. Only restore when the FK is empty or still ours.
        $checkResult = $db->query("SELECT $columnQ FROM $tableQ WHERE id = $whereQ AND deleted = 0");
        if ($checkResult === false) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': check query failed');
            return false;
        }
        $currentRow = $db->fetchByAssoc($checkResult);
        if (empty($currentRow)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': related row not available: ' . $relatedBean->id);
            self::setSkipReason($db, $relId, 'related_deleted');
            return false;
        }
        $currentParent = $currentRow[$ourColumn] ?? null;
        if ($currentParent !== null && $currentParent !== '' && $currentParent !== $recordId) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': related row reassigned to another parent (' . $currentParent . '), skipping: ' . $relatedBean->id);
            self::setSkipReason($db, $relId, 'reassigned');
            return false;
        }

        $sql = "UPDATE $tableQ SET $columnQ = $valueQ WHERE id = $whereQ AND deleted = 0"
            . " AND ($columnQ IS NULL OR $columnQ = '' OR $columnQ = $valueQ)";
        $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . $sql);
        $result = $db->query($sql);
        if ($result === false) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': query failed');
            return false;
        }
        $affected = $db->getAffectedRowCount($result);
        $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': affected rows: ' . $affected);
        return $affected > 0;
    }

    /**
     * Restores a many-to-many relationship by undeleting an existing row (deleted=1)
     * or inserting a new one when the row is missing. Special handling for
     * email_addr_bean_rel which requires bean_module and may need primary_address
     * set for email_addresses_primary.
     *
     * @param string $module Parent module being restored
     * @param string $linkName Link field name (used to detect primary variant for emails)
     * @param string $recordId Parent record ID
     * @param SugarBean $relatedBean Related record
     * @param array $relDef Relationship definition from the rel object
     * @param string $joinTable M2M join table
     * @param object $db Database instance
     * @param string|null $dateDeleted Deletion datetime of the bin entry (Y-m-d H:i:s)
     * @param string|null $relId Recycle bin relationship row ID (to record the skip reason)
     * @return bool true if the row was undeleted or inserted
     */
    private static function restoreManyToMany($module, $linkName, $recordId, $relatedBean, $relDef, $joinTable, $db, $dateDeleted = null, $relId = null)
    {
        global $log;

        if (!self::isValidIdentifier($joinTable)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': invalid joinTable: ' . $joinTable);
            return false;
        }

        $lhsModule = $relDef['lhs_module'] ?? '';
        $rhsModule = $relDef['rhs_module'] ?? '';
        $lhsKey = $relDef['join_key_lhs'] ?? '';
        $rhsKey = $relDef['join_key_rhs'] ?? $relDef['rhs_key'] ?? 'id';

        if ($lhsModule === $module) {
            $ourKey = $lhsKey;
            $theirKey = $rhsKey;
        } else {
            $ourKey = $rhsKey;
            $theirKey = $lhsKey;
        }

        if (!self::isValidIdentifier($ourKey) || !self::isValidIdentifier($theirKey)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': invalid keys: ourKey=' . $ourKey . ' theirKey=' . $theirKey);
            return false;
        }

        // Reassigned-record guard: if the related record has been linked to
        // another parent of the same module after this entry's deletion, it is
        // no longer orphan and restoring would give it two parents. Older
        // coexisting links (genuine multi-parent M2M) still restore normally.
        if (self::hasNewerParentLink($db, $module, $joinTable, $ourKey, $theirKey, $recordId, $relatedBean->id, $dateDeleted)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': related record reassigned to another parent after deletion, skipping: ' . $relatedBean->id);
            self::setSkipReason($db, $relId, 'reassigned');
            return false;
        }

        $ourKeyQ = self::quoteIdentifier($ourKey);
        $theirKeyQ = self::quoteIdentifier($theirKey);
        $ourIdQ = $db->quoted($recordId);
        $theirIdQ = $db->quoted($relatedBean->id);
        $joinTableQ = self::quoteIdentifier($joinTable);

        $nowDb = $GLOBALS['timedate']->nowDb();
        $nowDbQ = $db->quoted($nowDb);

        $selectSql = 'SELECT id, deleted FROM ' . $joinTableQ
            . ' WHERE ' . $ourKeyQ . ' = ' . $ourIdQ
            . ' AND ' . $theirKeyQ . ' = ' . $theirIdQ
            . ' ORDER BY deleted ASC LIMIT 1';
        $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . $selectSql);
        $selectResult = $db->query($selectSql);
        $existingRow = $selectResult ? $db->fetchByAssoc($selectResult) : null;

        if ($existingRow !== null) {
            $existingIdQ = $db->quoted($existingRow['id']);
            $updateSql = 'UPDATE ' . $joinTableQ
                . ' SET deleted = 0, date_modified = ' . $nowDbQ
                . ' WHERE id = ' . $existingIdQ;
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . $updateSql);
            $updateResult = $db->query($updateSql);
            if ($updateResult === false) {
                $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': undelete query failed');
                return false;
            }
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': row already existed (deleted=' . ($existingRow['deleted'] ?? '?') . '), set to 0');
            return true;
        }

        $columns = [self::quoteIdentifier('id'), $ourKeyQ, $theirKeyQ];
        $values = [$db->quoted(create_guid()), $ourIdQ, $theirIdQ];

        if ($joinTable === 'email_addr_bean_rel') {
            $columns[] = self::quoteIdentifier('bean_module');
            $values[] = $db->quoted($module);
            if (strpos((string) $linkName, '_primary') !== false) {
                $columns[] = self::quoteIdentifier('primary_address');
                $values[] = $db->quoted('1');
            }
        }

        $columns[] = self::quoteIdentifier('deleted');
        $values[] = '0';
        $columns[] = self::quoteIdentifier('date_modified');
        $values[] = $nowDbQ;

        $sql = 'INSERT INTO ' . $joinTableQ . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $values) . ')';
        $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . $sql);
        $result = $db->query($sql);
        if ($result === false) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': insert query failed');
            return false;
        }
        $affected = $db->getAffectedRowCount($result);
        $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': inserted, affected rows: ' . $affected);
        return $affected > 0;
    }

    /**
     * Checks whether the related record has been linked to another parent of
     * the same module after the bin entry's deletion date. Used to avoid
     * restoring a relationship over a reassignment (the record would end up
     * with two parents). Links older than the deletion (genuine multi-parent
     * M2M) do not block the restore.
     *
     * @param object $db Database instance
     * @param string $module Parent module being restored
     * @param string $joinTable M2M join table
     * @param string $ourKey Join column pointing to the parent side
     * @param string $theirKey Join column pointing to the related side
     * @param string $recordId Parent record ID being restored
     * @param string $relatedId Related record ID
     * @param string|null $dateDeleted Deletion datetime of the bin entry (Y-m-d H:i:s)
     * @return bool true if a newer link to another parent exists
     */
    private static function hasNewerParentLink($db, $module, $joinTable, $ourKey, $theirKey, $recordId, $relatedId, $dateDeleted)
    {
        global $log;

        if (!self::isValidIdentifier($joinTable) || !self::isValidIdentifier($ourKey) || !self::isValidIdentifier($theirKey)) {
            return false;
        }
        if (!self::isValidId($recordId) || !self::isValidId($relatedId)) {
            return false;
        }

        $joinTableQ = self::quoteIdentifier($joinTable);
        $ourKeyQ = self::quoteIdentifier($ourKey);
        $theirKeyQ = self::quoteIdentifier($theirKey);

        $sql = 'SELECT date_modified FROM ' . $joinTableQ
            . ' WHERE ' . $theirKeyQ . ' = ' . $db->quoted($relatedId)
            . ' AND ' . $ourKeyQ . ' != ' . $db->quoted($recordId)
            . ' AND deleted = 0';
        if ($joinTable === 'email_addr_bean_rel' && self::isValidModule($module)) {
            $sql .= " AND bean_module = " . $db->quoted($module);
        }
        $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . $sql);
        $result = $db->query($sql);
        if ($result === false) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': conflict check query failed, proceeding');
            return false;
        }

        while ($row = $db->fetchByAssoc($result)) {
            $linkModified = $row['date_modified'] ?? null;
            if ($linkModified === null || $linkModified === '') {
                $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': other active parent link without date, skipping restore: relatedId=' . $relatedId);
                return true;
            }
            if (empty($dateDeleted) || $linkModified >= $dateDeleted) {
                $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': other active parent link newer than deletion (' . $linkModified . ' >= ' . $dateDeleted . '), skipping restore: relatedId=' . $relatedId);
                return true;
            }
        }
        return false;
    }

    /**
     * Records why a relationship row was skipped (or clears the reason on success).
     *
     * @param object $db Database instance
     * @param string|null $relId Recycle bin relationship row ID
     * @param string $reason One of '', 'related_deleted', 'reassigned'
     * @return void
     */
    private static function setSkipReason($db, $relId, $reason)
    {
        if (!self::isValidId($relId) || !in_array($reason, array('', 'related_deleted', 'reassigned'), true)) {
            return;
        }
        $db->query(
            'UPDATE stic_recycle_bin_relationships SET skip_reason = ' . $db->quoted($reason)
            . ' WHERE id = ' . $db->quoted($relId)
        );
    }

    /**
     * Gets the database table name for a module.
     *
     * @param string $module Module name
     * @return string|null Table name or null if module cannot be loaded
     */
    private static function getTableForModule($module)
    {
        $bean = BeanFactory::newBean($module);
        if (!$bean) {
            return null;
        }
        return $bean->table_name;
    }

    /**
     * Validates a SugarCRM identifier (table/column name). Allows letters, digits, underscores.
     *
     * @param string $name Identifier to validate
     * @return bool true if safe to interpolate into SQL
     */
    private static function isValidIdentifier($name)
    {
        return is_string($name) && preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $name) === 1;
    }

    /**
     * Quotes a validated identifier with backticks.
     *
     * @param string $name Validated identifier
     * @return string Backtick-quoted identifier
     */
    private static function quoteIdentifier($name)
    {
        return '`' . $name . '`';
    }

    /**
     * Validates that a module name is safe to pass to BeanFactory.
     *
     * @param string $module Module name
     * @return bool true if the module name matches the expected pattern
     */
    private static function isValidModule($module)
    {
        return is_string($module) && preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $module) === 1;
    }

    /**
     * Validates that an ID is a UUID (36-char with hyphens).
     *
     * @param string $id ID to validate
     * @return bool true if the value matches the UUID pattern
     */
    private static function isValidId($id)
    {
        return is_string($id) && preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $id) === 1;
    }
}
