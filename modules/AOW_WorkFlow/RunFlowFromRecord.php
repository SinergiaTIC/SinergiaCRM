<?php

if (!isset($_REQUEST['workflowID']) && !isset($_REQUEST['module']) && !isset($_REQUEST['uid'])) {
    die("Bad data passed in; <a href=\"{$sugar_config['site_url']}\">Return to Home</a>");
}

$workflowBean = BeanFactory::getBean('AOW_WorkFlow', $_REQUEST['workflowID']);
$recordBean = BeanFactory::getBean($_REQUEST['module'], $_REQUEST['uid']);
if (!$recordBean->id) {
    die("Bad data passed in; <a href=\"{$sugar_config['site_url']}\">Return to Home</a>");
}
$workflowBean->run_actions($recordBean);

SugarApplication::redirect('index.php?module='.$_REQUEST['module'].'&action=DetailView&record='.$_REQUEST['uid']);
