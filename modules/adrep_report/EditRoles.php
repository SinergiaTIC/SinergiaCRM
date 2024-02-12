<?php
global $app_list_strings, $db;

//ini_set('display_errors','On');

$focus = new adrep_report();
$focus->retrieve($_REQUEST['record']);

$roles = array();
$role_names = array();
$query = "SELECT r.id role_id,r.name role_name,a.id adrep_role_id,r.name,if(a.allowed_flag is null,0,a.allowed_flag) 'allowed_flag'
FROM acl_roles r
LEFT JOIN adrep_role a ON r.id=a.role_id AND a.report_id='$focus->id'
WHERE r.deleted=0 ORDER BY r.name";
$res = $db->query($query);
while ($row = $db->fetchByAssoc($res))
{
	$roles[$row['role_id']] = $row;
	$role_names[] = $row['role_name'];
}

$smarty = new Sugar_Smarty();
$smarty->assign("TITLE",translate('LBL_MODULE_NAME','adrep_role'));
$smarty->assign("FOR",translate('LBL_FOR','adrep_report'));
$smarty->assign("FOCUS_NAME",$focus->name);
$smarty->assign("record",$focus->id);
if (empty($_REQUEST['new']))
	$smarty->assign("LBL_SAVE",translate('LBL_SAVE','adrep_report'));
else
{
	$smarty->assign("LBL_SAVE",translate('LBL_NEXT','adrep_report'));
	$smarty->assign("new","yes");
}

// Roles
$smarty->assign("roles",$roles);

// Better than a checkbox
$yesno = array(0=>'No',1=>'Yes');
$smarty->assign("yesno",$yesno);

// ID's and names to make saving easier
$smarty->assign("role_ids",join(',',array_keys($roles)));
$smarty->assign("role_names",join(',',$role_names));

$smarty->display("modules/adrep_report/tpls/EditRoles.html");
