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
require_once('modules/adrep_menu_link/adrep_menu_link.php');

$focus = new adrep_menu_link();
$focus->retrieve($_REQUEST['record']);

$tmp = new adrep_report();
$tmp->retrieve($focus->report_id);
$tmp->retrieve_parameters();

// Now we manipulate the request
$_REQUEST['record'] = $focus->report_id;
$_REQUEST['action'] = 'RunReport';
$_REQUEST['module'] = 'adrep_report';

if (!empty($focus->id_parameter && $focus->id_parameter != 'na'))
{
	if (!isset($_REQUEST[$focus->id_parameter]))
		$_REQUEST[$focus->id_parameter] = '';
	$_REQUEST['name_'.$focus->id_parameter] = $tmp->get_record_name($_REQUEST[$focus->id_parameter],
																		$focus->menu_module);
}
if (!empty($focus->from_date_paramter && $focus->from_date_paramter != 'na'))
{
	$type = $tmp->parameters[$focus->from_date_paramter]->type;
	$_REQUEST[$focus->from_date_paramter] = $focus->date_ranges[$focus->date_range]["start$type"];
}
if (!empty($focus->to_date_parameter && $focus->to_date_parameter != 'na'))
{
	$type = $tmp->parameters[$focus->to_date_parameter]->type;
	$_REQUEST[$focus->to_date_parameter] = $focus->date_ranges[$focus->date_range]["end$type"];
}

if ($focus->run_flag == 1)
	$_REQUEST['run'] = 'yes';

// And run the report!
require "modules/adrep_report/RunReport.php";

