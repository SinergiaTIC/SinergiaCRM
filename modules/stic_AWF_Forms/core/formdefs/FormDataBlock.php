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

class FormDataBlock {
    public FormConfig $form_config;       // The configuration of the form it belongs to

    public string $id;                    // ID of the data block
    public string $name;                  // Internal name (UI identifier) of the data block
    public string $text;                  // Text to display for the data block
    public string $module;                // Module name
    /** @var FormDataBlockField[] */
    public array $fields;                 // Fields of the data block
    /** @var FormDuplicateRule[] */
    public array $duplicate_detections;   // Definition of duplicate detection

    private ?BeanReference $beanReference = null; // Bean where the data block has been saved

    /**
     * Creates an instance of FormDataBlock from a JSON array.
     * @param FormConfig $form The configuration of the form it belongs to
     * @param array $data The data in array format
     * @return FormDataBlock The created instance
     */
    public static function fromJsonArray(FormConfig $form, array $data): self {
        $dto = new self();
        $dto->form_config = $form;

        $dto->id = $data['id'];
        $dto->name = $data['name'];
        $dto->text = $data['text'];
        $dto->module = $data['module'];

        $dto->fields = [];
        if (isset($data['fields'])) {
            foreach ($data['fields'] as $fieldData) {
                $formDataBlockField = FormDataBlockField::fromJsonArray($dto, $fieldData);
                $dto->fields[$formDataBlockField->name] = $formDataBlockField;
            }
        }

        $dto->duplicate_detections = [];
        if (isset($data['duplicate_detections'])) {
            foreach ($data['duplicate_detections'] as $dupData) {
                $dto->duplicate_detections[] = FormDuplicateRule::fromJsonArray($dto, $dupData);
            }
        }

        return $dto;
    }

    public function setBeanReference(string $beanId): void {
        $this->beanReference = new BeanReference($this->module, $beanId);
    }

    public function getBeanReference(): ?BeanReference {
        return $this->beanReference;
    }
}