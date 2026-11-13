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

include_once "modules/stic_AWF_Forms/actions/coreActions.php";

/**
 * ScaleInterpretationAction
 *
 * Action that interprets a scale result (score) using one or more "baremos"
 * (normative tables). Each baremo may define an optional population condition
 * (a numeric field within a min/max range, typically the age) and its own
 * ranges ("min-max: Label"). The label of the first matching baremo (in the
 * declared order) is written to a target label field, both live in the browser
 * and recalculated and overwritten by the server on submission.
 */
class ScaleInterpretationAction extends HookActionDefinition implements IFrontendAction
{
    public function __construct()
    {
        $this->isActive = true;
        $this->isUserSelectable = true;
        $this->category = 'data';
        $this->baseLabel = 'LBL_SCALE_INTERPRETATION_ACTION';
    }

    /**
     * Defines the parameters that will be displayed in the wizard.
     */
    public function getParameters(): array
    {
        // 1. Field that contains the score to interpret
        $paramScoreField = new ActionParameterDefinition();
        $paramScoreField->name = 'score_field';
        $paramScoreField->text = $this->translate('SCORE_FIELD_TEXT');
        $paramScoreField->description = $this->translate('SCORE_FIELD_DESC');
        $paramScoreField->type = ActionParameterType::FIELD;
        $paramScoreField->required = true;

        // 2. Target field where the interpretation label is written
        $paramTargetLabel = new ActionParameterDefinition();
        $paramTargetLabel->name = 'target_label_field';
        $paramTargetLabel->text = $this->translate('TARGET_LABEL_FIELD_TEXT');
        $paramTargetLabel->description = $this->translate('TARGET_LABEL_FIELD_DESC');
        $paramTargetLabel->type = ActionParameterType::FIELD;
        $paramTargetLabel->required = true;

        // 3. Baremos (multiple, each one with an optional population condition)
        $paramBaremos = new ActionParameterDefinition();
        $paramBaremos->name = 'baremos';
        $paramBaremos->text = $this->translate('BAREMOS_TEXT');
        $paramBaremos->description = $this->translate('BAREMOS_DESC');
        $paramBaremos->type = ActionParameterType::ITEM_LIST;
        $paramBaremos->required = true;
        $paramBaremos->colSize = 'col-12';

        $itemTitle = new ActionParameterDefinition();
        $itemTitle->name = 'title';
        $itemTitle->text = $this->translate('BAREMO_ITEM_TITLE_TEXT');
        $itemTitle->description = $this->translate('BAREMO_ITEM_TITLE_DESC');
        $itemTitle->type = ActionParameterType::VALUE;
        $itemTitle->dataType = ActionDataType::TEXT;
        $itemTitle->example = 'Adultos (18-64)';
        $itemTitle->colSize = 'col-4';

        $itemConditionField = new ActionParameterDefinition();
        $itemConditionField->name = 'condition_field';
        $itemConditionField->text = $this->translate('BAREMO_ITEM_CONDITION_FIELD_TEXT');
        $itemConditionField->description = $this->translate('BAREMO_ITEM_CONDITION_FIELD_DESC');
        $itemConditionField->type = ActionParameterType::FIELD;
        $itemConditionField->colSize = 'col-4';

        $itemConditionMin = new ActionParameterDefinition();
        $itemConditionMin->name = 'condition_min';
        $itemConditionMin->text = $this->translate('BAREMO_ITEM_CONDITION_MIN_TEXT');
        $itemConditionMin->description = $this->translate('BAREMO_ITEM_CONDITION_MIN_DESC');
        $itemConditionMin->type = ActionParameterType::VALUE;
        $itemConditionMin->dataType = ActionDataType::FLOAT;
        $itemConditionMin->example = '18';
        $itemConditionMin->colSize = 'col-2';

        $itemConditionMax = new ActionParameterDefinition();
        $itemConditionMax->name = 'condition_max';
        $itemConditionMax->text = $this->translate('BAREMO_ITEM_CONDITION_MAX_TEXT');
        $itemConditionMax->description = $this->translate('BAREMO_ITEM_CONDITION_MAX_DESC');
        $itemConditionMax->type = ActionParameterType::VALUE;
        $itemConditionMax->dataType = ActionDataType::FLOAT;
        $itemConditionMax->example = '64';
        $itemConditionMax->colSize = 'col-2';

        $itemRanges = new ActionParameterDefinition();
        $itemRanges->name = 'ranges';
        $itemRanges->text = $this->translate('BAREMO_ITEM_RANGES_TEXT');
        $itemRanges->description = $this->translate('BAREMO_ITEM_RANGES_DESC');
        $itemRanges->type = ActionParameterType::VALUE;
        $itemRanges->dataType = ActionDataType::TEXTAREA;
        $itemRanges->required = true;
        $itemRanges->example = "0-20: Bajo | 21-60: Medio | >60: Alto";
        $itemRanges->colSize = 'col-12';

        $paramBaremos->itemParameters = [
            $itemTitle,
            $itemConditionField,
            $itemConditionMin,
            $itemConditionMax,
            $itemRanges,
        ];

        return [
            $paramScoreField,
            $paramTargetLabel,
            $paramBaremos,
        ];
    }

