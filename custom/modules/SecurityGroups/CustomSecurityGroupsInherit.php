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
                            $currentRecordGroups = self::getRelatedSG($bean->{$value['field']});
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
                $result=$bean->db->query("SELECT id FROM securitygroups where deleted=0 and noninheritable=1");
                foreach ($result as $row) {
                    $notInheritableGroups[]=$row['id'];
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
                foreach ($securityGroupsCandidatesToInherit as $key => $sg) {
                    $securityGroupsCandidatesToInherit[$key]['grupo'] = $bean->db->getOne("SELECT name from securitygroups WHERE id='{$sg['securitygroup_id']}'");
                }
                var_dump('$securityGroupsCandidatesToInherit', $securityGroupsCandidatesToInherit);
                var_dump('$notInheritableGroups', $notInheritableGroups);
                // die();

                // if inheritance of the parent is enabled only for specific modules
                // Obtenemos los valores de inherit_from_modules a partir del control multienum (for all modules or only for some modules)

                // if (!empty($filteredRelatedModules)) {

                //     $someRelatedModules = stic_Advanced_Security_GroupsUtils::getRelatedModulesList($bean->module_dir, 'module_names');
                //     foreach ($someRelatedModules as $key => $value) {
                //         # code...
                //     }
                // }

                //     // Temporarily enable the inheritance setting
                //     $sugar_config['securitysuite_inherit_parent'] = true;

                //     // Apply the inheritance logic for the parent
                //     // SecurityGroup::inherit_parent($bean, false);

                //     // Reset the inheritance setting to its original state
                //     $sugar_config['securitysuite_inherit_parent'] = false;
                // } else {
                //     // Check if the inheritance setting is enabled
                //     if ($sugar_config['securitysuite_inherit_parent'] == true) {
                //         // Log a message indicating that the inheritance rule does not apply
                //         // due to the configuration setting
                //         $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' .
                //             "The variable {sugar_config['securitysuite_inherit_parent']} " .
                //             "is set to true, indicating that the inheritance rule for the " .
                //             "parent defined in the stic_Advanced_Security_Groups module " .
                //             "does not apply");
                //     }
                // }

                // Si se han establecido reglas de herencia de algún módulo relacioando la aplicamos

                $finalSGtoInherit = array();

                $bean->load_relationship('SecurityGroups');

                foreach ($filteredRelatedModules as $customInherit) {

                    if (!empty($customInherit) && is_string($customInherit)) {
                        $finalSGtoInherit[] = $customInherit;
                    } else if (!empty($customInherit)) {
                        $relatedSG = '';
                        $customInheritBean = SticUtils::getRelatedBeanObject($bean, $customInherit);
                        if (!empty($customInheritBean->id)) {
                            $relatedSG = self::getRelatedSG($customInheritBean->id);
                            foreach ($relatedSG as $relSG) {
                                $finalSGtoInherit[] = $relSG;
                            }
                        }
                    }
                }

                // for ($i = 10; $i > 0; $i--) {
                //     $customInherit = '';
                //     $customInherit = $rulesBean->{"inherit_from_modules_" . $i};

                //     if (!empty($customInherit) && (is_string($bean->$customInherit))) {
                //         $finalSGtoInherit[] = $bean->$customInherit;
                //     } else if (!empty($customInherit)) {
                //         $relatedSG = '';
                //         $customInheritBean = SticUtils::getRelatedBeanObject($bean, $customInherit);
                //         //$GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': relatedField: '.$customInheritBean->id);
                //         if (!empty($customInheritBean->id)) {
                //             $relatedSG = self::getRelatedSG($customInheritBean->id);
                //             foreach ($relatedSG as $relSG) {
                //                 $finalSGtoInherit[] = $relSG;
                //             }
                //         }
                //     }
                // }

                //$exemptInherit = $rulesBean->non_inherit_from_security_groups;

                // $notInheritableGroups = array();

                // for ($i = 5; $i > 0; $i--) {
                //     $exemptInherit = '';
                //     $exemptInherit = $rulesBean->{"non_inherit_from_security_groups_" . $i};
                //     if (!empty($exemptInherit)) {
                //         $notInheritableGroups[] = $exemptInherit;
                //     }
                // }

                if (!empty($finalSGtoInherit)) {
                    //$finalSGtoInherit = array_unique($finalSGtoInherit);
                    $finalSGtoInherit = array_unique($finalSGtoInherit);

                    if (!empty($notInheritableGroups)) {
                        $notInheritableGroups = array_unique($notInheritableGroups);
                        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': exemptInherit: ' . $exemptInherit);
                        //$finalSGtoInherit.indexOf($exemptInherit);
                        /*foreach (array_diff($finalSGtoInherit, $notInheritableGroups) as $key) {
                        unset($finalSGtoInherit[$key]);
                        }*/
                        $finalSGtoInherit = array_diff($finalSGtoInherit, $notInheritableGroups);
                    }
                    $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': relatedSG: ' . print_r($finalSGtoInherit, true));

                    foreach ($finalSGtoInherit as $SG_ID) {
                        self::setCustomInherit($bean->id, $bean->module_name, $SG_ID);
                    }
                }
            }
        }
    }

    public static function getRelatedSG($relatedID)
    {
        global $db;
        $relatedIDs = array();
        $result = $db->query("SELECT DISTINCT securitygroup_id
                            FROM securitygroups_records sr
                                LEFT JOIN securitygroups sg ON sr.securitygroup_id=sg.id
                            WHERE sr.record_id='{$relatedID}'
                            AND sr.deleted=0
                            AND sg.noninheritable=0");
        foreach ($result as $row) {
            $relatedIDs[] = $row['securitygroup_id'];
        }
        return $relatedIDs;
    }

    /**
     * Save one securitygroup record
     *
     * @param String $recordId el id de registro
     * @param String $moduleName el módulo
     * @param String $securityGroupId el grupo de seguridad a asignar
     * @return void
     */
    public static function setCustomInherit($recordId, $moduleName, $securityGroupId)
    {
        global $current_user, $db;
        $query = "INSERT INTO securitygroups_records(id, securitygroup_id, record_id, module, date_modified, modified_user_id, created_by, deleted)
        VALUES (uuid(),'{$securityGroupId}','{$recordId}','{$moduleName}',now(),'{$current_user->id}','{$current_user->id}',0)";
        $db->query($query);
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
