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
global $app_list_strings, $db, $sugar_config;

//ini_set('display_errors','On');

$focus = new adrep_report();
$focus->retrieve($_REQUEST['record']);
$focus->retrieve_columns();

$smarty = new Sugar_Smarty();
$smarty->assign("TITLE",translate('LBL_MODULE_NAME','adrep_column'));
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

// Columns
$query_columns = $focus->get_columns_from_query();
$columns = array();
foreach ($query_columns as $col)
{
	if (isset($focus->columns[$col['id']]))
		$columns[$col['name']] = array('name' => $focus->columns[$col['id']]->name,
								'id' => $focus->columns[$col['id']]->id,
								'column_label' => $focus->columns[$col['id']]->column_label,
								'type' => $focus->columns[$col['id']]->type,
								'decimals' => $focus->columns[$col['id']]->decimals,
								'dropdown_name' => $focus->columns[$col['id']]->dropdown_name,
								'priority' => $focus->columns[$col['id']]->priority,
								'module' => $focus->columns[$col['id']]->linked_module_name,
								'width' => $focus->columns[$col['id']]->width,
								'default_value' => $focus->columns[$col['id']]->default_value,
								'total_type' => $focus->columns[$col['id']]->total_type,
							);
	else
		$columns[$col['name']] = array('name' => $col['name'],
								'id' => '',
								'column_label' => '',
								'type' => $col['type'],
								'decimals' => $col['decimals'],
								'dropdown_name' => '',
								'priority' => $col['priority'],
								'module' => $focus->primary_module,
								'Width' => 'Auto',
								'default_value' => '',
								'total_type' => 'blank',
							);
}

// Pad to adrep_max_columns/20 columns
if (isset($sugar_config['adrep_max_columns']))
	$adrep_max_columns = $sugar_config['adrep_max_columns'];
else
	$adrep_max_columns = 20;
$priority = count($columns);
while ($priority<$adrep_max_columns)
{
		$priority++;
		$columns["col_$priority"] = array('name' => "column_$priority",
								'id' => '',
								'column_label' => '',
								'type' => 'Text',
								'decimals' => 0,
								'dropdown_name' => '',
								'priority' => $priority,
								'default_value' => '',
								'editable' => 'yes',
							);
}
$smarty->assign("columns",$columns);

// Column names to make saving easier
$colnames = array();
foreach ($columns as $name => $col)
	$colnames[] = $name;
$smarty->assign("colnames",join(',',$colnames));

// dropdown lists
$dropdowns = array(''=>'');
foreach ($app_list_strings as $key => $value)
	$dropdowns[$key] = $key;
ksort($dropdowns);
$smarty->assign("dropdowns",$dropdowns);

// modules
$mods = array();
$keys = $app_list_strings['moduleList'];
asort($keys);
foreach ($keys as $key => $value)
	if (!empty($key))
		$mods[$key] = $value;
$smarty->assign("moduleList",$mods);

// Parameter Types
$type_list = array();
foreach ($app_list_strings['adrep_param_type_list'] as $key => $value)
	if (!empty($key))
		$type_list[$key] = $value;
$smarty->assign("types",$type_list);

// Sub/Total Types
$smarty->assign("total_types",$app_list_strings['adrep_subtotal_type_list']);
// Widths
$smarty->assign("width_list",$app_list_strings['adrep_width_list']);

// Parameter priorities
$priority_list = array(0=>'Hide');
$i=1;
while ($i <=20)
{
	$priority_list[$i] = $i;
	$i++;
}
$smarty->assign("priority_list",$priority_list);

// Date default values
$date_types = array('none'=>'None','today'=>'Today','yesterday'=>'Yesterday','tomorrow'=>'Tomorrow');
$smarty->assign("date_types",$date_types);

$smarty->display("modules/adrep_report/tpls/EditColumns.html");
