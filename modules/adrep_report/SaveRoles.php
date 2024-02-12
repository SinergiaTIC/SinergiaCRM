<?php
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
