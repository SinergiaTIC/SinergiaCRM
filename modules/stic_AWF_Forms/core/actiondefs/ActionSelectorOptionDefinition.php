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


/**
 * Definition of an option for a parameter of type option selector.
 */
class ActionSelectorOptionDefinition {
    public string $name;                       // Internal name of the option
    public string $text;                       // The text to display
    public ActionParameterType $resolvedType;  // Resolved parameter type for this option

    /** @var string[] */
    public array $supportedModules = [];       // Modules supported by this option if the destination is a CRM record

    /** @var ActionDataType[] */
    public array $supportedDataTypes = [];     // List of data types allowed by this action, if applicable

    public ?ActionDataType $resolvedDataType;  // The resolved data type for parameters resolved to VALUE
}