    /**
     * Server-side validation: recalculates the interpretation label with the
     * submitted values and overwrites the target field, so the saved value
     * never depends exclusively on client-side JavaScript. A warning is
     * logged when the client value differs.
     */
    public function execute(ExecutionContext $context, FormAction $actionConfig): ActionResult
    {
        $scoreKey = (string)$actionConfig->getResolvedParameter('score_field', '');
        $targetKey = (string)$actionConfig->getResolvedParameter('target_label_field', '');
        /** @var array[] $baremos */
        $baremos = $actionConfig->getResolvedParameter('baremos', []);

        if (empty($scoreKey) || empty($targetKey)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Parameters 'score_field' and 'target_label_field' are required.");
        }

        // Read the value sent by the browser for the target field (before overwriting it)
        $submittedRaw = stic_AWFUtils::getSubmittedFieldValue($context->formConfig, $context->formData, $targetKey, $context->getInstanceIndexes());

        // Numeric score of the source field (option scores are applied)
        $scoreFieldDef = stic_AWFUtils::resolveFieldDefinition($context->formConfig, $scoreKey);
        $scoreRaw = stic_AWFUtils::getSubmittedFieldValue($context->formConfig, $context->formData, $scoreKey, $context->getInstanceIndexes());
        $score = stic_AWFUtils::computeFieldNumericValue($scoreFieldDef, $scoreRaw);

        if ($score === null) {
            return new ActionResult(ResultStatus::OK, $actionConfig, $this->translate('NO_SCORE_MESSAGE'));
        }

        $label = $this->interpret($context, $baremos, $score);

        // Warn when the label sent by the browser differs from the server result
        if ((string)$submittedRaw !== $label) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Advanced Web Forms: Field '{$targetKey}' recalculated on the server ('{$label}') differs from the value computed by the browser ('{$submittedRaw}'). The server value has been applied.");
        }

