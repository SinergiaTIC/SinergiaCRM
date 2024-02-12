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
ini_set('display_errors','On');
require_once "modules/adrep_report/adrep_report.php";
require_once "modules/adrep_role/adrep_role.php";

/*echo "<pre>\n";
print_r($_REQUEST);
echo "</pre>\n";
die('');*/

$focus = new adrep_report();
$focus->retrieve($_REQUEST['record']);

$role_ids = explode(',',$_REQUEST['role_ids']);
$role_names = explode(',',$_REQUEST['role_names']);

foreach ($role_ids as $key => $role_id)
{
	$role = new adrep_role();

	// check for existing
	$id = md5($focus->id . $role_id);
	$query = "select count(*) cnt FROM adrep_role WHERE id='$id'";	// primary key
	$res = $db->query($query);
	$row = $db->fetchByAssoc($res);
	if ($row['cnt'] > 0)
		$role->retrieve($id);
	else
	{
		$role->report_id = $focus->id;
		$role->role_id = $role_id;
	}
	$role->name = $role_names[$key];
	$role->allowed_flag = $_REQUEST["allowed_flag_$role_id"];

	$role->save();
}

header("Location: index.php?module=adrep_report&action=DetailView&record=$focus->id");
die('redirect');
