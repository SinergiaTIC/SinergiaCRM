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
        // Read-only list: no bulk selection, no bulk actions dropdown and no
        // mass update form. This also keeps the pagination buttons URL-based:
        // with checkboxes rendered but no MassUpdate form, sListView.save_checks
        // (their onclick) would fail and pagination would stop working.
        $this->params['massupdate'] = false;
        $this->lv->multiSelect = false;
        $this->lv->show_action_dropdown = false;
    }
}
