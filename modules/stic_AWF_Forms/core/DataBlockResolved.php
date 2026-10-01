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
 * Class representing the resolved data of a form's Data Block, including the mapping of form fields to CRM fields and their values.
 */
class DataBlockResolved {
    public FormDataBlock $dataBlock;     // The Data Block configuration

    /** @var array<string, DataBlockFieldResolved> */
    public array $formData = [];         // Data mapped to CRM [crm_field_name => DataBlockFieldResolved]
    
    /** @var array<string, DataBlockFieldResolved> */
    public array $detachedData = [];     // Unmapped data [detached_field_name => DataBlockFieldResolved]

    public ?int $instanceIndex = null;

    /**
     * Full loop-index stack (outer to inner) addressing this instance in the
     * n-dimensional POST matrix (one index per group-head loop in the block's
     * chain: repeatable, optional and simple heads alike).
     * Example (A -> B -> C, three group heads): C's instance = [i1, i2, i3].
     */
    public array $loopIndexes = [];

    private array $fullFormData;                 // RAW form data (for the _toggle_ activation signal)
    private ExecutionContext $executionContext;  // Request context (for the uploaded files fallback)

    public function __construct(FormDataBlock $config, array $fullFormData, ExecutionContext $context, array $loopIndexes = []) {
        // Warning: PHP POST replaces all '.' with '_'
        // DataBlock names use PascalCase without '_'
        // Form field names:
        //   DataBlockName0_field_name              ->  "field_name" from DataBlockName0 TO CRM
        //   _detached_DataBlockName0_field_name    ->  "field_name" from DataBlockName0 DETACHED
        //   DataBlockName[index][field_name]       ->  "field_name" from instance index of DataBlockName
        //   _detached_DataBlockName[index][field_name] ->  "field_name" from instance index of detached DataBlockName
        //   DataBlockName[i1]...[in][field_name]   ->  n loop levels (one per group head in the chain)

        $this->dataBlock = $config;
        $this->loopIndexes = array_map('intval', $loopIndexes);
        $this->instanceIndex = $this->loopIndexes === [] ? null : (int)end($this->loopIndexes);
        $this->fullFormData = $fullFormData;
        $this->executionContext = $context;

        // Indexed form data for blocks inside group loops
        if ($this->instanceIndex !== null) {
            $this->resolveIndexedInstance($config, $fullFormData, $context);
            return;
        }

        // Load default values (fixed/hidden) from the configuration
        // These values can be overridden by form-submitted values
        foreach ($config->fields as $fieldName => $fieldDef) {
            if ($fieldDef->value_type === DataBlockFieldValueType::FIXED) {
                // Get the CRM field type to perform casting
                $castedValue = stic_AWFUtils::castCrmValue($fieldDef->value, $fieldDef->type, $context);

                // Field is not present in the form; compute the logical key it would have
                $formKey = ""; 
                $logicalKey = $fieldDef->getKey();
                $fieldResolved = new DataBlockFieldResolved($logicalKey, $fieldName, $fieldDef, $castedValue);

                // Store the field in the appropriate array
                if ($fieldDef->type_field === DataBlockFieldType::UNLINKED) {
                     $this->detachedData[$fieldName] = $fieldResolved;
                } else {
                     $this->formData[$fieldName] = $fieldResolved;
                }
            }
        }

        // Process the form data
        // Form-submitted values always take precedence over fixed/hidden defaults
        $blockPrefix = $config->name . '_'; 
        $detachedPrefix = '_detached_' . $blockPrefix;
        foreach ($fullFormData as $formKey => $value) {
            $fieldName = null;
            $isUnlinked = false;

            // Identify if the field belongs to the block or is detached
            if (str_starts_with($formKey, $blockPrefix)) {
                $fieldName = substr($formKey, strlen($blockPrefix));
            } else if (str_starts_with($formKey, $detachedPrefix)) {
                $fieldName = substr($formKey, strlen($detachedPrefix));
                $isUnlinked = true;
            }

            // If the field belongs to this block, process it
            if ($fieldName) {
                // Find the field configuration to determine its type; otherwise assume text
                $definition = $config->fields[$fieldName] ?? null;
                $crmFieldType = $definition?->type;
                
                // Cast the value to the appropriate type
                $castedValue = stic_AWFUtils::castCrmValue($value, $crmFieldType, $context);

                // Rebuild the original logical key
                $logicalKey = ($isUnlinked ? '_detached.' : '') . $config->name . '.' . $fieldName;
                $fieldResolved = new DataBlockFieldResolved($logicalKey, $fieldName, $definition, $castedValue);

                // Store the field in the appropriate array
                if ($isUnlinked) {
                    $this->detachedData[$fieldName] = $fieldResolved;
                } else {
                    $this->formData[$fieldName] = $fieldResolved;
                }
            }
        }

        // Handling unchecked checkboxes: HTML does not send unchecked checkboxes, so they would not be updated in the CRM when unchecked.
        foreach ($config->fields as $fieldName => $fieldDef) {
            if ($fieldDef->type_field === DataBlockFieldType::FIXED) continue;

            $isUnlinked = ($fieldDef->type_field === DataBlockFieldType::UNLINKED);
            if ($isUnlinked && isset($this->detachedData[$fieldName])) continue;
            if (!$isUnlinked && isset($this->formData[$fieldName])) continue;

            // The field was expected but did NOT arrive in the POST.
            if ($fieldDef->type === 'bool' || $fieldDef->type === 'checkbox') {
                // Rebuild the original logical key
                $logicalKey = ($isUnlinked ? '_detached.' : '') . $config->name . '.' . $fieldName;
                $fieldResolved = new DataBlockFieldResolved($logicalKey, $fieldName, $fieldDef, 0); // 0 = False en DB
                
                // Store the field in the appropriate array
                if ($isUnlinked) {
                    $this->detachedData[$fieldName] = $fieldResolved;
                } else {
                    $this->formData[$fieldName] = $fieldResolved;
                }
            }
        }

    }

