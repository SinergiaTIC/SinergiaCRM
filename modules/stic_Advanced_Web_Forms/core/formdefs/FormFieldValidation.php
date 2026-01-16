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

class FormFieldValidation {
    public FormDataBlockField $field;    // El campo al que pertenece la validación

    public string $name;                 // Nombre interno del validador
    public string $validator;            // Nombre de la clase validadora (ex: RegexValidatorAction)
    public string $message;              // Mensaje de error personalizado
    public array $params;                // Parámetros (ex: ['pattern' => '...'])
    
    // Condición para aplicar la validación (si campo_x tiene valor y)
    public string $condition_field;
    public string $condition_value;

    /**
     * Crea una instancia de FormFieldValidation a partir de un array JSON.
     * @param FormDataBlockField $field El campo al que pertenece
     * @param array $data Los datos en formato array
     * @return FormFieldValidation La instancia creada
     */
    public static function fromJsonArray(FormDataBlockField $field, array $data): self {
        $dto = new self();
        $dto->field = $field;

        $dto->name = $data['name'] ?? '';
        $dto->validator = $data['validator'] ?? '';
        $dto->message = $data['message'] ?? '';
        $dto->params = $data['params'] ?? [];
        
        // Condición
        $dto->condition_field = $data['condition_field'] ?? '';
        $dto->condition_value = $data['condition_value'] ?? '';

        return $dto;
    }
}