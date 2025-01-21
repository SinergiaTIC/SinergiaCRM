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
require_once "modules/adrep_report/adrep_report.php";
require_once "modules/adrep_column/adrep_column.php";

$focus = new adrep_report();
$focus->retrieve($_REQUEST['record']);
$focus->retrieve_columns();

$colnames = explode(',',$_REQUEST['colnames']);
$fields = array('name','decimals','dropdown_name','type',
				'linked_module_name','column_label','priority','total_type','width');

foreach ($colnames as $name)
{
	if (!empty($_REQUEST["column_label_".$name]))
	{
		$column = new adrep_column();
	
		if (!empty($_REQUEST["id_".$name]))	// Existing record
			$column->retrieve($_REQUEST["id_".$name]);
	
		foreach($fields as $field)
			if (isset($_REQUEST["{$field}_$name"]))
				$column->$field = $_REQUEST["{$field}_$name"];
	
		$column->report_id = $focus->id;
		$column->active_flag = 1;
	
		$column->save();
	}
}

if (isset($_REQUEST['new']) && $_REQUEST['new'] == 'yes' && $focus->permission == 'Roles')
	header("Location: index.php?module=adrep_report&action=EditRoles&record=$focus->id&new=yes");
else
	header("Location: index.php?module=adrep_report&action=DetailView&record=$focus->id");
die('redirect');
