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

class FormFlow {
    private string $id;               // ID of the action flow
    private string $name;             // Internal name of the action flow
    private string $text;             // The text to display

    /** @var FormAction[] */
    private array $actions;           // The actions of the flow

    /**
     * Creates an instance of FormFlow from a JSON array.
     * @param array $data The data in array format
     * @return FormFlow The created instance
     */
    public static function fromJsonArray(array $data): self {
        $dto = new self();

        $dto->id = $data['id'];
        $dto->name = $data['name'];
        $dto->text = $data['text'];
        
        $dto->actions = [];
        if (isset($data['actions'])) {
            foreach ($data['actions'] as $actionData) {
                $formAction = FormAction::fromJsonArray($dto->id, $actionData);
                $dto->addAction($formAction);
            }
        }

        return $dto;
    }

    /**
     * Creates a virtual flow with a subset of actions from this flow.
     * This is the only supported way to build a flow with a custom action set
     * from outside the class construction process.
     * @param string $id The ID of the virtual flow
     * @param string $name The name of the virtual flow
     * @param string $text The text of the virtual flow
     * @param string[] $actionIds The IDs of the actions to include in the virtual flow
     * @return FormFlow The created virtual flow
     */
    public function createVirtual(string $id, string $name, string $text, array $actionIds): self {
        $dto = new self();
        $dto->id = $id;
        $dto->name = $name;
        $dto->text = $text;
        foreach ($actionIds as $actionId) {
            $action = $this->getActionById($actionId);
            if ($action !== null) {
                $dto->addAction($action);
            }
        }
        return $dto;
    }

    /**
     * Returns the actions of the flow.
     * @return FormAction[] The actions (copy by value)
     */
    public function getActions(): array {
        return $this->actions;
    }

    /**
     * Returns an action by its ID.
     * @param string $id The action ID
     * @return ?FormAction The action or null if not found
     */
    public function getActionById(string $id): ?FormAction {
        return $this->actions[$id] ?? null;
    }

    /**
     * Adds an action to the flow.
     * @param FormAction $action The action to add
     */
    private function addAction(FormAction $action): void {
        $this->actions[$action->getId()] = $action;
    }

    /**
     * Returns the flow ID.
     * @return string The flow ID
     */
    public function getId(): string {
        return $this->id;
    }

    /**
     * Returns the internal name of the flow.
     * @return string The flow name
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Returns the display text of the flow.
     * @return string The flow text
     */
    public function getText(): string {
        return $this->text;
    }
}