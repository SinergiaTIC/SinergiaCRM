<?php

if (ACLController::checkAccess('stic_Sessions', 'edit', true)) {
    $last = array_pop($module_menu);
    $module_menu[] = array("index.php?module=stic_Sessions&action=EditView&return_module=stic_Sessions&return_action=DetailView", translate('LNK_NEW_RECORD', 'stic_Sessions'), "create-stic-sessions", 'stic_Sessions');
    $module_menu[] = $last;
}

if (ACLController::checkAccess('stic_FollowUps', 'edit', true)) {
    $last = array_pop($module_menu);
    $module_menu[] = array("index.php?module=stic_FollowUps&action=EditView&return_module=stic_FollowUps&return_action=DetailView", translate('LNK_NEW_RECORD', 'stic_FollowUps'), "create-stic-followups", 'stic_FollowUps');
    $module_menu[] = $last;
}