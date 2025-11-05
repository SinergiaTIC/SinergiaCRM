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
 * Clase abstracta para acciones que operen sobre UN bloque de datos.
 * Automatiza la definición, obtención y validación del parámetro DataBlock.
 */
abstract class HookDataBlockActionDefinition extends HookActionDefinition {

    /** 
     * (Opcional) Sobreescribir para cambiar el nombre del parámetro que contiene el bloque de datos.
     * @return string
    */
    protected function getDataBlockParameterName(): string {
        return 'data_block_id';
    }

    /**
     * (Opcional) Sobreescribir para definir el texto (label) del parámetro de bloque de datos.
     * @return string
     */
    protected function getDataBlockParameterText(): string {
        return translate('LBL_ACTION_DATABLOCK_PARAM_TEXT', 'stic_Advanced_Web_Forms');
    }

    /**
     * (Opcional) Sobreescribir para definir la descripción (help text) del parámetro de bloque de datos.
     * @return string
     */
    protected function getDataBlockParameterDescription(): string {
        return translate('LBL_ACTION_DATABLOCK_PARAM_DESC', 'stic_Advanced_Web_Forms');
    }

    /**
     * (Opcional) Sobreescribir para limitar a qué módulos puede apuntar el parámetro de bloque de datos.
     * @return string[] Lista de módulos permitidos (ej: ['Contacts', 'Accounts'])
     */
    protected function getSupportedModules(): array {
        return []; // Vacío = todos los módulos
    }

    /**
     * (Opcional) Sobreescribir para añadir parámetros ADICIONALES a parte del parámetro de bloque de datos.
     * @return ActionParameterDefinition[]
     */
    protected function getCustomParameters(): array {
        return [];
    }

    final public function getParameters(): array
    {
        // Crear el parámetro de bloque de datos
        $blockParam = new ActionParameterDefinition();
        $blockParam->name = $this->getDataBlockParameterName();
        $blockParam->text = $this->getDataBlockParameterText();
        $blockParam->description = $this->getDataBlockParameterDescription();
        $blockParam->type = ActionParameterType::DATA_BLOCK;
        $blockParam->supportedModules = $this->getSupportedModules();
        $blockParam->required = true;

        // Añadir los parámetros personalizados del programador
        $customParams = $this->getCustomParameters();
        
        return array_merge([$blockParam], $customParams);
    }

    final public function execute(ExecutionContext $context, FormAction $actionConfig): ActionResult
    {
        /** @var ?DataBlockResolved $block */
        $block = $actionConfig->getResolvedParameter($this->getDataBlockParameterName());

        if ($block === null) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Can not resolve DataBlock parameter '{$this->getDataBlockParameterName()}'.");
        }

        // Llamar al nuevo método que se implementará en la acción
        return $this->executeWithBlock($context, $actionConfig, $block);
    }

    /**
     * Método a implementar
     * Ejecuta la acción, recibe el bloque de datos principal resuelto y validado.
     *
     * @param ExecutionContext $context El contexto global.
     * @param FormAction $actionConfig La configuración de la acción.
     * @param DataBlockResolved $block El bloque de datos principal, listo para ser usado.
     * @return ActionResult
     */
    public abstract function executeWithBlock(ExecutionContext $context, FormAction $actionConfig, DataBlockResolved $block): ActionResult;
}