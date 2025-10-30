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

enum ResultStatus: string {
    case OK      = 'ok';
    case SKIPPED = 'skipped';
    case ERROR   = 'error';
}

/**
 * Clase para representar el resultado de una acción.
 */
class ActionResult {
    public ResultStatus $status;        // Estado del resultado
    public ?string $message;            // Mensaje adicional del resultado
    /** @var BeanModified[] */
    public array $modifiedBeans;        // Beans modificados por la acción

    public float $timestamp;            // Marca temporal de la ejecución
    public ?FormAction $actionConfig;   // Configuración de la acción ejecutada

    public function __construct(ResultStatus $status, ?FormAction $actionConfig, ?string $message = null) {
        $this->status = $status;
        $this->actionConfig = $actionConfig;
        $this->message = $message;
        $this->modifiedBeans = [];
        $this->timestamp = microtime(true);
    }

    public function addModifiedBean(BeanModified $bean): void {
        $this->modifiedBeans[] = $bean;
    }

    public function hasModifiedBeans(): bool {
        return !empty($this->modifiedBeans);
    }

    public function resetTimestamp(): void {
        $this->timestamp = microtime(true);
    }

    public function isError(): bool {
        return $this->status === ResultStatus::ERROR;
    }

    public function isSkipped(): bool {
        return $this->status === ResultStatus::SKIPPED;
    }

    public function isOk(): bool {
        return $this->status === ResultStatus::OK;
    }

}
