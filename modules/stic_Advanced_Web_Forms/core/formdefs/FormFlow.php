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

class FormFlow {
    public FormConfig $form_config;  // La configuración del formulario al que pertenece

    public string $id;               // Id del flujo de acciones
    public string $name;             // Nombre interno del flujo de acciones
    public string $text;             // El texto a mostrar

    // @var FormAction[]
    public array $actions;           // Las acciones del Flujo

    /**
     * Crea una instancia de FormFlow a partir de un array JSON.
     * @param FormConfig $form La configuración del formulario al que pertenece
     * @param array $data Los datos en formato array
     * @return FormFlow La instancia creada
     */
    public static function fromJsonArray(FormConfig $form, array $data): self {
        $dto = new self();
        $dto->form_config = $form;

        $dto->id = $data['id'];
        $dto->name = $data['name'];
        $dto->text = $data['text'];
        
        $dto->actions = [];
        if (isset($data['actions'])) {
            foreach ($data['actions'] as $actionData) {
                $dto->actions[] = FormAction::fromJsonArray($dto, $actionData);
            }
        }

        return $dto;
    }
}