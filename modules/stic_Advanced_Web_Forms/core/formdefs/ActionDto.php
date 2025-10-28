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

class ActionDto {
    public string $id;      // Id del Flujo de acciones
    public string $name;    // Nombre del Flujo de acciones
    public string $label;   // La etiqueta a traducir para el nombre
    public string $text;    // El texto a mostrar 
    // @var ActionsDto[]
    public array $actions;  // Las acciones del Flujo

    /**
     * Crea una instancia de ActionDto a partir de un array JSON.
     * @param array $data Los datos en formato array
     * @return ActionDto La instancia creada
     */
    public static function fromJsonArray(array $data): self {
        $dto = new self();
        $dto->id = $data['id'];
        $dto->name = $data['name'];
        $dto->label = $data['label'];
        $dto->text = $data['text'];

        $dto->actions = [];
        if (isset($data['actions'])) {
            foreach ($data['actions'] as $actionData) {
                $dto->actions[] = ActionDto::fromJsonArray($actionData);
            }
        }

        return $dto;
    }
}