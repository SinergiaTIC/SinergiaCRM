<?php
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
