<?php

/**
 * Definition function model
 */
$functionDef = array(
    'id' => '14875de6-ed5e-443a-abbc-54d57dec100e', // Function identifier
    'class' => 'CheckPCBeanData', // Class name
    'classFile' => './CheckPCBeanData.php', // Class File Path
    'action' => 'UPDATE_REPORT', // Type of action of the 'UPDATE', 'REPORT' or 'UPDATE_REPORT' function
    'selector' => 'INCREMENTAL', // Selector type 'INCREMENTAL' or 'SPECIFIC'
    'module' => 'stic_Payment_Commitments', // Main module on which the action is executed
    'fieldsToValidate' => array("first_payment_date", "payment_method", "periodicity", "amount", "name", "payment_type"), // Fields to validate (if the payment method is validated, the account number will also be validated)
);
