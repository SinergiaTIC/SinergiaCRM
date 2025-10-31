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

//IEPA!!!
// Revisar

class ParameterResolverService {

    /**
     * Resuelve todos los parámetros de una acción, paritiendo de las definiciones y la configuración del wizard
     *
     * @param ActionParameterDefinition[] $paramDefinitions Las definiciones de los parámetros
     * @param FormActionParameter[] $paramConfigurations La configuración de los parámetros
     * @param ExecutionContext $context El contexto de ejecución
     * @return array Un mapa [param_name => resolved_value]
     */
    public function resolveAll(array $paramDefinitions, array $paramConfigurations, ExecutionContext $context): array {
        $resolvedParameters = [];

        $paramConfigMap = [];
        foreach ($paramConfigurations as $paramConfig) {
            $paramConfigMap[$paramConfig->name] = $paramConfig;
        }

        // Iterate over paramDefinifions
        foreach ($paramDefinitions as $paramDef) {
            $paramName = $paramDef->name;
            $paramConfig = $paramConfigMap[$paramName] ?? null;
            $resolvedParameters[$paramName] = $this->resolveSingleParam($paramDef, $paramConfig, $context);
        }
        
        return $resolvedParameters;
    }

    /**
     * Resuelve un parámetro observando todo el contexto
     */
    private function resolveSingleParam(ActionParameterDefinition $def, ?FormActionParameter $config, ExecutionContext $context): mixed {
        // Gestión del parámetro no configurado
        if ($config === null) {
            if ($def->type == ActionParameterType::VALUE) {
                return $this->resolveFixedValue($def->defaultValue, $def->dataType?->value);
            }
        } else {
            $value = $config->value;
            switch ($def->type) {
                case ActionParameterType::VALUE:
                    // El parámetro es un valor fijo. Value contiene el valor
                    return $this->resolveFixedValue($value, $def->dataType?->value);

                case ActionParameterType::DATA_BLOCK:
                    // El parámetro es un Bloque de datos. Value contiene el id del bloque
                    return $this->resolveDataBlockConfig($value, $context);
                    
                case ActionParameterType::CRM_RECORD:
                    // El parámetro es un Registro del crm. Value contiene 'modulo|id'
                    return $this->resolveDataBlockBean($value, $context);

                case ActionParameterType::FIELD:
                    // El parámetro es un campo del formulario. Value contiene el nombre del campo
                    return $this->resolveFormField($value, $context);

                case ActionParameterType::OBJECT_SELECTOR:
                    // El parámetro es un selector 'selectedOption' contiene la selección
                    $selectedOption = $config->selectedOption;
                    return $this->resolveObjectSelector($def, $config, $context);
            }
        }

        // No se ha retornado ninguna resolución
        if ($def->required) {
            $GLOBALS['log']->error("Line ".__LINE__.": ".__METHOD__.": Required parameter {$def->name} not informed in form configuration.");
        } else {
            $GLOBALS['log']->warning("Line ".__LINE__.": ".__METHOD__.": Parameter {$def->name} not informed in form configuration.");
        }
        return null;
    }

    // --- MÈTODES PRIVATS D'AJUDA ---

    private function resolveDataBlockConfig(string $dataBlockId, ExecutionContext $context): ?FormDataBlock {
        foreach ($context->formConfig->data_blocks as $dataBlock) {
            if ($dataBlock->id === $dataBlockId) {
                return $dataBlock;
            }
        }
        $GLOBALS['log']->warning("ParameterResolver: Configuració del bloc '{$dataBlockId}' no trobada.");
        return null;
    }

    // TODO: Esta lógica se tendrá que refactorizar cuando se implementen los Grupos de Bloques de Datos:
    // Se crearán múltiples bloques de datos con el mísmo FormDataBlock, y por lo tanto sólo se tendrá
    // la beanReference del último (se sobreescribirá en cada iteración)

    private function resolveDataBlockBean(string $dataBlockId, ExecutionContext $context): ?BeanReference {
        // Busquem la configuració del bloc
        $dataBlock = $this->resolveDataBlockConfig($dataBlockId, $context);
        
        if ($dataBlock === null) {
            $GLOBALS['log']->warning("ParameterResolver: No es pot resoldre el bean per al bloc '{$dataBlockId}' (bloc no trobat).");
            return null;
        }

        // *** APLIQUEM LA TEVA ÚLTIMA LÒGICA ***
        // Accedim a la propietat on la SaveBeanAction ha desat el seu resultat
        $beanReference = $dataBlock->getBeanReference();
        if ($beanReference != null)
            return $beanReference;

        // TODO: (Refactorització futura per DataGroups)
        
        $GLOBALS['log']->warning("ParameterResolver: Bloc '{$dataBlockId}' trobat, però encara no té un BeanReference.");
        return null;
    }

    private function resolveFormField(string $fieldName, ExecutionContext $context): mixed {
        if (array_key_exists($fieldName, $context->formData)) {
            return $context->formData[$fieldName];
        }
        $GLOBALS['log']->warning("ParameterResolver: Camp '{$fieldName}' no trobat a formData.");
        return null;
    }

    private function resolveFixedValue(string $value, ?string $dataType): mixed {
        // Aquí podríem fer 'type casting' basat en el $dataType
        // (ex: 'boolean', 'integer')
        return $value;
    }

    private function resolveObjectSelector(
        ActionParameterDefinition $def, 
        FormActionParameter $config,
        ExecutionContext $context
    ): mixed {
        
        $selectedOptionName = $config->selectedOption;
        $value = $config->value;

        // Trobem la definició de l'opció seleccionada
        $optionDef = null;
        foreach ($def->selectorOptions as $o) {
            if ($o->name === $selectedOptionName) {
                $optionDef = $o;
                break;
            }
        }
        
        if ($optionDef === null) {
            $GLOBALS['log']->warning("ParameterResolver: Opció '{$selectedOptionName}' no trobada. Tornant a 'fixed_value'.");
            return $this->resolveFixedValue($value, $def->dataType?->value);
        }

        // Ara resolem basant-nos en el 'resolvedType' de l'OPCIÓ
        switch ($optionDef->resolvedType) {
            case ActionParameterType::VALUE:
                return $this->resolveFixedValue($value, $def->dataType?->value);
            case ActionParameterType::FIELD:
                return $this->resolveFormField($value, $context);
            case ActionParameterType::DATA_BLOCK:
                return $this->resolveDataBlockConfig($value, $context);
            case ActionParameterType::CRM_RECORD:
                return $this->resolveDataBlockBean($value, $context);
            default:
                return null;
        }
    }
}

