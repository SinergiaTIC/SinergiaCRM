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

class FormAction {
    private string $flow_id;          // ID of the action flow it belongs to

    private string $id;               // ID of the action
    private string $name;             // Name of the action
    private string $text;             // The text to display
    private string $description;      // The description of the action
    /** @var string[] */
    private array $requisite_actions; // Array with the identifiers of the actions prior to the current one
    /** @var FormActionParameter[] */
    private array $parameters;        // The parameters of the action

    private array $resolvedParameters = []; // Resolved parameters, with the final value

    /** @var FormCondition[] */
    private array $conditions = [];        // Conditions to execute the validation (all must be accomplished)

    private bool $continue_on_error = false; // Indicates if the flow should continue if this action fails (throws an exception or returns an error result)

    // For deferred actions
    private ?string $flow_success_id = null; // Flow to execute if the deferred action returns successfully
    private ?string $flow_error_id = null;   // Flow to execute if the deferred action returns with an error


    /**
     * Creates an instance of FormAction from a JSON array.
     * @param string $flowId The ID of the action flow it belongs to
     * @param array $data The data in array format
     * @return FormAction The created instance
     */
    public static function fromJsonArray(string $flowId, array $data): self {
        $dto = new self();
        $dto->flow_id = $flowId;

        $dto->id = $data['id'];
        $dto->name = $data['name'];
        $dto->text = $data['text'];
        $dto->description = $data['description'];
        $dto->requisite_actions = $data['requisite_actions'] ?? [];
        $dto->continue_on_error = !empty($data['continue_on_error']);
        
        // Deferred actions
        $dto->flow_success_id = $data['flow_success_id'] ?? '';
        $dto->flow_error_id = $data['flow_error_id'] ?? '';

        // Condition
        if (isset($data['conditions'])) {
            foreach ($data['conditions'] as $conditionData) {
                $dto->conditions[] = FormCondition::fromJsonArray($conditionData);
            }
        }

        $dto->parameters = [];
        if (isset($data['parameters'])) {
            foreach ($data['parameters'] as $parameterData) {
                $formActionParameter = FormActionParameter::fromJsonArray($dto, $parameterData);
                $dto->parameters[$formActionParameter->name] = $formActionParameter;
            }
        }

        return $dto;
    }

    /**
     * Method to save the resolved parameters
     * @param array $params [name => resolved_value]
     */
    public function setResolvedParameters(array $params): void {
        $this->resolvedParameters = $params;
    }

    /**
     * Method for actions to obtain the resolved values
     * @param string $name The name of the parameter
     * @param mixed $default A default value if not found
     * @return mixed The resolved value
     */
    public function getResolvedParameter(string $name, mixed $default = null): mixed { 
        return $this->resolvedParameters[$name] ?? $default;
    }

    /**
     * Returns the action ID.
     * @return string The action ID
     */
    public function getId(): string {
        return $this->id;
    }

    /**
     * Returns the ID of the action flow this action belongs to.
     * @return string The flow ID
     */
    public function getFlowId(): string {
        return $this->flow_id;
    }

    /**
     * Returns the action internal name.
     * @return string The action name
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Returns the action display text.
     * @return string The action text
     */
    public function getText(): string {
        return $this->text;
    }

    /**
     * Returns the action description.
     * @return string The action description
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * Returns the identifiers of the prerequisite actions.
     * @return string[] The prerequisite action IDs
     */
    public function getRequisiteActions(): array {
        return $this->requisite_actions;
    }

    /**
     * Returns the parameters of the action.
     * @return FormActionParameter[] The action parameters
     */
    public function getParameters(): array {
        return $this->parameters;
    }

    /**
     * Returns the conditions to execute the action.
     * @return FormCondition[] The action conditions
     */
    public function getConditions(): array {
        return $this->conditions;
    }

    /**
     * Returns whether the flow should continue if this action fails.
     * @return bool True if the flow should continue on error
     */
    public function getContinueOnError(): bool {
        return $this->continue_on_error;
    }

    /**
     * Returns the ID of the flow to execute if the deferred action succeeds.
     * @return ?string The success flow ID
     */
    public function getFlowSuccessId(): ?string {
        return $this->flow_success_id;
    }

    /**
     * Returns the ID of the flow to execute if the deferred action fails.
     * @return ?string The error flow ID
     */
    public function getFlowErrorId(): ?string {
        return $this->flow_error_id;
    }
}