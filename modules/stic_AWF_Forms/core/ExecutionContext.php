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

class ExecutionContext {
    public array $formData = [];       // Copy of the RAW form data received
    
    public string $formId = '';        // ID of the form being processed
    public string $responseId = '';    // Response ID generated for this submission
    public ?SugarBean $responseBean;   // Response Bean generated for this submission

    public FormConfig $formConfig;     // Form configuration

    /** @var ActionResult[] */
    public array $actionResults = [];

    public float $submissionTimestamp;

    public string $formType = '';

    public string $defaultAssignedUserId;
    public ?string $visitorUserId = null;

    /** @var array<string, array> Uploaded files from $_FILES, indexed by PHP key */
    public array $uploadedFiles = [];
    
    /** @var ?DeferredContextData Context object for deferred processes */
    public ?DeferredContextData $deferredContext = null;

    /**
     * Active loop-index stack (outer to inner) of the group heads being
     * unrolled: [] for scalar flows, [i] for one loop level, [i1, i2] for two
     * nested levels, ... (repeatable levels ≤ 2, optional levels unlimited).
     */
    public array $instanceIndexes = [];
    
    /**
     * Constructor for ExecutionContext.
     * @param string $formId ID of the form being processed
     * @param string $responseId Response ID generated for this submission
     * @param array $formData RAW form data received
     * @param FormConfig $formConfig Form configuration
     * @param ?float $timestamp Submission timestamp (optional)
     * @param string $defaultAssignedUserId Default assigned user ID (optional)
     * @param ?SugarBean $responseBean The response Bean (optional)
     * @param string $formType The form type (e.g., 'web', 'crm')
     * @param array $uploadedFiles Uploaded files data from $_FILES (optional)
     */
    public function __construct(string $formId, string $responseId, array $formData, FormConfig $formConfig, ?float $timestamp = null, string $defaultAssignedUserId = '', ?SugarBean $responseBean = null, string $formType = '', array $uploadedFiles = []) {
        $this->formId = $formId;
        $this->responseId = $responseId;
        $this->formData = $formData;
        $this->formConfig = $formConfig;
        $this->actionResults = [];
        $this->submissionTimestamp = $timestamp ?? microtime(true);
        $this->defaultAssignedUserId = $defaultAssignedUserId;
        $this->responseBean = $responseBean;
        $this->formType = $formType;
        $this->uploadedFiles = $uploadedFiles;
    }

    /**
     * Adds an action result to the execution context.
     * Per-instance executions (B-4) store one result per instance:
     *  - instance index 0 keeps the plain action id (backward compatible);
     *  - later indexes use "{$actionId}_{index}";
     *  - depth-2 executions use "{$actionId}_{outer}_{inner}".
     * Without an active instance index, the plain action id is used (scalar).
     * @param ActionResult $result Action result
     */
    public function addActionResult(ActionResult $result): void {
        $result->resetTimestamp();
        $key = $result->actionConfig?->id;
        if ($key === null) {
            $key = 'unknown_' . count($this->actionResults);
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Adding ActionResult with unknown action ID to ExecutionContext. Assigned key: {$key}");
        }
        $instanceKey = $this->getInstanceIndexKey();
        if ($instanceKey !== null && $instanceKey !== '0') {
            $key .= '_' . str_replace(':', '_', $instanceKey);
        }
        $this->actionResults[$key] = $result;
        if($result->isError()) {
            $GLOBALS['log']->error("Line ".__LINE__.": ".__METHOD__.": Action '{$result->actionConfig?->name}' resulted in ERROR: " . $result->message);
        }
    }

    /**
     * Adds an error to the execution context.
     * Accept \Throwable to handle both Exceptions and PHP 8 Errors.
     * @param \Throwable $e The exception or error thrown
     * @param ?FormAction $actionConfig Configuration of the action where the error occurred
     * @return ActionResult The ActionResult added to Context
     */
    public function addError(\Throwable $e, ?FormAction $actionConfig): ActionResult {
        // Create an ActionResult with ERROR status
        $errorResult = new ActionResult(ResultStatus::ERROR, $actionConfig, $e->getMessage());
        $this->addActionResult($errorResult);

        $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": AWF Execution Exception: " . $e->getMessage());
        $GLOBALS['log']->error($e->getTraceAsString());
        
        return $errorResult;
    }

    /**
     * Gets an action result by its ID.
     * @param string $actionId Action ID
     * @return ?ActionResult Action result or null if not found
     */
    public function getActionResultById(string $actionId): ?ActionResult {
        return $this->actionResults[$actionId] ?? null;
    }

    /**
     * Gets the result of an action for a specific instance (B-4/B-6), with
     * fallback: exact "{$actionId}_{index}" key first, then the plain
     * "{$actionId}" key (instance 0 keeps the plain key for backward
     * compatibility). With no index, behaves like getActionResultById().
     * The instance key can be an int (depth-1) or a composite "i:j" string
     * (depth-2), matching the keys generated by addActionResult().
     */
    public function getResultForAction(string $actionId, int|string|null $instanceIndex = null): ?ActionResult {
        if ($instanceIndex !== null && "$instanceIndex" !== '0') {
            $instanceKey = $this->actionResults[$actionId . '_' . str_replace(':', '_', (string)$instanceIndex)] ?? null;
            if ($instanceKey !== null) {
                return $instanceKey;
            }
        }
        return $this->actionResults[$actionId] ?? null;
    }

    /**
     * Composite key of the active loop indexes: null (scalar) or the
     * colon-joined vector ("i", "i:j", "i:j:k"...).
     */
    public function getInstanceIndexKey(): ?string {
        return $this->instanceIndexes === [] ? null : implode(':', $this->instanceIndexes);
    }

    /**
     * Gets a data block by its ID.
     * @param string $blockId Data block ID
     * @return ?FormDataBlock The data block or null if not found
     */
    public function getDataBlockById(string $blockId): ?FormDataBlock {
        return $this->formConfig->data_blocks[$blockId] ?? null;
    }

    /**
     * Gets a data block by its name.
     * @param string $blockName The data block name
     * @return ?FormDataBlock The data block or null if not found
     */
    public function getDataBlockByName(string $blockName): ?FormDataBlock {
        foreach ($this->formConfig->data_blocks as $block) {
            if ($block->name === $blockName) {
                return $block;
            }
        }
        return null;
    }

    /**
     * Sets the active loop-index stack (outer to inner) for the instance being
     * executed. Must be set before any parameter resolution so that
     * instance-aware keys and bean references are read.
     */
    public function setInstanceIndexes(array $indexes): void {
        $this->instanceIndexes = array_map('intval', array_values($indexes));
    }

    /**
     * Gets the active loop-index stack (outer to inner).
     * @return array
     */
    public function getInstanceIndexes(): array {
        return $this->instanceIndexes;
    }

    /**
     * Backward-compatible single-level setter: replaces the stack with one
     * index (or empties it for null).
     * @param ?int $index The instance index, or null for scalar flows
     */
    public function setCurrentInstanceIndex(?int $index): void {
        $this->instanceIndexes = $index === null ? [] : [$index];
    }

    /**
     * Innermost (current) loop index of the active stack, or null for scalar flows.
     * @return ?int
     */
    public function getCurrentInstanceIndex(): ?int {
        return $this->instanceIndexes === [] ? null : (int)end($this->instanceIndexes);
    }
}


