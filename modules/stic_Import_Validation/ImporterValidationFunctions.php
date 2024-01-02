<?php

require_once 'SticInclude/Utils.php';

/**
 * 
 */
function identificationNumberValidation($module, $rowValue, $row)
{
    $returnValue = false;
    switch ($module) 
    {
        case 'Accounts':
            return SticUtils::isValidCIF($rowValue) ? $rowValue : 'LBL_ERROR_INVALID_IDENTIFICATION_NUMBER';
            break;

        case 'Contacts':
            // If the stic_identification_type_c field is mapped and its value is NIF or NIE
            if ((in_array('NIF', $row) || in_array('NIE', $row))){
                return SticUtils::isValidNIForNIE($rowValue) ? $rowValue : 'LBL_ERROR_INVALID_IDENTIFICATION_NUMBER';
            } else {
                return 'LBL_ERROR_INVALID_IDENTIFICATION_NUMBER_MISSING_TYPE_FIELD';
            }
            break;

        default:
            return true;
            break;
    }
}


/**
 * 
 */
function bankAccountValidation($rowValue)
{
    return SticUtils::checkIBAN($rowValue) ? $rowValue : 'LBL_ERROR_INVALID_BANK_ACCOUNT';
}