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

    // Las validaciones son a nivel de campo (por defecto)
    public ActionScope $scope = ActionScope::FIELD;

    /** @var ActionDataType[] */
    public array $supportedDataTypes = [];

    /**
     * Retorna la función JS. 
     * Firma JS: (value, params, formElement) => boolean
     *   value: valor del campo a validar
     *   params: parámetros de la acción de validación
     *   formElement: elemento HTML del formulario (para validaciones más complejas)
     * @return string Código JS de la función de validación
     */
    abstract public function getValidationJS(): string;

    /**
     * Retorna el mensaje de error por defecto de la validación
     * @return string Mensaje de error por defecto 
     */
    abstract public function getDefaultErrorMessage(): string;
}   
