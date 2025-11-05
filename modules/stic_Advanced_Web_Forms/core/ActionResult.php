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

enum ResultStatus: string {
    case OK      = 'ok';
    case SKIPPED = 'skipped';
    case ERROR   = 'error';
}

/**
 * Clase para representar el resultado de una acción.
 */
class ActionResult {
    public ResultStatus $status;        // Estado del resultado
    public ?string $message;            // Mensaje adicional del resultado
    /** @var BeanModified[] */
    public array $modifiedBeans;        // Beans modificados por la acción

    public float $timestamp;            // Marca temporal de la ejecución
    public ?FormAction $actionConfig;   // Configuración de la acción ejecutada

    public function __construct(ResultStatus $status, ?FormAction $actionConfig, ?string $message = null) {
        $this->status = $status;
        $this->actionConfig = $actionConfig;
        $this->message = $message;
        $this->modifiedBeans = [];
        $this->timestamp = microtime(true);
    }

    /**
     * Registra una modificación de un bean al resultado de la acción
     * Se usa cuando el bean NO proviene de un DataBlock 
     * 
     * @param SugarBean $bean El bean modificado.
     * @param BeanModificationType $action El tipo de modificación (CREATED, UPDATED, ENRICHED, SKIPPED)
     */
    public function registerBeanModification(SugarBean $bean, BeanModificationType $action): void 
    {
        $modifiedBean = new BeanModified($bean->id, $bean->module_name, $action);
        $this->addModifiedBean($modifiedBean);
    }

    /**
     * Registra una modificación de un bean que SÍ proviene de un DataBlock.
     *  - Registra la modificación
     *  - Guarda la referencia al bean en el FormDataBlock original para futures acciones.
     * 
     * @param SugarBean $bean El bean que se ha procesado.
     * @param DataBlockResolved $block El DataBlockResolved que se ha procesado.
     * @param BeanModificationType $action El tipo de modificación  (CREATED, UPDATED, ENRICHED, SKIPPED)
     * @throws \LogicException Si el módulo del bean no coincide con el del bloque.
     */
    public function registerBeanModificationFromBlock(SugarBean $bean, DataBlockResolved $block, BeanModificationType $action): void 
    {
        $blockModule = $block->dataBlock->module;
        if ($bean->module_name !== $blockModule) {
            throw new \LogicException("Error in registerBeanModificationFromBlock: Bean module ('{$bean->module_name}') is different from block module ('{$blockModule}').");
        }

        // Extraemos los datos del formulario mapeables al bean para registrarlos
        $dataToLog = [];
        foreach ($block->formData as $fieldName => $fieldResolved) {
            $dataToLog[$fieldName] = $fieldResolved->value;
        }

        $modifiedBean = new BeanModified($bean->id, $bean->module_name, $action, $dataToLog);
        $this->addModifiedBean($modifiedBean);

        $block->dataBlock->setBeanReference($bean->id);
    }

    public function addModifiedBean(BeanModified $bean): void {
        $this->modifiedBeans[] = $bean;
    }

    public function hasModifiedBeans(): bool {
        return !empty($this->modifiedBeans);
    }

    public function resetTimestamp(): void {
        $this->timestamp = microtime(true);
    }

    public function isError(): bool {
        return $this->status === ResultStatus::ERROR;
    }

    public function isSkipped(): bool {
        return $this->status === ResultStatus::SKIPPED;
    }

    public function isOk(): bool {
        return $this->status === ResultStatus::OK;
    }

}
