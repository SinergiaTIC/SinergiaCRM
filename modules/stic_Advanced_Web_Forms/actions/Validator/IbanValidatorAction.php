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
 * IbanValidatorAction
 *
 * Action that validates an IBAN
 */
class IbanValidatorAction extends ValidatorActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->baseLabel = 'LBL_IBAN_VALIDATOR_ACTION';
        $this->supportedDataTypes = [ActionDataType::TEXT];
    }

    /**
     * Returns rules to automatically apply this validation.
     * Can filter by field type (vardef type) editor in form (subtype_in_form), or by name pattern (regex).
     * @return array ex: ['types' => ['email'], 'subtypes_in_form' => ['text_email'], 'name_patterns' => ['/^email/i']]
     */
    public function getAutoApplyRules(): array {
        return [
            'types' => [],
            'subtypes_in_form' => [], 
            'name_patterns' => ['/^bank_account/i']      // Name starts with bank_account
        ];
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
            
            // Clean spaces and hyphens and convert to uppercase
            const iban = value.toUpperCase().replace(/[\s\-_]/g, '');

            // Basic check of length (Spain is 24, but standard varies)
            if (iban.length < 5 || iban.length > 34) return false;

            // Move first 4 chars to the end
            const rearrenged = iban.substring(4) + iban.substring(0, 4);

            // Convert to numeric representation (A=10, B=11, ..., Z=35)
            let numericIban = "";
            for (let i = 0; i < rearrenged.length; i++) {
                const charCode = rearrenged.charCodeAt(i);
                if (charCode >= 65 && charCode <= 90) {
                    numericIban += (charCode - 55).toString();
                } else if (charCode >= 48 && charCode <= 57) {
                    numericIban += rearrenged.charAt(i);
                } else {
                    return false; // Caràcter invàlid
                }
            }

            // Check checksum (Mod 97 == 1)
            // It must be done in parts to avoid overflow in old JS engines or using BigInt if modern browsers (cleaner)
            if (typeof BigInt === 'function') {
                return (BigInt(numericIban) % 97n) === 1n;
            } else {
                // Process in chunks in case BigInt is not supported
                let remainder = "";
                for (let i = 0; i < numericIban.length; i++) {
                    remainder = (remainder + numericIban[i]) % 97;
                }
                return remainder === 1;
            }
        }
JS;
    }
}