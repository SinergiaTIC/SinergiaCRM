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
require_once 'SticInclude/Views.php';

class CustomAOW_WorkFlowViewDetail extends ViewDetail
{
    public function preDisplay()
    {
        parent::preDisplay();

        SticViews::preDisplay($this);

        // Write here you custom code
    }

    public function display()
    {
        if ($this->bean->run_when == 'Never') {
            removeFromMultidimensionalArray($this->dv, 'flow_run_on');
        }
        parent::display();

        SticViews::display($this);

        echo getVersionedScript("custom/modules/AOW_WorkFlow/SticUtils.js");

        // Write here you custom code
    }
}

function removeFromMultidimensionalArray(&$array, $searchValue) {
    foreach ($array as $key => &$element) {
        if (is_array($element)) {
            // If the element is an array, recursively call the function
            removeFromMultidimensionalArray($element, $searchValue);

            // If the inner array is empty after removal, unset it as well
            if (empty($element)) {
                unset($array[$key]);
            }
        } else {
            // If the element is not an array, compare the value
            if ($element === $searchValue) {
                // Remove both the key and the value associated with "flow_run"
                unset($array[$key]);
            }
        }
    }
}
