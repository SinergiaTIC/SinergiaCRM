<?php
require_once "modules/adrep_report/adrep_report.php";

// Create (and retrieve if needed) the focus object
$new_record = false;
$focus = new adrep_report();
if (!empty($_REQUEST['record']))
	$focus->retrieve($_REQUEST['record']);
else
	$new_record = true;

// Populate with values from the form
foreach ($_REQUEST as $key => $value)
	if (isset($focus->field_defs[$key]) && $focus->$key != $value)
		$focus->$key = $value;

// And save it
$focus->save();
$focus->retrieve();

// Now go to EditParams for a new report and back to DetailView for an existing one
if ($new_record)
	header("Location: index.php?module=adrep_report&action=EditParams&record=$focus->id&new=yes");
elseif ($focus->permission == 'Roles')
	header("Location: index.php?module=adrep_report&action=EditRoles&record=$focus->id");
else
	header("Location: index.php?module=adrep_report&action=DetailView&record=$focus->id");
die('redirect');
