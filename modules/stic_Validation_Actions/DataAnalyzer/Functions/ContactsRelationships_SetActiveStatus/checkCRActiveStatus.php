<?php

/**
 * 
 */
class checkCRActiveStatus extends DataCheckFunction {

    /**
     * Receive an SQL proposal and modify it with the particularities necessary for the function.
     * Most functions should overwrite this method.
     * @param $actionBean Bean of the action in which the function is being executed.
     * @param $proposedSQL Array generated automatically (if possible) with the keys select, from, where and order_by.
     * @return string
     */
    public function prepareSQL(stic_Validation_Actions $actionBean, $proposedSQL) {

        // Create global update SQL query to set active value according starst_date & end_date
        $sql = "UPDATE
                    stic_contacts_relationships
                SET active = 
                    if( 
                        (isnull(start_date)	OR start_date IS NULL 	OR start_date <= CURRENT_DATE()) 
                        AND (isnull(end_date)  OR end_date IS NULL  OR end_date > CURRENT_DATE()),
                    true,
                    false ) 
                where
                    deleted = 0;";

        return $sql;
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
        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": Update checkCRActiveStatus (stic_Contacts_Relationships)");
        return true;
    }
}
