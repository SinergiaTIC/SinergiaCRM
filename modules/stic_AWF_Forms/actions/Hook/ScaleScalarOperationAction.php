<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by
 * the Free Software Foundation.
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
 * ScaleScalarOperationAction
 *
 * Atomic action that applies ONE arithmetic operation (addition, subtraction,
 * multiplication or division) between a single form field and an integer
 * scalar, and writes the result to a numeric target field. The calculation
 * runs live in the browser and is recalculated and overwritten by the server
 * on submission, so the saved value never depends exclusively on client-side
 * JavaScript.
 *
 * Complex calculations can be built by chaining several actions: this one
 * operates on the result written by a ScaleCalculationAction (or by another
 * instance of this action). For the interpretation of the result (baremos) use
 * the ScaleInterpretationAction.
 */
class ScaleScalarOperationAction extends HookActionDefinition implements IFrontendAction
{
    /** @var string[] Numeric action data types allowed for the target field */
    private const NUMERIC_TYPES = ['integer', 'float'];

    public function __construct()
    {
        $this->isActive = true;
        $this->isUserSelectable = true;
        $this->category = 'data';
        $this->baseLabel = 'LBL_SCALE_SCALAR_OPERATION_ACTION';
    }

    /**
     * Defines the parameters that will be displayed in the wizard.
     */
    public function getParameters(): array
    {
        // 1. Operation between the field and the scalar
        $paramOperation = new ActionParameterDefinition();
        $paramOperation->name = 'operation';
        $paramOperation->text = $this->translate('OPERATION_TEXT');
        $paramOperation->description = $this->translate('OPERATION_DESC');
        $paramOperation->type = ActionParameterType::VALUE;
        $paramOperation->dataType = ActionDataType::SELECT;
        $paramOperation->defaultValue = 'multiply';
        $paramOperation->options = [
            new ActionParameterOption('sum', $this->translate('OPERATION_OPTION_SUM')),
            new ActionParameterOption('subtract', $this->translate('OPERATION_OPTION_SUBTRACT')),
            new ActionParameterOption('multiply', $this->translate('OPERATION_OPTION_MULTIPLY')),
            new ActionParameterOption('divide', $this->translate('OPERATION_OPTION_DIVIDE')),
        ];
        $paramOperation->required = true;

        // 2. Operand: the source field
        $paramSource = new ActionParameterDefinition();
        $paramSource->name = 'source_field';
        $paramSource->text = $this->translate('SOURCE_FIELD_TEXT');
        $paramSource->description = $this->translate('SOURCE_FIELD_DESC');
        $paramSource->type = ActionParameterType::FIELD;
        $paramSource->supportedDataTypes = array_map(fn($type) => ActionDataType::from($type), self::NUMERIC_TYPES);
        $paramSource->required = true;

        // 3. Operand: the integer scalar
        $paramScalar = new ActionParameterDefinition();
        $paramScalar->name = 'scalar';
        $paramScalar->text = $this->translate('SCALAR_TEXT');
        $paramScalar->description = $this->translate('SCALAR_DESC');
        $paramScalar->type = ActionParameterType::VALUE;
        $paramScalar->dataType = ActionDataType::TEXT;
        $paramScalar->defaultValue = '1';
        $paramScalar->required = true;

        // 4. Numeric result field (target field)
        $paramTarget = new ActionParameterDefinition();
        $paramTarget->name = 'target_field';
        $paramTarget->text = $this->translate('TARGET_FIELD_TEXT');
        $paramTarget->description = $this->translate('TARGET_FIELD_DESC');
        $paramTarget->type = ActionParameterType::FIELD;
        $paramTarget->supportedDataTypes = array_map(fn($type) => ActionDataType::from($type), self::NUMERIC_TYPES);
        $paramTarget->required = true;

        // 5. Rounding decimals
        $paramDecimals = new ActionParameterDefinition();
        $paramDecimals->name = 'decimals';
        $paramDecimals->text = $this->translate('DECIMALS_TEXT');
        $paramDecimals->description = $this->translate('DECIMALS_DESC');
        $paramDecimals->type = ActionParameterType::VALUE;
        $paramDecimals->dataType = ActionDataType::INTEGER;
        $paramDecimals->defaultValue = '2';
        $paramDecimals->required = false;

        return [
            $paramOperation,
            $paramSource,
            $paramScalar,
            $paramTarget,
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
        // The engine resolves and validates the parameters: the resolved FIELD
        // parameters are DataBlockFieldResolved objects carrying the field
        // configuration and the submitted value already casted. No field
        // resolution or value reading is done here again.
        $targetResolved = $actionConfig->getResolvedParameter('target_field');
        $targetKey = ($targetResolved instanceof DataBlockFieldResolved) ? $targetResolved->formKey : (string)($targetResolved ?? '');
        $sourceResolved = $actionConfig->getResolvedParameter('source_field');
        $sourceKey = ($sourceResolved instanceof DataBlockFieldResolved) ? $sourceResolved->formKey : (string)($sourceResolved ?? '');
        $operation = (string)($actionConfig->getResolvedParameter('operation', 'multiply') ?: 'multiply');
        // TEXT data type: the raw string is kept so an empty value is not cast to 0
        $scalarRaw = $actionConfig->getResolvedParameter('scalar', '1');
        $decimals = (int)($actionConfig->getResolvedParameter('decimals', 2) ?: 0);

        if (empty($targetKey) || empty($sourceKey)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Parameters 'target_field' and 'source_field' are required.");
        }

        // Value the browser sent for the target field (before overwriting it)
        $submittedNumber = null;
        if ($targetResolved instanceof DataBlockFieldResolved && $targetResolved->value !== null && $targetResolved->value !== '') {
            $submittedNumber = ScaleActionUtils::computeFieldNumericValue($targetResolved->dataBlockField, $targetResolved->value);
        }

        // Numeric value of the source field (option scores are applied), taken
        // from the resolved parameter (already verified by the engine)
        $sourceValue = null;
        if ($sourceResolved instanceof DataBlockFieldResolved && $sourceResolved->value !== null && $sourceResolved->value !== '') {
            $sourceValue = ScaleActionUtils::computeFieldNumericValue($sourceResolved->dataBlockField, $sourceResolved->value);
        }
        if ($sourceValue === null) {
            // No source value: nothing to recalculate
            return new ActionResult(ResultStatus::OK, $actionConfig, $this->translate('NO_VALUE_MESSAGE'));
        }

        $scalar = is_numeric($scalarRaw) ? (float)$scalarRaw : 1.0;
        switch ($operation) {
            case 'sum':
                $result = $sourceValue + $scalar;
                break;
            case 'subtract':
                $result = $sourceValue - $scalar;
                break;
            case 'multiply':
                $result = $sourceValue * $scalar;
                break;
            case 'divide':
                if ($scalar == 0) {
                    return new ActionResult(ResultStatus::ERROR, $actionConfig, $this->translate('DIVIDE_BY_ZERO_MESSAGE'));
                }
                $result = $sourceValue / $scalar;
                break;
            default:
                $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Unknown operation '{$operation}'.");
                return new ActionResult(ResultStatus::ERROR, $actionConfig, "Unknown operation '{$operation}'.");
        }

        $result = round($result, max(0, min(10, $decimals)));

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
     * Returns the assets (CSS/JS) that should be injected into the frontend for this action.
     *
     * @param array $params Action parameters (unresolved)
     * @param FormConfig|null $formConfig Form configuration (if applicable)
     * @param string $formId Form ID (if applicable)
     * @return array Structure: ['script' => ['...'], 'css' => [], 'html' => []]
     */
    public function getFrontendAssets(array $params, ?FormConfig $formConfig = null, string $formId = ''): array
    {
        $operation = 'multiply';
        $sourceField = '';
        $scalar = 1;
        $targetField = '';
        $decimals = 2;

        foreach ($params as $p) {
            switch ($p->name) {
                case 'operation':
                    $operation = (string)($p->value ?: 'multiply');
                    break;
                case 'source_field':
                    $sourceField = (string)$p->value;
                    break;
                case 'scalar':
                    $scalar = is_numeric($p->value) ? (float)$p->value : 1;
                    break;
                case 'target_field':
                    $targetField = (string)$p->value;
                    break;
                case 'decimals':
                    $decimals = (int)($p->value ?: 2);
                    break;
            }
        }

        if (empty($targetField) || empty($sourceField) || $sourceField === $targetField) {
            return [];
        }

        $jsConfig = json_encode([
            'formId' => $formId,
            'operation' => $operation,
            'sourceField' => $sourceField,
            'scalar' => $scalar,
            'targetField' => $targetField,
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

    // Applies the configured operation between the field value and the scalar
    function computeResult(sourceValue) {
        switch (CFG.operation) {
            case 'sum': return sourceValue + CFG.scalar;
            case 'subtract': return sourceValue - CFG.scalar;
            case 'multiply': return sourceValue * CFG.scalar;
            case 'divide': return CFG.scalar === 0 ? null : sourceValue / CFG.scalar;
            default: return null;
        }
    }

    // Recalculates the target field. When the change comes from a repeatable
    // instance (info provided), all reads and writes are scoped to that instance.
    function recalculate(info) {
        if (updating) return;
        var getValue = function(key) { return info ? info.getValue(key) : api.getValue(key); };
        var getAttr = function(key, attr) { return info ? info.getAttribute(key, attr) : api.getAttribute(key, attr); };

        var sourceValue = numericOf(getValue, getAttr, CFG.sourceField);
        if (sourceValue === null) return;
        var result = computeResult(sourceValue);
        if (result === null || isNaN(result)) return;
        result = Number(result.toFixed(CFG.decimals));

        updating = true;
        try {
            if (info) { info.setValue(CFG.targetField, result); } else { api.setValue(CFG.targetField, result); }
        } finally {
            updating = false;
        }
    }

    // Listen for changes in the source field
    api.onChange(CFG.sourceField, function(value, info) { recalculate(info); }, { immediate: true });

    // Initial calculation
    recalculate(null);
});
JS;

        return ['script' => [$script]];
    }
}
