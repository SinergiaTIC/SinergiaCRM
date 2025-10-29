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

class FormAction {
    public FormFlow $flow;           // El Flujo de acciones al que pertenece

    public string $id;               // Id de la acción
    public string $name;             // Nombre de la acción
    public string $text;             // El texto a mostrar
    public string $description;      // La descripción de la acción
    public string $data_block_id;    // Id del Bloque de datos al que pertenece
    // @var string[]
    public array $requisite_actions; // Array con los identificadores de las acciones previas a la actual
    // @var FormActionParameter[]
    public array $parameters;        // Los parámetros de la acción

    /**
     * Crea una instancia de FormAction a partir de un array JSON.
     * @param FormFlow $flow El Flujo de acciones al que pertenece 
     * @param array $data Los datos en formato array
     * @return FormAction La instancia creada
     */
    public static function fromJsonArray(FormFlow $flow, array $data): self {
        $dto = new self();
        $dto->flow = $flow;

        $dto->id = $data['id'];
        $dto->name = $data['name'];
        $dto->text = $data['text'];
        $dto->description = $data['description'];
        $dto->data_block_id = $data['data_block_id'];
        $dto->requisite_actions = $data['requisite_actions'] ?? [];

        $dto->parameters = [];
        if (isset($data['parameters'])) {
            foreach ($data['parameters'] as $parameterData) {
                $dto->parameters[] = FormActionParameter::fromJsonArray($dto, $parameterData);
            }
        }

        return $dto;
    }
}