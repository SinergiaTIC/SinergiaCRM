<?php

/**
 * Class to check the relations of the Inscriptions
 */
class CheckRegistrationsRelationship extends DataCheckFunction 
{

    /**
     * Receive an SQL proposal and modify it with the particularities necessary for the function.
     * Most functions should overwrite this method.
     * @param $actionBean Bean of the action in which the function is being executed.
     * @param $proposedSQL Array generated automatically (if possible) with the keys select, from, where and order_by.
     * @return string
     */
    public function prepareSQL(stic_Validation_Actions $actionBean, $proposedSQL) 
    {
        $ins = BeanFactory::getBean($this->module);

        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": Loading relationship with Contacts ...");
        $ins->load_relationship('stic_registrations_contacts');
        $rel = $ins->stic_registrations_contacts->getRelationshipObject();

        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": Loading relationship with Accounts ...");
        $ins->load_relationship('stic_registrations_accounts');
        $rel2 = $ins->stic_registrations_accounts->getRelationshipObject();

        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": Loading relationship with Leads ...");
        $ins->load_relationship('stic_registrations_leads');
        $rel3 = $ins->stic_registrations_leads->getRelationshipObject();

        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": Loading relationship with Events ...");
        $ins->load_relationship('stic_registrations_stic_events');
        $rel4 = $ins->stic_registrations_stic_events->getRelationshipObject();

        return "SELECT distinct {$rel->rhs_table}.{$rel->rhs_key} as id,
                        TRIM(COALESCE({$rel->join_table}.{$rel->join_key_lhs}, '')) as contact_id,
                        TRIM(COALESCE({$rel2->join_table}.{$rel2->join_key_lhs}, '')) as account_id,
                        TRIM(COALESCE({$rel3->join_table}.{$rel3->join_key_lhs}, '')) as leads_id,
                        TRIM(COALESCE({$rel4->join_table}.{$rel4->join_key_rhs}, '')) as evento_id
                    FROM {$rel->rhs_table}
                    LEFT JOIN {$rel->join_table} ON {$rel->rhs_table}.{$rel->rhs_key} = {$rel->join_table}.{$rel->join_key_rhs} and {$rel->join_table}.deleted = 0
                    LEFT JOIN {$rel2->join_table} ON {$rel2->rhs_table}.{$rel2->rhs_key} = {$rel2->join_table}.{$rel2->join_key_rhs} and {$rel2->join_table}.deleted = 0
                    LEFT JOIN {$rel3->join_table} ON {$rel3->rhs_table}.{$rel3->rhs_key} = {$rel3->join_table}.{$rel3->join_key_rhs} and {$rel3->join_table}.deleted = 0
                    LEFT JOIN {$rel4->join_table} ON {$rel4->rhs_table}.{$rel4->rhs_key} = {$rel4->join_table}.{$rel4->join_key_rhs} and {$rel4->join_table}.deleted = 0
                    WHERE {$rel->rhs_table}.deleted = 0 and ( ( TRIM(COALESCE({$rel->join_table}.{$rel->join_key_lhs}, '')) = '' and
                                                                TRIM(COALESCE({$rel2->join_table}.{$rel2->join_key_lhs}, '')) = ''  and
                                                                TRIM(COALESCE({$rel3->join_table}.{$rel3->join_key_lhs}, '')) = '' ) or
                                                                TRIM(COALESCE({$rel4->join_table}.{$rel4->join_key_rhs}, '')) = '' )";
    }

    /**
     * DoAction function
     * Perform the action defined in the function
     * @param $records Set of records on which the validation action is to be applied 
     * @param $actionBean stic_Validation_Actions Bean of the action in which the function is being executed.
     * @return boolean It will return true in case of success and false in case of error.
     */
    public function doAction($records, stic_Validation_Actions $actionBean) 
    {
        $count = 0;

        // We will receive the data only of the wrong forms of registrations
        while ($ins = array_pop($records)) 
        {
            // Check if you have to take any action or inform
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": Bad record found [{$this->module}] [{$ins['id']}], retrieving object ...");
            $bean = $this->loadBean($bean, $ins);

            // If you have no linked event, write it down.
            if (!$ins['evento_id']) {
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": The registration [{$bean->id} - {$bean->name}] has no event id.");
                $name = $this->getLabel('NO_EVENT');
                $errorMsg = '<span style="color:red;">' . $name . '</span>';                
                $data = array(
                    'name' => $this->getLabel('NAME') . ' - ' . $bean->name,
                    'stic_validation_actions_id' => $actionBean->id,
                    'log' =>  '<div>' . $errorMsg . '</div>',
                    'parent_type' => $this->functionDef['module'],
                    'parent_id' => $bean->id,
                    'reviewed' => 'no',      
                    'assigned_user_id' => $bean->assigned_user_id,
                );
                $this->logValidationResult($data);
            }

            // If you do not have a person, organization or interested party, write it down.
            if (!$ins['account_id'] && !$ins['leads_id'] && !$ins['contact_id']) {
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": The registration [{$bean->id} - {$bean->name}] does not have a contacts, account or lead id.");
                $name = $this->getLabel('NO_LINK');
                $errorMsg = '<span style="color:red;">' . $name . '</span>';        
                $data = array(
                    'name' => $this->getLabel('NAME') . ' - ' . $bean->name,
                    'stic_validation_actions_id' => $actionBean->id,
                    'log' =>  '<div>' . $errorMsg . '</div>',
                    'parent_type' => $this->functionDef['module'],
                    'parent_id' => $bean->id,
                    'reviewed' => 'no',      
                    'assigned_user_id' => $bean->assigned_user_id,
                );
                $this->logValidationResult($data);
            }

            $count++; // Records Processed
        }

        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": [{$count}] registrations with relationship errors.");

        // Report_always
        if (!$count && $actionBean->report_always) {
            global $current_user;
            $errorMsg = $this->getLabel('NO_ERRORS');
            $data = array(
                'name' => $errorMsg,
                'stic_validation_actions_id' => $actionBean->id,
                'log' => '<div>' . $errorMsg . '</div>',
                'reviewed' => 'not_necessary',   
                'assigned_user_id' => $current_user->id, // In this message we indicate the administrator user           
            );
            $this->logValidationResult($data);
        }

        return true;
    }
}
