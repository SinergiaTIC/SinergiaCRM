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
 * Clase para representar el resultado de una acción.
 */
class ActionResult {
    public function __construct(
        public ResultStatus $status,
        public ?SugarBean $bean = null,
        public ?string $message = null,
        public array $extra = []
    ) {}
}

/**
 * Clase para indicar un parámetro de una acción
 */
class ActionParameter {
    public function __construct(
        public string $name,
        public string $text,
        public string $type,
        public bool $required = false,
        public array $options = []
    ) {}
}

/**
 * Clase para indicar un objecto modificado por una acción
 */
class ModifiedBean {
    public function __construct(
        public string $id,
        public string $name,
        public string $module,
        public BeanModification $modificationType
    ) {}
}

/**
 * Clase para representar el resultado de un webhook.
 */
class WebhookResult {
    public function __construct(
        public ?string $externalTransactionId,
        public WebhookStatus $status,
        public ?string $message = null,
        public array $extraData = []
    ) {}
}