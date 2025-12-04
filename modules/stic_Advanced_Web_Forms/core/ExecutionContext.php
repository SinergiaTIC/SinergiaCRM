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

class ExecutionContext {
    public array $formData = [];       // Copia de los datos RAW del formulario recibido
    
    public string $formId = '';        // ID del formulario que se está procesando
    public string $responseId = '';    // ID de respuesta generado para este envío

    public FormConfig $formConfig;     // Configuración del formulario

    /** @var ActionResult[] */
    public array $actionResults = [];

    public float $submissionTimestamp;

    /**
     * Constructor de la clase ExecutionContext.
     * @param string $formId ID del formulario que se está procesando
     * @param string $responseId ID de respuesta generado para este envío
     * @param array $formData Datos RAW del formulario recibido
     * @param FormConfig $formConfig Configuración del formulario
     * @param ?float $timestamp El timestamp del envío de datos del formulario (opcional)
     */
    public function __construct(string $formId, string $responseId, array $formData, FormConfig $formConfig, ?float $timestamp = null) {
        $this->formId = $formId;
        $this->responseId = $responseId;
        $this->formData = $formData;
        $this->formConfig = $formConfig;
        $this->actionResults = [];
        $this->submissionTimestamp = $timestamp ?? microtime(true);
    }

    /**
     *  Agrega el resultado de una acción al contexto de ejecución.
     * @param ActionResult $result Resultado de la acción
     */
    public function addActionResult(ActionResult $result): void {
        $result->resetTimestamp();
        $key = $result->actionConfig?->id;
        if ($key === null) {
            $key = 'unknown_' . count($this->actionResults);
            $GLOBALS['log']->warning("Adding ActionResult with unknown action ID to ExecutionContext. Assigned key: {$key}");
        }
        $this->actionResults[$key] = $result;
        if($result->isError()) {
            $GLOBALS['log']->error("Action '{$result->actionConfig?->name}' resulted in ERROR: " . $result->message);
        }
    }

    /**
     * Agrega un error al contexto de ejecución.
     * @param \Exception $e Excepción que representa el error
     * @param ?FormAction $actionConfig Configuración de la acción donde ocurrió el error (null si no aplica)
     */
    public function addError(\Exception $e, ?FormAction $actionConfig): void {
        $errorResult = new ActionResult(ResultStatus::ERROR, $actionConfig, $e->getMessage());
        $this->addActionResult($errorResult);

        $GLOBALS['log']->error("Exception: " . $e->getMessage());
        $GLOBALS['log']->error($e->getTraceAsString());
    }

    /**
     * Obtiene el resultado de una acción por su ID.
     * @param string $actionId ID de la acción
     * @return ?ActionResult Resultado de la acción o null si no se encuentra
     */
    public function getActionResultById(string $actionId): ?ActionResult {
        return $this->actionResults[$actionId] ?? null;
    }

    /**
     * Obtiene el bloque de datos por su ID.
     * @param string $blockId ID del bloque de datos
     * @return ?FormDataBlock El bloque de datos o null si no se encuentra
     */
    public function getDataBlockById(string $blockId): ?FormDataBlock {
        return $this->formConfig->data_blocks[$blockId] ?? null;
    }

    /**
     * Obtiene el bloque de datos por su Nombre.
     * @param string $blockName El nombre del bloque de datos
     * @return ?FormDataBlock El bloque de datos o null si no se encuentra
     */
    public function getDataBlockByName(string $blockName): ?FormDataBlock {
        foreach ($this->formConfig->data_blocks as $block) {
            if ($block->name === $blockName) {
                return $block;
            }
        }
        return null;
    }

}