        // Write the recalculated label into the raw form data, so later actions
        // (data block saves, ...) use the server-computed value
        $targetFieldDef = stic_AWFUtils::resolveFieldDefinition($context->formConfig, $targetKey);
        $written = stic_AWFUtils::setSubmittedFieldValue(
            $context->formConfig,
            $context->formData,
            $targetKey,
            stic_AWFUtils::castCrmValue($label, $targetFieldDef?->type ?? 'varchar', $context),
            $context->getInstanceIndexes()
        );
        if (!$written) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Unable to write the recalculated label into field '{$targetKey}'.");
        }

        return new ActionResult(ResultStatus::OK, $actionConfig, $this->translate('EXECUTE_MESSAGE') . ' ' . $label);
    }

    /**
     * Interprets a score using the configured baremos: returns the label of
     * the first range of the first baremo whose population condition matches
     * the submitted data (baremos without condition always match and act as
     * the default/fallback interpretation when placed last).
     * @param ExecutionContext $context The execution context
     * @param array[] $baremos The configured baremos
     * @param float $score The score to interpret
     * @return string The interpretation label ('' when nothing matches)
     */
    private function interpret(ExecutionContext $context, array $baremos, float $score): string
    {
        foreach ($baremos as $baremo) {
            if (!$this->baremoConditionMatches($context, $baremo)) {
                continue;
            }
            $ranges = stic_AWFUtils::parseRangeDefinition((string)($baremo['ranges'] ?? ''));
            return stic_AWFUtils::findRangeLabel($ranges, $score);
        }
        return '';
    }

    /**
     * Evaluates the population condition of a baremo against the submitted
     * form data. A baremo without a condition field always matches.
     * @param ExecutionContext $context The execution context
     * @param array $baremo The baremo configuration
     * @return bool True when the condition matches (or there is no condition)
     */
    private function baremoConditionMatches(ExecutionContext $context, array $baremo): bool
    {
        $conditionField = trim((string)($baremo['condition_field'] ?? ''));
        if ($conditionField === '') {
            return true; // Baremo without condition: always applies (default)
        }

        $conditionFieldDef = stic_AWFUtils::resolveFieldDefinition($context->formConfig, $conditionField);
        $conditionRaw = stic_AWFUtils::getSubmittedFieldValue($context->formConfig, $context->formData, $conditionField, $context->getInstanceIndexes());
        $conditionValue = stic_AWFUtils::computeFieldNumericValue($conditionFieldDef, $conditionRaw);

        if ($conditionValue === null) {
            return false; // Condition field has no value: the baremo does not apply
        }

        $min = $baremo['condition_min'] ?? null;
        $max = $baremo['condition_max'] ?? null;
        $min = ($min !== null && $min !== '' && is_numeric($min)) ? (float)$min : null;
        $max = ($max !== null && $max !== '' && is_numeric($max)) ? (float)$max : null;

        if ($min !== null && $max !== null && $min > $max) {
            [$min, $max] = [$max, $min];
        }

        return ($min === null || $conditionValue >= $min) && ($max === null || $conditionValue <= $max);
    }

    /**
     * Returns the assets (CSS/JS) that should be injected into the frontend for this action.
     *
     * @param array $params Action parameters (unresolved)
     * @param FormConfig|null $formConfig Form configuration (if applicable)
     * @param string $formId Form ID (if applicable)
     * @return array Structure: ['script' => ['...'], 'css' => [], 'html' => []]
     */
    public function getFrontendAssets(array $params, ?FormConfig $formConfig = null, string $formId = ''): array
    {
        $scoreField = '';
        $targetLabelField = '';
        $baremos = [];

        foreach ($params as $p) {
            switch ($p->name) {
                case 'score_field':
                    $scoreField = (string)$p->value;
                    break;
                case 'target_label_field':
                    $targetLabelField = (string)$p->value;
                    break;
                case 'baremos':
                    $decoded = is_array($p->value) ? $p->value : json_decode((string)$p->value, true);
                    if (is_array($decoded)) {
                        $baremos = $decoded;
                    }
                    break;
            }
        }

        if (empty($scoreField) || empty($targetLabelField) || empty($baremos)) {
            return [];
        }

        // Collect the condition fields used by the baremos (also watched for changes)
        $conditionFields = array_values(array_unique(array_filter(array_map(
            fn($b) => trim((string)($b['condition_field'] ?? '')),
            $baremos
        ), fn($f) => !empty($f))));

        $jsConfig = json_encode([
            'formId' => $formId,
            'scoreField' => $scoreField,
            'targetLabelField' => $targetLabelField,
            'baremos' => $baremos,
            'conditionFields' => $conditionFields,
        ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);

        $script = <<<JS
document.addEventListener('DOMContentLoaded', function() {
    var CFG = {$jsConfig};
    var api = (window.AWF_FORMS || {})[CFG.formId];
    if (!api || !api.form) return;

    var updating = false;

    // Checks the population condition of a baremo against the submitted data
    function conditionMatches(getValue, getAttribute, baremo) {
        var conditionField = String(baremo.condition_field || '').trim();
        if (conditionField === '') return true; // Baremo without condition: default

        var raw = getValue(conditionField);
        var scoreAttr = getAttribute(conditionField, 'score');
        var value;
        if (scoreAttr !== null && scoreAttr !== undefined && String(scoreAttr).trim() !== '') {
            value = parseFloat(scoreAttr);
        } else {
            var list = (raw === null || raw === undefined) ? [] : (Array.isArray(raw) ? raw : [raw]);
            value = null;
            for (var i = 0; i < list.length; i++) {
                if (list[i] === '' || list[i] === null || list[i] === undefined) continue;
                var n = parseFloat(list[i]);
                if (!isNaN(n)) { value = (value === null ? 0 : value) + n; }
            }
        }
        if (value === null || isNaN(value)) return false;

        var min = (baremo.condition_min !== '' && baremo.condition_min !== null && baremo.condition_min !== undefined && !isNaN(parseFloat(baremo.condition_min))) ? parseFloat(baremo.condition_min) : null;
        var max = (baremo.condition_max !== '' && baremo.condition_max !== null && baremo.condition_max !== undefined && !isNaN(parseFloat(baremo.condition_max))) ? parseFloat(baremo.condition_max) : null;
        if (min !== null && max !== null && min > max) { var t = min; min = max; max = t; }
        return (min === null || value >= min) && (max === null || value <= max);
    }

    // Parses the ranges of a baremo ("min-max: Label" entries)
    function parseRanges(raw) {
        var ranges = [];
        String(raw || '').split(/[\\r\\n|]+/).forEach(function(line) {
            line = line.trim();
            if (line === '' || line.indexOf(':') === -1) return;
            var colon = line.indexOf(':');
            var key = line.substring(0, colon).trim().replace(/\\s+/g, '');
            var label = line.substring(colon + 1).trim();
            if (key === '' || label === '') return;

            var open = key.match(/^(>=|<=|>|<)(-?\\d+(?:\\.\\d+)?)$/);
            if (open) {
                var v = parseFloat(open[2]);
                if (open[1] === '>=' || open[1] === '>') ranges.push({ min: v, max: null, label: label });
                else ranges.push({ min: null, max: v, label: label });
                return;
            }
            var closed = key.match(/^(-?\\d+(?:\\.\\d+)?)-(-?\\d+(?:\\.\\d+)?)$/);
            if (closed) {
                var min = parseFloat(closed[1]);
                var max = parseFloat(closed[2]);
                if (min > max) { var t2 = min; min = max; max = t2; }
                ranges.push({ min: min, max: max, label: label });
                return;
            }
            var single = key.match(/^(-?\\d+(?:\\.\\d+)?)$/);
            if (single) {
                var val = parseFloat(single[1]);
                ranges.push({ min: val, max: val, label: label });
            }
        });
        return ranges;
    }

    // Computes the label for a score using the first matching baremo
    function interpret(getValue, getAttribute, score) {
        for (var i = 0; i < CFG.baremos.length; i++) {
            var baremo = CFG.baremos[i];
            if (!conditionMatches(getValue, getAttribute, baremo)) continue;
            var ranges = parseRanges(baremo.ranges);
            for (var j = 0; j < ranges.length; j++) {
                var r = ranges[j];
                if ((r.min === null || score >= r.min) && (r.max === null || score <= r.max)) {
                    return r.label;
                }
            }
            return '';
        }
        return '';
    }

    // Numeric value of a field within an instance: uses the score configured
    // for the selected option when available, otherwise the plain value
    function numericOf(getValue, getAttribute, key) {
        var score = getAttribute(key, 'score');
        if (score !== null && score !== undefined && String(score).trim() !== '') {
            var s = parseFloat(score);
            if (!isNaN(s)) return s;
        }
        var raw = getValue(key);
        var list = (raw === null || raw === undefined) ? [] : (Array.isArray(raw) ? raw : [raw]);
        var sum = 0;
        var any = false;
        list.forEach(function(v) {
            if (v === '' || v === null || v === undefined) return;
            var n = parseFloat(v);
            if (!isNaN(n)) { sum += n; any = true; }
        });
        return any ? sum : null;
    }

    function recalculate(info) {
        if (updating) return;
        var getValue = function(key) { return info ? info.getValue(key) : api.getValue(key); };
        var getAttr = function(key, attr) { return info ? info.getAttribute(key, attr) : api.getAttribute(key, attr); };

        var score = numericOf(getValue, getAttr, CFG.scoreField);
        if (score === null) return;

        var label = interpret(getValue, getAttr, score);

        updating = true;
        try {
            if (info) { info.setValue(CFG.targetLabelField, label); } else { api.setValue(CFG.targetLabelField, label); }
        } finally {
            updating = false;
        }
    }

    // Listen for changes in the score and in the baremo condition fields
    var watched = [CFG.scoreField].concat(CFG.conditionFields);
    watched.forEach(function(key) {
        api.onChange(key, function(value, info) { recalculate(info); }, { immediate: true });
    });

    // Initial calculation
    recalculate(null);
});
JS;

        return ['script' => [$script]];
    }
}
