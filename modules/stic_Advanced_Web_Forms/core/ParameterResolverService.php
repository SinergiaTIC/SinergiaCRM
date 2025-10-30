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
    
    private ExecutionContext $context;

    public function __construct(ExecutionContext $context) {
        $this->context = $context;
    }

    /**
     * Resuelve un parámetro de configuración a su valor real.
     * @param FormActionParameter $paramConfig La configuración del parámetro (del wizard)
     * @return mixed El valor resuelto (string, bool, BeanReference, FormDataBlock, etc.)
     */
    public function resolve(FormActionParameter $paramConfig): mixed {
        $option = $paramConfig->selectedOption;
        $value = $paramConfig->value;

        // 'selectedOption' ens diu COM interpretar el 'value'
        switch ($option) {
            case 'form_field':
                return $this->resolveFormField($value);
                
            case 'data_block_config':
                return $this->resolveDataBlockConfig($value);

            case 'data_block_bean':
                return $this->resolveDataBlockBean($value);

            case 'fixed_value':
            default:
                // Si 'selectedOption' és buit o 'fixed_value',
                // el valor és el propi valor.
                return $this->resolveFixedValue($value);
        }
    }

    // --- MÈTODES PRIVATS D'AJUDA ---

    /**
     * Retorna el valor d'un camp del formulari enviat.
     */
    private function resolveFormField(string $fieldName): mixed {
        // Assumeix que $fieldName és la clau exacta al formData.
        // TODO: Caldrà gestionar camps de grups repetibles (DataGroup) en el futur.
        if (array_key_exists($fieldName, $this->context->formData)) {
            return $this->context->formData[$fieldName];
        }
        
        $GLOBALS['log']->warning("ParameterResolver: Camp '{$fieldName}' no trobat a formData.");
        return null;
    }

    /**
     * Retorna la *definició* d'un Bloc de Dades.
     * Útil per a accions que necessiten la configuració (ex: SaveBeanAction).
     */
    private function resolveDataBlockConfig(string $dataBlockId): ?FormDataBlock {
        foreach ($this->context->formConfig->data_blocks as $dataBlock) {
            if ($dataBlock->id === $dataBlockId) {
                return $dataBlock;
            }
        }
        
        $GLOBALS['log']->warning("ParameterResolver: Configuració del bloc '{$dataBlockId}' no trobada.");
        return null;
    }

    /**
     * Retorna la *referència al bean* (BeanReference) creada per un Bloc de Dades.
     * Útil per a accions que enllacen registres (ex: RelateRecordsAction).
     */
    private function resolveDataBlockBean(string $dataBlockId): ?BeanReference {
        // Busquem a tots els resultats d'accions executades prèviament
        // foreach ($this->context->actionResults as $result) {
            
        //     // Mirem si el resultat pertany a una acció d'aquest bloc de dades
        //     if ($result->actionConfig?->data_block_id === $dataBlockId) {
                
        //         // Aquest resultat és del bloc correcte. Mirem si ha modificat un bean.
        //         if ($result->hasModifiedBeans()) {
        //             // Trobada! Retornem el primer bean modificat (ex: SaveBeanAction)
        //             $modifiedBean = $result->modifiedBeans[0];
        //             return new BeanReference($modifiedBean->module, $modifiedBean->id);
        //         }
        //     }
        // }
        
        $GLOBALS['log']->warning("ParameterResolver: Cap BeanReference trobat per al bloc '{$dataBlockId}'.");
        return null;
    }

    /**
     * Retorna un valor fix.
     */
    private function resolveFixedValue(string $value): string {
        // En el futur, podria gestionar el 'type casting' (a bool, int...)
        // si la definició del paràmetre (ActionParameterDefinition) estigués disponible.
        // Per ara, retornem el string directament.
        return $value;
    }
}