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
 * Data Transfer Object (DTO) para la definición de una acción
 * Se enviará al Frontend para mostrar la configuración de la acción
 */
class ActionDefinitionDTO {
    public string $name;
    public string $title;
    public string $description;

    public bool $isActive;

    /** @var string[] */
    public array $tags;
    
    public string $scope; // 'form', 'block', 'field'
    
    /** @var string[] */
    public array $supportedModules;
    
    /** @var string[] */
    public array $supportedFieldSubTypes;
    
    public int $order;
    
    /** @var ActionParameterDefinitionDTO[] */
    public array $parameters;

    public function __construct(ActionDefinition $def) {
        $this->name = $def->getName();
        $this->title = $def->getTitle();
        $this->description = $def->getDescription();

        $this->isActive = $def->isActive;
        $this->tags = $def->tags;
        $this->scope = $def->scope->value; // Convert enum to string
        $this->supportedModules = $def->supportedModules;
        $this->supportedFieldSubTypes = $def->supportedFieldSubTypes;
        $this->order = $def->order;
        
        $this->parameters = array_map(
            fn($paramDef) => new ActionParameterDefinitionDTO($paramDef), 
            $def->getParameters()
        );
    }
}