    /**
     * Resolve a single instance of a block inside group loops. The matrix is
     * navigated by ALL the loop indexes except the last (the last one
     * addresses the row itself): formData[Block][i1]...[i_{n-1}][i_n][field].
     */
    private function resolveIndexedInstance(FormDataBlock $config, array $fullFormData, ExecutionContext $context): void {
        $blockName = $config->name;
        $linkedKey = $blockName;
        $detachedKey = '_detached_' . $blockName;

        $linkedArray = is_array($fullFormData[$linkedKey] ?? null) ? $fullFormData[$linkedKey] : [];
        $detachedArray = is_array($fullFormData[$detachedKey] ?? null) ? $fullFormData[$detachedKey] : [];

        $rowIndexes = $this->loopIndexes;
        $innerIndex = array_pop($rowIndexes);
        foreach ($rowIndexes as $levelIndex) {
            $linkedArray = is_array($linkedArray[$levelIndex] ?? null) ? $linkedArray[$levelIndex] : [];
            $detachedArray = is_array($detachedArray[$levelIndex] ?? null) ? $detachedArray[$levelIndex] : [];
        }

        $linkedInstance = is_array($linkedArray[$innerIndex] ?? null) ? $linkedArray[$innerIndex] : [];
        $detachedInstance = is_array($detachedArray[$innerIndex] ?? null) ? $detachedArray[$innerIndex] : [];

        // Logical-key builders: the keys include every loop index (Block[i1]...[in][field])
        $keyIndexes = $this->loopIndexes;

        // Process linked fields
        foreach ($config->fields as $fieldName => $fieldDef) {
            if ($fieldDef->type_field === DataBlockFieldType::FIXED) {
                $castedValue = stic_AWFUtils::castCrmValue($fieldDef->value, $fieldDef->type, $context);
                $logicalKey = $fieldDef->getKeyForIndexes($keyIndexes);
                $fieldResolved = new DataBlockFieldResolved($logicalKey, $fieldName, $fieldDef, $castedValue);
                $this->formData[$fieldName] = $fieldResolved;
                continue;
            }
            if ($fieldDef->type_field === DataBlockFieldType::UNLINKED) continue;

            $value = $linkedInstance[$fieldName] ?? null;
            $crmFieldType = $fieldDef->type;
            $castedValue = stic_AWFUtils::castCrmValue($value, $crmFieldType, $context);
            $logicalKey = $fieldDef->getKeyForIndexes($keyIndexes);
            $fieldResolved = new DataBlockFieldResolved($logicalKey, $fieldName, $fieldDef, $castedValue);
            $this->formData[$fieldName] = $fieldResolved;
        }

        // Process detached fields
        foreach ($config->fields as $fieldName => $fieldDef) {
            if ($fieldDef->type_field !== DataBlockFieldType::UNLINKED) continue;
            $value = $detachedInstance[$fieldName] ?? null;
            $crmFieldType = $fieldDef->type;
            $castedValue = stic_AWFUtils::castCrmValue($value, $crmFieldType, $context);
            $logicalKey = $fieldDef->getKeyForIndexes($keyIndexes);
            $fieldResolved = new DataBlockFieldResolved($logicalKey, $fieldName, $fieldDef, $castedValue);
            $this->detachedData[$fieldName] = $fieldResolved;
        }

        // Fill missing booleans
        foreach ($config->fields as $fieldName => $fieldDef) {
            if ($fieldDef->type_field === DataBlockFieldType::FIXED) continue;
            $isUnlinked = ($fieldDef->type_field === DataBlockFieldType::UNLINKED);
            $targetArray = $isUnlinked ? $detachedInstance : $linkedInstance;
            if ($isUnlinked && isset($this->detachedData[$fieldName])) continue;
            if (!$isUnlinked && isset($this->formData[$fieldName])) continue;
            if ($fieldDef->type === 'bool' || $fieldDef->type === 'checkbox') {
                $logicalKey = $fieldDef->getKeyForIndexes($keyIndexes);
                $fieldResolved = new DataBlockFieldResolved($logicalKey, $fieldName, $fieldDef, 0);
                if ($isUnlinked) {
                    $this->detachedData[$fieldName] = $fieldResolved;
                } else {
                    $this->formData[$fieldName] = $fieldResolved;
                }
            }
        }
    }

