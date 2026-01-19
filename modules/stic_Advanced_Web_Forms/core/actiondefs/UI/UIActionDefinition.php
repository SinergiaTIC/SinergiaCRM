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

abstract class UIActionDefinition extends ActionDefinition implements IFrontendAction
{
    final public function getType(): ActionType {
        return ActionType::UI;
    }

    /**
     * Devuelve los assets (CSS/JS) que deben inyectarse en el frontend para esta acción.
     * @param array $params Parámetros de la acción (sin resolver)
     * @param FormConfig|null $formConfig Configuración del formulario (si aplica)
     * @param string $formId ID del formulario (si aplica)
     * @return array array Estructura: ['script' => ['console.log("hi")'], 'css' => [], 'html' => []]
     */
    abstract public function getFrontendAssets(array $params, ?FormConfig $formConfig = null, string $formId = ''): array;
}   
