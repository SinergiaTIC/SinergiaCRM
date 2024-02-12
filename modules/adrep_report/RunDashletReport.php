<?php

include_once('modules/adrep_report/adrep_report.php');
$focus = new adrep_report();
$focus->retrieve($_REQUEST['record']);

if(isset($_REQUEST['template']))
	$template=$_REQUEST['template'];
else {
	$template=null;
}

if(isset($_REQUEST['rpp']))
	$noresults=$_REQUEST['rpp'];
else {
	$noresults=10;
}

if(isset($_REQUEST['page']) &&is_numeric($_REQUEST['page']) && $_REQUEST['page'] >0)
	$page=$_REQUEST['page'];
else {
	$page=1;
}
if (!empty($focus->id))
{
		$focus->run_report(array());

		$file=$focus->generate_html($page,$noresults,'html',$template);
		readfile($file);
		unlink($file);
}
