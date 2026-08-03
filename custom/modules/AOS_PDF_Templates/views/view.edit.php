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
        
        $lb = function($key) { return translate($key, 'AOS_PDF_Templates'); };
        $sel = '-- ' . $lb('LBL_SELECT') . ' --';
        
        $subpanelHtml = '';
        
        // Row 1: Subpanel + Field selectors
        $subpanelHtml .= '<div class="row">';
        $subpanelHtml .= '<div class="col-xs-12 col-sm-4">';
        $subpanelHtml .= '<label>' . $lb('LBL_SUBPANEL_TYPE') . '</label>';
        $subpanelHtml .= '<select id="subpanel_name" class="form-control" onchange="populateSubpanelFields()">';
        $subpanelHtml .= '<option value="">' . $sel . '</option></select></div>';
        
        $subpanelHtml .= '<div class="col-xs-12 col-sm-4">';
        $subpanelHtml .= '<label>' . $lb('LBL_SUBPANEL_FIELD') . '</label>';
        $subpanelHtml .= '<select id="subpanel_field_name" class="form-control">';
        $subpanelHtml .= '<option value="">' . $sel . '</option></select></div>';
        
        // Buttons
        $subpanelHtml .= '<div class="col-xs-12 col-sm-4" style="margin-top:24px;">';
        $subpanelHtml .= '<button type="button" class="btn btn-primary" onclick="insertSubpanelLoop()">' . $lb('LBL_INSERT_SUBPANEL_LOOP') . '</button> ';
        $subpanelHtml .= '<button type="button" class="btn btn-default" onclick="insertSubpanelField()">' . $lb('LBL_INSERT_FIELD') . '</button> ';
        $subpanelHtml .= '<button type="button" class="btn btn-default" onclick="insertAggregateField()">' . $lb('LBL_INSERT_AGGREGATE') . '</button>';
        $subpanelHtml .= '</div></div>';
        
        // Row 2: Aggregate function
        $subpanelHtml .= '<div class="row" style="margin-top:8px;">';
        $subpanelHtml .= '<div class="col-xs-12 col-sm-4">';
        $subpanelHtml .= '<label>' . $lb('LBL_SUBPANEL_AGGREGATE') . '</label>';
        $subpanelHtml .= '<select id="subpanel_agg_func" class="form-control">';
        $subpanelHtml .= '<option value="">' . $sel . '</option>';
        $subpanelHtml .= '<option value="SUM">' . $lb('LBL_SUBPANEL_AGG_SUM') . '</option>';
        $subpanelHtml .= '<option value="COUNT">' . $lb('LBL_SUBPANEL_AGG_COUNT') . '</option>';
        $subpanelHtml .= '<option value="AVG">' . $lb('LBL_SUBPANEL_AGG_AVG') . '</option>';
        $subpanelHtml .= '<option value="MIN">' . $lb('LBL_SUBPANEL_AGG_MIN') . '</option>';
        $subpanelHtml .= '<option value="MAX">' . $lb('LBL_SUBPANEL_AGG_MAX') . '</option>';
        $subpanelHtml .= '</select></div></div>';
        
        // Row 3: Order by + Direction + Limit
        $subpanelHtml .= '<div class="row" style="margin-top:8px;">';
        $subpanelHtml .= '<div class="col-xs-12 col-sm-4">';
        $subpanelHtml .= '<label>' . $lb('LBL_SUBPANEL_ORDER') . '</label>';
        $subpanelHtml .= '<select id="subpanel_order_field" class="form-control">';
        $subpanelHtml .= '<option value="">' . $sel . '</option></select></div>';
        
        $subpanelHtml .= '<div class="col-xs-6 col-sm-2">';
        $subpanelHtml .= '<label>' . $lb('LBL_SUBPANEL_DIR') . '</label>';
        $subpanelHtml .= '<select id="subpanel_order_dir" class="form-control">';
        $subpanelHtml .= '<option value="ASC">' . $lb('LBL_SUBPANEL_ASC') . '</option>';
        $subpanelHtml .= '<option value="DESC">' . $lb('LBL_SUBPANEL_DESC') . '</option>';
        $subpanelHtml .= '</select></div>';
        
        $subpanelHtml .= '<div class="col-xs-6 col-sm-2">';
        $subpanelHtml .= '<label>' . $lb('LBL_SUBPANEL_LIMIT') . '</label>';
        $subpanelHtml .= '<input type="number" id="subpanel_limit" class="form-control" min="1" value="" placeholder="100">';
        $subpanelHtml .= '</div></div>';
        
        // Row 4: Filter controls
        $subpanelHtml .= '<div class="row" style="margin-top:8px;">';
        $subpanelHtml .= '<div class="col-xs-12 col-sm-3">';
        $subpanelHtml .= '<label>' . $lb('LBL_SUBPANEL_FILTER_FIELD') . '</label>';
        $subpanelHtml .= '<select id="subpanel_filter_field" class="form-control">';
        $subpanelHtml .= '<option value="">' . $sel . '</option></select></div>';
        
        $subpanelHtml .= '<div class="col-xs-12 col-sm-2">';
        $subpanelHtml .= '<label>' . $lb('LBL_SUBPANEL_FILTER_OP') . '</label>';
        $subpanelHtml .= '<select id="subpanel_filter_op" class="form-control">';
        $subpanelHtml .= '<option value="eq">= (' . $lb('LBL_SUBPANEL_FILTER_EQ') . ')</option>';
        $subpanelHtml .= '<option value="neq">!= (' . $lb('LBL_SUBPANEL_FILTER_NEQ') . ')</option>';
        $subpanelHtml .= '<option value="gt">&gt; (' . $lb('LBL_SUBPANEL_FILTER_GT') . ')</option>';
        $subpanelHtml .= '<option value="gte">&gt;= (' . $lb('LBL_SUBPANEL_FILTER_GTE') . ')</option>';
        $subpanelHtml .= '<option value="lt">&lt; (' . $lb('LBL_SUBPANEL_FILTER_LT') . ')</option>';
        $subpanelHtml .= '<option value="lte">&lt;= (' . $lb('LBL_SUBPANEL_FILTER_LTE') . ')</option>';
        $subpanelHtml .= '<option value="like">~ (' . $lb('LBL_SUBPANEL_FILTER_LIKE') . ')</option>';
        $subpanelHtml .= '<option value="in">IN (' . $lb('LBL_SUBPANEL_FILTER_IN') . ')</option>';
        $subpanelHtml .= '</select></div>';
        
        $subpanelHtml .= '<div class="col-xs-12 col-sm-3">';
        $subpanelHtml .= '<label>' . $lb('LBL_SUBPANEL_FILTER_VALUE') . '</label>';
        $subpanelHtml .= '<input type="text" id="subpanel_filter_value" class="form-control" placeholder="">';
        $subpanelHtml .= '</div>';
        
        $subpanelHtml .= '<div class="col-xs-12 col-sm-2" style="margin-top:24px;">';
        $subpanelHtml .= '<button type="button" class="btn btn-default" onclick="addSubpanelFilter()">' . $lb('LBL_SUBPANEL_ADD_FILTER') . '</button>';
        $subpanelHtml .= '</div></div>';
        
        // Active filters display
        $subpanelHtml .= '<div class="row" style="margin-top:4px;">';
        $subpanelHtml .= '<div class="col-xs-12"><div id="subpanel_active_filters"></div></div>';
        $subpanelHtml .= '</div>';
        
        $this->ss->assign('SUBPANEL_FIELDS', $subpanelHtml);
        
        parent::display();
        SticViews::display($this);
        
        echo '<script type="text/javascript">var subpanelModuleOptions = ' . $subpanelFieldsJson . ';</script>';
        echo getVersionedScript("custom/modules/AOS_PDF_Templates/SticUtils.js");
    }
}
