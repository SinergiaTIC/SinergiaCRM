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
            $castedValue = $this->castCrmValue($fieldDef->value, $fieldDef->type, $context);
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
                $castedValue = $this->castCrmValue($value, $crmFieldType, $context);

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

    /**
     * Convierte un valor string del formulario al tipo PHP correcto basándose en el tipo de campo en el CRM.
     * @param mixed $valueToCast El valor a convertir
     * @param ?string $crmFieldType El tipo de campo en el CRM
     * @param ExecutionContext $context El contexto de ejecución
     * @return mixed El valor convertido
     */
    private function castCrmValue(mixed $valueToCast, ?string $crmFieldType, ExecutionContext $context): mixed {
        // Si no es un string (ej: un array de un multiselect), lo retornamos tal cual.
        if (!is_string($valueToCast)) {
            return $valueToCast;
        }

        // Si no hay tipo definido, lo tratamos como texto
        if ($crmFieldType === null) {
            $crmFieldType = 'text';
        }

        switch ($crmFieldType) {
            // Boolean
            case 'bool':
            case 'checkbox':
                $lowerValue = strtolower(trim($valueToCast));
                return !($lowerValue === 'false' || $lowerValue === '0' || $lowerValue === 'off' || $lowerValue === '');

            // Numéricos
            case 'int':
                return (int)$valueToCast;
            
            case 'float':
            case 'double':
            case 'decimal':
            case 'currency': 
                return (float)$valueToCast;

            // Fechas y horas
            case 'date':
            case 'time':
            case 'datetime':
            case 'datetimecombo':
                $baseTimestamp = (int)$context->submissionTimestamp;
                // strtotime también gestiona "today", "+1 day", etc.
                $parsedTime = @strtotime($valueToCast, $baseTimestamp);
                
                if ($parsedTime === false) {
                    $GLOBALS['log']->warning("Line ".__LINE__.": ".__METHOD__.": Can not parse date '{$valueToCast}'.");
                    return null;
                }
                try {
                    $dateTimeObj = new \DateTime();
                    $dateTimeObj->setTimestamp($parsedTime);
                    return $dateTimeObj;
                } catch (\Exception $e) { return null; }

            // Strings 
            case 'varchar':
            case 'text':
            case 'relate':
            case 'enum':
            case 'multienum':
            case 'phone':
            case 'email':
            case 'text':
            default:
                return (string)$valueToCast;
        }
    }

    public function getFieldValue($fieldName): ?DataBlockFieldResolved {
        return $this->formData[$fieldName] ?? null;
    }

    public function getDetachedFieldValue($fieldName): ?DataBlockFieldResolved {
        return $this->detachedData[$fieldName] ?? null;
    }

}
