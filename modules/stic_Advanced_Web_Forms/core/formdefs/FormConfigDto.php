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

class FormConfigDto {
    // @var DataBlockDto[]
    public array $data_blocks;        // Los bloques de datos del formulario
    // @var FlowDto[]
    public array $flows;              // Los flujos de acciones del formulario

    /**
     * Crea una instancia de FormConfigDto a partir de un array JSON.
     * @param array $data Los datos en formato array
     * @return FormConfigDto La instancia creada
     */
    public static function fromJsonArray(array $data): self {
        $dto = new self();

        $dto->data_blocks = [];
        if (isset($data['data_blocks'])) {
            foreach ($data['data_blocks'] as $dataBlockData) {
                $dto->data_blocks[] = DataBlockDto::fromJsonArray($dto, $dataBlockData);
            }
        }

        $dto->flows = [];
        if (isset($data['flows'])) {
            foreach ($data['flows'] as $flowData) {
                $dto->flows[] = FlowDto::fromJsonArray($dto, $flowData);
            }
        }

        return $dto;
    }
}