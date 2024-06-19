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
require_once('modules/adrep_report/adrep_report.php');
require_once('modules/adrep_menu_link/adrep_menu_link_sugar.php');

class adrep_menu_link extends adrep_menu_link_sugar
{
	function __construct()
	{
		global $app_list_strings, $db;

		parent::__construct();

		if ($_REQUEST['action'] == 'EditView' && $_REQUEST['module'] == 'adrep_menu_link')
		{
			
			// Modify passed variables
			if (isset($_REQUEST['adrep_report_id']))
			{
				$_REQUEST['report_id'] = $_REQUEST['adrep_report_id'];
				$_REQUEST['report_name'] = $_REQUEST['adrep_report_name'];
			}

			if (isset($_REQUEST['report_id']) && !empty($_REQUEST['report_id']))
				$this->populate_lists($_REQUEST['report_id']);
		}
	}

	function retrieve($id = -1, $encode = true, $deleted = true)
	{
		global $current_user, $timedate;

		$retval = parent::retrieve($id, $encode, $deleted);

		if ($retval)
				$this->populate_lists($this->report_id);

		return $retval;
	}

	function populate_lists($record)
	{
		global $app_list_strings, $db;

		// Create default empty lists
		/* $app_list_strings['adrep_date_range_list'] = array('na'=>'N/A');
		$app_list_strings['adrep_id_parameter_list'] = array('na'=>'N/A');
		$app_list_strings['adrep_date_parameter_list'] = array('na'=>'N/A');
		$app_list_strings['adrep_menu_view_list'] = array('ListView'=>'List View',
															'DetailView'=>'Detail View'); */

		// Populate lists of a record was passed
		$query = "SELECT id,name,type,parameter_label FROM adrep_parameter WHERE report_id='$record' AND deleted=0 AND active_flag=1 ORDER BY name";
		$res = $db->query($query);
		while ($row = $db->fetchByAssoc($res))
		{
			if ($row['type'] == 'ModuleLink')
				$app_list_strings['adrep_id_parameter_list'][$row['name']] =
																$row['parameter_label'];
			elseif ($row['type'] == 'Date' || $row['type'] == 'DateTime')
				$app_list_strings['adrep_date_parameter_list'][$row['name']] =
																$row['parameter_label'];
		}

		// List of date ranges
		$tmp = new adrep_report();
		$this->date_ranges = $tmp->gen_date_ranges();
		foreach ($this->date_ranges as $key => $range)
			$app_list_strings['adrep_date_range_list'][$key] = $key;
	}
}
