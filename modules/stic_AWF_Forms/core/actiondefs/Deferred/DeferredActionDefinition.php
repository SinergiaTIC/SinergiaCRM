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
 * Abstract class representing a deferred action definition, which is a type of server action that is executed asynchronously after the form submission.
 * This class implements the IDeferredAction interface and extends the ServerActionDefinition class, 
 * providing a common structure for all deferred actions while enforcing the implementation of the getType method to return ActionType::
 */
abstract class DeferredActionDefinition extends ServerActionDefinition implements IDeferredAction {
    /**
     * Returns the type of the action, which is ActionType::DEFERRED for all classes extending DeferredActionDefinition.
     * This method is final to ensure that all deferred actions consistently return the correct type which is ActionType::DEFERRED.
     * @return ActionType The type of the action, which is ActionType::DEFERRED
     */
    final public function getType(): ActionType {
        return ActionType::DEFERRED;
    }
}