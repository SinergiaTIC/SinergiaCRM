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
 * ScaleActionUtils
 *
 * Shared utilities for AWF actions that compute values from the submitted form
 * data (scale calculations, custom formulas, range/baremo interpretation, ...).
 * Used by the scale actions (Hook) and by SaveRecordAction to fill the
 * "submission_data" field of stic_Assessments_Scores.
 */
class ScaleActionUtils {

    /**
     * Resolves the data block that owns a form field key ("Block.field" or
     * "_detached.Block.field").
     * @param FormConfig $formConfig The form configuration
     * @param string $formKey The full field key
     * @return ?FormDataBlock The owning data block (null if not found)
     */
    public static function resolveFieldOwnerBlock(FormConfig $formConfig, string $formKey): ?FormDataBlock {
        $keyToParse = str_starts_with($formKey, '_detached.') ? substr($formKey, 10) : $formKey;
        foreach ($formConfig->data_blocks as $block) {
            if (str_starts_with($keyToParse, $block->name . '.')) {
                return $block;
            }
        }
        return null;
    }

    /**
     * Resolves the definition of the data block field referenced by a form
     * field key ("Block.field" or "_detached.Block.field").
     * @param FormConfig $formConfig The form configuration
     * @param string $formKey The full field key
     * @return ?FormDataBlockField The field definition (null if not found)
     */
    public static function resolveFieldDefinition(FormConfig $formConfig, string $formKey): ?FormDataBlockField {
        $block = self::resolveFieldOwnerBlock($formConfig, $formKey);
        if ($block === null) {
            return null;
        }
        $keyToParse = str_starts_with($formKey, '_detached.') ? substr($formKey, 10) : $formKey;
        $fieldName = substr($keyToParse, strlen($block->name) + 1);
        return $block->fields[$fieldName] ?? null;
    }

    /**
     * Reads the submitted value of a form field from the raw form data,
     * addressing scalar fields by their flat PHP key and fields inside
     * repeatable/optional groups through the active instance-index stack.
     * @param FormConfig $formConfig The form configuration
     * @param array $formData The raw form data (POST)
     * @param string $formKey The full field key ("Block.field" / "_detached.Block.field")
     * @param array $instanceIndexes Active loop-index stack (outer to inner)
     * @return mixed The submitted value or null if the field has no submitted value
     */
    public static function getSubmittedFieldValue(FormConfig $formConfig, array $formData, string $formKey, array $instanceIndexes = []): mixed {
        $block = self::resolveFieldOwnerBlock($formConfig, $formKey);
        $depth = $block?->getLoopDepth() ?? 0;

        // Scalar field: read directly from the flat formData
        if ($depth === 0) {
            $phpKey = str_replace('.', '_', $formKey);
            if (array_key_exists($phpKey, $formData)) {
                return $formData[$phpKey];
            }
            if (array_key_exists($formKey, $formData)) {
                return $formData[$formKey];
            }
            return null;
        }

        // Field inside a group: navigate the POST matrix through the indexes
        $isDetached = str_starts_with($formKey, '_detached.');
        $blockKey = ($isDetached ? '_detached_' : '') . $block->name;
        $fieldName = substr(str_starts_with($formKey, '_detached.') ? substr($formKey, 10) : $formKey, strlen($block->name) + 1);

        if (!isset($formData[$blockKey]) || !is_array($formData[$blockKey])) {
            return null;
        }
        $node = $formData[$blockKey];
        $indexes = array_slice($instanceIndexes, 0, $depth);
        while (count($indexes) < $depth) {
            $indexes[] = 0;
        }
        foreach ($indexes as $index) {
            if (!is_array($node) || !array_key_exists($index, $node)) {
                return null;
            }
            $node = $node[$index];
        }
        if (!is_array($node) || !array_key_exists($fieldName, $node)) {
            return null;
        }
        return $node[$fieldName];
    }

