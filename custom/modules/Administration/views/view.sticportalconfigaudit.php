<?php
require_once 'include/MVC/View/SugarView.php';

class AdministrationViewSticportalconfigaudit extends SugarView
{
    public function preDisplay()
    {
        global $current_user;
        if (!is_admin($current_user)) {
            sugar_die("Unauthorized access to administration.");
        }
        $this->ss->assign('RETURN_MODULE', 'Administration');
        $this->ss->assign('RETURN_ACTION', 'sticportalconfig');
    }

    public function display()
    {
        global $mod_strings, $db;
        $audit = [];
        $r = $db->query("SELECT * FROM stic_portal_login_audit WHERE deleted=0 ORDER BY date_entered DESC LIMIT 100");
        while ($row = $db->fetchByAssoc($r)) {
            $audit[] = $row;
        }
        $this->ss->assign('MOD', $mod_strings);
        $this->ss->assign('AUDIT', $audit);
        echo $this->ss->fetch('custom/modules/Administration/templates/SticPortalAudit.tpl');
    }
}
