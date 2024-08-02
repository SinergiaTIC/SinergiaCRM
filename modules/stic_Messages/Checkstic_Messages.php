<?php


trait Checkstic_Messages {
    function echoIsMessagesModuleActive() {
        require_once('modules/stic_Settings/Utils.php');
        require_once 'modules/MySettings/TabController.php';
        $controller = new TabController();
        $currentTabs = $controller->get_system_tabs();
        if (!$currentTabs['stic_Messages']){
            $active = 'false';
        }
        else {
            $active = 'true';
        }

        $messagesLimit = stic_SettingsUtils::getSetting('MESSAGES_LIMIT');

        echo "<script type='text/javascript'>function getMessagesActive() {return {$active};} function getMessagesLimit() {return {$messagesLimit};} </script>";
    }
}