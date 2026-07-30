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
    private array $formData = [];       // Copy of the RAW form data received
    
    private string $formId = '';        // ID of the form being processed
    private string $responseId = '';    // Response ID generated for this submission
    private ?SugarBean $responseBean;   // Response Bean generated for this submission

    private FormConfig $formConfig;     // Form configuration

    /** @var ActionResult[] */
    private array $actionResults = [];

    private float $submissionTimestamp;

    private string $formType = '';

    private string $defaultAssignedUserId;
    private ?string $visitorUserId = null;

    /** @var ?DeferredContextData Objecte de context per a processos diferits */
    private ?DeferredContextData $deferredContext = null;
    
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
     */
    public function __construct(string $formId, string $responseId, array $formData, FormConfig $formConfig, ?float $timestamp = null, string $defaultAssignedUserId = '', ?SugarBean $responseBean = null, string $formType = '') {
        $this->formId = $formId;
        $this->responseId = $responseId;
        $this->formData = $formData;
        $this->formConfig = $formConfig;
        $this->actionResults = [];
        $this->submissionTimestamp = $timestamp ?? microtime(true);
        $this->defaultAssignedUserId = $defaultAssignedUserId;
        $this->responseBean = $responseBean;
        $this->formType = $formType;
    }

    public function getFormData(): array { return $this->formData; }
    public function setFormData(array $formData): void { $this->formData = $formData; }
    public function addFormDataValue(string $key, $value): void { $this->formData[$key] = $value; }
    public function getFormId(): string { return $this->formId; }
    public function getResponseId(): string { return $this->responseId; }
    public function getResponseBean(): ?SugarBean { return $this->responseBean; }
    public function getFormConfig(): FormConfig { return $this->formConfig; }
    public function getActionResults(): array { return $this->actionResults; }
    public function getSubmissionTimestamp(): float { return $this->submissionTimestamp; }
    public function getFormType(): string { return $this->formType; }
    public function getDefaultAssignedUserId(): string { return $this->defaultAssignedUserId; }
    public function getVisitorUserId(): ?string { return $this->visitorUserId; }
    public function setVisitorUserId(?string $visitorUserId): void { $this->visitorUserId = $visitorUserId; }
    public function getDeferredContext(): ?DeferredContextData { return $this->deferredContext; }
    public function setDeferredContext(?DeferredContextData $deferredContext): void { $this->deferredContext = $deferredContext; }

    /**
     * Adds an action result to the execution context.
     * @param ActionResult $result Action result
     */
    public function addActionResult(ActionResult $result): void {
        $result->resetTimestamp();
        $key = $result->actionConfig?->getId();
        if ($key === null) {
            $key = 'unknown_' . count($this->actionResults);
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Adding ActionResult with unknown action ID to ExecutionContext. Assigned key: {$key}");
        }
        $this->actionResults[$key] = $result;
        if($result->isError()) {
            $GLOBALS['log']->error("Line ".__LINE__.": ".__METHOD__.": Action '{$result->actionConfig?->getName()}' resulted in ERROR: " . $result->message);
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
     * Gets a data block by its ID.
     * @param string $blockId Data block ID
     * @return ?FormDataBlock The data block or null if not found
     */
    public function getDataBlockById(string $blockId): ?FormDataBlock {
        return $this->formConfig->getDataBlockById($blockId);
    }

    /**
     * Gets a data block by its name.
     * @param string $blockName The data block name
     * @return ?FormDataBlock The data block or null if not found
     */
    public function getDataBlockByName(string $blockName): ?FormDataBlock {
        foreach ($this->formConfig->getDataBlocks() as $block) {
            if ($block->getName() === $blockName) {
                return $block;
            }
        }
        return null;
    }
}