    /**
     * Returns one DataBlockResolved per instance for a repeatable block.
     * For optional blocks with zero instances, returns an empty array.
     * GENERIC n-dimensional enumeration (one loop level per group head in the
     * block's chain): with no $prefix it enumerates ALL the instances of the
     * block (walking every level recursively); with $prefix (a partial index
     * stack of the ANCESTOR levels already fixed) it enumerates only the
     * instances below that prefix.
     * @param FormDataBlock $block
     * @param array $formData
     * @param ExecutionContext $context
     * @param array $prefix Already-fixed ancestor loop indexes (outer to inner)
     * @return DataBlockResolved[]
     */
    public static function resolveInstances(FormDataBlock $block, array $formData, ExecutionContext $context, array $prefix = []): array {
        $depth = $block->getLoopDepth();
        $blockName = $block->name;
        $linkedNode = is_array($formData[$blockName] ?? null) ? $formData[$blockName] : [];
        $detachedNode = is_array($formData['_detached_' . $blockName] ?? null) ? $formData['_detached_' . $blockName] : [];

        // Navigate the matrix through the already-fixed ancestor indexes
        foreach ($prefix as $levelIndex) {
            $linkedNode = is_array($linkedNode[$levelIndex] ?? null) ? $linkedNode[$levelIndex] : [];
            $detachedNode = is_array($detachedNode[$levelIndex] ?? null) ? $detachedNode[$levelIndex] : [];
        }

        $indexes = array_unique(array_merge(
            array_filter(array_keys($linkedNode), 'is_int'),
            array_filter(array_keys($detachedNode), 'is_int')
        ));
        sort($indexes);

        // Levels below this one (nested group heads inside this block's subtree)
        $remainingLevels = $depth - count($prefix) - 1;

        if (empty($indexes)) {
            // Optional heads with no submitted instances → no instances at this
            // level. Mandatory heads → one empty instance so required-field
            // validation errors are produced.
            if ($block->isOptional()) {
                return [];
            }
            $indexes = [0];
            $remainingLevels = max($remainingLevels, 0);
        }

        $instances = [];
        foreach ($indexes as $index) {
            $newPrefix = array_merge($prefix, [(int)$index]);
            if ($remainingLevels > 0) {
                $instances = array_merge($instances, self::resolveInstances($block, $formData, $context, $newPrefix));
            } else {
                $instances[] = new DataBlockResolved($block, $formData, $context, $newPrefix);
            }
        }
        return $instances;
    }

    /**
     * Full loop-index vector (outer to inner) of this instance: [] for scalar,
     * [i] for depth-1, [i1, i2] for depth-2, ...
     */
    public function getLoopIndexes(): array {
        return $this->loopIndexes;
    }

    /**
     * Composite key addressing this instance in bean-reference maps and
     * per-instance result keys: null (scalar) or the colon-joined index
     * vector ("i", "i:j", "i:j:k"...).
     */
    public function getInstanceIndexKey(): ?string {
        if ($this->instanceIndex === null) return null;
        return implode(':', $this->loopIndexes);
    }

