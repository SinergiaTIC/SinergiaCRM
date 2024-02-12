<?php
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
