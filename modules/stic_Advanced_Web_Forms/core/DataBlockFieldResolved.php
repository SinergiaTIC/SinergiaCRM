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

/**
 * Clase para un campo de bloque de datos con los datos rellenados de un formulario
 */
class DataBlockFieldResolved {
    public ?FormDataBlockField $dataBlockField;   // La confifuración del Campo del Bloque de datos (si existe)

    public string $formKey;         // El nombre completo del campo en el formulario
    public string $fieldName;       // El nombre del campo (después del prefijo) (ex: email1, first_name)
    public mixed $value;            // El valor enviado desde el formulario

    public function __construct(string $formKey, string $fieldName, ?FormDataBlockField $config, mixed $value) {
        $this->formKey = $formKey;
        $this->fieldName = $fieldName;
        $this->dataBlockField = $config;
        $this->value = $value;
    }

    public function isDetached(): bool {
        return str_starts_with($this->formKey, '_detached.') || str_starts_with($this->formKey, '_detached_');
    }
}
