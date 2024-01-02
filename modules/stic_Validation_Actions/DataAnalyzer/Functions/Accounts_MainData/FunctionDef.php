<?php

/**
 * Definition function model
 */
$functionDef = array(
    'id' => 'd1d60459-3713-488d-94ce-ff38bf3e1f98', // Function identifier
    'class' => 'checkAccountsBeanData', // Class name
    'classFile' => './CheckAccountsBeanData.php', // Class File Path
    'action' => 'UPDATE_REPORT', // Type of action of the 'UPDATE', 'REPORT' or 'UPDATE_REPORT' function
    'selector' => 'INCREMENTAL', // Selector type 'INCREMENTAL' or 'SPECIFIC'
    'module' => 'Accounts', // Main module on which the action is executed
    'fieldsToValidate' => array("name"), // Fields to validate
);
