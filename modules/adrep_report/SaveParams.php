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
require_once "modules/adrep_parameter/adrep_parameter.php";

$focus = new adrep_report();
$focus->retrieve($_REQUEST['record']);
$focus->get_parameter_names();

$fields = array('name','decimals','dropdown_name','type'
				,'default_value','default_date','parameter_label','priority','link_module_name');

foreach ($focus->parameter_names as $name)
{
	$param = new adrep_parameter();

	if (!empty($_REQUEST["id_".$name]))	// Existing record
		$param->retrieve($_REQUEST["id_".$name]);

	foreach($fields as $field)
		if (isset($_REQUEST["{$field}_$name"]))
			$param->$field = $_REQUEST["{$field}_$name"];

	if ($param->type == 'DateTime' ||$param->type == 'Date')
		$param->default_value = $param->default_date;

	if ($param->type == 'ModuleLink')
		$param->dropdown_name = $param->link_module_name;

	$param->report_id = $focus->id;
	$param->active_flag = 1;

	$param->save();
}

if (isset($_REQUEST['new']) && $_REQUEST['new'] == 'yes')
	header("Location: index.php?module=adrep_report&action=EditColumns&record=$focus->id&new=yes");
else
	header("Location: index.php?module=adrep_report&action=DetailView&record=$focus->id");
die('redirect');
