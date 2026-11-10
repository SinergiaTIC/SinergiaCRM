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
 * CatSalutCipValidatorAction
 *
 * Action that validates a CIP from CatSalut (Catalan Health Service Personal Identification Code)
 */
class CatSalutCipValidatorAction extends ValidatorActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->baseLabel = 'LBL_CATSALUT_CIP_VALIDATOR_ACTION';
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
            const cip = value.toUpperCase().trim();
            const regex = /^[A-Z]{4}\d{10}$/;
            return regex.test(cip);
        }
JS;
    }

    public function validateBackend(mixed $value, array $params): bool {
        if (empty($value)) return true;
        
        $cip = trim(strtoupper((string) $value));
        return preg_match('/^[A-Z]{4}\d{10}$/', $cip) === 1;
    }
}