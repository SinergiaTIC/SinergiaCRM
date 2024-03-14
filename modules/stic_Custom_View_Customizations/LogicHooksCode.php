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
        require_once 'modules/stic_Custom_Views/Utils.php';

        // customization_order must be >0
        if($bean->customization_order<=0) {
            $bean->customization_order = 1;
        }
        
        // Ensure customization_order is not set or change others
        $customViewBean = getRelatedBeanObject($bean, 'stic_custom_views_stic_custom_view_customizations');
        if($customViewBean) {
            $customizationBeanArray = getRelatedBeanObjectArray($customViewBean, 'stic_custom_views_stic_custom_view_customizations');
            foreach ($customizationBeanArray as $customizationBean) {
                if ($customizationBean->id != $bean->id && 
                    $customizationBean->deleted == "0" &&
                    $customizationBean->customization_order == $bean->customization_order) {
                        $customizationBean->customization_order = $customizationBean->customization_order + 1;
                        $customizationBean->save();
                    }
            }
        }
    }

    public function after_save(&$bean, $event, $arguments)
    {
    }

}
