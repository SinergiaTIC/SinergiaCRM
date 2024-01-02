<?php

/**
 * This file includes those fields that need changes in order to include its information into a SEPE file.
 * 
 * On the Left side are listed the values that will come from the CRM database, on the right side side appears 
 * those values that are needed by SEPE.
 * 
 * It is used mainly by the class SEPEgestter.
 */

$TIPO_CONTRATO = array (
    '' => '',
    '001' => '001',
    '003' => '002',
    '401' => '003',
    '501' => '004',
    '005' => '005',
);

$SEXO_TRABAJADOR = array (
    '' => '',
    'male' => 1,
    'female' => 2,
);