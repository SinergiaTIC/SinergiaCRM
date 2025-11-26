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
 * Clase para un bloque de datos con los datos rellenados de un formulario
 */
class DataBlockResolved {
    public FormDataBlock $dataBlock;     // La confifuración del Bloque de datos

    /** @var array<string, DataBlockFieldResolved> */
    public array $formData = [];         // Los datos mapeados al crm [crm_field_name => DataBlockFieldResolved]
    
    /** @var array<string, DataBlockFieldResolved> */
    public array $detachedData = [];     // Los datos no mapeados [detached_field_name => DataBlockFieldResolved]

    public function __construct(FormDataBlock $config, array $fullFormData, ExecutionContext $context) {
        $this->dataBlock = $config;

        // Procesamos los valores fijos (hidden) del Bloque de datos: 
        //  los consideramos valores por defecto, se sobreescriben por los valores del formulario
        foreach ($config->fields as $fieldName => $fieldDef) {
            if ($fieldDef->value_type !== DataBlockFieldValueType::FIXED) { 
                continue;
            }

            // Obtenemos el tipo de campo en el crm para hacer su casting
            $castedValue = AWF_Utils::castCrmValue($fieldDef->value, $fieldDef->type, $context);
            $formKey = ""; // El campo no está en el formulario

            // Creamos el campo resuelto
            $fieldResolved = new DataBlockFieldResolved($formKey, $fieldName, $fieldDef, $castedValue);

            // Comprobamos si el campo hidden es 'unlinked'
            if ($fieldDef->type_field === DataBlockFieldType::UNLINKED) {
                 $this->detachedData[$fieldName] = $fieldResolved;
            } else {
                 $this->formData[$fieldName] = $fieldResolved;
            }
        }

        // Form field names:
        //   dataBlockName.fieldName            ->  Field to crm
        //   _detached.dataBlockName.fieldName  ->  Field detached
        $namePrefix = $config->name . '.';
        $namePrefixLen = strlen($namePrefix);
        $detachedPrefix = '_detached.' . $config->name . '.';
        $detachedPrefixLen = strlen($detachedPrefix);

        // Iteramos sobre todos los campos definidos: 
        //   Permitimos campos no definidos en la configuración
        foreach ($fullFormData as $formKey => $value) {
            // Campo enlazado al crm (Ex: persona_tutor.first_name)
            if (str_starts_with($formKey, $namePrefix)) {
                $fieldName = substr($formKey, $namePrefixLen);
                $definition = $config->fields[$fieldName] ?? null; // Puede ser un campo no definido en la configuración

                // Obtenemos el tipo de campo en el crm para hacer su casting
                $crmFieldType = $definition?->type;
                $castedValue = AWF_Utils::castCrmValue($value, $crmFieldType, $context);

                $this->formData[$fieldName] = new DataBlockFieldResolved($formKey, $fieldName, $definition, $castedValue);
            
            // Campo NO enlazado al crm (Ex: _detached.persona_menor.accept_photos)
            } else if (str_starts_with($formKey, $detachedPrefix)) {
                $fieldName = substr($formKey, $detachedPrefixLen);
                $definition = $config->fields[$fieldName] ?? null; // Puede ser un campo no definido en la configuración

                // Los campos no enlazados al crm los tratamos como strings (no hay tipo a mapear)
                $this->detachedData[$fieldName] = new DataBlockFieldResolved($formKey, $fieldName, $definition, $value);
            }
        }
    }

    public function getFieldValue($fieldName): ?DataBlockFieldResolved {
        return $this->formData[$fieldName] ?? null;
    }

    public function getDetachedFieldValue($fieldName): ?DataBlockFieldResolved {
        return $this->detachedData[$fieldName] ?? null;
    }

}
