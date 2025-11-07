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

class ParameterResolverService {

    /**
     * Resuelve todos los parámetros de una acción, paritiendo de las definiciones y la configuración del wizard
     *
     * @param FormAction $actionConfig La configuración de la acción
     * @param ActionParameterDefinition[] $paramDefinitions Las definiciones de los parámetros
     * @param FormActionParameter[] $paramConfigurations La configuración de los parámetros
     * @param ExecutionContext $context El contexto de ejecución
     * @return array Un mapa [param_name => resolved_value]
     */
    public function resolveAll(FormAction $actionConfig, array $paramDefinitions, array $paramConfigurations, ExecutionContext $context): array {
        $resolvedParameters = [];

        $paramConfigMap = [];
        foreach ($paramConfigurations as $paramConfig) {
            $paramConfigMap[$paramConfig->name] = $paramConfig;
        }

        // Iterate over paramDefinifions
        foreach ($paramDefinitions as $paramDef) {
            $paramName = $paramDef->name;
            $paramConfig = $paramConfigMap[$paramName] ?? null;
            $resolvedValue = $this->resolveSingleParam($paramDef, $paramConfig, $context);
            if ($paramDef->required && $resolvedValue === null) {
                throw new RequiredParameterException($paramName, $actionConfig->name);
            }
            $resolvedParameters[$paramName] = $resolvedValue;
        }
        
        return $resolvedParameters;
    }

    /**
     * Resuelve un parámetro observando todo el contexto
     */
    private function resolveSingleParam(ActionParameterDefinition $def, ?FormActionParameter $config, ExecutionContext $context): mixed {
        // Gestión del parámetro no configurado
        $value = $config?->value;
        switch ($def->type) {
            case ActionParameterType::VALUE:
                // El parámetro es un valor fijo. Value contiene el valor
                return $this->resolveFixedValue($def, $value, $context);

            case ActionParameterType::DATA_BLOCK:
                // El parámetro es un Bloque de datos. Value contiene el id del bloque
                return $this->resolveDataBlock($def, $value, $context);
                
            case ActionParameterType::FIELD:
                // El parámetro es un campo del formulario. Value contiene el nombre del campo
                return $this->resolveFormField($def, $value, $context);

            case ActionParameterType::CRM_RECORD:
                // El parámetro es un Registro del crm. Value contiene 'modulo|id'
                return $this->resolveBean($def, $value, $context);

            case ActionParameterType::OPTION_SELECTOR:
                // El parámetro es un selector 'selectedOption' contiene la selección
                $selectedOption = $config?->selectedOption;
                return $this->resolveOptionSelector($def, $selectedOption, $value, $context);
        }

        // No se ha retornado ninguna resolución
        $GLOBALS['log']->warning("Line ".__LINE__.": ".__METHOD__.": Parameter {$def->name} not informed in form configuration.");
        return null;
    }

    private function resolveFixedValue(ActionParameterDefinition $def, ?string $value, ExecutionContext $context): mixed {
        $valueToCast = $value !== null ? $value : $def->defaultValue;
        if ($valueToCast === null) {
            return null;
        }

        switch ($def->dataType) { 
            case ActionDataType::INTEGER:
                return (int)$valueToCast;

            case ActionDataType::FLOAT:
                return (float)$valueToCast;

            case ActionDataType::BOOLEAN:
                $lowerValue = strtolower(trim($valueToCast));
                if ($lowerValue === 'false' || $lowerValue === '0' || $lowerValue === 'off' || $lowerValue === '') {
                    return false;
                }
                return true;

            case ActionDataType::DATE:
            case ActionDataType::DATETIME:
            case ActionDataType::TIME:
            case ActionDataType::RELATIVE_DATE:
                $baseTimestamp = (int)$context->submissionTimestamp;
                // strtotime gestiona fechas fijas ("2025-10-31") y relativas ("today", "+1 day")
                $parsedTime = @strtotime($valueToCast, $baseTimestamp);
                if ($parsedTime === false) {
                    $GLOBALS['log']->warning("Line ".__LINE__.": ".__METHOD__.": Can not parse date '{$valueToCast}' with base timestamp '{$baseTimestamp}'.");
                    return null;
                }
                try {
                    $dateTimeObj = new \DateTime();
                    $dateTimeObj->setTimestamp($parsedTime);
                    return $dateTimeObj;
                } catch (\Exception $e) {
                    $GLOBALS['log']->error("Line ".__LINE__.": ".__METHOD__.": Error creating DateTime from timestamp '{$parsedTime}': " . $e->getMessage());
                    return null;
                }

            case ActionDataType::TEXT:
            default:
                return (string)$valueToCast;
        }
            
        return $valueToCast;
    }

    private function resolveDataBlock(ActionParameterDefinition $def, ?string $value, ExecutionContext $context): ?DataBlockResolved {
        $dataBlockId = $value !== null ? $value : $def->defaultValue;
        if ($dataBlockId === null) {
            return null;
        }

        $dataBlockConfig = $context->formConfig->data_blocks[$dataBlockId] ?? null;
        if ($dataBlockConfig === null) {
            $GLOBALS['log']->warning("Line ".__LINE__.": ".__METHOD__.": DataBlock config not found with Id: '{$dataBlockId}'.");
            return null;
        }

        return new DataBlockResolved($dataBlockConfig, $context->formData, $context);
    }

