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
class stic_Custom_ViewsLogicHooks
{

    public function before_save(&$bean, $event, $arguments)
    {
        // Ensure name is correct
        global $app_list_strings;
        $bean->name = $app_list_strings['moduleList'][$bean->view_module] . ' - ' . 
                      $app_list_strings['stic_custom_views_views_list'][$bean->view_module_view] . ' - ' . 
                      $bean->view_name;

        // Update all related names
        include_once 'SticInclude/Utils.php';
        $relatedBeans = SticUtils::getRelatedBeanObjectArray($bean, 'stic_custom_views_stic_custom_view_customizations');
        foreach ($relatedBeans as $relatedBean) {
            // before_save LogicHook updates the name
            $relatedBean->save(); 
        }
    }

    public function after_save(&$bean, $event, $arguments)
    {
    }

}
