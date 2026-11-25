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
 * ScaleCalculationAction
 *
 * Action that applies a single operation (sum, average, product, minimum,
 * maximum or count of answered items) to a set of form fields and writes the
 * result to a numeric target field. The calculation runs live in the browser
 * and is recalculated and overwritten by the server on submission, so the
 * saved value never depends exclusively on client-side JavaScript.
 *
 * An optional integer scalar can be configured to multiply the result of the
 * operation. More complex calculations can be built by chaining several of
 * these actions. For the interpretation of the result (baremos) use the
 * ScaleInterpretationAction.
 */
class ScaleCalculationAction extends HookActionDefinition implements IFrontendAction
{
    /** @var string[] Numeric action data types allowed for the target field */
    private const NUMERIC_TYPES = ['integer', 'float'];

    public function __construct()
    {
        $this->isActive = true;
        $this->isUserSelectable = true;
        $this->category = 'data';
        $this->baseLabel = 'LBL_SCALE_CALCULATION_ACTION';
    }

    /**
     * Defines the parameters that will be displayed in the wizard.
     */
    public function getParameters(): array
    {
        // 1. Numeric result field (target field)
        $paramTarget = new ActionParameterDefinition();
        $paramTarget->name = 'target_field';
        $paramTarget->text = $this->translate('TARGET_FIELD_TEXT');
        $paramTarget->description = $this->translate('TARGET_FIELD_DESC');
        $paramTarget->type = ActionParameterType::FIELD;
        $paramTarget->supportedDataTypes = array_map(fn($type) => ActionDataType::from($type), self::NUMERIC_TYPES);
        $paramTarget->required = true;

        // 2. List of source fields (multiple selection; numeric fields and
        // fields whose options define scores, e.g. dropdowns/radios, are allowed)
        $paramSources = new ActionParameterDefinition();
        $paramSources->name = 'source_fields';
        $paramSources->text = $this->translate('SOURCE_FIELDS_TEXT');
        $paramSources->description = $this->translate('SOURCE_FIELDS_DESC');
        $paramSources->type = ActionParameterType::FIELD_LIST;
        $paramSources->required = true;

        // 3. Operation to apply
        $paramOperation = new ActionParameterDefinition();
        $paramOperation->name = 'operation';
        $paramOperation->text = $this->translate('OPERATION_TEXT');
        $paramOperation->description = $this->translate('OPERATION_DESC');
        $paramOperation->type = ActionParameterType::VALUE;
        $paramOperation->dataType = ActionDataType::SELECT;
        $paramOperation->defaultValue = 'sum';
        $paramOperation->options = [
            new ActionParameterOption('sum', $this->translate('OPERATION_OPTION_SUM')),
            new ActionParameterOption('average', $this->translate('OPERATION_OPTION_AVERAGE')),
            new ActionParameterOption('product', $this->translate('OPERATION_OPTION_PRODUCT')),
            new ActionParameterOption('min', $this->translate('OPERATION_OPTION_MIN')),
            new ActionParameterOption('max', $this->translate('OPERATION_OPTION_MAX')),
            new ActionParameterOption('count', $this->translate('OPERATION_OPTION_COUNT')),
        ];
        $paramOperation->required = true;

        // 4. Rounding decimals
        $paramDecimals = new ActionParameterDefinition();
        $paramDecimals->name = 'decimals';
        $paramDecimals->text = $this->translate('DECIMALS_TEXT');
        $paramDecimals->description = $this->translate('DECIMALS_DESC');
        $paramDecimals->type = ActionParameterType::VALUE;
        $paramDecimals->dataType = ActionDataType::INTEGER;
        $paramDecimals->defaultValue = '2';
        $paramDecimals->required = false;

        // 5. Optional integer scalar (multiplier) applied to the result
        //    (TEXT type: the raw string is kept so an empty value is not cast to 0)
        $paramScalar = new ActionParameterDefinition();
        $paramScalar->name = 'scalar';
        $paramScalar->text = $this->translate('SCALAR_TEXT');
        $paramScalar->description = $this->translate('SCALAR_DESC');
        $paramScalar->type = ActionParameterType::VALUE;
        $paramScalar->dataType = ActionDataType::TEXT;
        $paramScalar->defaultValue = '';
        $paramScalar->required = false;

        return [
            $paramTarget,
            $paramSources,
            $paramOperation,
            $paramDecimals,
            $paramScalar,
        ];
    }

