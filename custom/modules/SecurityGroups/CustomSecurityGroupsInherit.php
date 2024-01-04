<?php
class CustomSecurityGroupsInherit
{

    public function after_save(&$bean, $event, $arguments)
    {

        if (in_array($bean->module_name, ['SugarFeed'])) {
            return;
        }

        if (!$bean->fetched_row) {

            $rulesBean = self::getModuleRule($bean->module_name);

            // Comprobamos si hay una regla definida y activa de herencia para el módulo.
            if (!empty($rulesBean) && $rulesBean->active == 1) {

                $securityGroupsCandidatesToInherit = [];

                require_once 'modules/SecurityGroups/SecurityGroup.php';
                require_once 'SticInclude/Utils.php';
                require_once 'modules/stic_Advanced_Security_Groups/Utils.php';

                // Get security groups for assigned user if enabled
                if ($rulesBean->inherit_assigned == 1) {
                    $userGroups = SecurityGroup::getUserSecurityGroups($bean->assigned_user_id);

                    if (!empty($userGroups)) {
                        foreach ($userGroups as $group) {
                            $securityGroupsCandidatesToInherit = array_merge(
                                $securityGroupsCandidatesToInherit,
                                [['record_id' => $bean->id, 'securitygroup_id' => $group['id']]]
                            );
                        }
                    }
                }

                // Get security groups for creator user if enabled
                if ($rulesBean->inherit_creator == 1) {
                    $userGroups = SecurityGroup::getUserSecurityGroups($bean->created_by);

                    if (!empty($userGroups)) {
                        foreach ($userGroups as $group) {
                            $securityGroupsCandidatesToInherit = array_merge(
                                $securityGroupsCandidatesToInherit,
                                [['record_id' => $bean->id, 'securitygroup_id' => $group['id']]]
                            );
                        }
                    }
                }

                // get security groups for parent records if the inheritance of the parent is enabled (for all modules or only for some modules)
                $allRelatedModules = stic_Advanced_Security_GroupsUtils::getRelatedModulesList($bean->module_dir, 'module_names');
                $filteredRelatedModules = unencodeMultienum($rulesBean->inherit_from_modules);
                foreach ($allRelatedModules as $value) {
                    if (!empty($bean->{$value['field']})) {
                        if ($rulesBean->inherit_parent == 1 || in_array($value['relationship'], $filteredRelatedModules)) {
                            $currentRecordGroups = self::getRelatedSecurityGroupIDs($bean->{$value['field']});
                            foreach ($currentRecordGroups as $val2) {
                                $securityGroupsCandidatesToInherit = array_merge(
                                    $securityGroupsCandidatesToInherit,
                                    [['record_id' => $bean->id, 'securitygroup_id' => $val2]]
                                );
                            }

                        }
                    }
                }

                // Creamos un array con los grupos que no son heredables en ningún caso para el módulo actual
                $notInheritableGroups = unencodeMultienum($rulesBean->non_inherit_from_security_groups);
                // Añadimos los grupos de seguridad definidos como NO heredables de forma global
                $result = $bean->db->query("SELECT id FROM securitygroups where deleted=0 and noninheritable=1");
                foreach ($result as $row) {
                    $notInheritableGroups[] = $row['id'];
                }

                // Añadimos los grupos de seguridad al registro, exluyendo los no heredables
                $securityGroupBean = BeanFactory::newBean('SecurityGroups');
                foreach ($securityGroupsCandidatesToInherit as $key => $item) {
                    if (!in_array($item['securitygroup_id'], $notInheritableGroups)) {
                        $securityGroupBean->addGroupToRecord($bean->module_name, $item['record_id'], $item['securitygroup_id']);
                    }
                }

                // Añadimos los grupos por defecto definidos en la configuración general para el módulo
                SecurityGroup::assign_default_groups($bean, $false);

                // This code is only for develop purposes and must be comment in production
                // foreach ($securityGroupsCandidatesToInherit as $key => $sg) {
                //     $securityGroupsCandidatesToInherit[$key]['grupo'] = $bean->db->getOne("SELECT name from securitygroups WHERE id='{$sg['securitygroup_id']}'");
                // }
                // var_dump('$securityGroupsCandidatesToInherit', $securityGroupsCandidatesToInherit);
                // var_dump('$notInheritableGroups', $notInheritableGroups);
                // die();

            }
        }
    }


    /**
 * Retrieves a list of security group IDs associated with a given record ID.
 *
 * This function executes a database query to fetch distinct security group IDs from the 
 * 'securitygroups_records' and 'securitygroups' tables. It selects IDs where the record's 
 * ID matches the specified 'relatedRecordID', and where both 'deleted' and 'noninheritable' flags are set to 0.
 *
 * @param int|string $relatedRecordID The ID of the record for which related security group IDs are to be fetched.
 * @return array An array of security group IDs associated with the specified record ID.
 * @global object $databaseConnection A global database connection object used to execute the query.
 */
public static function getRelatedSecurityGroupIDs($relatedRecordID)
{
    global $db;
    $securityGroupIDs = [];
    $queryResult = $db->query("SELECT DISTINCT securitygroup_id
                                               FROM securitygroups_records
                                               LEFT JOIN securitygroups ON securitygroups_records.securitygroup_id = securitygroups.id
                                               WHERE securitygroups_records.record_id = '{$relatedRecordID}'
                                               AND securitygroups_records.deleted = 0
                                               AND securitygroups.noninheritable = 0");

    foreach ($queryResult as $row) {
        $securityGroupIDs[] = $row['securitygroup_id'];
    }

    return $securityGroupIDs;
}
    



    /**
     * Obtener la regla definida para el módulo
     *
     * @param String $moduleName
     * @return Object
     */
    public static function getModuleRule($moduleName)
    {
        global $db;
        $ruleId = $db->getOne("SELECT id FROM stic_advanced_security_groups WHERE name='{$moduleName}' AND deleted=0 AND active=true");
        $rulesBean = BeanFactory::getBean('stic_Advanced_Security_Groups', $ruleId);
        return $rulesBean;

    }

    public static function checkrelatedField($relatedID, $bean)
    {
        global $db;
        $relID = '';
        $relID = $db->getOne("SELECT {$relatedID} FROM contacts_cstm WHERE id_c='{$bean->id}' LIMIT 1");
        //$relID = $db->getOne("SELECT id FROM securitygroups WHERE id='{$relatedID}' AND deleted=0 LIMIT 1");

        return $relID;
    }

}
