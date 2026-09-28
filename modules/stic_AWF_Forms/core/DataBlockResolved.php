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

    /** @var ?int Outer loop index for depth-2 blocks (ADR-8): Parent[i] -> Subgroup[j] */
    public ?int $parentInstanceIndex = null;

    public function __construct(FormDataBlock $config, array $fullFormData, ExecutionContext $context, ?int $instanceIndex = null, ?int $parentInstanceIndex = null) {
        // Warning: PHP POST replaces all '.' with '_'
        // DataBlock names use PascalCase without '_'
        // Form field names:
        //   DataBlockName0_field_name              ->  "field_name" from DataBlockName0 TO CRM
        //   _detached_DataBlockName0_field_name    ->  "field_name" from DataBlockName0 DETACHED
        //   DataBlockName[index][field_name]       ->  "field_name" from instance index of DataBlockName
        //   _detached_DataBlockName[index][field_name] ->  "field_name" from instance index of detached DataBlockName
        //   DataBlockName[outer][inner][field_name]     ->  depth-2 (block inside a subgroup of a repeatable root)

        $this->dataBlock = $config;
        $this->instanceIndex = $instanceIndex;
        $this->parentInstanceIndex = $parentInstanceIndex;

        // Indexed form data for repeatable blocks
        $isIndexed = $instanceIndex !== null;
        if ($isIndexed) {
            $this->resolveIndexedInstance($config, $fullFormData, $context, $instanceIndex, $parentInstanceIndex);
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
     * Resolve a single instance of a repeatable block.
     * Depth-1 (parentInstanceIndex === null): reads formData[Block][index][field].
     * Depth-2 (parentInstanceIndex set): reads formData[Block][outer][index][field],
     * where outer is the enclosing repeatable root's instance index.
     */
    private function resolveIndexedInstance(FormDataBlock $config, array $fullFormData, ExecutionContext $context, int $instanceIndex, ?int $parentInstanceIndex = null): void {
        $blockName = $config->name;
        $linkedKey = $blockName;
        $detachedKey = '_detached_' . $blockName;

        // Linked fields
        $linkedArray = is_array($fullFormData[$linkedKey] ?? null) ? $fullFormData[$linkedKey] : [];
        $detachedArray = is_array($fullFormData[$detachedKey] ?? null) ? $fullFormData[$detachedKey] : [];

        if ($parentInstanceIndex !== null) {
            // Depth-2: the block's matrix is indexed [outer][inner]
            $linkedArray = is_array($linkedArray[$parentInstanceIndex] ?? null) ? $linkedArray[$parentInstanceIndex] : [];
            $detachedArray = is_array($detachedArray[$parentInstanceIndex] ?? null) ? $detachedArray[$parentInstanceIndex] : [];
        }

        $linkedInstance = $linkedArray[$instanceIndex] ?? [];
        $detachedInstance = $detachedArray[$instanceIndex] ?? [];

        // Logical-key builders: depth-2 keys include both indexes (Block[i][j][field])
        $keyIndexes = $parentInstanceIndex !== null
            ? [$parentInstanceIndex, $instanceIndex]
            : [$instanceIndex];

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
     * @param FormDataBlock $block
     * @param array $formData
     * @param ExecutionContext $context
     * @return DataBlockResolved[]
     */
    public static function resolveInstances(FormDataBlock $block, array $formData, ExecutionContext $context): array {
        if ($block->getLoopDepth() >= 2) {
            // Depth-2 blocks are resolved per outer index via resolveInstancesForParent
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": DataBlockResolved: Block '{$block->name}' is a depth-2 block; use resolveInstancesForParent().");
            return [];
        }
        $blockName = $block->name;
        $linkedArray = is_array($formData[$blockName] ?? null) ? $formData[$blockName] : [];
        $detachedKey = '_detached_' . $blockName;
        $detachedArray = is_array($formData[$detachedKey] ?? null) ? $formData[$detachedKey] : [];

        $indexes = array_unique(array_merge(
            array_filter(array_keys($linkedArray), 'is_int'),
            array_filter(array_keys($detachedArray), 'is_int')
        ));
        sort($indexes);

        if (empty($indexes)) {
            // Optional blocks (repeatable or not) with no submitted instances → no instances.
            // Mandatory blocks → one empty instance so required-field validation errors are produced.
            if ($block->isOptional()) {
                return [];
            }
            return [new DataBlockResolved($block, $formData, $context, 0)];
        }

        $instances = [];
        foreach ($indexes as $index) {
            $instances[] = new DataBlockResolved($block, $formData, $context, (int)$index);
        }
        return $instances;
    }

    /**
     * Composite key addressing this instance in bean-reference maps and
     * per-instance result keys: null (scalar), "i" (depth-1) or "i:j" (depth-2).
     */
    public function getInstanceIndexKey(): ?string {
        if ($this->instanceIndex === null) return null;
        return $this->parentInstanceIndex !== null
            ? $this->parentInstanceIndex . ':' . $this->instanceIndex
            : (string)$this->instanceIndex;
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
     * Loop-depth-aware resolution: picks the instance indexes from the context
     * according to the block's own loop depth (B-4 / ADR-8 depth-2).
     *  - depth 0: scalar resolution (no indexes).
     *  - depth 1: addressed by the outermost active loop index (the parent
     *    index when the action is bound to a deeper block).
     *  - depth 2: addressed by the [outer, inner] index pair.
     */
    public static function resolveForBlock(FormDataBlock $blockConfig, array $formData, ExecutionContext $context): ?DataBlockResolved {
        $depth = $blockConfig->getLoopDepth();
        $current = $context->getCurrentInstanceIndex();
        $parent = $context->getParentInstanceIndex();
        if ($depth === 0) {
            return new DataBlockResolved($blockConfig, $formData, $context);
        }
        if ($depth === 1) {
            return new DataBlockResolved($blockConfig, $formData, $context, $parent ?? $current);
        }
        return new DataBlockResolved($blockConfig, $formData, $context, $current, $parent);
    }

    /**
     * Returns one DataBlockResolved per inner (depth-2) instance of the block
     * for the given outer loop index: reads the int keys of
     * formData[Block][outerIndex]. Blocks with no submitted inner instances
     * return an empty array when optional, or one empty instance otherwise.
     * @return DataBlockResolved[]
     */
    public static function resolveInstancesForParent(FormDataBlock $block, array $formData, ExecutionContext $context, int $parentInstanceIndex): array {
        $linkedArray = is_array($formData[$block->name] ?? null) ? $formData[$block->name] : [];
        $detachedArray = is_array($formData['_detached_' . $block->name] ?? null) ? $formData['_detached_' . $block->name] : [];

        $linkedRow = is_array($linkedArray[$parentInstanceIndex] ?? null) ? $linkedArray[$parentInstanceIndex] : [];
        $detachedRow = is_array($detachedArray[$parentInstanceIndex] ?? null) ? $detachedArray[$parentInstanceIndex] : [];

        $indexes = array_unique(array_merge(
            array_filter(array_keys($linkedRow), 'is_int'),
            array_filter(array_keys($detachedRow), 'is_int')
        ));
        sort($indexes);

        if (empty($indexes)) {
            if ($block->isOptional()) return [];
            return [new DataBlockResolved($block, $formData, $context, 0, $parentInstanceIndex)];
        }

        $instances = [];
        foreach ($indexes as $index) {
            $instances[] = new DataBlockResolved($block, $formData, $context, (int)$index, $parentInstanceIndex);
        }
        return $instances;
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

}