    /**
     * Server-side validation: recalculates the result with the submitted
     * values and overwrites the target field, so the value saved in the CRM
     * is always the one computed by the server (the browser calculation is
     * only a live preview). A warning is logged when the client value differs.
     */
    public function execute(ExecutionContext $context, FormAction $actionConfig): ActionResult
    {
        $targetResolved = $actionConfig->getResolvedParameter('target_field');
        $targetKey = ($targetResolved instanceof DataBlockFieldResolved) ? $targetResolved->formKey : (string)($targetResolved ?? '');
        /** @var array $sourceValues [formKey => submitted raw value] */
        $sourceValues = $actionConfig->getResolvedParameter('source_fields', []);
        $operation = (string)($actionConfig->getResolvedParameter('operation', 'sum') ?: 'sum');
        $decimals = (int)($actionConfig->getResolvedParameter('decimals', 2) ?: 0);
        // Optional integer multiplier applied to the result (empty/non-numeric = 1)
        $scalarRaw = $actionConfig->getResolvedParameter('scalar', '');
        $scalar = is_numeric($scalarRaw) ? (int)$scalarRaw : 1;

        if (empty($targetKey)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Parameter 'target_field' is missing.");
        }

        // Read the value sent by the browser for the target field (before overwriting it)
        $targetDef = ScaleActionUtils::resolveFieldDefinition($context->formConfig, $targetKey);
        $submittedRaw = ScaleActionUtils::getSubmittedFieldValue($context->formConfig, $context->formData, $targetKey, $context->getInstanceIndexes());
        $submittedNumber = ScaleActionUtils::computeFieldNumericValue($targetDef, $submittedRaw);

        // Compute the numeric value of every source field (option scores are applied)
        $numericValues = [];
        foreach ($sourceValues as $formKey => $rawValue) {
            $fieldDef = ScaleActionUtils::resolveFieldDefinition($context->formConfig, (string)$formKey);
            $numericValues[(string)$formKey] = ScaleActionUtils::computeFieldNumericValue($fieldDef, $rawValue);
        }

        $result = $this->computeResult($operation, $numericValues);
        if ($result === null) {
            // No source value at all: nothing to recalculate
            return new ActionResult(ResultStatus::OK, $actionConfig, $this->translate('NO_VALUES_MESSAGE'));
        }

        $result = round($result * $scalar, max(0, min(10, $decimals)));

        // Warn when the value sent by the browser differs from the server result
        if ($submittedNumber !== null && abs($submittedNumber - $result) > 0.0001) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Advanced Web Forms: Field '{$targetKey}' recalculated on the server ({$result}) differs from the value computed by the browser ({$submittedNumber}). The server value has been applied.");
        }

