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
 * data (scale calculations, baremos interpretation, ...). Used by the scale
 * actions (Hook).
 *
 * Note: these helpers are NOT duplicated engine logic. The AWF engine resolves
 * the ACTION PARAMETERS (verified and casted); these helpers complement it with
 * what the engine does not provide:
 *  - Option score mapping (computeFieldNumericValue / resolveFieldScore)
 *  - Display text resolution of an option value (resolveDisplayTextValue)
 *  - Raw form data read/write for the server-side recalculation of fields
 *    that are not action parameters (get/setSubmittedFieldValue)
 *  - Baremo range parsing and lookup (parseRangeDefinition / findRangeLabel)
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



}