    /**
     * Writes a value into the raw form data at the position that corresponds
     * to the given field key, so later actions (data block saves, ...) read
     * the recomputed value instead of the one sent by the browser.
     * @param FormConfig $formConfig The form configuration
     * @param array $formData The raw form data (passed by reference)
     * @param string $formKey The full field key ("Block.field" / "_detached.Block.field")
     * @param mixed $value The value to write
     * @param array $instanceIndexes Active loop-index stack (outer to inner)
     * @return bool True when the value was written
     */
    public static function setSubmittedFieldValue(FormConfig $formConfig, array &$formData, string $formKey, mixed $value, array $instanceIndexes = []): bool {
        $block = self::resolveFieldOwnerBlock($formConfig, $formKey);
        $depth = $block?->getLoopDepth() ?? 0;

        // Scalar field: write into the flat formData
        if ($depth === 0) {
            $formData[str_replace('.', '_', $formKey)] = $value;
            return true;
        }

        // Field inside a group: navigate the POST matrix through the indexes
        $isDetached = str_starts_with($formKey, '_detached.');
        $blockKey = ($isDetached ? '_detached_' : '') . $block->name;
        $fieldName = substr(str_starts_with($formKey, '_detached.') ? substr($formKey, 10) : $formKey, strlen($block->name) + 1);

        if (!isset($formData[$blockKey]) || !is_array($formData[$blockKey])) {
            return false;
        }
        $indexes = array_slice($instanceIndexes, 0, $depth);
        while (count($indexes) < $depth) {
            $indexes[] = 0;
        }

        // Navigate (and create when missing) the instance nodes
        $node = &$formData[$blockKey];
        foreach ($indexes as $index) {
            if (!is_array($node)) {
                return false;
            }
            if (!isset($node[$index]) || !is_array($node[$index])) {
                $node[$index] = [];
            }
            $node = &$node[$index];
        }
        $node[$fieldName] = $value;
        return true;
    }

    /**
     * Computes the numeric value of a submitted field, using the score
     * configured for the selected option when the field options define one
     * (dropdown/radio/checkbox fields with "score" attributes) and falling
     * back to the plain numeric value of the submitted data.
     * @param ?FormDataBlockField $fieldDef The field definition (if found)
     * @param mixed $rawValue The submitted value (scalar or array for multi-fields)
     * @return ?float The numeric value (null when there is no value at all)
     */
    public static function computeFieldNumericValue(?FormDataBlockField $fieldDef, mixed $rawValue): ?float {
        if ($rawValue === null || $rawValue === '' || $rawValue === []) {
            return null;
        }

        // Build the option-value -> score map when the options define scores
        $scoreMap = [];
        $hasScores = false;
        foreach ($fieldDef?->value_options ?? [] as $option) {
            if ($option->score !== null) {
                $scoreMap[(string)$option->value] = (float)$option->score;
                $hasScores = true;
            }
        }

        $toNumber = function ($value) use ($scoreMap, $hasScores): ?float {
            if ($hasScores && array_key_exists((string)$value, $scoreMap)) {
                return $scoreMap[(string)$value];
            }
            return is_numeric($value) ? (float)$value : null;
        };

        // Multi-value fields: the numeric value is the sum of their values
        if (is_array($rawValue)) {
            $sum = 0.0;
            $anyValue = false;
            foreach ($rawValue as $single) {
                $number = $toNumber($single);
                if ($number !== null) {
                    $sum += $number;
                    $anyValue = true;
                }
            }
            return $anyValue ? $sum : null;
        }

        return $toNumber($rawValue);
    }

    /**
     * Resolves the score of a submitted field: when the field options define
     * scores (PR: "Puntuación" in option editors), the score of the chosen
     * option(s) is returned (the sum for multi-value fields). Returns null
     * when the field has no scored options or no selected value.
     * @param ?FormDataBlockField $fieldDef The field definition (if found)
     * @param mixed $rawValue The submitted value (scalar or array for multi-fields)
     * @return ?float The score (null when there is no value or no scores)
     */
    public static function resolveFieldScore(?FormDataBlockField $fieldDef, mixed $rawValue): ?float {
        if ($rawValue === null || $rawValue === '' || $rawValue === []) {
            return null;
        }

        $scoreMap = [];
        $hasScores = false;
        foreach ($fieldDef?->value_options ?? [] as $option) {
            if ($option->score !== null) {
                $scoreMap[(string)$option->value] = (float)$option->score;
                $hasScores = true;
            }
        }
        if (!$hasScores) {
            return null;
        }

        if (is_array($rawValue)) {
            $sum = 0.0;
            foreach ($rawValue as $single) {
                if (array_key_exists((string)$single, $scoreMap)) {
                    $sum += $scoreMap[(string)$single];
                }
            }
            return $sum;
        }
        return $scoreMap[(string)$rawValue] ?? null;
    }

    /**
     * Resolves the display text of a submitted value: when the field options
     * define the chosen value, its label (text) is returned; otherwise the
     * raw value itself.
     * @param ?FormDataBlockField $fieldDef The field definition (if found)
     * @param mixed $rawValue The submitted value (scalar or array for multi-fields)
     * @return ?string The display text (null when there is no value at all)
     */
    public static function resolveDisplayTextValue(?FormDataBlockField $fieldDef, mixed $rawValue): ?string {
        if ($rawValue === null || $rawValue === '' || $rawValue === []) {
            return null;
        }

        $textMap = [];
        foreach ($fieldDef?->value_options ?? [] as $option) {
            if ($option->text !== null && $option->text !== '') {
                $textMap[(string)$option->value] = (string)$option->text;
            }
        }

        $toText = function ($value) use ($textMap): ?string {
            if (array_key_exists((string)$value, $textMap)) {
                return $textMap[(string)$value];
            }
            return is_scalar($value) ? (string)$value : null;
        };

        // Multi-value fields: the display text is the concatenation of the chosen texts
        if (is_array($rawValue)) {
            $texts = [];
            foreach ($rawValue as $single) {
                $text = $toText($single);
                if ($text !== null) {
                    $texts[] = $text;
                }
            }
            return empty($texts) ? null : implode(', ', $texts);
        }

        return $toText($rawValue);
    }

