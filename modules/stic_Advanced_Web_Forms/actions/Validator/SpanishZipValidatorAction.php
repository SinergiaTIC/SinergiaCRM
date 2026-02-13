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
 * SpanishZipValidatorAction
 *
 * Action that validates a Spanish postal code
 */
class SpanishZipValidatorAction extends ValidatorActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->baseLabel = 'LBL_SPANISH_ZIP_VALIDATOR_ACTION';
        $this->supportedDataTypes = [ActionDataType::TEXT, ActionDataType::INTEGER];
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
            'name_patterns' => ['/postalcode/i',   // Name contains postalcode
                                '/postal_code/i',  // Name contains postal_code
                                '/zip$/i',         // Name ends with zip
                                '/zipcode$/i'      // Name ends with zipcode
                                ],      
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

            // Strict regex for Spanish ZIP codes:
            // ^           Start
            // (?:0[1-9]   From 01...
            // |[1-4]\d    To 49...
            // |5[0-2])    or to 52...
            // \d{3}       Followed by 3 digits
            // $           End
            
            const regexCP = /^(?:0[1-9]|[1-4]\d|5[0-2])\d{3}$/;
            
            return regexCP.test(value.toString().trim());
        }
JS;
    }
}