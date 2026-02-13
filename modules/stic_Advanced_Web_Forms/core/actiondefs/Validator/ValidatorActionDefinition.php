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

abstract class ValidatorActionDefinition extends ActionDefinition
{
    final public function getType(): ActionType {
        return ActionType::VALIDATOR;
    }

    public string $category = 'validation';

    // Validations are at field level (by default)
    public ActionScope $scope = ActionScope::FIELD;

    /** @var ActionDataType[] */
    public array $supportedDataTypes = [];

    /**
     * Returns the JS function. 
     * JS Signature: (value, params, formElement) => boolean
     *   value: value of the field to validate
     *   params: parameters of the validation action
     *   formElement: HTML element of the form (for more complex validations)
     * @return string JS code of the validation function
     */
    abstract public function getValidationJS(): string;

    /**
     * Returns the default error message of the validation
     * @return string Default error message 
     */
    abstract public function getDefaultErrorMessage(): string;
}   
