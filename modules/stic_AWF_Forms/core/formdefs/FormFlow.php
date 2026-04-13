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
    public FormConfig $form_config;  // The configuration of the form it belongs to

    public string $id;               // ID of the action flow
    public string $name;             // Internal name of the action flow
    public string $text;             // The text to display

    /** @var FormAction[] */
    public array $actions;           // The actions of the flow

    /**
     * Creates an instance of FormFlow from a JSON array.
     * @param FormConfig $form The configuration of the form it belongs to
     * @param array $data The data in array format
     * @return FormFlow The created instance
     */
    public static function fromJsonArray(FormConfig $form, array $data): self {
        $dto = new self();
        $dto->form_config = $form;

        $dto->id = $data['id'];
        $dto->name = $data['name'];
        $dto->text = $data['text'];
        
        $dto->actions = [];
        if (isset($data['actions'])) {
            foreach ($data['actions'] as $actionData) {
                $formAction = FormAction::fromJsonArray($dto, $actionData);
                $dto->actions[$formAction->id] = $formAction;
            }
        }

        return $dto;
    }
}