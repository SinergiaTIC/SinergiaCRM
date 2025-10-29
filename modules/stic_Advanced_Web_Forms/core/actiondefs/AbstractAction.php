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

enum ActionScope: string {
    case FORM  = 'form';
    case BLOCK = 'block';
    case FIELD = 'field';
}

abstract class AbstractAction {
    public bool $isPublic = true;
    public string $name = 'BaseAction';
    public string $label = 'Base Action';
    // @var string[]
    public array $tags = [];
    public ActionScope $scope = ActionScope::BLOCK;
    // @var string[]
    public array $supportedModules = [];       // moduleList
    // @var string[]
    public array $supportedFieldSubTypes = []; // stic_advanced_web_forms_field_in_form_subtype_list
    // @var ActionParameter[]
    public array $parameters = [];
    public int $order = 0;
}
