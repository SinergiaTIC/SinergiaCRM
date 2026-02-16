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
 * NumericValidatorAction
 *
 * Action that validates a numeric value
 */
class NumericValidatorAction extends ValidatorActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->baseLabel = 'LBL_NUMERIC_VALIDATOR_ACTION';
        $this->supportedDataTypes = [ActionDataType::INTEGER, ActionDataType::FLOAT, ActionDataType::TEXT, ActionDataType::TEXTAREA, ActionDataType::TEL];
    }

    public function getParameters(): array {
        // Minimum parameter (optional)
        $paramMin = new ActionParameterDefinition();
        $paramMin->name = 'min';
        $paramMin->text = $this->translate('MIN_TEXT');
        $paramMin->type = ActionParameterType::VALUE;
        $paramMin->dataType = ActionDataType::FLOAT; 
        $paramMin->required = false;

        // Maximum parameter (optional)
        $paramMax = new ActionParameterDefinition();
        $paramMax->name = 'max';
        $paramMax->text = $this->translate('MAX_TEXT');
        $paramMax->type = ActionParameterType::VALUE;
        $paramMax->dataType = ActionDataType::FLOAT;
        $paramMax->required = false;

        return [$paramMin, $paramMax];
    }


    public function getDefaultErrorMessage(): string {
        return $this->translate('ERROR_MESSAGE_TEXT');
    }

    public function getValidationJS(): string {
        return <<<JS
        (value, params, formElement) => {
            if (!value && value !== 0 && value !== '0') return true;

            // Basic Numeric Validation
            const sanitizedValue = String(value).replace(',', '.');
            const num = parseFloat(sanitizedValue);
            if (isNaN(num) || !isFinite(num)) {
                return false; 
            }

            // Range Validation
            if (params.min !== undefined && params.min !== null && params.min !== '') {
                if (num < parseFloat(params.min)) {
                    return false;
                }
            }
            if (params.max !== undefined && params.max !== null && params.max !== '') {
                if (num > parseFloat(params.max)) {
                    return false;
                }
            }

            return true;
        }
JS;
    }
}