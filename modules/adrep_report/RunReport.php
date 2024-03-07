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
$smarty->assign("TITLE",translate('LBL_RUNNING','adrep_report'));
$smarty->assign("FOCUS_NAME",$focus->name);
$smarty->assign("record",$focus->id);
$smarty->assign("LBL_RUN_REPORT",translate('LBL_RUN_NOW','adrep_report'));
$smarty->assign("date_format",$focus->date_format);

if (isset($_REQUEST['return_module']))
	$smarty->assign('return_module',$_REQUEST['return_module']);
if (isset($_REQUEST['return_record']))
	$smarty->assign('return_record',$_REQUEST['return_record']);
if (isset($_REQUEST['return_action']))
	$smarty->assign('return_action',$_REQUEST['return_action']);

// Hours
$hours = array();
$i = 0;
$max_hour = (stristr($focus->date_time_format,'a')) ? 12 : 23;
while ($i <= $max_hour)
{
	$hour = sprintf("%02d",$i);
	$hours[$hour] = $hour;
	$i++;
}

// Minutes
$mins = array();
$i = 0;
while ($i <= 59)
{
	$min = sprintf("%02d",$i);
	$mins[$min] = $min;
	$i++;
}

$smarty->assign("hours",$hours);
$smarty->assign("minutes",$mins);
$am_pm_format  = substr($focus->date_time_format,strlen($focus->date_time_format)-1,1);
$smarty->assign("ampm_format","A");
if ($am_pm_format == 'a')
	$smarty->assign("ampm_list",array('am'=>'am','pm'=>'pm'));
else
	$smarty->assign("ampm_list",array('AM'=>'AM','PM'=>'PM'));

$dates = $focus->gen_dates();

if (!isset($_REQUEST['run']) || $_REQUEST['run'] != 'yes')	// Prompt for parameters
{
	// Parameters
	$cnt = 0;
	$parameters = array();
	foreach ($focus->parameter_names as $name)
	{
		$cnt++;
		if (isset($focus->parameters[$name]))
		{
			if (isset($_REQUEST[$name])) // Parameter passed
			{
				if ($focus->parameters[$name]->type == 'DateTime') // Drop time - will add later
				{
					$tmp = split(" ",$_REQUEST[$name]);
					$_REQUEST[$name] = $tmp[0];
				}

				if ($focus->parameters[$name]->type == 'ModuleLink')
					$focus->parameters[$name]->default_name = $_REQUEST["name_$name"];
				else
					$focus->parameters[$name]->default_name = '';

				$focus->parameters[$name]->default_value = $_REQUEST[$name];
			}
			elseif (substr($focus->parameters[$name]->type,0,4) == 'Date')
				$focus->parameters[$name]->default_value = 
											$dates[$focus->parameters[$name]->default_value];

			$parameters[$name] = array('name' => $name,
									'id' => $focus->parameters[$name]->id,
									'parameter_label' => $focus->parameters[$name]->parameter_label,
									'type' => $focus->parameters[$name]->type,
									'decimals' => $focus->parameters[$name]->decimals,
									'dropdown_name' => $focus->parameters[$name]->dropdown_name,
									'priority' => $focus->parameters[$name]->priority,
									'default_value' => $focus->parameters[$name]->default_value,
									'default_date' => $focus->parameters[$name]->default_value,
									'default_name' => $focus->parameters[$name]->default_name,
									'list' => array(),
								);

			if ($focus->parameters[$name]->type == 'Dropdown')
				//print_r($app_list_strings[$focus->parameters[$name]->dropdown_name]);
				$parameters[$name]['list'] = $app_list_strings[$focus->parameters[$name]->dropdown_name];
			if ($focus->parameters[$name]->type == 'DateTime' && isset($_REQUEST["hour_$name"]))
			{
				$parameters[$name]['hour'] = $_REQUEST["hour_$name"];
				$parameters[$name]['min'] = $_REQUEST["min_$name"];
				$parameters[$name]['ampm'] = $_REQUEST["ampm_$name"];
			}
		}
		else	// Unlikely
			$parameters[$name] = array('name' => $name,
									'id' => '',
									'parameter_label' => $name,
									'type' => 'Text',
									'decimals' => 0,
									'dropdown_name' => '',
									'priority' => $cnt,
									'default_value' => '',
									'list' => array(),
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

	// Report formats
	$smarty->assign("adrep_format_list",$app_list_strings['adrep_format_list']);
	if (isset($_REQUEST['format']))
		$smarty->assign("format",$_REQUEST['format']);
	else
		$smarty->assign("format","view");

	// Action to run
	$smarty->assign("LBL_ACTION_TO_RUN",translate('LBL_ACTION_TO_RUN','adrep_report'));	

	// Date default values
	$date_types = array('none'=>'None','today'=>'Today','yesterday'=>'Yesterday','tomorrow'=>'Tomorrow');
	$smarty->assign("date_types",$date_types);
	
	$smarty->display("modules/adrep_report/tpls/RunReport.html");
}
else // Run the report and display results
{
	echo "<h2>";
	echo translate('LBL_RUNNING','adrep_report');
	echo " <b>$focus->name</b></h2>\n";

	set_time_limit(0);	// Query may take considerable time

	$cnt = $focus->run_report($_REQUEST);

	if (empty($_REQUEST['format']) || $_REQUEST['format'] == 'view')
	{
		echo "<script type='text/javascript'>
	window.open('index.php?module=adrep_report&action=ViewHTML&record=$focus->id&page=1&rpp=50&sugar_body_only=yes');
</script>\n";
	}
	else
	{
		$format = $_REQUEST['format'];
		echo "<script type='text/javascript'>
	window.open('index.php?module=adrep_report&action=DownloadReport&record=$focus->id&format=$format&sugar_body_only=yes');
</script>\n";
	}

	if (isset($_REQUEST['return_module']))
	{
		$return_module = $_REQUEST['return_module'];
		$return_action = $_REQUEST['return_action'];
		$return_record = $_REQUEST['return_record'];
	}
	else
	{
		$return_module = 'adrep_report';
		$return_action = 'DetailView';
		$return_record = $focus->id;
	}

	unset($_REQUEST['run']);
	$query = http_build_query($_REQUEST);

	echo "
	<br /><br />
	<a href='index.php?$query'>
    	<button type='button' class='button'>" . translate('LBL_RUN_AGAIN','adrep_report') . "</button>
	</a>";
	echo "
	<a href='index.php?module=$return_module&action=$return_action&record=$return_record'>
    	<button type='button' class='button'>" . translate('LBL_BACK','adrep_report') . "</button>
	</a>";	
}
