<?php
require_once 'include/MVC/View/SugarView.php';
require_once 'SticInclude/Portal/ConfigUtils.php';
require_once 'SticInclude/Portal/AuthUtils.php';

class AdministrationViewSticportalconfig extends SugarView
{
    public function preDisplay()
    {
        global $current_user;
        if (!is_admin($current_user)) {
            sugar_die("Unauthorized access to administration.");
        }
        $this->ss->assign('RETURN_MODULE', 'Administration');
        $this->ss->assign('RETURN_ACTION', 'index');
    }

    public function display()
    {
        global $mod_strings, $app_strings;

        $settings = SticPortalConfigUtils::getAll();
        $logoUrl  = SticPortalConfigUtils::getLogoUrl();
        $emailTemplates = $this->getEmailTemplates();
        
        $portalApps = @unserialize(htmlspecialchars_decode($settings["PORTAL_APPS"] ?? ""), ["allowed_classes" => false]) ?: [];

        
        $this->ss->assign('MOD', $mod_strings);
        $this->ss->assign('APP', $app_strings);
        $this->ss->assign('SETTINGS', $settings);
        $this->ss->assign('LOGO_URL', $logoUrl);
        $this->ss->assign('EMAIL_TEMPLATES', $emailTemplates);
        $this->ss->assign('PORTAL_APPS', $portalApps);

        echo $this->ss->fetch('custom/modules/Administration/templates/SticPortalConfig.tpl');
    }

    protected function getEmailTemplates()
    {
        global $db;
        $templates = ['' => '--None--'];
        $r = $db->query("SELECT id, name FROM email_templates WHERE type='email' AND email_templates.deleted=0 ORDER BY name");
        while ($row = $db->fetchByAssoc($r)) {
            $templates[$row['id']] = $row['name'];
        }
        return $templates;
    }
}
