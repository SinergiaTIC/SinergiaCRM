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

class stic_AWF_LinksLogicHooks {
    
    public function translateParentType($bean, $event, $arguments): void {
        if (!empty($bean->parent_type)) {
            global $app_list_strings;
            $moduleListSingular = $app_list_strings['moduleListSingular']  ?? [];
            $moduleList = $app_list_strings['moduleList'] ?? [];
            
            $bean->parent_type_translated = $moduleListSingular[$bean->parent_type] ?? $moduleList[$bean->parent_type] ?? $bean->parent_type;
        }
    }
}