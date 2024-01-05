<?php
class stic_Security_Groups_Rules_Rules
{

    /**
     * after_save function for SuiteCRM, handling security group inheritance.
     * This function applies security group inheritance rules to newly created records
     * based on conditions such as the assigned user, creator, and parent records.
     *
     * @param SugarBean $bean The current bean being processed.
     * @param string $event The event that triggers the function.
     * @param array $arguments Additional arguments passed to the function.
     */
    public function after_save(&$bean, $event, $arguments)
    {
        // Skip processing if $sugar_config['stic_security_groups_rules_enabled'] is disabled
        global $sugar_config;
        if ($sugar_config['stic_security_groups_rules_enabled'] === false) {
            return;
        }

        // Skip processing for the 'SugarFeed' module
        if (in_array($bean->module_name, ['SugarFeed'])) {
            return;
        }

        // Process only if it's a new record
        if (!$bean->fetched_row) {

            // Retrieve module-specific inheritance rules
            $rulesBean = self::getModuleRule($bean->module_name);

            // Check if there is a defined and active inheritance rule for the module
            if (!empty($rulesBean) && $rulesBean->active == 1) {

                // Initialize an array to store candidate security groups for inheritance
                $securityGroupsCandidatesToInherit = [];

                // Include necessary modules for security group processing
                require_once 'modules/SecurityGroups/SecurityGroup.php';
                require_once 'SticInclude/Utils.php';
                require_once 'modules/stic_Security_Groups_Rules/Utils.php';

                // Inherit security groups from the assigned user, if enabled
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

                // Inherit security groups from the creator user, if enabled
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

                // Inherit security groups from parent records, if enabled
                $allRelatedModules = stic_Security_Groups_RulesUtils::getRelatedModulesList($bean->module_dir, 'module_names');
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

                // Create an array of security groups that are not inheritable under any circumstances for the current module
                $notInheritableGroups = unencodeMultienum($rulesBean->non_inherit_from_security_groups);
                // Add globally defined non-inheritable security groups
                $result = $bean->db->query("SELECT id FROM securitygroups WHERE deleted=0 AND noninheritable=1");
                foreach ($result as $row) {
                    $notInheritableGroups[] = $row['id'];
                }

                // Add security groups to the record, excluding the non-inheritable ones
                $securityGroupBean = BeanFactory::newBean('SecurityGroups');
                foreach ($securityGroupsCandidatesToInherit as $key => $item) {
                    if (!in_array($item['securitygroup_id'], $notInheritableGroups)) {
                        $securityGroupBean->addGroupToRecord($bean->module_name, $item['record_id'], $item['securitygroup_id']);
                    }
                }

                // Assign default groups defined in the general configuration for the module
                SecurityGroup::assign_default_groups($bean, false);

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
     * Retrieve the defined rule for a specified module.
     *
     * This method searches the database for a rule associated with a module
     * and returns a bean with the rule configuration.
     *
     * @param String $moduleName The name of the module for which the rule is sought.
     * @return Object The bean of the found rule, or null if not found.
     */
    public static function getModuleRule($moduleName)
    {
        global $db;
        // Query to obtain the rule ID based on the module name
        $ruleId = $db->getOne("SELECT id FROM stic_security_groups_rules WHERE name='{$moduleName}' AND deleted=0 AND active=true");

        // Retrieve and return the rule bean using the obtained ID
        $rulesBean = BeanFactory::getBean('stic_Security_Groups_Rules', $ruleId);
        return $rulesBean;
    }

    /**
     * Verify and obtain the related field for a specific bean.
     *
     * This method retrieves the value of a related field of a specific bean
     * based on its ID. It is useful for verifying relationships in SuiteCRM.
     *
     * @param String $relatedID The ID of the related field to be verified.
     * @param Object $bean The bean for which the related field is sought.
     * @return String The value of the related field, or an empty string if not found.
     */
    public static function checkRelatedField($relatedID, $bean)
    {
        global $db;
        // Query to obtain the value of the related field based on the bean's ID
        $relID = $db->getOne("SELECT {$relatedID} FROM contacts_cstm WHERE id_c='{$bean->id}' LIMIT 1");

        return $relID;
    }

}