    private function resolveBean(ActionParameterDefinition $def, ?string $value, ExecutionContext $context): ?BeanReference {
        $recordString = $value !== null ? $value : $def->defaultValue;
        if ($recordString === null) {
            return null;
        }

        // recordString: 'module|id'
        $parts = explode('|', $recordString, 2);
        if (count($parts) !== 2 || empty($parts[0]) || empty($parts[1])) {
            $GLOBALS['log']->warning("Line ".__LINE__.": ".__METHOD__.": Format error. Expected 'Module|id', but received '{$recordString}'.");
            return null;
        }

        return new BeanReference($parts[0], $parts[1]);
    }

    private function resolveFormField(ActionParameterDefinition $def, ?string $value, ExecutionContext $context): ?DataBlockFieldResolved {
        $formKey = $value !== null ? $value : $def->defaultValue;
        if ($formKey === null || $formKey == '') {
            $GLOBALS['log']->warning("Line ".__LINE__.": ".__METHOD__.": Field name is null or empty.");
            return null;
        }

        // Parse formKey to find the dataBlock and dataBlockField definitions
        $isDetached = str_starts_with($formKey, '_detached.');
        $keyToParse = $isDetached ? substr($formKey, strlen('_detached.')) : $formKey;
        $parts = explode('.', $keyToParse, 2);
        
        $blockName = null;
        $fieldName = $formKey;
        if ($isDetached) {
            // Ex: _detached.PersonaTutor.accept_photos
            $parts = explode('.', $formKey, 3);
            if (count($parts) === 3) {
                $blockName = $parts[1]; // "PersonaTutor"
                $fieldName = $parts[2]; // "accept_photos"
            }
        } else {
            // Ex: PersonaTutor.email1
            $parts = explode('.', $formKey, 2);
            if (count($parts) === 2) {
                $blockName = $parts[0]; // "PersonaTutor"
                $fieldName = $parts[1]; // "email1"
            }
        }

        $fieldDefinition = null;
        if ($blockName !== null) {
            // Find the Field definition
            $dataBlockConfig = $context->getDataBlockByName($blockName);
            if ($dataBlockConfig !== null) {
                $fieldDefinition = $dataBlockConfig->fields[$fieldName] ?? null;
            }
        } else {
            $GLOBALS['log']->warning("Line ".__LINE__.": ".__METHOD__.": The field name '{$formKey}' has an invalid format.");
        }

        $finalValue = null;
        $crmFieldType = $fieldDefinition?->type ?? 'text';
        if (array_key_exists($formKey, $context->formData)) {
            // Fill value from form data
            $finalValue = AWF_Utils::castCrmValue($context->formData[$formKey], $crmFieldType, $context);
        } else {
            // If not set in form data, then find if is a field with fixed value in DataBlock
            if ($fieldDefinition !== null && $fieldDefinition->value_type === DataBlockFieldValueType::FIXED) {
                $finalValue = AWF_Utils::castCrmValue($fieldDefinition->value, $crmFieldType, $context);
            }
        }
        return new DataBlockFieldResolved($formKey, $fieldName, $fieldDefinition, $finalValue);
    }

    private function resolveOptionSelector(ActionParameterDefinition $def, ?string $selectedOption, ?string $value, ExecutionContext $context): ?OptionSelectorResolved {
        if ($selectedOption === null) {
            $GLOBALS['log']->warning("Line ".__LINE__.": ".__METHOD__.": Selected option is null");
            return null;
        }
        
        // Find the SelectedOption definition
        $optionDef = null;
        foreach ($def->selectorOptions as $o) {
            if ($o->name === $selectedOption) {
                $optionDef = $o;
                break;
            }
        }
        
        if ($optionDef === null) {
            $GLOBALS['log']->warning("Line ".__LINE__.": ".__METHOD__.": Option '{$selectedOption}' not found.");
            return null;
        }

        // Resolve parameter with resolvedType
        $resolvedValue = null;
        switch ($optionDef->resolvedType) {
            case ActionParameterType::VALUE:
                // El parámetro es un valor fijo. Value contiene el valor
                $resolvedValue = $this->resolveFixedValue($def, $value, $context);
                break;

            case ActionParameterType::DATA_BLOCK:
                // El parámetro es un Bloque de datos. Value contiene el id del bloque
                $resolvedValue = $this->resolveDataBlock($def, $value, $context);
                break;
                
            case ActionParameterType::FIELD:
                // El parámetro es un campo del formulario. Value contiene el nombre del campo
                $resolvedValue = $this->resolveFormField($def, $value, $context);
                break;

            case ActionParameterType::CRM_RECORD:
                // El parámetro es un Registro del crm. Value contiene 'modulo|id'
                $resolvedValue = $this->resolveBean($def, $value, $context);
                break;

            default:
                $GLOBALS['log']->warning("Line ".__LINE__.": ".__METHOD__.": Unknown ResolvedType '{$optionDef->resolvedType->value}'.");
                return null;
        }
        
        return new OptionSelectorResolved($selectedOption, $resolvedValue);        
    }
}

