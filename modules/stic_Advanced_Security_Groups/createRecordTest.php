<?php
global $current_user;
$bean = BeanFactory::newBean($_REQUEST['modulo']);
$bean->set_created_by=false;

$bean->name = rand();
$bean->assigned_user_id = 2;
$bean->created_by = 1;
$bean->save();
// var_dump($bean);die();
SugarApplication::redirect("index.php?module={$_REQUEST['modulo']}&action=detailview&record={$bean->id}");
