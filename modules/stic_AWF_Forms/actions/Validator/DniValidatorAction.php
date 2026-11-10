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

include_once "modules/stic_AWF_Forms/actions/coreActions.php";

/**
 * DniValidatorAction
 *
 * Action that validates a DNI (Spanish ID)
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
            
            // Check DNI
            return isValidDNI(value);
        }
JS;
    }

    public function validateBackend(mixed $value, array $params): bool {
        if (empty($value)) {
            return true;
        }

        $dni = trim(strtoupper((string) $value));
        if (preg_match('/^[XYZ]?\d{5,8}[A-Z]$/', $dni) !== 1) {
            return false;
        }
        $numberString = substr($dni, 0, -1);
        $numberString = str_replace(['X', 'Y', 'Z'], ['0', '1', '2'], $numberString);
        $lett = substr($dni, -1);
        $mod = (int) $numberString % 23;
        $letterMap = "TRWAGMYFPDXBNJZSQVHLCKET";

        return $letterMap[$mod] === $lett;
    }
}