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
class stic_Organizational_EnvironmentLogicHooks
{
    public function before_save(&$bean, $event, $arguments)
    {
        if (empty($bean->name)) {
            $this->autoGenerateName($bean);
        }
    }

    private function autoGenerateName(&$bean)
    {
        global $app_list_strings;

        $networkName = '';
        if (!empty($bean->network_organization_id_c)) {
            $relatedBean = BeanFactory::getBean('Accounts', $bean->network_organization_id_c);
            $networkName = $relatedBean ? $relatedBean->name : '';
        } elseif (!empty($bean->network_person_id_c)) {
            $relatedBean = BeanFactory::getBean('Contacts', $bean->network_person_id_c);
            $networkName = $relatedBean ? $relatedBean->first_name . ' ' . $relatedBean->last_name : '';
        }

        $baseName = '';
        if (!empty($bean->base_organization_id_c)) {
            $relatedBean = BeanFactory::getBean('Accounts', $bean->base_organization_id_c);
            $baseName = $relatedBean ? $relatedBean->name : '';
        }

        $typeLabel = $app_list_strings['stic_organizational_environment_relationships_list'][$bean->relationship_type] ?? $bean->relationship_type;

        $bean->name = "$networkName - $typeLabel - $baseName";
    }
}