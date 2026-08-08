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

    public bool $is_repeatable = false;      // Indicates if the block can be repeated 0..N times
    public int $min_instances = 1;           // Minimum required instances (0 = optional)
    public ?int $max_instances = 1;          // Maximum allowed instances (1 = simple, >1 or null = repeatable, null = no limit)
    public string $group_title = '';         // Visual title for the repeat group
    public bool $is_custom_group_title = false; // Flag to track manual title overrides
    public string $toggle_label = '';        // Label for the "include instance data" toggle switch
    public string $add_button_label = '';    // Label for the "add instance" button
    public string $remove_button_label = ''; // Label for the "remove instance" button
    public string $group_root = '';  // ID of the repeatable root this block belongs to

    private ?BeanReference $beanReference = null; // Bean where the data block has been saved

    /** @var array<int, BeanReference> */
    private array $beanReferences = [];

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

        // STIC-Custom OC - 20250803 - Map repeatable data block fields
        $dto->is_repeatable = $data['is_repeatable'] ?? false;
        $dto->min_instances = isset($data['min_instances']) ? (int)$data['min_instances'] : 1;
        $dto->max_instances = isset($data['max_instances']) && $data['max_instances'] !== '' && $data['max_instances'] !== null ? (int)$data['max_instances'] : null;
        $dto->group_title = $data['group_title'] ?? '';
        $dto->is_custom_group_title = isset($data['is_custom_group_title']) ? (bool)$data['is_custom_group_title'] : false;
        
        $dto->toggle_label = $data['toggle_label'] ?? '';
        $dto->add_button_label = $data['add_button_label'] ?? '';
        $dto->remove_button_label = $data['remove_button_label'] ?? '';
        $dto->group_root = $data['group_root'] ?? '';
        // END STIC-Custom OC

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

    public function setBeanReference(string $beanId, ?int $index = null): void {
        // STIC-Custom OC - 20250803 - Support indexed bean references for repeatable blocks
        if ($index === null) {
            $this->beanReference = new BeanReference($this->module, $beanId);
            return;
        }
        $this->beanReferences[$index] = new BeanReference($this->module, $beanId);
        // END STIC-Custom OC
    }

    public function getBeanReference(?int $index = null): ?BeanReference {
        // STIC-Custom OC - 20250803 - Support indexed bean references for repeatable blocks
        if ($index === null) {
            return $this->beanReference;
        }
        return $this->beanReferences[$index] ?? null;
        // END STIC-Custom OC
    }

    // STIC-Custom OC - 20250803 - Intra-POST duplicate detection support
    /**
     * Returns the bean references already registered for repeatable instances
     * during the current request execution.
     * @return array<int, BeanReference>
     */
    public function getIndexedBeanReferences(): array {
        return $this->beanReferences;
    }
    // END STIC-Custom OC
}