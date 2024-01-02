<?php

/**
 * Class to check the relationship of Organization to Organization Relationship
 */
class checkARRelationship extends DataCheckFunction 
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
        $fp = BeanFactory::getBean($this->module);

        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": Loading relationship with Organizations ...");
        $fp->load_relationship('stic_accounts_relationships_accounts');
        $rel = $fp->stic_accounts_relationships_accounts->getRelationshipObject();

        return "SELECT distinct id FROM
                (
                    (SELECT distinct {$rel->rhs_table}.{$rel->rhs_key} as id
                        FROM {$rel->rhs_table}
                        LEFT JOIN {$rel->join_table} ON {$rel->rhs_table}.{$rel->rhs_key} = {$rel->join_table}.{$rel->join_key_rhs} and {$rel->join_table}.deleted = 0
                        WHERE {$rel->rhs_table}.deleted = 0 and TRIM(COALESCE({$rel->join_table}.{$rel->join_key_lhs}, '')) = ''
                    )
                    UNION
                    (SELECT distinct {$rel->rhs_table}.id
                        FROM (
                        SELECT distinct {$rel->join_key_rhs} as id
                            FROM {$rel->join_table}
                            LEFT JOIN {$rel->lhs_table} ON {$rel->join_table}.{$rel->join_key_lhs} = {$rel->lhs_table}.{$rel->lhs_key} and {$rel->lhs_table}.deleted = 0
                            WHERE {$rel->join_table}.deleted = 0 and TRIM(COALESCE({$rel->lhs_table}.{$rel->lhs_key}, '')) = ''
                        ) as IDS_ERRONEOS_EN_TABLA_RELACION
                        INNER JOIN {$rel->rhs_table} ON IDS_ERRONEOS_EN_TABLA_RELACION.id = {$rel->rhs_table}.{$rel->rhs_key}
                        WHERE {$rel->rhs_table}.deleted = 0
                    )
                ) as WRONG_IDS";
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
        global $sugar_config;        
        // It will indicate if records have been found to validate.
        $count = 0;

        // We will receive the data only of the wrong forms of payment
        while ($row = array_pop($records)) 
        {
            // Check if you have to take any action or inform
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": Contact relationship record does not have a relationship with any person: [{$this->module}] [{$row['id']}]");
            $bean = BeanFactory::getBean($this->module, $row['id']);
            $name = $this->getLabel('NO_LINK');
            $errorMsg = '<span style="color:red;">' . $name . '</span>';
            $data = array(
                'name' => $this->getLabel('NAME') . ' - ' . $name,
                'stic_validation_actions_id' => $actionBean->id,
                'log' => '<div>' . $errorMsg . '</div>',
                'parent_type' => $this->functionDef['module'],
                'parent_id' => $bean->id,
                'reviewed' => 'no',   
                'assigned_user_id' => $bean->assigned_user_id,
            );
            $this->logValidationResult($data);

            $count++; // Records Processed
        }

        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": [{$count}] Relationship Organization without Organization.");

        // Report_always
        if (!$count && $actionBean->report_always) {
            global $current_user;
            $errorMsg = $this->getLabel('NO_ROWS');
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