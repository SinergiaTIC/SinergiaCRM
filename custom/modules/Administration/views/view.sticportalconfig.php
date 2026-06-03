<?php
require_once 'include/MVC/View/SugarView.php';
require_once 'SticInclude/SticPortalConfigUtils.php';
require_once 'SticInclude/SticPortalAuthUtils.php';

class AdministrationViewSticportalconfig extends SugarView
{
    public function preDisplay()
    {
        global $current_user;
        if (!is_admin($current_user)) {
            sugar_die("Unauthorized access to administration.");
        }
    }

    public function display()
    {
        global $mod_strings, $app_strings;
        $settings = SticPortalConfigUtils::getAll();
        $logoUrl  = SticPortalConfigUtils::getLogoUrl();

        $this->ss->assign('MOD', $mod_strings);
        $this->ss->assign('SETTINGS', $settings);
        $this->ss->assign('LOGO_URL', $logoUrl);
        $this->ss->assign('title', $this->getModuleTitle(false));

        // Fetch audit log (last 20 entries)
        global $db;
        $audit = array();
        $r = $db->query("SELECT * FROM stic_portal_login_audit WHERE deleted=0 ORDER BY date_entered DESC LIMIT 20");
        while ($row = $db->fetchByAssoc($r)) {
            $audit[] = $row;
        }
        $this->ss->assign('AUDIT', $audit);

        echo $this->ss->fetch('custom/modules/Administration/templates/SticPortalConfig.tpl');
    }
}
