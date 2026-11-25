<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class Stic_SecurityGroupsListViewOptimization
{
    public function optimizeGroupWhere($bean, $event, $arguments)
    {
        if ($arguments->view !== 'list') {
            return;
        }

        if (!isset($arguments->conditions['group'])) {
            return;
        }

        global $sugar_config;
        $userId = $arguments->user->id;
        $db = DBManagerFactory::getInstance();

        if ($bean->module_dir === 'Users'
            && !is_admin($arguments->user)
            && isset($sugar_config['securitysuite_filter_user_list'])
            && $sugar_config['securitysuite_filter_user_list']
        ) {
            $groupIds = $this->getUserGroupIds($userId);
            if (empty($groupIds)) {
                return;
            }
            $groupIdList = $this->formatInList($groupIds);
            $arguments->conditions['group'] = " users.id in (
                select sec.user_id from securitygroups_users sec
                inner join securitygroups secg on sec.securitygroup_id = secg.id and secg.deleted = 0
                where sec.deleted = 0 and sec.securitygroup_id in ({$groupIdList})
            )";
            return;
        }

        $groupIds = $this->getUserGroupIds($userId);
        if (empty($groupIds)) {
            return;
        }

        $groupIdList = $this->formatInList($groupIds);
        $tableName = $bean->table_name;
        $module = $bean->module_dir;

        if ($module === 'SecurityGroups') {
            $arguments->conditions['group'] = " {$tableName}.id in (
                select secg.id from securitygroups secg
                inner join securitygroups_users secu on secg.id = secu.securitygroup_id and secu.deleted = 0
                    and secu.user_id = '{$db->quote($userId)}'
                where secg.deleted = 0 and secg.id in ({$groupIdList})
            )";
            return;
        }

        $simplifiedExists = "EXISTS (SELECT 1 FROM securitygroups_records secr
            WHERE secr.record_id = {$tableName}.id
                AND secr.deleted = 0
                AND secr.module = '{$module}'
                AND secr.securitygroup_id IN ({$groupIdList}))";

        $ownerWhere = $bean->getOwnerWhere($userId);
        if (!empty($ownerWhere)) {
            $arguments->conditions['group'] = " ({$ownerWhere} OR {$simplifiedExists}) ";
        } else {
            $arguments->conditions['group'] = $simplifiedExists;
        }
    }

    private function getUserGroupIds($userId)
    {
        $db = DBManagerFactory::getInstance();
        $quotedUserId = $db->quote($userId);

        $query = "SELECT secu.securitygroup_id
            FROM securitygroups_users secu
            INNER JOIN securitygroups secg ON secg.id = secu.securitygroup_id AND secg.deleted = 0
            WHERE secu.user_id = '{$quotedUserId}' AND secu.deleted = 0";

        $result = $db->query($query);
        $groupIds = array();
        while ($row = $db->fetchByAssoc($result)) {
            $groupIds[] = $row['securitygroup_id'];
        }
        return $groupIds;
    }

    private function formatInList($groupIds)
    {
        $db = DBManagerFactory::getInstance();
        $quoted = array();
        foreach ($groupIds as $id) {
            $quoted[] = $db->quote($id);
        }
        return "'" . implode("','", $quoted) . "'";
    }

    public static function getUserAccessibleRecordIds($module, $action = 'list')
    {
        global $current_user, $sugar_config, $db;

        $ids = array();

        $query = "SELECT DISTINCT secr.record_id "
            . "FROM securitygroups_records secr "
            . "INNER JOIN securitygroups_users secu "
            . "  ON secr.securitygroup_id = secu.securitygroup_id "
            . "  AND secu.deleted = 0 "
            . "  AND secu.user_id = '{$db->quote($current_user->id)}' ";

        if (!empty($action)
            && isset($sugar_config['securitysuite_strict_rights'])
            && $sugar_config['securitysuite_strict_rights'] == true
        ) {
            $query .= "INNER JOIN securitygroups_acl_roles sar "
                . "  ON secr.securitygroup_id = sar.securitygroup_id AND sar.deleted = 0 "
                . "INNER JOIN acl_roles_actions ara "
                . "  ON sar.role_id = ara.role_id AND ara.deleted = 0 "
                . "INNER JOIN acl_actions aa "
                . "  ON ara.action_id = aa.id AND aa.deleted = 0 "
                . "  AND aa.category = '{$db->quote($module)}' AND aa.name = '{$db->quote($action)}' ";
        }

        $query .= "WHERE secr.deleted = 0 AND secr.module = '{$db->quote($module)}'";

        if (!empty($action)
            && isset($sugar_config['securitysuite_strict_rights'])
            && $sugar_config['securitysuite_strict_rights'] == true
        ) {
            $query .= " AND ara.access_override = 80";
        }

        $result = $db->query($query);
        while ($row = $db->fetchByAssoc($result)) {
            $ids[$row['record_id']] = true;
        }

        return $ids;
    }
}