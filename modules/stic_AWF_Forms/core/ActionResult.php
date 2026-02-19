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

enum ResultStatus: string {
    case OK      = 'ok';
    case SKIPPED = 'skipped';
    case WAIT    = 'wait';
    case ERROR   = 'error';
}

/**
 * Class representing the result of an action.
 */
class ActionResult {
    public ResultStatus $status;             // Result status
    public ?string $message;                 // Additional result message
    /** @var BeanModified[] */
    public array $modifiedBeans;             // Beans modified by the action

    public float $timestamp;                 // Execution timestamp
    public ?FormAction $actionConfig;        // Configuration of the executed action

    protected ?IServerAction $actionSource;  // The action that generated this result 
    public array $data = array();            // Result data from the execution

    public function setAction(IServerAction $action) {
        $this->actionSource = $action;
        return $this;
    }

    public function getAction(): ?IServerAction {
        return $this->actionSource;
    }

    public function setData(array $data) {
        $this->data = $data;
        return $this;
    }

    public function getData(): array {
        return $this->data;
    }

    public function __construct(ResultStatus $status, ?FormAction $actionConfig, ?string $message = null) {
        $this->status = $status;
        $this->actionConfig = $actionConfig;
        $this->message = $message;
        $this->modifiedBeans = [];
        $this->timestamp = microtime(true);
        $this->actionSource = null;
        $this->data = array();
    }

    /**
     * Registers a bean modification in the action result
     * Used when the bean does NOT come from a DataBlock
     *
     * @param SugarBean $bean The modified bean.
     * @param BeanModificationType $action The modification type (CREATED, UPDATED, ENRICHED, SKIPPED)
     * @param array $submittedData (Optional) The [field => value] data used or applied by the action.
     */
    public function registerBeanModification(SugarBean $bean, BeanModificationType $action, array $submittedData = []): void 
    {
        $modifiedBean = new BeanModified($bean->id, $bean->module_name, $action, $submittedData);
        $this->addModifiedBean($modifiedBean);
    }

    /**
     * Registers a bean modification that DOES come from a DataBlock.
     *  - Registers the modification
     *  - Saves a reference to the bean in the original FormDataBlock for future actions.
     *
     * @param SugarBean $bean The processed bean.
     * @param DataBlockResolved $block The processed DataBlockResolved.
     * @param BeanModificationType $action The modification type (CREATED, UPDATED, ENRICHED, SKIPPED)
     * @param array<string, FieldModification> $submittedData Optional. Structured data.
     * @throws \LogicException If the bean module does not match the block's module.
     */
    public function registerBeanModificationFromBlock(SugarBean $bean, DataBlockResolved $block, BeanModificationType $action, array $submittedData): void 
    {
        $blockModule = $block->dataBlock->module;
        if ($bean->module_name !== $blockModule) {
            throw new \LogicException("Error in registerBeanModificationFromBlock: Bean module ('{$bean->module_name}') is different from block module ('{$blockModule}').");
        }

        $modifiedBean = new BeanModified($bean->id, $bean->module_name, $action, $submittedData);
        $this->addModifiedBean($modifiedBean);

        $block->dataBlock->setBeanReference($bean->id);
    }

    /**
     * Records metadata or logs of secondary actions (Emails, Lists, Relationships).
     * Used when the action does not modify physical fields of the Bean, but rather performs context operations.
     *
     * @param SugarBean $bean The bean on which the action was performed
     * @param array $metadata Simple dictionary key => value with the info to record
     */
    public function registerActionMetadata(SugarBean $bean, array $metadata): void
    {
        require_once 'modules/stic_AWF_Forms/core/FieldModification.php';

        $logData = [];
        foreach ($metadata as $key => $value) {
            // Wrap the flat data in a FieldModification object with APPLIED state.
            $logData[$key] = new FieldModification($key, FieldModificationStatus::APPLIED, $value);
        }

        // Consider all these actions as an UPDATE of the record context
        $modifiedBean = new BeanModified($bean->id, $bean->module_name, BeanModificationType::UPDATED, $logData);
        
        $this->addModifiedBean($modifiedBean);
    }

    public function addModifiedBean(BeanModified $bean): void {
        $this->modifiedBeans[] = $bean;
    }

    public function hasModifiedBeans(): bool {
        return !empty($this->modifiedBeans);
    }

    public function resetTimestamp(): void {
        $this->timestamp = microtime(true);
    }

    public function isError(): bool {
        return $this->status === ResultStatus::ERROR;
    }

    public function isSkipped(): bool {
        return $this->status === ResultStatus::SKIPPED;
    }

    public function isOk(): bool {
        return $this->status === ResultStatus::OK;
    }

    public function isWait(): bool {
        return $this->status === ResultStatus::WAIT;
    }
}
