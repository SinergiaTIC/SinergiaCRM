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

require_once "modules/stic_AWF_Forms/actions/Common/ScaleActionUtils.php";

/**
 * ScaleInterpretationAction
 *
 * Action that interprets a scale result (score) with a normative table
 * ("baremo"): reads the score field, finds the label of the first matching
 * range ("min-max: Label") and writes it to a target label field. The
 * interpretation runs live in the browser and is recalculated and overwritten
 * by the server on submission, so the saved value never depends exclusively on
 * client-side JavaScript.
 *
 * Keep it simple: one baremo per action. When several normative tables are
 * needed (e.g. one per age range), add one action per baremo and use the
 * standard action execution conditions to decide which one applies.
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

        // 2. Ranges of the baremo
        $paramRanges = new ActionParameterDefinition();
        $paramRanges->name = 'ranges';
        $paramRanges->text = $this->translate('RANGES_TEXT');
        $paramRanges->description = $this->translate('RANGES_DESC');
        $paramRanges->type = ActionParameterType::VALUE;
        $paramRanges->dataType = ActionDataType::TEXTAREA;
        $paramRanges->required = true;
        $paramRanges->colSize = 'col-12';

        // 3. Target field where the interpretation label is written
        $paramTargetLabel = new ActionParameterDefinition();
        $paramTargetLabel->name = 'target_label_field';
        $paramTargetLabel->text = $this->translate('TARGET_LABEL_FIELD_TEXT');
        $paramTargetLabel->description = $this->translate('TARGET_LABEL_FIELD_DESC');
        $paramTargetLabel->type = ActionParameterType::FIELD;
        $paramTargetLabel->required = true;

        return [
            $paramScoreField,
            $paramRanges,
            $paramTargetLabel,
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
        $scoreResolved = $actionConfig->getResolvedParameter('score_field');
        $scoreKey = ($scoreResolved instanceof DataBlockFieldResolved) ? $scoreResolved->formKey : (string)($scoreResolved ?? '');
        $targetResolved = $actionConfig->getResolvedParameter('target_label_field');
        $targetKey = ($targetResolved instanceof DataBlockFieldResolved) ? $targetResolved->formKey : (string)($targetResolved ?? '');
        $rangesRaw = (string)$actionConfig->getResolvedParameter('ranges', '');

        if (empty($scoreKey) || empty($targetKey)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Parameters 'score_field' and 'target_label_field' are required.");
        }

        // Value the browser sent for the target field and numeric score of the
        // source field, both taken from the resolved parameters (already
        // verified and casted by the engine)
        $submittedRaw = ($targetResolved instanceof DataBlockFieldResolved) ? $targetResolved->value : null;
        $score = null;
        if ($scoreResolved instanceof DataBlockFieldResolved && $scoreResolved->value !== null && $scoreResolved->value !== '') {
            $score = ScaleActionUtils::computeFieldNumericValue($scoreResolved->dataBlockField, $scoreResolved->value);
        }

        if ($score === null) {
            return new ActionResult(ResultStatus::OK, $actionConfig, $this->translate('NO_SCORE_MESSAGE'));
        }

        $label = ScaleActionUtils::findRangeLabel(ScaleActionUtils::parseRangeDefinition($rangesRaw), $score);

        // Warn when the label sent by the browser differs from the server result
        if ((string)$submittedRaw !== $label) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Advanced Web Forms: Field '{$targetKey}' recalculated on the server ('{$label}') differs from the value computed by the browser ('{$submittedRaw}'). The server value has been applied.");
        }

        // Write the recalculated label into the raw form data, so later actions
        // (data block saves, ...) use the server-computed value
        $targetFieldDef = ($targetResolved instanceof DataBlockFieldResolved) ? $targetResolved->dataBlockField : null;
        $written = ScaleActionUtils::setSubmittedFieldValue(
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
        $rangesRaw = '';

        foreach ($params as $p) {
            switch ($p->name) {
                case 'score_field':
                    $scoreField = (string)$p->value;
                    break;
                case 'target_label_field':
                    $targetLabelField = (string)$p->value;
                    break;
                case 'ranges':
                    $rangesRaw = (string)$p->value;
                    break;
            }
        }

        if (empty($scoreField) || empty($targetLabelField) || trim($rangesRaw) === '') {
            return [];
        }

        $jsConfig = json_encode([
            'formId' => $formId,
            'scoreField' => $scoreField,
            'targetLabelField' => $targetLabelField,
            'ranges' => $rangesRaw,
        ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);

        $script = <<<JS
document.addEventListener('DOMContentLoaded', function() {
    var CFG = {$jsConfig};
    var api = (window.AWF_FORMS || {})[CFG.formId];
    if (!api || !api.form) return;

    var updating = false;

    // Parses the ranges of the baremo ("min-max: Label" entries)
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

    // Recalculates the interpretation label. When the change comes from a
    // repeatable instance (info provided), reads and writes are scoped to it.
    function recalculate(info) {
        if (updating) return;
        var getValue = function(key) { return info ? info.getValue(key) : api.getValue(key); };
        var getAttr = function(key, attr) { return info ? info.getAttribute(key, attr) : api.getAttribute(key, attr); };

        var score = numericOf(getValue, getAttr, CFG.scoreField);
        if (score === null) return;

        var label = '';
        var ranges = parseRanges(CFG.ranges);
        for (var i = 0; i < ranges.length; i++) {
            var r = ranges[i];
            if ((r.min === null || score >= r.min) && (r.max === null || score <= r.max)) {
                label = r.label;
                break;
            }
        }

        updating = true;
        try {
            if (info) { info.setValue(CFG.targetLabelField, label); } else { api.setValue(CFG.targetLabelField, label); }
        } finally {
            updating = false;
        }
    }

    // Listen for changes in the score field
    api.onChange(CFG.scoreField, function(value, info) { recalculate(info); }, { immediate: true });

    // Initial calculation
    recalculate(null);
});
JS;

        return ['script' => [$script]];
    }
}
