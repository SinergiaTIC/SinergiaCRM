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

enum ActionParameterType: string {
    case VALUE       = 'value';
    case DATA_BLOCK  = 'dataBlock';
    case FIELD       = 'field';
}

enum ActionDataType: string {
    // Primive types
    case TEXT          = 'text';
    case INTEGER       = 'integer';
    case FLOAT         = 'float';
    case BOOLEAN       = 'boolean';

    // Date types
    case DATE          = 'date';
    case DATETIME      = 'datetime';
    case TIME          = 'time';

    // Relative dates
    case RELATIVE_DATE = 'relative_date';   // Para aceptar fechas relativas como "today", "yesterday", "tomorrow", etc.
    
    // Specific formats
    case EMAIL         = 'email';
    case PHONE         = 'phone';
     
    // References to specific objects
    case TARGET_LIST   = 'target_list';     // Permite seleccionar una LPO
    case TEMPLATE      = 'email_template';  // Permite seleccionar una plantilla de email
}

/**
 * Clase para definir un parámetro de una acción
 */
class ActionParameterDefinition {
    public string $name;                // Nombre del Parámetro
    public string $text;                // El texto a mostrar
    public ActionParameterType $type;   // El tipo de parámetro
    public ActionDataType $dataType;    // El tipo de dato del parámetro: Obligado si $type es VALUE
    public bool $required = true;       // Indica si el parámetro es obligatorio
    public string $defaultValue = '';   // Valor por defecto del parámetro
    public string $helpText = '';       // Texto de ayuda para el parámetro
}

