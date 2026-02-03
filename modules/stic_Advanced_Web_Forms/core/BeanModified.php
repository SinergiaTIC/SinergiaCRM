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

enum BeanModificationType: string {
    case CREATED  = 'created';
    case UPDATED  = 'updated';
    case ENRICHED = 'enriched';
    case SKIPPED  = 'skipped';
}

/**
 * Class to represent a Bean modified by an action
 */
class BeanModified extends BeanReference {
    public BeanModificationType $modificationType;

    /** @var array Submitted data [field_name => value] that were applied */
    public array $submittedData; 

    /** BeanModified constructor
     * @param string $id The modified bean ID
     * @param string $module The modified bean module
     * @param BeanModificationType $modificationType The modification type
     * @param array $submittedData The data that was applied
    */
    public function __construct(string $id, string $module, BeanModificationType $modificationType, array $submittedData = []) {
        parent::__construct($module, $id);
        $this->modificationType = $modificationType;
        $this->submittedData = $submittedData;
    }
}