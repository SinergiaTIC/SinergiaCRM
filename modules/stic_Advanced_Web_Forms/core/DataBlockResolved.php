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
        // Warning: PHP POST replaces all '.' to '_'
        // DataBlock names are in PascalCase, without '_'
        // Form field names:
        //   DataBlockName0_field_name              ->  "field_name" from DataBlockName0 TO CRM
        //   _detached_DataBlockName0_field_name    ->  "field_name" from DataBlockName0 DETACHED

        $this->dataBlock = $config;

        // Cargar los valores por defecto (fijos/ocultos) de la configuración
        // Estos valores se pueden sobreescribir por los valores del formulario
        foreach ($config->fields as $fieldName => $fieldDef) {
            if ($fieldDef->value_type === DataBlockFieldValueType::FIXED) {
                // Obtenemos el tipo de campo en el crm para hacer su casting
                $castedValue = AWF_Utils::castCrmValue($fieldDef->value, $fieldDef->type, $context);

                // El campo no está en el formulario, buscamos la clave lógica que tendría
                $formKey = ""; 
                $logicalKey = ($fieldDef->type_field === DataBlockFieldType::UNLINKED ? '_detached.' : '') . $config->name . '.' . $fieldName;
                $fieldResolved = new DataBlockFieldResolved($logicalKey, $fieldName, $fieldDef, $castedValue);

                // Guardamos el campo en el array correspondiente
                if ($fieldDef->type_field === DataBlockFieldType::UNLINKED) {
                     $this->detachedData[$fieldName] = $fieldResolved;
                } else {
                     $this->formData[$fieldName] = $fieldResolved;
                }
            }
        }

        // Procesar los datos del formulario
        // Lo que venga del formulario siempre tiene prioridad sobre los valores fijos/ocultos
        $blockPrefix = $config->name . '_'; 
        $detachedPrefix = '_detached_' . $blockPrefix;
        foreach ($fullFormData as $formKey => $value) {
            $fieldName = null;
            $isUnlinked = false;

            // Identificamos si el campo pertenece al bloque o es detached
            if (str_starts_with($formKey, $blockPrefix)) {
                $fieldName = substr($formKey, strlen($blockPrefix));
            } else if (str_starts_with($formKey, $detachedPrefix)) {
                $fieldName = substr($formKey, strlen($detachedPrefix));
                $isUnlinked = true;
            }

            // Si hemos encontrado un campo para este bloque:
            if ($fieldName) {
                // Buscamos la configuración del campo para su tipo, sino asumimos texto
                $definition = $config->fields[$fieldName] ?? null;
                $crmFieldType = $definition?->type;
                
                // Hacemos el casting del valor al tipo adecuado
                $castedValue = AWF_Utils::castCrmValue($value, $crmFieldType, $context);

                // Reconstruimos la clave lógica original
                $logicalKey = ($isUnlinked ? '_detached.' : '') . $config->name . '.' . $fieldName;
                $fieldResolved = new DataBlockFieldResolved($logicalKey, $fieldName, $definition, $castedValue);

                // Guardamos el campo en el array correspondiente
                if ($isUnlinked) {
                    $this->detachedData[$fieldName] = $fieldResolved;
                } else {
                    $this->formData[$fieldName] = $fieldResolved;
                }
            }
        }

        // Gestión de Checkboxes Desmarcados: HTML no envia los checkboxes desmarcados, no se actualizarían en el CRM si se desmarcan.
        foreach ($config->fields as $fieldName => $fieldDef) {
            if ($fieldDef->type_field === DataBlockFieldType::HIDDEN) continue;

            $isUnlinked = ($fieldDef->type_field === DataBlockFieldType::UNLINKED);
            if ($isUnlinked && isset($this->detachedData[$fieldName])) continue;
            if (!$isUnlinked && isset($this->formData[$fieldName])) continue;

            // El campo se esperaba pero NO ha llegado en el POST.
            if ($fieldDef->type === 'bool' || $fieldDef->type === 'checkbox') {
                // Reconstruimos la clave lógica original
                $logicalKey = ($isUnlinked ? '_detached.' : '') . $config->name . '.' . $fieldName;
                $fieldResolved = new DataBlockFieldResolved($logicalKey, $fieldName, $fieldDef, 0); // 0 = False en DB
                
                // Guardamos el campo en el array correspondiente
                if ($isUnlinked) {
                    $this->detachedData[$fieldName] = $fieldResolved;
                } else {
                    $this->formData[$fieldName] = $fieldResolved;
                }
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
