<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */
// Prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

include_once "modules/stic_Advanced_Web_Forms/actions/coreActions.php";

/**
 * DniValidatorAction
 *
 * Acción que valida un DNI
 */
class DniValidatorAction extends ValidatorActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->baseLabel = 'LBL_DNI_VALIDATOR_ACTION';
        $this->supportedDataTypes = [ActionDataType::TEXT];
    }

    public function getParameters(): array {
        return [];
    }


    public function getDefaultErrorMessage(): string {
        return $this->translate('ERROR_MESSAGE_TEXT');
    }

    public function getValidationJS(): string {
        return <<<JS
        (value, params, formElement) => {
            if (!value) return true; 
            
            value = value.toUpperCase().trim();

            const isValidDNI = (dni) => {
                const regex = /^[XYZ]?\d{5,8}[A-Z]$/;
                if (regex.test(dni) === true) {
                    let number = dni.substr(0, dni.length - 1);
                    number = number.replace("X", 0).replace("Y", 1).replace("Z", 2);
                    const lett = dni.substr(dni.length - 1, 1);
                    number = number % 23;
                    const letter = "TRWAGMYFPDXBNJZSQVHLCKET";
                    return letter.substring(number, number + 1) === lett;
                }
                return false;
            };
            const isValidCif = (cif) => {
                const cifRegEx1 = /^[ABEH][0-9]{8}/i;
                const cifRegEx2 = /^[KPQS][0-9]{7}[A-J]/i;
                const cifRegEx3 = /^[CDFGJLMNRUVW][0-9]{7}[0-9A-J]/i;

                if (cif.match(cifRegEx1) || cif.match(cifRegEx2) || cif.match(cifRegEx3)) {
                    const control = cif.charAt(cif.length - 1);
                    let sum_A = 0;
                    let sum_B = 0;
                    for (let i = 1; i < 8; i++) {
                        if (i % 2 == 0) {
                            sum_A += parseInt(cif.charAt(i));
                        } else {
                            const t = (parseInt(cif.charAt(i)) * 2).toString();
                            let p = 0;
                            for (let j = 0; j < t.length; j++) {
                                p += parseInt(t.charAt(j));
                            }
                            sum_B += p;
                        }
                    }
                    const sum_C = (sum_A + sum_B) + "";
                    const sum_D = (10 - parseInt(sum_C.charAt(sum_C.length - 1))) % 10;
                    const letters = "JABCDEFGHI";

                    if (control >= "0" && control <= "9") {
                        return control == sum_D;
                    } else {
                        return control.toUpperCase() == letters[sum_D];
                    }
                }
                return false;
            };

            // Check DNI or CIF            
            return isValidDNI(value) || isValidCif(value);
        }
JS;
    }
}