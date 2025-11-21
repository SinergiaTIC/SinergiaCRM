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
 * Referencia a un bean del CRM
 */
class BeanReference {
    public string $moduleName;
    public string $beanId;
    private ?SugarBean $_bean = null;

    /**
     * Constructor de BeanReference
     * @param string $moduleName Nombre del módulo del bean
     * @param string $beanId ID del bean
     */
    public function __construct(string $moduleName, string $beanId) {
        $this->moduleName = $moduleName;
        $this->beanId = $beanId;
    }

    /**
     * Recupera el bean referenciado
     * @return SugarBean|null El bean referenciado o null si no se encuentra
     */
    public function getBean(): ?SugarBean {
        if ($this->_bean !== null) {
            return $this->_bean;
        }

        if (!empty($this->moduleName) && !empty($this->beanId)) {
            $this->_bean = BeanFactory::retrieveBean($this->moduleName, $this->beanId);
        }

        return $this->_bean;
    }
}