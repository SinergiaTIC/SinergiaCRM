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

// Including necessary dependencies
global $current_user, $app_strings, $sugar_config, $app_list_strings;
require_once 'modules/SecurityGroups/SecurityGroup.php';
require_once 'modules/MySettings/TabController.php';
require_once 'SticInclude/Utils.php';

// Checking if the current user is an administrator
if (!is_admin($current_user)) {
    sugar_die($app_strings['ERR_NOT_ADMIN']);
}

// Class stic_Security_Groups_RulesUtils
class stic_Security_Groups_RulesUtils
{
    /**
     * Generates and returns an array with filtered modules.
     *
     * @return array Array of filtered modules.
     */
    public static function generateFilteredModuleArray()
    {
        global $app_list_strings;
        $systemTabs = TabController::get_system_tabs();
        $resArray = array();

        // Filtering modules based on specific criteria
        foreach ($app_list_strings['moduleList'] as $key => $val) {
            if (!in_array($key, $GLOBALS['modInvisList']) && in_array($key, $systemTabs) && $key != 'Home') {
                $resArray[$key] = $val;
            } else {
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . $key . ' Module discarded');
            }
        }

        asort($resArray);
        return $resArray;
    }

    /**
     * Sets the custom filtered module list.
     */
    public static function setCustomFilteredModuleList()
    {
        $resArray = self::generateFilteredModuleArray();
        $overridedListName = 'dynamic_filtered_module_list';
        global $app_list_strings;
        $app_list_strings[$overridedListName] = $resArray;
    }

    /**
     * Generates and returns an array with related module options.
     *
     * @param string $mainModule The main module.
     * @return array Returns an array for each related module, including the module name, relationship name, related field, and the translated label of the relationship.
     */
    public static function getRelatedModulesList($mainModule)
    {
        global $app_list_strings;
        $mainModuleBean = BeanFactory::newBean($mainModule);
        $options = array();

        if (!empty($mainModuleBean)) {
            foreach ($mainModuleBean->getFieldDefinitions() as $val) {
                unset($destModuleLabel, $moduleLabel);
                $moduleLabel = translate($val['vname'], $mainModule);
                if (!empty($val['module']) && $moduleLabel != $app_list_strings['moduleList'][$val['module']]) {
                    $destModuleLabel = " ({$app_list_strings['moduleList'][$val['module']]})";
                }
                if (isset($val['type']) && $val['type'] === 'relate' && ($val['ext2'] && $val['module'] !== 'SecurityGroups') && $val['id_name']) {
                    $options[] = [
                        'relationship' => $mainModule . $val['id_name'],
                        'field' => $val['id_name'],
                        'module' => $val['module'],
                        'label' => translate($val['vname'], $mainModule) . $destModuleLabel,
                    ];
                } else if (isset($val['type']) && in_array($val['type'], ['link', 'relate']) && !in_array($val['module'], ['SecurityGroups', 'Users'])) {
                    if (empty($val['link'])) {
                        // n:n relationships
                        if (!in_array($val['relationship'], array_column($options, 'relationship'))) {
                            $options[] = [
                                'relationship' => $mainModule . '__' . $val['relationship'],
                                'field' => $val['name'],
                                'module' => $val['module'],
                                'label' => translate($val['vname'], $mainModule) . $destModuleLabel,
                            ];
                        }
                    } elseif (!in_array($val['link'], array_column($options, 'relationship'))) {
                        // 1:n or 1:1 relationships
                        $options[] = [
                            'relationship' => $mainModule . '__' . $val['link'],
                            'field' => $val['id_name'],
                            'module' => $val['module'],
                            'label' => translate($val['vname'], $mainModule) . $destModuleLabel,
                        ];

                    }
                }
            }
        }

        // orden alfabético
        usort($options, function ($a, $b) {return strcmp($a['label'], $b['label']);});

        return $options;
    }

    /**
     * Sets the custom related module list.
     *
     * @param string $mainModule The main module.
     */
    public static function setCustomRelatedModuleList($mainModule)
    {
        $options = array_column(self::getRelatedModulesList($mainModule), 'label', 'relationship');
        $overridedListName = 'dynamic_related_module_list';
        global $app_list_strings;
        $app_list_strings[$overridedListName] = $options;
    }

    /**
     * Sets a list of all related modules with their labels.
     * This function retrieves and populates a list with all existing module relationships and their labels,
     * filtering out specific types and modules.
     */
    public static function setAllRelatedModuleList()
    {
        global $app_list_strings;
        $systemTabs = TabController::get_system_tabs();
        $options = [];

        foreach ($systemTabs as $key => $mainModule) {
            // if (!in_array($mainModule, ['stic_Payment_Commitments'])) {continue;}

            $thisModuleRels = self::getRelatedModulesList($mainModule);
            if (is_array($thisModuleRels)) {

                foreach ($thisModuleRels as $rel) {
                    if (!empty($rel['relationship'])) {$options[$rel['relationship']] = $rel['label'];}

                }
            }

        }

        // orden alfabético
        uasort($options, function ($a, $b) {return strcmp($a, $b);});
        // var_dump($options);die();

        global $app_list_strings;
        $overridedListName = 'dynamic_related_module_list';
        $app_list_strings[$overridedListName] = $options;
    }

    /**
     * Sets a custom list of security groups.
     * This function retrieves all active security groups from the database
     * and populates a global list with their IDs and names.
     */
    public static function setCustomSecurityGroupList()
    {
        global $db, $app_list_strings;
        $overridedListName = 'dynamic_security_group_list';
        $sql = "SELECT id, name as 'value' FROM securitygroups WHERE deleted = 0";

        unset($app_list_strings[$overridedListName]);
        $result = $db->query($sql);

        $app_list_strings[$overridedListName][''] = '';
        while ($row = $db->fetchByAssoc($result)) {
            $app_list_strings[$overridedListName][$row['id']] = $row['value'];
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
                                               AND securitygroups.noninheritable = 0
                                               AND securitygroups.deleted=0");

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
     * Function for SuiteCRM, handling security group inheritance.
     * This function applies custom security group inheritance rules to newly created records
     * based on conditions such as the assigned user, creator, and parent records.
     *
     * @param SugarBean $bean The current bean being processed.
     */
    public static function applyCustomInheritance($bean)
    {

        // Skip processing for the 'SugarFeed' module
        if (in_array($bean->module_name, ['SugarFeed'])) {
            return;
        }

        // Retrieve module-specific inheritance rules
        $rulesBean = self::getModuleRule($bean->module_name);

        // Check if there is a defined and active inheritance rule for the module
        if (!empty($rulesBean) && $rulesBean->active == 1) {

            // Initialize an array to store candidate security groups for inheritance
            $securityGroupsCandidatesToInherit = [];

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
            $allRelatedModules = self::getRelatedModulesList($bean->module_dir, 'module_names');
            $filteredRelatedModules = unencodeMultienum($rulesBean->inherit_from_modules);
            foreach ($allRelatedModules as $value) {
                if (!empty($bean->{$value['field']})) {
                    if ($rulesBean->inherit_parent == 1 || in_array($value['relationship'], $filteredRelatedModules)) {

                        // Obtain id from rarent record
                        $relatedId = $bean->{$value['field']};

                        if (!is_string($relatedId)) {
                            // If it in not a string, it's because we're coming from a subpanel, so we get the id in the following way:
                            $relatedId = key($bean->{$value['field']}->rows);
                        }

                        $currentRecordGroups = self::getRelatedSecurityGroupIDs($relatedId);

                        foreach ($currentRecordGroups as $val2) {
                            $securityGroupsCandidatesToInherit = array_merge(
                                $securityGroupsCandidatesToInherit,
                                [['record_id' => $bean->id, 'securitygroup_id' => $val2]]
                            );
                        }
                    }
                }
            }

            // var_dump($securityGroupsCandidatesToInherit);die();

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
