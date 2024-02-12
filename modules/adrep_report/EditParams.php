<?php
global $app_list_strings, $db;

//ini_set('display_errors','On');

$focus = new adrep_report();
$focus->retrieve($_REQUEST['record']);
$focus->retrieve_parameters();

$smarty = new Sugar_Smarty();
$smarty->assign("TITLE",translate('LBL_MODULE_NAME','adrep_parameter'));
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

// Parameters
$cnt = 0;
$parameters = array();
foreach ($focus->parameter_names as $name)
{
	$cnt++;
	if (isset($focus->parameters[$name]))
		$parameters[$name] = array('name' => $name,
								'id' => $focus->parameters[$name]->id,
								'parameter_label' => $focus->parameters[$name]->parameter_label,
								'type' => $focus->parameters[$name]->type,
								'decimals' => $focus->parameters[$name]->decimals,
								'dropdown_name' => $focus->parameters[$name]->dropdown_name,
								'priority' => $focus->parameters[$name]->priority,
								'default_value' => $focus->parameters[$name]->default_value,
								'default_date' => $focus->parameters[$name]->default_value,
							);
	else
		$parameters[$name] = array('name' => $name,
								'id' => '',
								'parameter_label' => $name,
								'type' => 'Text',
								'decimals' => 0,
								'dropdown_name' => '',
								'priority' => $cnt,
								'default_value' => '',
							);
}
$smarty->assign("parameters",$parameters);

// dropdown lists
$dropdowns = array(''=>'');
foreach ($app_list_strings as $key => $value)
	$dropdowns[$key] = $key;
ksort($dropdowns);
$smarty->assign("dropdowns",$dropdowns);

// Parameter Types
$type_list = array();
foreach ($app_list_strings['adrep_param_type_list'] as $key => $value)
	if (!empty($key))
		$type_list[$key] = $value;
$smarty->assign("types",$type_list);

// Parameter priorities
$priority_list = array();
$i=1;
while ($i <=20)
{
	$priority_list[$i] = $i;
	$i++;
}
$smarty->assign("priority_list",$priority_list);

// modules
$mods = array();
$keys = $app_list_strings['moduleList'];
asort($keys);
foreach ($keys as $key => $value)
	if (!empty($key))
		$mods[$key] = $value;
$smarty->assign("moduleList",$mods);

// Date default values
$date_types = array();
$dates = $focus->gen_dates();
foreach ($dates as $key => $value)
	$date_types[$key] = $key;
$smarty->assign("date_types",$date_types);

$smarty->display("modules/adrep_report/tpls/EditParams.html");
