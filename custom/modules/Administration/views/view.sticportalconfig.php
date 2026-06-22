<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */
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

        $this->ss->assign('MOD', $mod_strings);
        $this->ss->assign('APP', $app_strings);
        $this->ss->assign('SETTINGS', $settings);
        $this->ss->assign('LOGO_URL', $logoUrl);
        $this->ss->assign('EMAIL_TEMPLATES', $emailTemplates);

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
