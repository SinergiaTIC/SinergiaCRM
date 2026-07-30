<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class TtsListviewOrder
{
    public function getOrderedIds($listContext)
    {
        $module = isset($listContext['module']) ? $listContext['module'] : '';
        $uids = isset($listContext['uids']) ? $listContext['uids'] : array();
        $currentQueryByPage = isset($listContext['current_query_by_page']) ? $listContext['current_query_by_page'] : '';
        $lvso = isset($listContext['lvso']) ? $listContext['lvso'] : 'ASC';
        $orderBy = isset($listContext['orderBy']) ? $listContext['orderBy'] : '';
        $selectEntireList = isset($listContext['select_entire_list']) ? $listContext['select_entire_list'] : '0';

        if ($selectEntireList !== '1') {
            return $uids;
        }

        if (empty($module) || empty($uids)) {
            return $uids;
        }

        $seed = BeanFactory::getBean($module);
        if (!$seed) {
            return $uids;
        }

        $tableName = $seed->table_name;
        $where = $this->buildWhereFromQuery($currentQueryByPage, $seed);
        global $db;
        $quotedIds = array();
        foreach ($uids as $uid) {
            $quotedIds[] = "'" . $db->quote($uid) . "'";
        }
        $where .= (empty($where) ? '' : ' AND ') . $tableName . '.id IN (' . implode(',', $quotedIds) . ')';

        $orderByClause = $this->buildOrderBy($seed, $orderBy, $lvso);

        $query = "SELECT " . $tableName . ".id FROM " . $tableName
               . " WHERE " . $tableName . ".deleted = 0"
               . (empty($where) ? '' : " AND " . $where)
               . (empty($orderByClause) ? '' : " ORDER BY " . $orderByClause);

        $result = $db->query($query);
        $orderedIds = array();
        while ($row = $db->fetchByAssoc($result)) {
            $orderedIds[] = $row['id'];
        }

        return !empty($orderedIds) ? $orderedIds : $uids;
    }

    private function buildWhereFromQuery($currentQueryByPage, $seed)
    {
        if (empty($currentQueryByPage)) {
            return '';
        }
        $queryData = json_decode($currentQueryByPage, true);
        if ($queryData === null || !is_array($queryData)) {
            return '';
        }

        if (!isset($queryData['searchFields'])) {
            return '';
        }

        $whereClauses = array();
        foreach ($queryData['searchFields'] as $field => $value) {
            if (empty($value)) {
                continue;
            }
            $condition = $this->buildSearchCondition($field, $value, $seed);
            if ($condition !== '') {
                $whereClauses[] = $condition;
            }
        }

        return implode(' AND ', $whereClauses);
    }

    private function buildSearchCondition($field, $value, $seed)
    {
        if (!isset($seed->field_defs[$field])) {
            return '';
        }
        global $db;
        $column = $seed->table_name . '.' . $field;
        if (is_array($value)) {
            $parts = array();
            foreach ($value as $v) {
                $parts[] = $column . " LIKE '%" . $db->quote($v) . "%'";
            }
            return '(' . implode(' OR ', $parts) . ')';
        }
        return $column . " LIKE '%" . $db->quote($value) . "%'";
    }

    private function buildOrderBy($seed, $orderBy, $lvso)
    {
        if (empty($orderBy) || empty($lvso)) {
            return '';
        }
        if (!isset($seed->field_defs[$orderBy])) {
            return '';
        }
        $direction = strtoupper($lvso) === 'DESC' ? 'DESC' : 'ASC';
        return $seed->table_name . '.' . $orderBy . ' ' . $direction;
    }
}
