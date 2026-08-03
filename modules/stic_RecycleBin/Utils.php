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
class stic_RecycleBinUtils
{
    /**
     * Restores a single deleted record and all its relationships.
     *
     * @param string $recycleBinId ID of the stic_RecycleBin entry
     * @return array Result with success flag, message, and counters
     */
    public static function restoreRecord($recycleBinId)
    {
        global $db, $current_user, $log;

        if (!self::isValidId($recycleBinId)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': invalid recycleBinId: ' . $recycleBinId);
            return [
                'success' => false,
                'message' => translate('LBL_RESTORE_INVALID_ID', 'stic_RecycleBin'),
            ];
        }

        $binBean = BeanFactory::getBean('stic_RecycleBin', $recycleBinId);
        if (!$binBean || empty($binBean->recycle_record_id)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': recycle bin entry not found: ' . $recycleBinId);
            return [
                'success' => false,
                'message' => translate('LBL_RESTORE_NOT_FOUND', 'stic_RecycleBin'),
            ];
        }

        if (!self::isValidId($binBean->recycle_record_id)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': invalid recycle_record_id: ' . $binBean->recycle_record_id);
            return [
                'success' => false,
                'message' => translate('LBL_RESTORE_INVALID_ID', 'stic_RecycleBin'),
            ];
        }

        if (!empty($binBean->recycle_restored)) {
            return [
                'success' => false,
                'message' => translate('LBL_RESTORE_ALREADY', 'stic_RecycleBin'),
            ];
        }

        $module = $binBean->recycle_module;
        $recordId = $binBean->recycle_record_id;

        $table = self::getTableForModule($module);
        if (!$table) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': cannot resolve table for module: ' . $module);
            return [
                'success' => false,
                'message' => translate('LBL_RESTORE_NO_TABLE', 'stic_RecycleBin'),
            ];
        }

        $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': restoring record: module=' . $module . ' id=' . $recordId);

        $db->query('UPDATE ' . self::quoteIdentifier($table) . ' SET deleted = 0 WHERE id = ' . $db->quoted($recordId));

        $relationsRestored = 0;
        $relationsSkipped = 0;

        $relsResult = $db->query(
            'SELECT * FROM stic_recycle_bin_relationships
             WHERE recyclebin_id = ' . $db->quoted($recycleBinId) . '
             AND deleted = 0 AND recycle_restored = 0'
        );

        while ($rel = $db->fetchByAssoc($relsResult)) {
            $restored = self::reinsertRelationshipRow($rel, $module, $recordId, $db);
            if ($restored) {
                $relationsRestored++;
            } else {
                $relationsSkipped++;
            }
        }

        $nowDb = $GLOBALS['timedate']->nowDb();
        $currentUserId = $current_user->id ?? '1';
        $db->query(
            'UPDATE stic_recycle_bin SET
                recycle_date_restored = ' . $db->quoted($nowDb) . ',
                recycle_user_restored_id = ' . $db->quoted($currentUserId) . ',
                recycle_restored = 1
             WHERE id = ' . $db->quoted($recycleBinId)
        );

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
     * @param array $ids Array of stic_RecycleBin IDs
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
     * Reinserts a relationship row for a restored record. Dispatches by relationship
     * cardinality: many-to-many (join table) or one-to-many (column on the related row).
     *
     * @param array $rel Relationship row from stic_recycle_bin_relationships
     * @param string $module Parent module being restored
     * @param string $recordId Parent record ID
     * @param object $db Database instance
     * @return bool true if restored, false if skipped
     */
    private static function reinsertRelationshipRow($rel, $module, $recordId, $db)
    {
        global $log;

        $linkName = $rel['recycle_relationship_name'];
        $joinTable = $rel['recycle_join_table'];
        $lhsKey = $rel['recycle_join_lhs_key'];
        $rhsKey = $rel['recycle_join_rhs_key'];

        if (empty($linkName)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': empty linkName, skipping');
            return false;
        }

        if (!self::isValidModule($rel['recycle_related_module'])) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': invalid related module: ' . $rel['recycle_related_module']);
            return false;
        }
        if (!self::isValidId($rel['recycle_related_record_id'])) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': invalid related id: ' . $rel['recycle_related_record_id']);
            return false;
        }

        $relatedBean = BeanFactory::getBean($rel['recycle_related_module'], $rel['recycle_related_record_id'], [], true);
        if (!$relatedBean || !empty($relatedBean->deleted) || empty($relatedBean->id)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': related record not available (soft-deleted or missing): ' . $rel['recycle_related_module'] . ' / ' . $rel['recycle_related_record_id']);
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
        $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': restoring relationship: link=' . $linkName . ' relatedModule=' . $rel['recycle_related_module'] . ' relatedId=' . $rel['recycle_related_record_id'] . ' joinTable=' . $joinTable . ' lhsKey=' . $lhsKey . ' rhsKey=' . $rhsKey);

        $restored = false;
        if (empty($joinTable)) {
            $restored = self::restoreOneToMany($bean, $linkName, $recordId, $relatedBean, $relDef, $lhsKey, $db);
        } else {
            $restored = self::restoreManyToMany($module, $linkName, $recordId, $relatedBean, $relDef, $lhsKey, $rhsKey, $joinTable, $db);
        }

        if ($restored) {
            $db->query(
                'UPDATE stic_recycle_bin_relationships SET recycle_restored = 1
                 WHERE id = ' . $db->quoted($rel['id'])
            );
            return true;
        }

        $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': relationship UPDATE affected 0 rows or failed: link=' . $linkName . ' relatedId=' . $rel['recycle_related_record_id']);
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
    private static function restoreOneToMany($bean, $linkName, $recordId, $relatedBean, $relDef, $lhsKey, $db)
    {
        global $log;

        $rhsTable = !empty($relDef['rhs_table']) ? $relDef['rhs_table'] : (!empty($relDef['lhs_table']) ? $relDef['lhs_table'] : $bean->table_name);
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
            $ourColumn = !empty($relDef['rhs_key']) ? $relDef['rhs_key'] : (!empty($relDef['join_key_lhs']) ? $relDef['join_key_lhs'] : (!empty($lhsKey) ? $lhsKey : 'parent_id'));
        }
        if (!self::isValidIdentifier($ourColumn)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': invalid ourColumn: ' . $ourColumn);
            return false;
        }

        $tableQ = self::quoteIdentifier($rhsTable);
        $columnQ = self::quoteIdentifier($ourColumn);
        $valueQ = $db->quoted($recordId);
        $whereQ = $db->quoted($relatedBean->id);

        $sql = "UPDATE $tableQ SET $columnQ = $valueQ WHERE id = $whereQ AND deleted = 0";
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
     * @param string $lhsKey Join key for LHS side
     * @param string $rhsKey Join key for RHS side
     * @param string $joinTable M2M join table
     * @param object $db Database instance
     * @return bool true if the row was undeleted or inserted
     */
    private static function restoreManyToMany($module, $linkName, $recordId, $relatedBean, $relDef, $lhsKey, $rhsKey, $joinTable, $db)
    {
        global $log;

        if (!self::isValidIdentifier($joinTable)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': invalid joinTable: ' . $joinTable);
            return false;
        }

        $lhsModule = $relDef['lhs_module'] ?? '';
        $rhsModule = $relDef['rhs_module'] ?? '';

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
                $values[] = '1';
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