    /**
     * Parses a range definition in the compact format "min-max: Label", with
     * ranges separated by newlines or pipes. Also supports open ranges
     * (">=5", "<=5", ">5", "<5") and single values ("5"). The first matching
     * range (in the declared order) wins when ranges overlap.
     * @param string $raw The raw ranges definition
     * @return array[] List of ['min' => ?float, 'max' => ?float, 'label' => string]
     */
    public static function parseRangeDefinition(string $raw): array {
        $ranges = [];
        $entries = preg_split('/[\r\n|]+/', $raw) ?: [];
        foreach ($entries as $entry) {
            $entry = trim($entry);
            if ($entry === '' || !str_contains($entry, ':')) {
                continue;
            }
            $colonIndex = strpos($entry, ':');
            $key = trim(substr($entry, 0, $colonIndex));
            $label = trim(substr($entry, $colonIndex + 1));
            if ($key === '' || $label === '') {
                continue;
            }

            // Open ranges: >=x, <=x, >x, <x
            if (preg_match('/^(>=|<=|>|<)\s*(-?\d+(?:\.\d+)?)$/', $key, $matches)) {
                $value = (float)$matches[2];
                $min = in_array($matches[1], ['>=', '>'], true) ? $value : null;
                $max = in_array($matches[1], ['<=', '<'], true) ? $value : null;
                $ranges[] = ['min' => $min, 'max' => $max, 'label' => $label];
                continue;
            }

            // Closed range: min-max (also supports a single value: "5")
            if (preg_match('/^(-?\d+(?:\.\d+)?)\s*-\s*(-?\d+(?:\.\d+)?)$/', $key, $matches)) {
                $min = (float)$matches[1];
                $max = (float)$matches[2];
                if ($min > $max) {
                    [$min, $max] = [$max, $min];
                }
                $ranges[] = ['min' => $min, 'max' => $max, 'label' => $label];
                continue;
            }
            if (preg_match('/^(-?\d+(?:\.\d+)?)$/', $key, $matches)) {
                $value = (float)$matches[1];
                $ranges[] = ['min' => $value, 'max' => $value, 'label' => $label];
            }
        }
        return $ranges;
    }

    /**
     * Finds the label of the first range (in declared order) that contains
     * the given value. Ranges without a bound are treated as unbounded.
     * @param array[] $ranges Parsed ranges (see parseRangeDefinition)
     * @param float $value The value to classify
     * @return string The label of the matching range ('' when none matches)
     */
    public static function findRangeLabel(array $ranges, float $value): string {
        foreach ($ranges as $range) {
            $min = $range['min'] ?? null;
            $max = $range['max'] ?? null;
            if (($min !== null && $value < $min) || ($max !== null && $value > $max)) {
                continue;
            }
            return (string)($range['label'] ?? '');
        }
        return '';
    }

    /**
     * Gets the data blocks of the form in display order (the order their
     * sections appear in the form layout).
     * @param FormConfig $formConfig The form configuration
     * @return FormDataBlock[] Blocks ordered as displayed in the form
     */
    public static function getBlocksInDisplayOrder(FormConfig $formConfig): array {
        $ordered = [];

        $collect = function ($elements) use (&$collect, &$ordered, $formConfig) {
            foreach ($elements ?? [] as $element) {
                if (($element->type ?? '') === 'datablock' && !empty($element->ref_id)
                    && isset($formConfig->data_blocks[$element->ref_id])) {
                    $ordered[] = $formConfig->data_blocks[$element->ref_id];
                } elseif (!empty($element->elements) && is_array($element->elements)) {
                    $collect($element->elements);
                }
            }
        };
        $collect($formConfig->layout->structure ?? []);

        // Any block not referenced by the layout goes at the end
        foreach ($formConfig->data_blocks as $block) {
            if (!in_array($block, $ordered, true)) {
                $ordered[] = $block;
            }
        }
        return $ordered;
    }

    /**
     * Gets the display position of a data block (0-based) in the form layout.
     * @param FormConfig $formConfig The form configuration
     * @param FormDataBlock $block The data block
     * @return int The position (blocks outside the layout go last)
     */
    public static function getBlockDisplayPosition(FormConfig $formConfig, FormDataBlock $block): int {
        foreach (self::getBlocksInDisplayOrder($formConfig) as $position => $orderedBlock) {
            if ($orderedBlock === $block || $orderedBlock->id === $block->id) {
                return $position;
            }
        }
        return PHP_INT_MAX;
    }

