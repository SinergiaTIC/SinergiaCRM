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

require_once 'modules/AOS_PDF_Templates/views/view.edit.php';
require_once 'SticInclude/Views.php';
class CustomAOS_PDF_TemplatesViewEdit extends AOS_PDF_TemplatesViewEdit
{
    public function __construct()
    {
        parent::__construct();
        $this->useForSubpanel = true;
        $this->useModuleQuickCreateTemplate = true;
        // SuiteCRM modules use singular form for bean names. Plural form is set in SticViews class in order to load the language files

        $this->moduleName = 'AOS_PDF_Templates';
    }

    public function preDisplay()
    {
        parent::preDisplay();

        SticViews::preDisplay($this);

        // Write here you custom code
    }

    public function display()
    {
        global $app_list_strings, $beanList;

        $modules = $app_list_strings['pdf_template_type_dom'];
        require_once 'modules/AOS_PDF_Templates/templateParser.php';
        
        $subpanelFields = array();
        
        foreach ($modules as $moduleName => $value) {
            if (empty($beanList[$moduleName]) || !class_exists($beanList[$moduleName])) {
                continue;
            }
            
            $module = BeanFactory::getBean($moduleName);
            if (!$module) {
                continue;
            }
            
            $subpanels = templateParser::getSubpanelRelationships($module);
            
            if (!empty($subpanels)) {
                $subpanelFields[$moduleName] = array(
                    'module' => $module->module_dir,
                    'subpanels' => $subpanels
                );
            }
        }
        
        $subpanelFieldsJson = json_encode($subpanelFields, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        
        $subpanelHtml = '<div class="row">';
        $subpanelHtml .= '<div class="col-xs-12 col-sm-4">';
        $subpanelHtml .= '<label>' . translate('LBL_SUBPANEL_TYPE', 'AOS_PDF_Templates') . '</label>';
        $subpanelHtml .= '<select id="subpanel_name" class="form-control" onchange="populateSubpanelFields()">';
        $subpanelHtml .= '<option value="">-- ' . translate('LBL_SELECT', 'AOS_PDF_Templates') . ' --</option>';
        $subpanelHtml .= '</select>';
        $subpanelHtml .= '</div>';
        
        $subpanelHtml .= '<div class="col-xs-12 col-sm-4">';
        $subpanelHtml .= '<label>' . translate('LBL_SUBPANEL_FIELD', 'AOS_PDF_Templates') . '</label>';
        $subpanelHtml .= '<select id="subpanel_field_name" class="form-control">';
        $subpanelHtml .= '<option value="">-- ' . translate('LBL_SELECT', 'AOS_PDF_Templates') . ' --</option>';
        $subpanelHtml .= '</select>';
        $subpanelHtml .= '</div>';
        
        $subpanelHtml .= '<div class="col-xs-12 col-sm-4" style="margin-top: 24px;">';
        $subpanelHtml .= '<button type="button" class="btn btn-primary" onclick="insertSubpanelLoop()">' . translate('LBL_INSERT_SUBPANEL_LOOP', 'AOS_PDF_Templates') . '</button>';
        $subpanelHtml .= '<button type="button" class="btn btn-default" style="margin-left: 5px;" onclick="insertSubpanelField()">' . translate('LBL_INSERT_FIELD', 'AOS_PDF_Templates') . '</button>';
        $subpanelHtml .= '</div>';
        $subpanelHtml .= '</div>';
        
        $this->ss->assign('SUBPANEL_FIELDS', $subpanelHtml);
        
        parent::display();
        SticViews::display($this);
        
        echo '<script type="text/javascript">var subpanelModuleOptions = ' . $subpanelFieldsJson . ';</script>';
        echo getVersionedScript("custom/modules/AOS_PDF_Templates/SticUtils.js");
    }
}
