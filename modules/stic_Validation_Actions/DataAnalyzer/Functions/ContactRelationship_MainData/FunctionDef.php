<?php

/**
 * MODEL OF DEFINITION OF FUNCTION
 */
$functionDef = array(
    'id' => 'd49627f2-3623-44e3-bdb2-d5af0f8c5165', // Function identifier
    'class' => 'checkCRBeanData', // Class name
    'classFile' => './checkCRBeanData.php', // Class File Path
    'action' => 'REPORT', // Type of action of the 'UPDATE', 'REPORT' or 'UPDATE_REPORT' function
    'selector' => 'INCREMENTAL', // Selector type 'INCREMENTAL' or 'SPECIFIC'
    'module' => 'stic_Contacts_Relationships', // Main module on which the action is executed
    'fieldsToValidate' => array("start_date", "relationship_type", "name"), // Fields to validate
);
