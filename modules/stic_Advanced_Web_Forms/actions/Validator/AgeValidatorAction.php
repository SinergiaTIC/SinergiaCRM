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
 * AgeValidatorAction
 *
 * Acción que valida la edad a partir de un campo de fecha de nacimiento
 */
class AgeValidatorAction extends ValidatorActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->baseLabel = 'LBL_AGE_VALIDATOR_ACTION';
        $this->supportedDataTypes = [ActionDataType::DATE];
    }

    public function getParameters(): array {
        $paramMin = new ActionParameterDefinition();
        $paramMin->name = 'min_years';
        $paramMin->text = $this->translate('MIN_YEARS_TEXT');
        $paramMin->type = ActionParameterType::VALUE;
        $paramMin->dataType = ActionDataType::INTEGER;
        $paramMin->required = false;

        $paramMax = new ActionParameterDefinition();
        $paramMax->name = 'max_years';
        $paramMax->text = $this->translate('MAX_YEARS_TEXT');
        $paramMax->type = ActionParameterType::VALUE;
        $paramMax->dataType = ActionDataType::INTEGER;
        $paramMax->required = false;
        
        return [$paramMin, $paramMax];
    }


    public function getDefaultErrorMessage(): string {
        return $this->translate('ERROR_MESSAGE_TEXT');
    }

    public function getValidationJS(): string {
        return <<<JS
        (value, params, formElement) => {
            if (!value) return true; 

            const birthDate = new Date(value);
            const today = new Date();
            
            // Calculate precise age
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            // Validate minimum (if defined)
            if (params.min_years !== undefined && params.min_years !== null && params.min_years !== '') {
                if (age < parseInt(params.min_years)) {
                    return false;
                }
            }

            // Validate maximum (if defined)
            if (params.max_years !== undefined && params.max_years !== null && params.max_years !== '') {
                if (age > parseInt(params.max_years)) {
                    return false;
                }
            }

            return true;
        }
JS;
    }
}