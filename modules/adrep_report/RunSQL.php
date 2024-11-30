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

include_once('modules/adrep_report/lib/SqlFormatter.php');
$focus = new adrep_report();
$focus->retrieve($_REQUEST['record']);

$smarty = new Sugar_Smarty();
$smarty->assign("TITLE",translate('LBL_QUERY','adrep_report'));
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

global $current_user, $db;
$vars=array();
if (!empty($focus->id))
{
	$focus->retrieve_parameters();
	foreach ($focus->parameters as $param)
	{
		if ($param->type == 'CurrentUserID')
			//$vars[$param->name] = $current_user->id;
			$vars[$param->name] = $_SESSION['authenticated_id'];	// Better for portal
		elseif (!isset($vars[$param->name]))
			$vars[$param->name] = $param->default_value;
	}

	$query=$focus->fill_query($vars);


	$explainsql='EXPLAIN '.$query;

	$result = $GLOBALS["db"]->query($explainsql);

$formatted=SqlFormatter::format($query);

$html='<div class="tablediv"><table border="1" class="table table-striped table-condensed table-headed">
<thead>
	<tr>
<th>id</th>
<th>select_type</th>
<th>table</th>
<th>type</th>
<th>possible_keys</th>
<th>key</th>
<th>key_len</th>
<th>ref</th>
<th>rows</th>
<th>Extra</th>
	</tr>
</thead>
<tbody>
';

while($analyserows = $GLOBALS["db"]->fetchByAssoc($result)){

	$html.='<tr><td>'.$analyserows['id'].'</td>';
	$html.='<td>'.$analyserows['select'].'</td>';
	$html.='<td>'.$analyserows['table'].'</td>';
	$html.='<td>'.$analyserows['type'].'</td>';
	$html.='<td>'.$analyserows['possible_keys'].'</td>';
	$html.='<td>'.$analyserows['key'].'</td>';
	$html.='<td>'.$analyserows['key_len'].'</td>';
	$html.='<td>'.$analyserows['ref'].'</td>';
	$html.='<td>'.$analyserows['rows'].'</td>';
	$html.='<td>'.$analyserows['Extra'].'</td></tr>';

}

$html.='</tbody></table></div>';

//$formatted='HELLO';
$smarty->assign('formattedsql',$formatted);

$smarty->assign('explainoutput',$html);
$smarty->display("modules/adrep_report/tpls/RunSQL.html");
}
