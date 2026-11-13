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
 * ScaleCalculationAction
 *
 * Action that computes a scale result from one or more form fields, writes it
 * to a numeric target field and, optionally, applies a formula. The selected
 * operation (sum, average, product, minimum, maximum, count of answered items
 * or a custom arithmetic formula) is calculated live in the browser and is
 * recalculated and overwritten by the server on submission, so the saved value
 * never depends exclusively on client-side JavaScript.
 */
class ScaleCalculationAction extends HookActionDefinition implements IFrontendAction
{
    /** @var string[] Numeric action data types allowed for the source and target fields */
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
            new ActionParameterOption('custom', $this->translate('OPERATION_OPTION_CUSTOM')),
        ];
        $paramOperation->required = true;

        // 4. Custom formula (only used when the operation is "custom")
        $paramFormula = new ActionParameterDefinition();
        $paramFormula->name = 'custom_formula';
        $paramFormula->text = $this->translate('CUSTOM_FORMULA_TEXT');
        $paramFormula->description = $this->translate('CUSTOM_FORMULA_DESC');
        $paramFormula->type = ActionParameterType::VALUE;
        $paramFormula->dataType = ActionDataType::TEXT;
        $paramFormula->example = "({Bloque.item1} + {Bloque.item2} + {Bloque.item3}) * 2";
        $paramFormula->fieldKeysHint = true;
        $paramFormula->required = false;

        // 5. Rounding decimals
        $paramDecimals = new ActionParameterDefinition();
        $paramDecimals->name = 'decimals';
        $paramDecimals->text = $this->translate('DECIMALS_TEXT');
        $paramDecimals->description = $this->translate('DECIMALS_DESC');
        $paramDecimals->type = ActionParameterType::VALUE;
        $paramDecimals->dataType = ActionDataType::INTEGER;
        $paramDecimals->defaultValue = '2';
        $paramDecimals->example = '2';
        $paramDecimals->required = false;

        return [
            $paramTarget,
            $paramSources,
            $paramOperation,
            $paramFormula,
            $paramDecimals,
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
        $targetKey = (string)$actionConfig->getResolvedParameter('target_field', '');
        /** @var array $sourceValues [formKey => submitted raw value] */
        $sourceValues = $actionConfig->getResolvedParameter('source_fields', []);
        $operation = (string)($actionConfig->getResolvedParameter('operation', 'sum') ?: 'sum');
        $formula = (string)$actionConfig->getResolvedParameter('custom_formula', '');
        $decimals = (int)($actionConfig->getResolvedParameter('decimals', 2) ?: 0);

        if (empty($targetKey)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Parameter 'target_field' is missing.");
        }

        // Compute the numeric value of every source field (option scores are applied)
        $numericValues = [];
        foreach ($sourceValues as $formKey => $rawValue) {
            $fieldDef = stic_AWFUtils::resolveFieldDefinition($context->formConfig, (string)$formKey);
            $numericValues[(string)$formKey] = stic_AWFUtils::computeFieldNumericValue($fieldDef, $rawValue);
        }

        // Read the value sent by the browser for the target field (before overwriting it)
        $targetDef = stic_AWFUtils::resolveFieldDefinition($context->formConfig, $targetKey);
        $submittedRaw = stic_AWFUtils::getSubmittedFieldValue($context->formConfig, $context->formData, $targetKey, $context->getInstanceIndexes());
        $submittedNumber = stic_AWFUtils::computeFieldNumericValue($targetDef, $submittedRaw);

        $result = $this->computeResult($operation, $formula, $numericValues, $sourceValues);
        if ($result === null) {
            // No source value at all (or an invalid formula): nothing to recalculate
            if ($operation === 'custom' && !empty($numericValues)) {
                return new ActionResult(ResultStatus::ERROR, $actionConfig, $this->translate('INVALID_FORMULA_MESSAGE'));
            }
            return new ActionResult(ResultStatus::OK, $actionConfig, $this->translate('NO_VALUES_MESSAGE'));
        }

        $result = round($result, max(0, min(10, $decimals)));

        // Warn when the value sent by the browser differs from the server result
        if ($submittedNumber !== null && abs($submittedNumber - $result) > 0.0001) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Advanced Web Forms: Field '{$targetKey}' recalculated on the server ({$result}) differs from the value computed by the browser ({$submittedNumber}). The server value has been applied.");
        }

        // Write the recalculated value into the raw form data, so later actions
        // (data block saves, ...) use the server-computed value
        $written = stic_AWFUtils::setSubmittedFieldValue(
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
     * @param string $operation Selected operation (sum, average, product, min, max, count, custom)
     * @param string $formula Custom formula (only for the "custom" operation)
     * @param array $numericValues Numeric values of the source fields [formKey => ?float]
     * @param array $sourceValues Raw submitted values [formKey => mixed]
     * @return ?float The computed result (null when there is nothing to compute)
     */
    private function computeResult(string $operation, string $formula, array $numericValues, array $sourceValues): ?float
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

            case 'custom':
                if (trim($formula) === '') {
                    return null;
                }
                // Empty values are evaluated as 0 inside the formula
                $placeholderValues = array_map(fn($value) => $value ?? 0.0, $numericValues);
                return stic_AWFUtils::evaluateNumericExpression($formula, $placeholderValues);
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
        $customFormula = '';
        $decimals = 2;

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
                case 'custom_formula':
                    $customFormula = (string)$p->value;
                    break;
                case 'decimals':
                    $decimals = (int)($p->value ?: 2);
                    break;
            }
        }

        $sourceFields = array_values(array_filter(array_map('trim', $sourceFields), fn($f) => !empty($f) && $f !== $targetField));
        if (empty($targetField) || ($operation !== 'custom' && empty($sourceFields)) || ($operation === 'custom' && trim($customFormula) === '')) {
            return [];
        }

        // Placeholders used by the custom formula
        $formulaFields = [];
        if ($operation === 'custom' && preg_match_all('/\{([^}]+)\}/', $customFormula, $matches)) {
            $formulaFields = array_values(array_filter(array_map('trim', $matches[1]), fn($f) => !empty($f) && $f !== $targetField));
        }

        $jsConfig = json_encode([
            'formId' => $formId,
            'targetField' => $targetField,
            'sourceFields' => $sourceFields,
            'operation' => $operation,
            'formula' => $customFormula,
            'formulaFields' => $formulaFields,
            'decimals' => $decimals,
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
            case 'custom': return evaluateFormula(CFG.formula, values);
            default: return null;
        }
    }

    // Safe arithmetic evaluator (+, -, *, /, parentheses and {field} placeholders).
    // Mirrors the server-side evaluator (no eval is used).
    function evaluateFormula(expression, placeholderValues) {
        var tokens = [];
        var offset = 0;
        while (offset < expression.length) {
            var ch = expression.charAt(offset);
            if (ch === ' ' || ch === '\\t' || ch === '\\n' || ch === '\\r') { offset++; continue; }
            if (ch === '{') {
                var close = expression.indexOf('}', offset);
                if (close === -1) return null;
                var name = expression.substring(offset + 1, close).trim();
                if (name === '') return null;
                tokens.push({ type: 'number', value: placeholderValues[name] || 0 });
                offset = close + 1;
                continue;
            }
            var numberMatch = expression.substring(offset).match(/^(?:\\d+(?:\\.\\d+)?|\\.\\d+)/);
            if (numberMatch) {
                tokens.push({ type: 'number', value: parseFloat(numberMatch[0]) });
                offset += numberMatch[0].length;
                continue;
            }
            if ('+-*/()'.indexOf(ch) !== -1) {
                // Unary +/-: at the start or right after another operator or an open parenthesis
                var previous = tokens[tokens.length - 1];
                if ((ch === '-' || ch === '+') && (!previous || (previous.type === 'operator' && previous.value !== ')'))) {
                    var sign = (ch === '-') ? -1 : 1;
                    offset++;
                    while (offset < expression.length && /\\s/.test(expression.charAt(offset))) { offset++; }
                    if (offset >= expression.length) return null;
                    var nextChar = expression.charAt(offset);
                    if (nextChar === '{') {
                        var closeSign = expression.indexOf('}', offset);
                        if (closeSign === -1) return null;
                        var signName = expression.substring(offset + 1, closeSign).trim();
                        if (signName === '') return null;
                        tokens.push({ type: 'number', value: sign * (placeholderValues[signName] || 0) });
                        offset = closeSign + 1;
                    } else {
                        var signMatch = expression.substring(offset).match(/^(?:\\d+(?:\\.\\d+)?|\\.\\d+)/);
                        if (!signMatch) return null;
                        tokens.push({ type: 'number', value: sign * parseFloat(signMatch[0]) });
                        offset += signMatch[0].length;
                    }
                    continue;
                }
                tokens.push({ type: 'operator', value: ch });
                offset++;
                continue;
            }
            return null;
        }
        if (tokens.length === 0) return null;

        var precedence = { '+': 1, '-': 1, '*': 2, '/': 2 };
        var output = [];
        var operators = [];
        for (var i = 0; i < tokens.length; i++) {
            var token = tokens[i];
            if (token.type === 'number') { output.push(token); continue; }
            if (token.value === '(') { operators.push(token); continue; }
            if (token.value === ')') {
                while (operators.length && operators[operators.length - 1].value !== '(') {
                    output.push(operators.pop());
                }
                if (!operators.length) return null;
                operators.pop();
                continue;
            }
            while (operators.length && operators[operators.length - 1].value !== '(' &&
                   precedence[operators[operators.length - 1].value] >= precedence[token.value]) {
                output.push(operators.pop());
            }
            operators.push(token);
        }
        while (operators.length) {
            var op = operators.pop();
            if (op.value === '(') return null;
            output.push(op);
        }

        var stack = [];
        for (var j = 0; j < output.length; j++) {
            var tok = output[j];
            if (tok.type === 'number') { stack.push(tok.value); continue; }
            if (stack.length < 2) return null;
            var right = stack.pop();
            var left = stack.pop();
            if (tok.value === '+') stack.push(left + right);
            else if (tok.value === '-') stack.push(left - right);
            else if (tok.value === '*') stack.push(left * right);
            else if (tok.value === '/') { if (right === 0) return null; stack.push(left / right); }
        }
        return stack.length === 1 ? stack[0] : null;
    }

    // Recalculates the target field. When the change comes from a repeatable
    // instance (info provided), all reads and writes are scoped to that instance.
    function recalculate(info) {
        if (updating) return;
        var getValue = function(key) { return info ? info.getValue(key) : api.getValue(key); };
        var getAttr = function(key, attr) { return info ? info.getAttribute(key, attr) : api.getAttribute(key, attr); };

        var values;
        if (CFG.operation === 'custom') {
            values = {};
            CFG.formulaFields.forEach(function(key) { values[key] = numericOf(getValue, getAttr, key); });
        } else {
            values = CFG.sourceFields.map(function(key) { return numericOf(getValue, getAttr, key); });
        }

        var result = computeResult(values);
        if (result === null || isNaN(result)) return;
        result = Number(result.toFixed(CFG.decimals));

        updating = true;
        try {
            if (info) { info.setValue(CFG.targetField, result); } else { api.setValue(CFG.targetField, result); }
        } finally {
            updating = false;
        }
    }

    // Listen for changes in the involved fields (sources or formula placeholders)
    var listened = CFG.operation === 'custom' ? CFG.formulaFields : CFG.sourceFields;
    listened.forEach(function(key) {
        api.onChange(key, function(value, info) { recalculate(info); }, { immediate: true });
    });

    // Initial calculation
    recalculate(null);
});
JS;

        return ['script' => [$script]];
    }
}