        // Write the recalculated value into the raw form data, so later actions
        // (data block saves, ...) use the server-computed value
        $written = ScaleActionUtils::setSubmittedFieldValue(
            $context->formConfig,
            $context->formData,
            $targetKey,
            $result,
            $context->getInstanceIndexes()
        );
        if (!$written) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Unable to write the recalculated value into field '{$targetKey}'.");
        }

        return new ActionResult(ResultStatus::OK, $actionConfig, $this->translate('EXECUTE_MESSAGE') . ' ' . $result);
    }

    /**
     * Applies the selected operation to the numeric values of the source fields.
     * @param string $operation Selected operation (sum, average, product, min, max, count)
     * @param array $numericValues Numeric values of the source fields [formKey => ?float]
     * @return ?float The computed result (null when there is nothing to compute)
     */
    private function computeResult(string $operation, array $numericValues): ?float
    {
        $present = array_filter($numericValues, fn($value) => $value !== null);
        $countPresent = count($present);

        switch ($operation) {
            case 'sum':
                if ($countPresent === 0) {
                    return null;
                }
                return array_sum($present);

            case 'average':
                if ($countPresent === 0) {
                    return null;
                }
                return array_sum($present) / $countPresent;

            case 'product':
                if ($countPresent === 0) {
                    return null;
                }
                return array_product($present);

            case 'min':
                if ($countPresent === 0) {
                    return null;
                }
                return min($present);

            case 'max':
                if ($countPresent === 0) {
                    return null;
                }
                return max($present);

            case 'count':
                if ($countPresent === 0) {
                    return null;
                }
                return (float)$countPresent;
        }

        $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Unknown operation '{$operation}'.");
        return null;
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
        $targetField = '';
        $sourceFields = [];
        $operation = 'sum';
        $decimals = 2;
        $scalar = 1;

        foreach ($params as $p) {
            switch ($p->name) {
                case 'target_field':
                    $targetField = (string)$p->value;
                    break;
                case 'source_fields':
                    $sourceFields = is_array($p->value) ? $p->value : explode(',', (string)$p->value);
                    break;
                case 'operation':
                    $operation = (string)($p->value ?: 'sum');
                    break;
                case 'decimals':
                    $decimals = (int)($p->value ?: 2);
                    break;
                case 'scalar':
                    $scalar = (is_numeric($p->value)) ? (int)$p->value : 1;
                    break;
            }
        }

        $sourceFields = array_values(array_filter(array_map('trim', $sourceFields), fn($f) => !empty($f) && $f !== $targetField));
        if (empty($targetField) || empty($sourceFields)) {
            return [];
        }

        $jsConfig = json_encode([
            'formId' => $formId,
            'targetField' => $targetField,
            'sourceFields' => $sourceFields,
            'operation' => $operation,
            'decimals' => $decimals,
            'scalar' => $scalar,
        ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);

        $script = <<<JS
document.addEventListener('DOMContentLoaded', function() {
    var CFG = {$jsConfig};
    var api = (window.AWF_FORMS || {})[CFG.formId];
    if (!api || !api.form) return;

    var updating = false;

    // Reads the numeric value of a field within an instance: uses the score
    // configured for the selected option when available, otherwise the plain value
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

    // Applies the configured operation to the numeric values
    function computeResult(values) {
        var present = values.filter(function(v) { return v !== null; });
        var count = present.length;
        if (count === 0) return null;
        switch (CFG.operation) {
            case 'sum': return present.reduce(function(a, b) { return a + b; }, 0);
            case 'average': return present.reduce(function(a, b) { return a + b; }, 0) / count;
            case 'product': return present.reduce(function(a, b) { return a * b; }, 1);
            case 'min': return Math.min.apply(null, present);
            case 'max': return Math.max.apply(null, present);
            case 'count': return count;
            default: return null;
        }
    }

    // Recalculates the target field. When the change comes from a repeatable
    // instance (info provided), all reads and writes are scoped to that instance.
    function recalculate(info) {
        if (updating) return;
        var getValue = function(key) { return info ? info.getValue(key) : api.getValue(key); };
        var getAttr = function(key, attr) { return info ? info.getAttribute(key, attr) : api.getAttribute(key, attr); };

        var values = CFG.sourceFields.map(function(key) { return numericOf(getValue, getAttr, key); });
        var result = computeResult(values);
        if (result === null || isNaN(result)) return;
        result = result * CFG.scalar;
        result = Number(result.toFixed(CFG.decimals));

        updating = true;
        try {
            if (info) { info.setValue(CFG.targetField, result); } else { api.setValue(CFG.targetField, result); }
        } finally {
            updating = false;
        }
    }

    // Listen for changes in the source fields
    CFG.sourceFields.forEach(function(key) {
        api.onChange(key, function(value, info) { recalculate(info); }, { immediate: true });
    });

    // Initial calculation
    recalculate(null);
});
JS;

        return ['script' => [$script]];
    }
}