    /**
     * Bean reference of THIS resolved instance, using the same composite key
     * convention used at registration time (registerBeanModificationFromBlock).
     */
    public function getInstanceBeanReference(): ?BeanReference {
        if ($this->instanceIndex === null) {
            return $this->dataBlock->getBeanReference();
        }
        return $this->dataBlock->getBeanReference($this->getInstanceIndexKey());
    }

    /**
     * Loop-depth-aware resolution: takes the FIRST d indexes of the context's
     * active index stack (d = the block's loop depth) — a shallower target
     * read through a deeper motor uses the outer indexes. When the stack is
     * shorter than the block's depth, the missing levels default to 0
     * (deterministic; same-group deeper targets are resolved by their own
     * unrolling).
     */
    public static function resolveForBlock(FormDataBlock $blockConfig, array $formData, ExecutionContext $context): ?DataBlockResolved {
        $depth = $blockConfig->getLoopDepth();
        if ($depth === 0) {
            return new DataBlockResolved($blockConfig, $formData, $context);
        }
        $stack = $context->getInstanceIndexes();
        $indexes = array_slice($stack, 0, $depth);
        while (count($indexes) < $depth) { $indexes[] = 0; }
        return new DataBlockResolved($blockConfig, $formData, $context, $indexes);
    }

    /**
     * Gets the resolved field data for a given CRM field name. Returns null if the field is not present in the form.
     * @param string $fieldName The CRM field name to retrieve
     * @return ?DataBlockFieldResolved The resolved field data, or null if not found
     */
    public function getFieldValue($fieldName): ?DataBlockFieldResolved {
        return $this->formData[$fieldName] ?? null;
    }

    /**
     * Gets the resolved field data for a given detached field name. Returns null if the field is not present in the form.
     * @param string $fieldName The detached field name to retrieve
     * @return ?DataBlockFieldResolved The resolved field data, or null if not found
     */
    public function getDetachedFieldValue($fieldName): ?DataBlockFieldResolved {
        return $this->detachedData[$fieldName] ?? null;
    }

    /**
     * Three-layer activation check: whether this block instance should be persisted.
     *  (1) Mandatory blocks (min_instances > 0) are ALWAYS active.
     *  (2) Optional blocks (always group heads) check the explicit
     *      `_toggle_{BlockName}` signal posted by the activation switch: the
     *      switch renders OUTSIDE the block's own loop, so the signal is
     *      indexed by the first (depth - 1) loop indexes — scalar for a
     *      top-level optional group, `[i1]` / `[i1][i2]`... for deeper ones.
     *  (3) Fallback: any non-FIXED resolved field with a non-empty value
     *      (linked or detached), or an uploaded file for the block's file
     *      fields. FIXED/server values NEVER count as user input (ghost
     *      records protection).
     */
    public function isActivated(): bool {
        // (1) Mandatory blocks are always active
        if (!$this->dataBlock->isOptional()) return true;

        // (2) Explicit activation signal, indexed by the enclosing loops
        $signalKey = '_toggle_' . $this->dataBlock->name;
        $signal = $this->fullFormData[$signalKey] ?? null;
        $signalIndexes = array_slice($this->loopIndexes, 0, max(count($this->loopIndexes) - 1, 0));
        foreach ($signalIndexes as $levelIndex) {
            $signal = is_array($signal) ? ($signal[$levelIndex] ?? null) : null;
        }
        if ($signal !== null && in_array($signal, ['1', 1, true, 'on'], true)) {
            return true;
        }

        // (3) Fallback over the RESOLVED fields (FIXED/server values never count)
        foreach ([$this->formData, $this->detachedData] as $fieldMap) {
            foreach ($fieldMap as $fieldResolved) {
                $def = $fieldResolved->dataBlockField;
                if ($def !== null && $def->type_field === DataBlockFieldType::FIXED) continue;
                $value = $fieldResolved->value;
                if (is_array($value)) {
                    if (!empty($value)) return true;
                } elseif ($value !== null && $value !== '') {
                    return true;
                }
            }
        }

        // Fallback: uploaded files for the block's file fields
        foreach ($this->dataBlock->fields as $fieldDef) {
            if ($fieldDef->type_in_form !== 'file') continue;
            $phpKey = $fieldDef->getPhpKey();
            if (!empty($this->executionContext->uploadedFiles[$phpKey]['name'])) return true;
            if ($this->instanceIndex !== null && !empty($this->executionContext->uploadedFiles[$phpKey . '_' . $this->instanceIndex]['name'])) return true;
        }

        return false;
    }

}
