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
 * NafValidatorAction
 *
 * Action that validates a NAF, Número de Afiliado a la Seguridad Social (Spanish Social Security Affiliate Number)
 */
class NafValidatorAction extends ValidatorActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->baseLabel = 'LBL_NAF_VALIDATOR_ACTION';
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

            const naf = value.replace(/[^0-9]/g, '');
            if (naf.length !== 12) return false;

            const numberPart = naf.substring(0, 10);
            const controlDigit = naf.substring(10, 12);
            const validControl = (BigInt(numberPart) % 97n).toString().padStart(2, '0');
            
            return validControl === controlDigit;
        }
JS;
    }

    public function validateBackend(mixed $value, array $params): bool {
        if (empty($value)) return true;

        $naf = preg_replace('/[^0-9]/', '', (string) $value);
        if (strlen($naf) !== 12) {
            return false;
        }
        $numberPart = substr($naf, 0, 10);
        $controlDigit = substr($naf, 10, 2);

        $calculatedControl = str_pad((string) ((int)$numberPart % 97), 2, '0', STR_PAD_LEFT);
        return $calculatedControl === $controlDigit;
    }
}