<?php
if (ACLController::checkAccess('OAuth2Clients', 'edit', true)) {
    global $module_menu;
    $label = translate('LNK_NEW_OAUTH2_PORTAL_CLIENT', 'OAuth2Clients');
    if (empty($label) || $label === 'LNK_NEW_OAUTH2_PORTAL_CLIENT') {
        $label = 'Create Portal OAuth2 Client';
    }
    $module_menu[] = [
        "index.php?module=OAuth2Clients&action=EditViewPortal&return_module=OAuth2Tokens&return_action=index",
        $label,
        "Create"
    ];
}
