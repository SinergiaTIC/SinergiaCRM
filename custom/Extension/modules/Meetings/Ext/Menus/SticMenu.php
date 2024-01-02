<?php

if (ACLController::checkAccess('Calendar', 'list', true)) {
    $module_menu[]=array("index.php?module=Calendar&action=index&view=day", translate('LNK_VIEW_CALENDAR', 'Activities'),"Schedule");
}
