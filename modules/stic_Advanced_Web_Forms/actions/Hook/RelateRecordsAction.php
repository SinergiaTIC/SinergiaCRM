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

include_once "modules/stic_Advanced_Web_Forms/actions/CoreActions.php";
class RelateRecordsAction extends HookBeanActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->baseLabel = 'LBL_RELATE_RECORDS_ACTION';
    }

    /**
     * getCustomParameters()
     * Definim els paràmetres ADICIONALS que necessitem,
     * a més del paràmetre de bloc principal que ja defineix la classe pare.
     */
    protected function getCustomParameters(): array
    {
        // --- Paràmetre 1: El Bloc B (el bloc "destí" del qual agafarem l'ID) ---
        $paramTargetBlock = new ActionParameterDefinition();
        $paramTargetBlock->name = 'target_data_block';
        $paramTargetBlock->text = $this->translate('TARGET_BLOCK_TEXT'); // Ej: "Bloque de datos de destino"
        $paramTargetBlock->description = $this->translate('TARGET_BLOCK_DESC'); // Ej: "El bloque del cual se obtendrá el ID"
        $paramTargetBlock->type = ActionParameterType::DATA_BLOCK; 
        $paramTargetBlock->required = true;

        // --- Paràmetre 2: El "Camp X" (el camp que actualitzarem al Bean A) ---
        $paramFieldName = new ActionParameterDefinition();
        $paramFieldName->name = 'field_to_update';
        $paramFieldName->text = $this->translate('FIELD_TO_UPDATE_TEXT'); // Ej: "Campo a actualizar"
        $paramFieldName->description = $this->translate('FIELD_TO_UPDATE_DESC'); // Ej: "El nombre del campo (ej: 'contact_id_c') que recibirá el ID"
        $paramFieldName->type = ActionParameterType::VALUE; 
        $paramFieldName->dataType = ActionDataType::TEXT; 
        $paramFieldName->required = true;

        return [$paramTargetBlock, $paramFieldName];
    }

    /**
     * executeWithBean()
     * Aquest és el nucli de la nostra lògica.
     * La classe base ja ens dóna el '$bean' (Bean A) carregat.
     *
     * @param ExecutionContext $context El context global.
     * @param FormAction $actionConfig La configuració de l'acció.
     * @param SugarBean $bean El bean del bloc principal (Bean A), carregat i llest.
     * @param DataBlockResolved $block El bloc de dades principal (Bloc A), amb les dades del formulari.
     * @return ActionResult
     */
    public function executeWithBean(ExecutionContext $context, FormAction $actionConfig, SugarBean $bean, DataBlockResolved $block): ActionResult
    {
        // 1. Obtenim els nostres paràmetres personalitzats
        $fieldName = $actionConfig->getResolvedParameter('field_to_update');
        
        /** @var ?DataBlockResolved $targetBlock */
        $targetBlock = $actionConfig->getResolvedParameter('target_data_block');

        // 2. Validem els paràmetres (encara que el Resolver ja ho fa, és bona pràctica)
        if (empty($fieldName) || $targetBlock === null) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Paràmetres 'field_to_update' o 'target_data_block' no resolts.");
        }

        // 3. Obtenim la referència del Bean B
        $targetBeanRef = $targetBlock->dataBlock->getBeanReference();
        if ($targetBeanRef === null) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "El bloc destí '{$targetBlock->dataBlock->name}' no té un bean desat.");
        }

        // 4. Fem l'assignació (Lògica principal)
        $bean->{$fieldName} = $targetBeanRef->beanId;

        // 5. Desem el Bean A (el bean que hem modificat)
        $bean->save();

        // 6. Notifiquem el resultat
        $actionResult = new ActionResult(ResultStatus::OK, $actionConfig, "Relació establerta: {$bean->module_name}.{$fieldName} = {$targetBeanRef->beanId}");
        
        // Notifiquem que hem ACTUALITZAT el Bean A
        $actionResult->registerBeanModificationFromBlock($bean, $block, BeanModificationType::UPDATED);

        return $actionResult;
    }
}