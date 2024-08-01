<?php

trait Checkstic_Messages {
 function echoIsMessagesModuleActive() {
    require_once 'modules/MySettings/TabController.php';
    $controller = new TabController();
    $currentTabs = $controller->get_system_tabs();
    if (!$currentTabs['stic_Messages']){
        echo "<script type='text/javascript'>function getMessagesActive() {return false;}</script>";
    }
    echo "<script type='text/javascript'>function getMessagesActive() {return true;}</script>";
    }
}