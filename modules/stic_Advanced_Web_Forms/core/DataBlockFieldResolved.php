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
 * Class representing a data block field with the filled form data
 */
class DataBlockFieldResolved {
    public ?FormDataBlockField $dataBlockField;   // The Data Block Field configuration (if present)

    public string $formKey;         // The full field name in the form
    public string $fieldName;       // The field name (after the prefix) (ex: email1, first_name)
    public mixed $value;            // The value sent from the form in DB-ready format
    public mixed $originalValue;    // The original value in complex object form (e.g., DateTime)

    public function __construct(string $formKey, string $fieldName, ?FormDataBlockField $config, mixed $value) {
        $this->formKey = $formKey;
        $this->fieldName = $fieldName;
        $this->dataBlockField = $config;
        $this->originalValue = $value;

        // Normalize the value so it can be mapped to the Database
        if ($value instanceof \DateTime) {
            global $timedate;
            $type = $config->type ?? 'datetime'; 
            if ($type === 'date') {
                $this->value = $timedate->asDbDate($value);
            } elseif ($type === 'time') {
                $this->value = $value->format('H:i:s');
            } else {
                $this->value = $timedate->asDb($value);
            }
        } else {
            $this->value = $value;
        }
    }

    public function getDateTime(): ?\DateTime {
        return ($this->originalValue instanceof \DateTime) ? $this->originalValue : null;
    }

    public function isDetached(): bool {
        return str_starts_with($this->formKey, '_detached.') || str_starts_with($this->formKey, '_detached_');
    }
}
