<?php
$focus = new adrep_report();
$focus->retrieve($_REQUEST['record']);

if (!empty($focus->id))
{
	$focus->retrieve_columns();

	$format = (isset($_REQUEST['format']) ? $_REQUEST['format'] : 'csv');

	// Download headers
	header("Content-Disposition: attachment; filename=\"$focus->dl_name.$format\"");
	if ($format != 'pdf')
		header("Content-Type: text/$format");
	else
		header("Content-Type: application/$format");

	// Generate the file
	$method = "generate_$format";
	$filename = $focus->$method();

	require $filename;

	unlink($filename);
}
