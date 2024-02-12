<?php
/*
 * The MIT License (MIT)
 * 
 * Copyright (c) 2018 Marnus van Niekerk, crm@mjvn.net
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
$layout_defs['adrep_report']['subpanel_setup']['subpanel_adrep_parameter'] = array(
			'order' => 100,
			'sort_order' => 'asc',
			'sort_by' => 'name',
			'module' => 'adrep_parameter',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'subpanel_adrep_parameter',
			//'add_subpanel_data' => 'subpanel_adrep_parameter',
			'title_key' => 'LBL_SUBPANEL_ADREP_REPORT2ADREP_PARAMETER',
			'top_buttons' => array(
				//array('widget_class' => 'SubPanelTopCreateButton'),
				//array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
			),
		);

$layout_defs['adrep_report']['subpanel_setup']['subpanel_adrep_column'] = array(
			'order' => 102,
			'sort_order' => 'asc',
			'sort_by' => 'name',
			'module' => 'adrep_column',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'subpanel_adrep_column',
			//'add_subpanel_data' => 'subpanel_adrep_column',
			'title_key' => 'LBL_SUBPANEL_ADREP_REPORT2ADREP_COLUMN',
			'top_buttons' => array(
				//array('widget_class' => 'SubPanelTopCreateButton'),
				//array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
			),
		);

$layout_defs['adrep_report']['subpanel_setup']['subpanel_adrep_role'] = array(
			'order' => 106,
			'sort_order' => 'asc',
			'sort_by' => 'name',
			'module' => 'adrep_role',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'subpanel_adrep_role',
			//'add_subpanel_data' => 'subpanel_adrep_role',
			'title_key' => 'LBL_SUBPANEL_ADREP_REPORT2ADREP_ROLE',
			'top_buttons' => array(
				//array('widget_class' => 'SubPanelTopCreateButton'),
				//array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
			),
		);

$layout_defs['adrep_report']['subpanel_setup']['subpanel_adrep_menu_link'] = array(
			'order' => 108,
			'sort_order' => 'asc',
			'sort_by' => 'name',
			'module' => 'adrep_menu_link',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'subpanel_adrep_menu_link',
			//'add_subpanel_data' => 'subpanel_adrep_menu_link',
			'title_key' => 'LBL_SUBPANEL_ADREP_REPORT2ADREP_MENU_LINK',
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopCreateButton'),
				//array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
			),
		);

$layout_defs['adrep_report']['subpanel_setup']['subpanel_adrep_chart'] = array(
			'order' => 104,
			'sort_order' => 'asc',
			'sort_by' => 'name',
			'module' => 'adrep_chart',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'subpanel_adrep_chart',
			//'add_subpanel_data' => 'subpanel_adrep_chart',
			'title_key' => 'LBL_SUBPANEL_ADREP_REPORT2ADREP_CHART',
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopCreateButton'),
				//array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
			),
		);
