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
class stic_Custom_View_CustomizationsLogicHooks
{

    public function before_save(&$bean, $event, $arguments)
    {
        // Ensure name is correct
        include_once 'SticInclude/Utils.php';
        $customViewBean = SticUtils::getRelatedBeanObject($bean, 'stic_custom_views_stic_custom_view_customizations');
        $bean->name = $customViewBean->name . ' - ' . $bean->customization_name;

        // Initial Configuration: Order always 0
        if ($bean->default == 1) {
            $bean->order = 0;
        } else {
            // Order must be >0
            if($bean->order<=0) {
                $bean->order = 1;
            }
        }

        // Ensure order is not set or change others
        $customizationBeanArray = SticUtils::getRelatedBeanObjectArray($customViewBean, 'stic_custom_views_stic_custom_view_customizations');
        foreach ($customizationBeanArray as $customizationBean) {
            if ($customizationBean->id != $bean->id && 
                $customizationBean->default == $bean->default &&
                $customizationBean->order == $bean->order) {
                    $customizationBean->order = $customizationBean->order + 1;
                    $customizationBean->save();
                }
        }
    }

    public function after_save(&$bean, $event, $arguments)
    {
    }

}
