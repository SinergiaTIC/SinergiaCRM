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

class FlowDto {
    public FormConfigDto $form_config;  // La configuración del formulario al que pertenece

    public string $id;                  // Id de la acción
    public string $name;                // Nombre interno de la acción
    public string $data_block_id;       // Id del Bloque de datos al que pertenece
    // @var string[]
    public array $requisite_actions;    // Array con los identificadores de las acciones previas a la actual
    // @var ActionDto[]
    public array $actions;              // Las acciones del Flujo

    /**
     * Crea una instancia de FlowDto a partir de un array JSON.
     * @param FormConfigDto $form La configuración del formulario al que pertenece
     * @param array $data Los datos en formato array
     * @return FlowDto La instancia creada
     */
    public static function fromJsonArray(FormConfigDto $form, array $data): self {
        $dto = new self();
        $dto->form_config = $form;

        $dto->id = $data['id'];
        $dto->name = $data['name'];
        $dto->data_block_id = $data['data_block_id'];
        $dto->requisite_actions = $data['requisite_actions'] ?? [];
        
        $dto->actions = [];
        if (isset($data['actions'])) {
            foreach ($data['actions'] as $actionData) {
                $dto->actions[] = ActionDto::fromJsonArray($dto, $actionData);
            }
        }

        return $dto;
    }
}