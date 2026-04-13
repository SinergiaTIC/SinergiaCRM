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
 * TextLengthValidatorAction
 *
 * Action that validates a text length
 */
class TextLengthValidatorAction extends ValidatorActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->baseLabel = 'LBL_TEXT_LENGTH_VALIDATOR_ACTION';
        $this->supportedDataTypes = [ActionDataType::TEXT, ActionDataType::TEXTAREA, ActionDataType::EMAIL, ActionDataType::TEL, ActionDataType::URL];
    }

    public function getParameters(): array {
        // Min Length parameter (optional)
        $paramMinLen = new ActionParameterDefinition();
        $paramMinLen->name = 'min_length';
        $paramMinLen->text = $this->translate('MIN_LENGTH_TEXT');
        $paramMinLen->type = ActionParameterType::VALUE;
        $paramMinLen->dataType = ActionDataType::INTEGER; 
        $paramMinLen->required = false;

        // Max Length parameter (optional)
        $paramMaxLen = new ActionParameterDefinition();
        $paramMaxLen->name = 'max_length';
        $paramMaxLen->text = $this->translate('MAX_LENGTH_TEXT');
        $paramMaxLen->type = ActionParameterType::VALUE;
        $paramMaxLen->dataType = ActionDataType::INTEGER;
        $paramMaxLen->required = false;

        return [$paramMinLen, $paramMaxLen];
    }


    public function getDefaultErrorMessage(): string {
        return $this->translate('ERROR_MESSAGE_TEXT');
    }

    public function getValidationJS(): string {
        return <<<JS
        (value, params, formElement) => {
            if (!value) return true;

            const strValue = String(value).trim();
            const len = strValue.length;

            // Length Validation
            if (params.min_length !== undefined && params.min_length !== null && params.min_length !== '') {
                if (len < parseInt(params.min_length, 10)) {
                    return false;
                }
            }
            if (params.max_length !== undefined && params.max_length !== null && params.max_length !== '') {
                if (len > parseInt(params.max_length, 10)) {
                    return false;
                }
            }

            return true;
        }
JS;
    }

    public function validateBackend(mixed $value, array $params): bool {
        if ($value === null || $value === '') {
            return true;
        }

        $stringValue = trim((string)$value);
        $length = mb_strlen($stringValue, 'UTF-8');

        // Length Validation
        if (isset($params['min_length']) && $params['min_length'] !== '') {
            if ($length < (int)$params['min_length']) {
                return false;
            }
        }
        if (isset($params['max_length']) && $params['max_length'] !== '') {
            if ($length > (int)$params['max_length']) {
                return false;
            }
        }

        return true;
    }
}