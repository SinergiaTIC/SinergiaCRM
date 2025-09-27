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

interface stic_AWF_Scoped_ActionInterface {
    public const SCOPE_FORM = 'form';
    public const SCOPE_BLOCK = 'block';
    public const SCOPE_FIELD  = 'field';
    /**
     * Define a qué parte del formulario aplica la acción
     * @return string el Scope al que aplica
     */
    public function getScope(): string;

    /**
     * Si el scope es 'field', especifica los tipos de campo soportados.
     * Ej: ['varchar', 'text', 'int']
     * Retorna un array vacío si aplica a todos.
     * @return string[] Los tipos de campo soportados
     */
    public function getSupportedFieldTypes(): array;

    /**
     * Si el scope es 'block', especifica los módulos soportados.
     * Ej: ['Contacts', 'Leads']
     * Retorna un array vacío si aplica a todos.
     * @return string[] Los Módulos soportados
     */
    public function getSupportedModules(): array;
}