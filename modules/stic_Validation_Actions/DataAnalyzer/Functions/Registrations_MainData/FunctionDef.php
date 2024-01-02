<?php

/**
 * Definción de función
 */
$functionDef = array(
    'id' => '28874faf-7465-43a4-ad31-357769af3f6f', // Function identifier
    'class' => 'CheckRegistrationsBeanData', // Class name
    'classFile' => './CheckRegistrationsBeanData.php', // Class File Path
    'action' => 'REPORT', // Type of action of the 'UPDATE', 'REPORT' or 'UPDATE_REPORT' function
    'selector' => 'INCREMENTAL', // Selector type 'INCREMENTAL' or 'SPECIFIC'
    'module' => 'stic_Registrations', // Main module on which the action is executed
    'fieldsToValidate' => array("name", "registration_date"), // Fields to validate
);
