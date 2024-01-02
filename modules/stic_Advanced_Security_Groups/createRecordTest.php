<?php
global $current_user;
$bean=BeanFactory::newBean($_REQUEST['modulo']);
$bean->name=rand();
$bean->assigned_user_id=2;
$bean->save();

SugarApplication::redirect("index.php?module={$_REQUEST['modulo']}&action=detailview&record={$bean->id}");