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

// Create (and retrieve if needed) the focus object
$new_record = false;
$focus = new adrep_report();
if (!empty($_REQUEST['record']))
	$focus->retrieve($_REQUEST['record']);
else
	$new_record = true;

// Populate with values from the form
foreach ($_REQUEST as $key => $value)
	if (isset($focus->field_defs[$key]) && $focus->$key != $value)
		$focus->$key = $value;

// And save it
$focus->save();
$focus->retrieve();

// Now go to EditParams for a new report and back to DetailView for an existing one
if ($new_record)
	header("Location: index.php?module=adrep_report&action=EditParams&record=$focus->id&new=yes");
elseif ($focus->permission == 'Roles')
	header("Location: index.php?module=adrep_report&action=EditRoles&record=$focus->id");
else
	header("Location: index.php?module=adrep_report&action=DetailView&record=$focus->id");
die('redirect');
