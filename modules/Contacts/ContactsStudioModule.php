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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'modules/ModuleBuilder/Module/StudioModule.php';

class ContactsStudioModule extends StudioModule
{
    public function __construct($module)
    {
        parent::__construct($module);

        // Register the summary view so it can be edited from Studio
        $this->sources['summaryviewdefs.php'] = array(
            'name' => translate('LBL_SUMMARYVIEW'),
            'type' => MB_DETAILVIEW,
            'image' => 'SummaryView',
            'view' => 'summaryview',
        );
    }

    public function getLayouts()
    {
        $layouts = parent::getLayouts();

        // Use the DetailView icon for the summary view (no dedicated CSS glyph)
        foreach ($layouts as $key => $layout) {
            if (isset($layout['icon']) && strtolower($layout['icon']) === 'summaryview') {
                $layouts[$key]['icon'] = 'detailview';
            }
        }

        return $layouts;
    }
}