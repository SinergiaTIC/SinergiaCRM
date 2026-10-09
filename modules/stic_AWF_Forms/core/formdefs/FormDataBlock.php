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
    public bool $is_document_block = false; // Whether this is a Document upload block

    public int $min_instances = 1;           // Minimum required instances (0 = optional)
    public ?int $max_instances = 1;          // Maximum allowed instances (1 = simple, >1 or null = repeatable, null = no limit)
    public string $group_title = '';         // Visual title for the repeat group
    public bool $is_custom_group_title = false; // Flag to track manual title overrides
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
        $dto->is_document_block = $data['is_document_block'] ?? false;

        // Map repeatable data block fields
        $dto->min_instances = isset($data['min_instances']) ? (int)$data['min_instances'] : 1;
        $dto->max_instances = isset($data['max_instances']) && $data['max_instances'] !== '' && $data['max_instances'] !== null ? (int)$data['max_instances'] : null;
        $dto->group_title = $data['group_title'] ?? '';
        $dto->is_custom_group_title = isset($data['is_custom_group_title']) ? (bool)$data['is_custom_group_title'] : false;
        
        $dto->group_root = $data['group_root'] ?? '';

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

    /**
     * Check if the block can be repeated 0..N times 
     */
    public function isRepeatable(): bool {
        return $this->max_instances == null || $this->max_instances > 1;
    }

    /**
     * Check if the block is optional 
     */
    public function isOptional(): bool {
        return $this->min_instances == 0;
    }

    /**
     * Number of repeatable loop variables needed to address this block's
     * instances in the POST matrix:
     *  - 0: scalar block (static field names).
     *  - 1: block living inside one repeatable loop (the top-level group root
     *       or any direct child of it).
     *  - 2: block living inside two nested repeatable loops (a descendant of
     *       a subgroup head, e.g. Adult[i] -> Menor[i][j]).
     * The count walks the group_root chain: every repeatable/optional ancestor
     * adds a loop variable; a repeatable/optional ROOT block adds its own.
     */
    public function getLoopDepth(): int {
        // The loop depth counts every GROUP HEAD in the chain from THIS block
        // up to its root, INCLUDING this block: repeatable, optional AND
        // simple (max=1 with children) heads all render an x-for loop and add
        // one loop level. Repeatable levels are capped at 2 by the model
        // (canBeRepeatable); optional levels are UNLIMITED (N optional levels).
        // Examples: scalar block -> 0; repeatable root or its simple child -> 1;
        // subgroup head or its descendants -> 2; third optional level -> 3...
        $depth = 0;
        $current = $this;
        $visited = [$current->id => true];
        while (true) {
            if ($current->isGroupHead()) $depth++;
            if (empty($current->group_root)) break;
            $parent = $this->form_config->data_blocks[$current->group_root] ?? null;
            if ($parent === null || isset($visited[$parent->id])) break; // Missing parent or cycle guard
            $visited[$parent->id] = true;
            $current = $parent;
        }
        return $depth;
    }

    /**
     * True when this block renders its own x-for loop (a group head):
     * repeatable, optional, or a simple block with children.
     */
    public function isGroupHead(): bool {
        return $this->isRepeatable() || $this->isOptional()
            || count($this->form_config->getGroupChildren($this)) > 0;
    }

    public function setBeanReference(string $beanId, int|string|null $index = null): void {
        if ($index === null) {
            $this->beanReference = new BeanReference($this->module, $beanId);
            return;
        }
        // Depth-1 instances use int indexes; depth-2 instances use the
        // composite string key "outer:inner" so both levels are addressed.
        $this->beanReferences[$index] = new BeanReference($this->module, $beanId);
    }

    public function getBeanReference(int|string|null $index = null): ?BeanReference {
        if ($index === null) {
            return $this->beanReference;
        }
        return $this->beanReferences[$index] ?? null;
    }

    /**
     * Loop-depth-aware bean reference lookup for the ACTIVE execution context
     * (B-4 / n-dimensional POST matrix): the reference key is the first
     * d indexes of the context's active index stack (d = this block's loop
     * depth) — a shallower target read through a deeper action uses the outer
     * indexes. Returns null when the active stack is shallower than this
     * block's depth (the deeper instance is not addressed by this execution).
     */
    public function getReferenceForContext(ExecutionContext $context): ?BeanReference {
        $depth = $this->getLoopDepth();
        if ($depth === 0) {
            return $this->getBeanReference();
        }
        $stack = $context->getInstanceIndexes();
        if (count($stack) < $depth) {
            return null;
        }
        $index = implode(':', array_slice($stack, 0, $depth));
        return $this->getBeanReference($index);
    }

    /**
     * Returns the bean references already registered for repeatable instances
     * during the current request execution. Keys are int indexes (depth-1) or
     * composite "outer:inner" strings (depth-2).
     * @return array<int|string, BeanReference>
     */
    public function getIndexedBeanReferences(): array {
        return $this->beanReferences;
    }
}