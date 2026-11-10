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
 * Abstract class representing the definition of a Data Provider action, which is responsible for providing dynamic data to the form.
 * It extends the base ActionDefinition and defines the specific method getData that must be implemented by concrete Data Provider actions.
 */
abstract class DataProviderActionDefinition extends ActionDefinition 
{
    final public function getType(): ActionType {
        return ActionType::DATAPROVIDER;
    }
    
    /** 
     * Returns dynamic data for the form 
     * @param FormActionParameter[] $params Parameters received for the action 
     * @return string[] The data obtained 
     */
    abstract public function getData(array $params): array;
}