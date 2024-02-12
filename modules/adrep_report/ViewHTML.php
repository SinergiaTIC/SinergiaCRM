<?php
$focus = new adrep_report();
$focus->retrieve($_REQUEST['record']);

if (!empty($focus->id))
{
	$focus->retrieve_columns();

	$filename = $focus->generate_html($_REQUEST['page'],$_REQUEST['rpp']);

	require $filename;
	unlink($filename);
}
