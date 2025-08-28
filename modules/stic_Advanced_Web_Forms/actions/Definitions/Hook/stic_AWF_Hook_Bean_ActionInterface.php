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

interface stic_AWF_Hook_Bean_ActionInterface 
  extends stic_AWF_Hook_ActionInterface {
    public const ONDUPLICATE_UPDATE = 'update';
    public const ONDUPLICATE_ENRICH = 'enrich';
    public const ONDUPLICATE_SKIP   = 'skip';
    public const ONDUPLICATE_ERROR  = 'error';
    

    /**
     * El nombre del módulo al cual se ejecuta la acción
     * @return string Nombre del módulo
     */
    public function getBaseModule(): string;

    /**
     * Beans creados o modificados que pueden ser reutilizados
     * @return stic_AWF_ActionModifiedBean[]
     */
    public function getModifiedBeans(): array;
}
