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
    public FormConfig $form_config;               // The configuration of the form it belongs to

    public string $id;                            // ID of the data block
    public string $name;                          // Internal name (UI identifier) of the data block
    public string $text;                          // Text to display for the data block
    public string $module;                        // Module name
    /** @var FormDataBlockField[] */
    public array $fields;                         // Fields of the data block
    /** @var FormDuplicateRule[] */
    public array $duplicate_detections;           // Definition of duplicate detection
    public bool $is_document_block = false;       // Whether this is a Document upload block

    public int $min_instances = 1;                // Minimum required instances (0 = optional)
    public ?int $max_instances = 1;               // Maximum allowed instances (1 = simple, >1 or null = repeatable, null = no limit)
    public string $group_title = '';              // Visual title for the repeat group
    public bool $is_custom_group_title = false;   // Flag to track manual title overrides
    public string $group_root = '';               // ID of the repeatable root this block belongs to
    public string $element_singular_name = '';    // Singular name for repeatable elements (for UI)

    private ?BeanReference $beanReference = null; // Bean where the data block has been saved

    /** @var array<string, BeanReference> */
    private array $indexedBeanReferences = [];

    /**
     * Creates an instance of FormDataBlock from a JSON array.
     * @param FormConfig $form The configuration of the form it belongs to
     * @param array $data The data in array format
     * @return FormDataBlock The created instance
     */
    public static function fromJsonArray(FormConfig $form, array $data): self {
        $dto = new self();
        $dto->form_config = $form;

        $dto->id = $data['id'] ?? '';
        $dto->name = $data['name'] ?? '';
        $dto->text = $data['text'] ?? '';
        $dto->module = $data['module'] ?? '';

        $dto->is_document_block = $data['is_document_block'] ?? false;

        // Map repeatable data block fields

        // If 'min_instances' is absent (legacy form), default is 1 (mandatory).
        $dto->min_instances = 1; // Legacy default: mandatory block
        if (array_key_exists('min_instances', $data)) {
            // If present in JSON: 0 = optional, 1 = mandatory.
            $dto->min_instances = ($data['min_instances'] !== null && $data['min_instances'] !== '') ? (int)$data['min_instances'] : 1;
        } 

        // If 'max_instances' is absent (legacy form), default is 1 (scalar).
        $dto->max_instances = 1; // Legacy default: simple scalar block
        if (array_key_exists('max_instances', $data)) {
            // If present in JSON: null or empty string means UNLIMITED repeatable (null), otherwise integer.
            $dto->max_instances = ($data['max_instances'] !== null && $data['max_instances'] !== '') ? (int)$data['max_instances'] : null;
        }

        $dto->group_root = $data['group_root'] ?? '';
        $dto->group_title = $data['group_title'] ?? '';
        $dto->element_singular_name = $data['element_singular_name'] ?? '';
        $dto->is_custom_group_title = isset($data['is_custom_group_title']) ? (bool)$data['is_custom_group_title'] : false;
        
        $dto->fields = [];
        if (isset($data['fields']) && is_array($data['fields'])) {
            foreach ($data['fields'] as $fieldData) {
                $formDataBlockField = FormDataBlockField::fromJsonArray($dto, $fieldData);
                $dto->fields[$formDataBlockField->name] = $formDataBlockField;
            }
        }

        $dto->duplicate_detections = [];
        if (isset($data['duplicate_detections']) && is_array($data['duplicate_detections'])) {
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
        return ($this->min_instances ?? 1) == 0;
    }

    /**
     * Calculates the depth of the block within the chain of group roots. 
     * Called directly by ResponseHandler to decide whether to evaluate sub-loops at level >= 2.
     */
    public function getLoopDepth(): int {
        // Number of x-for loops that address this block's instances, i.e. the
        // number of REPEATABLE/OPTIONAL heads in the chain INCLUDING this block
        // (a repeatable root renders its own loop: depth 1). SIMPLE heads
        // (max=1 with children) render inline with STATIC names, so they add
        // no level (unlike the old count that included them).
        $depth = 0;
        $current = $this;
        $visited = [$current->id => true];
        while (true) {
            if ($current->isRepeatable() || $current->isOptional()) {
                $depth++;
            }
            if (empty($current->group_root)) {
                break;
            }
            $parentId = $current->group_root;
            if (isset($visited[$parentId]) || $parentId === $current->id) {
                break;
            }
            $visited[$parentId] = true;
            $parent = $this->form_config->data_blocks[$parentId] ?? null;
            if (!$parent) {
                break;
            }
            $current = $parent;
        }
        return $depth;
    }

    /**
     * Sets the bean reference for this data block, optionally for a specific instance index.
     * @param string $beanId The ID of the bean
     * @param string|null $instanceIndex Optional instance index (e.g., "i" for depth-1, "i:j" for depth-2)
     */
    public function setBeanReference(string $beanId, ?string $instanceIndex = null): void {
        $ref = new BeanReference($this->module, $beanId);
        $this->beanReference = $ref;
        if ($instanceIndex !== null) {
            $this->indexedBeanReferences[(string)$instanceIndex] = $ref;
        }
    }

    /**
     * Gets the bean reference for this data block, optionally for a specific instance index.
     * @param string|null $instanceIndex Optional instance index (e.g., "i" for depth-1, "i:j" for depth-2)
     * @return BeanReference|null The bean reference or null if not set
     */
    public function getBeanReference(?string $instanceIndex = null): ?BeanReference {
        if ($instanceIndex !== null && isset($this->indexedBeanReferences[(string)$instanceIndex])) {
            return $this->indexedBeanReferences[(string)$instanceIndex];
        }
        return $this->beanReference;
    }

    /**
     * Gets all indexed bean references for this data block.
     * @return array<string, BeanReference> An associative array of instance index to BeanReference
     */
    public function getIndexedBeanReferences(): array {
        return $this->indexedBeanReferences;
    }

    /**
     * Gets the bean reference for a specific instance index.
     * @param string|int $index The instance index (e.g., "i" for depth-1, "i:j" for depth-2)
     * @return BeanReference|null The bean reference or null if not set
     */
    public function getIndexedBeanReference(string|int $index): ?BeanReference {
        return $this->indexedBeanReferences[(string)$index] ?? null;
    }


    /**
     * True when this block is a group head: repeatable, optional, or a simple block with children.
     */
    public function isGroupHead(): bool {
        return $this->isRepeatable() || $this->isOptional()
            || count($this->form_config->getGroupChildren($this)) > 0;
    }

    /**
     * Resolves the reference for this block within the current execution context. 
     * This is the key to N:1 resolution: If this block is scalar (non-repeatable),
     * it must ALWAYS return the unique root instance, ignoring the index from the child context.
     * 
     * @param ExecutionContext $context The execution context
     * @param string|null $instanceKey Optional instance key (e.g., "i" for depth-1, "i:j" for depth-2)
     * @return BeanReference|null The bean reference or null if not set
     */
    public function getReferenceForContext(ExecutionContext $context, ?string $instanceKey = null): ?BeanReference {
        $depth = $this->getLoopDepth();

        // Scalar block (no loop): a single global reference
        if ($depth === 0) {
            return $this->getBeanReference();
        }

        // Explicit instance key wins (e.g. a group head resolving its own instance)
        if ($instanceKey !== null) {
            return $this->getBeanReference($instanceKey);
        }

        // N:1 — a NON-repeatable target (e.g. an optional 0..1 group shared by
        // every child) has a single instance, so the global reference is
        // authoritative regardless of the child's loop indexes.
        if (!$this->isRepeatable()) {
            return $this->getBeanReference();
        }

        // Repeatable target: address THIS instance via the first d indexes of
        // the active context stack; if the stack is shallower the deeper
        // instance is not addressed by this execution (null).
        $stack = $context->getInstanceIndexes();
        if (count($stack) < $depth) {
            return null;
        }
        return $this->getBeanReference(implode(':', array_slice($stack, 0, $depth)));
    }
}