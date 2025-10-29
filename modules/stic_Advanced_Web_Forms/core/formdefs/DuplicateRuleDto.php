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

enum OnDuplicateAction: string {
    case UPDATE = 'update';
    case ENRICH = 'enrich';
    case SKIP   = 'skip';
    case ERROR  = 'error';
}

class DuplicateRuleDto {
    public DataBlockDto $data_block;          // El Bloque de datos al que pertenece

    // @var string[] 
    public array $fields;                     // Array con el nombre de los campos para la detección de duplicados
    public OnDuplicateAction $on_duplicate;   // Acción a realizar con los duplicados: update, enrich, skip, error

    /**
     * Crea una instancia de DuplicateRuleDto a partir de un array JSON.
     * @param DataBlockDto $dataBlock El Bloque de datos al que pertenece
     * @param array $data Los datos en formato array
     * @return DuplicateRuleDto La instancia creada
     */
    public static function fromJsonArray(DataBlockDto $dataBlock, array $data): self {
        $dto = new self();
        $dto->data_block = $dataBlock;
        
        $dto->fields = $data['fields'];
        $dto->on_duplicate = OnDuplicateAction::from($data['on_duplicate']);

        return $dto;
    }
}