    /**
     * Builds a structured JSON string with all the data captured by a form
     * submission: one entry per data block, with every field, its label and
     * the value submitted (including unlinked/virtual fields and fixed
     * server fields). Used, for example, to fill the "submission_data" field
     * of the stic_Assessments_Scores module.
     * @param ExecutionContext $context The execution context of the submission
     * @return string The JSON string
     */
    public static function buildSubmissionJson(ExecutionContext $context): string {
        $blocksData = [];
        foreach (self::getBlocksInDisplayOrder($context->formConfig) as $order => $block) {
            $blocksData[] = self::buildBlockDataArray($context, $block, null, $order);
        }

        $submissionData = [
            'form_id' => $context->formId,
            'form_type' => $context->formType,
            'submission_timestamp' => date('Y-m-d H:i:s', (int)$context->submissionTimestamp),
            'blocks' => $blocksData,
        ];

        return json_encode($submissionData, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Builds a structured JSON string with the answers captured by ONE data
     * block of the submission (its fields, labels and values, per instance).
     * Used for "partial" records of stic_Assessments_Scores, so each partial
     * stores the questions/answers of its own section in its submission_data.
     * @param ExecutionContext $context The execution context of the submission
     * @param DataBlockResolved $block The resolved data block (current instance)
     * @return string The JSON string
     */
    public static function buildBlockSubmissionJson(ExecutionContext $context, DataBlockResolved $block): string {
        $submissionData = [
            'form_id' => $context->formId,
            'form_type' => $context->formType,
            'submission_timestamp' => date('Y-m-d H:i:s', (int)$context->submissionTimestamp),
            'block' => self::buildBlockDataArray($context, $block->dataBlock, $block,
                self::getBlockDisplayPosition($context->formConfig, $block->dataBlock)),
        ];

        return json_encode($submissionData, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Builds the data array of one data block (fields, labels and values).
     * @param ExecutionContext $context The execution context of the submission
     * @param FormDataBlock $block The data block configuration
     * @param ?DataBlockResolved $resolvedBlock The resolved block instance
     *        (for groups). When null, values are read from the raw form data
     * @return array The block data array
     */
    private static function buildBlockDataArray(ExecutionContext $context, FormDataBlock $block, ?DataBlockResolved $resolvedBlock = null, ?int $order = null): array {
        $fieldsData = [];
        $instancesData = [];

        foreach ($block->fields as $fieldDef) {
            $fieldData = [
                'label' => !empty($fieldDef->label) ? $fieldDef->label : $fieldDef->text_original,
                'type' => $fieldDef->type_in_form,
            ];
            if ($fieldDef->type_field === DataBlockFieldType::UNLINKED) {
                $fieldData['unlinked'] = true;
            }

            if ($block->getLoopDepth() > 0 && $resolvedBlock === null) {
                // Repeatable/optional block: resolve the value per instance
                $instances = DataBlockResolved::resolveInstances($block, $context->formData, $context);
                foreach ($instances as $instanceIndex => $instance) {
                    $value = $instance->getFieldValue($fieldDef->name)?->value;
                    if ($fieldDef->type === 'multienum' && is_array($value)) {
                        $value = implode(',', $value);
                    }
                    $fieldData['value'] = $value;
                    $fieldData['text'] = self::resolveDisplayTextValue($fieldDef, $value);
                    $fieldData['score'] = self::resolveFieldScore($fieldDef, $value);
                    $instancesData[$instanceIndex][$fieldDef->name] = $fieldData;
                }
                continue;
            }

            // Value from the resolved block instance (groups) or the flat form data
            if ($resolvedBlock !== null) {
                $value = $resolvedBlock->getFieldValue($fieldDef->name)?->value;
            } else {
                $phpKey = str_replace('.', '_', $fieldDef->getKey());
                $value = $context->formData[$phpKey] ?? ($fieldDef->value_type === DataBlockFieldValueType::FIXED ? $fieldDef->value : null);
            }
            if ($fieldDef->type === 'multienum' && is_array($value)) {
                $value = implode(',', $value);
            }
            $fieldData['value'] = $value;
            $fieldData['text'] = self::resolveDisplayTextValue($fieldDef, $value);
            $fieldData['score'] = self::resolveFieldScore($fieldDef, $value);
            $fieldsData[$fieldDef->name] = $fieldData;
        }

        $blockData = [
            'text' => $block->text,
            'module' => $block->module,
            'order' => $order,
        ];
        if (!empty($fieldsData)) {
            $blockData['fields'] = $fieldsData;
        }
        if (!empty($instancesData)) {
            $blockData['instances'] = array_values($instancesData);
        }
        return $blockData;
    }
}

