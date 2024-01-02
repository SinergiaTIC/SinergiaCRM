<?php

/**
 * MODEL OF DEFINITION OF FUNCTION
 */
$functionDef = array(
    'id' => 'ccd95008-28c1-42ff-be53-6722b821e1e5', // Function identifier
    'class' => 'CheckAccountsRelationshipsBeanData', // Class name
    'classFile' => './CheckAccountsRelationshipsBeanData.php', // Class File Path
    'action' => 'REPORT', // Type of action of the 'UPDATE', 'REPORT' or 'UPDATE_REPORT' function
    'selector' => 'INCREMENTAL', // Selector type 'INCREMENTAL' or 'SPECIFIC'
    'module' => 'stic_Accounts_Relationships', // Main module on which the action is executed
    'fieldsToValidate' => array("start_date", "relationship_type", "name"), // Fields to validate
);
