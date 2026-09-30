<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/MVC/View/views/view.list.php';

class stic_Portal_Login_AuditViewList extends ViewList
{
    public function preDisplay()
    {
        global $current_user;

        if (!is_admin($current_user)) {
            sugar_die('Unauthorized access to portal login audit.');
        }

        require_once 'SticInclude/Portal/ConfigUtils.php';
        SticPortalConfigUtils::purgeOldAudit();

        parent::preDisplay();
    }

    public function listViewPrepare()
    {
        parent::listViewPrepare();
        $this->params['massupdate'] = false;
    }
}
