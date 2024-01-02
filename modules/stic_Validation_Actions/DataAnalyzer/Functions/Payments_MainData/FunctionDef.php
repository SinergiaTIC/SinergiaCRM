<?php

/**
 * Definition function model
 */
$functionDef = array(
    'id' => 'e39516bb-9acf-4c6f-8e25-d3af9aac0a95', // Function identifier
    'class' => 'CheckPaymentsBeanData', // Class name
    'classFile' => './CheckPaymentsBeanData.php', // Class File Path
    'action' => 'UPDATE_REPORT', // Type of action of the 'UPDATE', 'REPORT' or 'UPDATE_REPORT' function
    'selector' => 'INCREMENTAL', // Selector type 'INCREMENTAL' or 'SPECIFIC'
    'module' => 'stic_Payments', // Main module on which the action is executed
    'fieldsToValidate' => array("payment_date", "payment_method", "amount", "name", "payment_type"), // Fields to validate (si se valida el medio de pago también se validará el número de cuenta)
);
