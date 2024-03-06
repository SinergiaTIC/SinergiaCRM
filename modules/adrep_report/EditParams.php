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
$smarty->assign("PARAMETER",translate('LBL_PARAMETER','adrep_parameter'));
$smarty->assign("LABEL",translate('LBL_COLUMN_LABEL','adrep_parameter'));
$smarty->assign("TYPE",translate('LBL_TYPE','adrep_parameter'));
$smarty->assign("PRIORITY",translate('LBL_PRIORITY','adrep_parameter'));
$smarty->assign("DEFAULT_VALUE",translate('LBL_DEFAULT_VALUE','adrep_parameter'));
$smarty->assign("DROPDOWN_NAME",translate('LBL_DROPDOWN_NAME','adrep_parameter'));
$smarty->assign("PRECISION",translate('LBL_DECIMALS','adrep_parameter'));

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
