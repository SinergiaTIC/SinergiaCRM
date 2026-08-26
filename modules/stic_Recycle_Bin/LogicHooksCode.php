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
        $recycleBean = BeanFactory::newBean('stic_Recycle_Bin');
        $recycleBean->new_with_id = true;
        $recycleBean->id = $recycleBinId;
        $recycleBean->name = $recordName;
        $recycleBean->date_entered = $dateDeleted;
        $recycleBean->date_modified = $dateDeleted;
        $recycleBean->created_by = $createdById;
        $recycleBean->recycle_module = $module;
        $recycleBean->recycle_record_id = $recordId;
        $recycleBean->recycle_record_name = $recordName;
        $recycleBean->recycle_date_deleted = $dateDeleted;
        $recycleBean->recycle_user_deleted_id = $userId;
        $recycleBean->assigned_user_id = $bean->assigned_user_id ?? '';
        $recycleBean->save(false);

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
            foreach (array_keys($relatedIds) as $rid) {
                if (self::isValidId($rid)) {
                    $quotedIds[] = $db->quoted($rid);
                }
            }
            if (empty($quotedIds)) {
                continue;
            }

            $selectSql = 'SELECT id, name FROM `' . $relatedTable . '` WHERE id IN (' . implode(',', $quotedIds) . ') AND deleted = 0';
            $selectResult = $db->query($selectSql);

            while ($row = $db->fetchByAssoc($selectResult)) {
                $relatedBean = new stdClass();
                $relatedBean->id = $row['id'];
                $relatedBean->name = $row['name'] ?? '';
                $relatedBean->module_dir = $relatedModule;
                $relatedBean->deleted = 0;
                $this->insertRelationshipRow(
                    $bean, $fieldName, $relatedBean, $recycleBinId, $db,
                    $joinTable, $lhsKey, $rhsKey
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
     * @param string $lhsKey LHS join key
     * @param string $rhsKey RHS join key
     * @return void
     */
    private function insertRelationshipRow($bean, $fieldName, $relatedBean, $recycleBinId, $db, $joinTable, $lhsKey, $rhsKey)
    {
        if (!self::isValidIdentifier($lhsKey) || !self::isValidIdentifier($rhsKey) || !self::isValidIdentifier($fieldName)) {
            return;
        }

        $relId = create_guid();
        $relRecordName = $db->quoted($relatedBean->name ?? '');
        $binId = $db->quoted($recycleBinId);
        $recId = $db->quoted($bean->id);
        $userIdQ = $db->quoted($bean->modified_user_id ?? '1');
        $relNameQ = $db->quoted($fieldName);
        $joinTableQ = $db->quoted($joinTable);
        $relModule = $db->quoted($relatedBean->module_dir);
        $relRecordId = $db->quoted($relatedBean->id);
        $lhsKeyQ = $db->quoted($lhsKey);
        $rhsKeyQ = $db->quoted($rhsKey);

        $sql = "INSERT INTO stic_recycle_bin_relationships (
                    id, name, date_entered, date_modified, created_by,
                    stic_recycle_bin_id, recycle_record_id, recycle_relationship_name,
                    recycle_join_table, recycle_related_module,
                    recycle_related_record_id, recycle_related_record_name,
                    recycle_join_lhs_key, recycle_join_rhs_key
                ) VALUES (
                    " . $db->quoted($relId) . ",
                    " . $relRecordName . ",
                    NOW(), NOW(),
                    " . $userIdQ . ",
                    " . $binId . ",
                    " . $recId . ",
                    " . $relNameQ . ",
                    " . $joinTableQ . ",
                    " . $relModule . ",
                    " . $relRecordId . ",
                    " . $relRecordName . ",
                    " . $lhsKeyQ . ",
                    " . $rhsKeyQ . "
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
