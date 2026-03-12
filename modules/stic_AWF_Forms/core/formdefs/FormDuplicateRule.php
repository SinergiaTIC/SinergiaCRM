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

class FormDuplicateRule {
    public FormDataBlock $data_block;          // The data block it belongs to

    /** @var string[] */
    public array $fields;                     // Array with the field names for duplicate detection
    public OnDuplicateAction $on_duplicate;   // Action to perform with duplicates: update, enrich, skip, error

    /**
     * Creates an instance of FormDuplicateRule from a JSON array.
     * @param FormDataBlock $dataBlock The data block it belongs to
     * @param array $data The data in array format
     * @return FormDuplicateRule The created instance
     */
    public static function fromJsonArray(FormDataBlock $dataBlock, array $data): self {
        $dto = new self();
        $dto->data_block = $dataBlock;

        $dto->fields = $data['fields'];
        $dto->on_duplicate = OnDuplicateAction::from($data['on_duplicate']);

        return $dto;
    }
}