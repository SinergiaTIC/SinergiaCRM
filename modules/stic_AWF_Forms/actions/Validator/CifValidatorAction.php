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
 * CifValidatorAction
 *
 * Action that validates a CIF (Spanish old ID)
 */
class CifValidatorAction extends ValidatorActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->baseLabel = 'LBL_CIF_VALIDATOR_ACTION';
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

            // Check CIF            
            return isValidCif(value);
        }
JS;
    }

    public function validateBackend(mixed $value, array $params): bool {
        if (empty($value)) {
            return true;
        }
        $cif = trim(strtoupper((string) $value));

        $cifRegEx1 = '/^[ABEH][0-9]{8}$/';
        $cifRegEx2 = '/^[KPQS][0-9]{7}[A-J]$/';
        $cifRegEx3 = '/^[CDFGJLMNRUVW][0-9]{7}[0-9A-J]$/';

        if (preg_match($cifRegEx1, $cif) || preg_match($cifRegEx2, $cif) || preg_match($cifRegEx3, $cif)) {
            $control = $cif[strlen($cif) - 1];

            $sum_A = 0;
            $sum_B = 0;
            for ($i = 1; $i < 8; $i++) {
                if ($i % 2 === 0) {
                    $sum_A += (int) $cif[$i];
                } else {
                    $t = (string) ((int) $cif[$i] * 2);
                    $p = 0;
                    for ($j = 0; $j < strlen($t); $j++) {
                        $p += (int) $t[$j];
                    }
                    $sum_B += $p;
                }
            }
            $sum_C = (string) ($sum_A + $sum_B);
            $lastDigitOfC = (int) $sum_C[strlen($sum_C) - 1];
            $sum_D = (10 - $lastDigitOfC) % 10;
            $letters = "JABCDEFGHI";

            if (is_numeric($control)) {
                return (int) $control === $sum_D;
            } else {
                return strtoupper($control) === $letters[$sum_D];
            }
        }

        return false;
    }
}