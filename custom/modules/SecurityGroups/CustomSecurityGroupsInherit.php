<?php
class CustomSecurityGroupsInherit
{

    public function after_save(&$bean, $event, $arguments)
    {
    //return; 
        if (in_array($bean->module_name, ['SugarFeed'])) {
            return;
        }
        if (!$bean->fetched_row) {
            global $sugar_config;
            $rulesBean = self::getModuleRule($bean->module_name);

            if (!empty($rulesBean)) {
                include_once 'modules/SecurityGroups/SecurityGroup.php';
                include_once 'SticInclude/Utils.php';

                // Se aplica la herencia del usuario asignado si está activada
                if ($rulesBean->inherit_assigned == 1) {
                    $sugar_config['securitysuite_inherit_assigned'] = true;
                    SecurityGroup::inherit_assigned($bean, false);
                    $sugar_config['securitysuite_inherit_assigned'] = false;
                }
                
                // Se aplica la herencia del usuario creador si está activada
                if ($rulesBean->inherit_creator == 1) {
                    $sugar_config['securitysuite_inherit_creator'] = true;
                    SecurityGroup::inherit_creator($bean, false);
                    $sugar_config['securitysuite_inherit_creator'] = false;
                }

                // Se aplica la herencia del registro padre si está activada
                if ($rulesBean->inherit_parent == 1) {
                    $sugar_config['securitysuite_inherit_parent'] = true;
                    SecurityGroup::inherit_parent($bean, false);
                    $sugar_config['securitysuite_inherit_parent'] = false;
                }

                // Si se han establecido reglas de herencia de algún módulo relacioando la aplicamos

                    $finalSGtoInherit = array();
                    $bean->load_relationship('SecurityGroups');

                    for ($i = 10; $i > 0; $i--) {
                        $customInherit ='';
                        $customInherit = $rulesBean->{"inherit_from_modules_".$i};
          
                        if (!empty($customInherit) && (is_string($bean->$customInherit))) {
                            $finalSGtoInherit[] = $bean->$customInherit;
                        } else if (!empty($customInherit)){
                            $relatedSG='';
                            $customInheritBean = SticUtils::getRelatedBeanObject($bean, $customInherit);
                           //$GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': relatedField: '.$customInheritBean->id);
                                if (!empty($customInheritBean->id)){
                                    $relatedSG = self::getRelatedSG($customInheritBean->id);
                                    foreach ($relatedSG as $relSG){
                                    $finalSGtoInherit[] = $relSG; 
                                    }
                                }                                       
                            } 
                    }

                    //$exemptInherit = $rulesBean->non_inherit_from_security_groups;
          
                    $finalExemptInherit = array();

                    for ($i = 5; $i > 0; $i--) {
                        $exemptInherit ='';
                        $exemptInherit = $rulesBean->{"non_inherit_from_security_groups_".$i};
                        if (!empty($exemptInherit)){
                            $finalExemptInherit[] =  $exemptInherit; 
                        }
                    }



                    if (!empty($finalSGtoInherit)) {
                        //$finalSGtoInherit = array_unique($finalSGtoInherit);
                        $finalSGtoInherit = array_unique($finalSGtoInherit);
                        
                        if (!empty($finalExemptInherit)){
                            $finalExemptInherit = array_unique($finalExemptInherit);
                            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': exemptInherit: '.$exemptInherit);
                            //$finalSGtoInherit.indexOf($exemptInherit);
                            /*foreach (array_diff($finalSGtoInherit, $finalExemptInherit) as $key) {
                                unset($finalSGtoInherit[$key]);
                            }*/
                            $finalSGtoInherit= array_diff($finalSGtoInherit, $finalExemptInherit);
                        }
                        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': relatedSG: '.print_r($finalSGtoInherit, true));
                        
                        foreach ($finalSGtoInherit as $SG_ID) {
                            self::setCustomInherit($bean->id, $bean->module_name, $SG_ID);
                        }
                    }                                
                }
            }
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
        $ruleId = $db->getOne("SELECT id FROM stic_advanced_security_groups WHERE name='{$moduleName}' AND deleted=0 ORDER BY date_entered DESC LIMIT 1");
        $rulesBean = BeanFactory::getBean('stic_Advanced_Security_Groups', $ruleId);
        return $rulesBean;

    }

    public static function getRelatedSG($relatedID)
    {
        global $db;
        $relatedIDs= array();
        $result = $db->query("SELECT DISTINCT securitygroup_id FROM securitygroups_records sr left join securitygroups sg on sr.securitygroup_id=sg.id WHERE sr.record_id='{$relatedID}' AND sr.deleted=0 AND sg.noninheritable=0");
        foreach($result as $row){
            $relatedIDs[]= $row['securitygroup_id'];
        }
        return $relatedIDs;
    }

    public static function checkrelatedField($relatedID, $bean)
    {
        global $db;
        $relID= '';
        $relID = $db->getOne("SELECT {$relatedID} FROM contacts_cstm WHERE id_c='{$bean->id}' LIMIT 1");
        //$relID = $db->getOne("SELECT id FROM securitygroups WHERE id='{$relatedID}' AND deleted=0 LIMIT 1");
  
        return $relID;
    }

}

