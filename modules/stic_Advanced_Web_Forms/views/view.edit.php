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

use Symfony\Component\Validator\Constraints\Length;

require_once 'include/MVC/View/views/view.edit.php';
require_once 'SticInclude/Views.php';

#[\AllowDynamicProperties]
class stic_Advanced_Web_FormsViewEdit extends ViewEdit
{
    public function __construct()
    {
        parent::__construct();
    }

    public function preDisplay()
    {
        // parent::preDisplay();

        SticViews::preDisplay($this);

        // Bootstrap (modified with scoped classes: do not crash current layout!)
        echo "<link rel='stylesheet' href='". getVersionedPath("SticInclude/vendor/bootstrap/css/bootstrap.scoped.min.css"). "'>";
        echo getVersionedScript("SticInclude/vendor/bootstrap/js/bootstrap.bundle.min.js");

        // Alpinejs
        // echo '<script src="//unpkg.com/alpinejs" defer></script>';
        echo '<script src="' . getVersionedPath('SticInclude/vendor/alpine/alpine.min.js') . '" defer></script>';

        // // jstree
        // echo '<link rel="stylesheet" href="SticInclude/vendor/jstree/themes/default/style.min.css" />';
        // echo getVersionedScript("SticInclude/vendor/jstree/jstree.min.js");

        // Quill
        echo getVersionedScript("SticInclude/vendor/quill/quill.min.js");
        echo "<link rel='stylesheet' href='". getVersionedPath("SticInclude/vendor/quill/quill.snow.css"). "'>";

        // AWF 
        echo getVersionedScript("modules/stic_Advanced_Web_Forms/custom_views/js/config.js");
        echo getVersionedScript("modules/stic_Advanced_Web_Forms/custom_views/js/utils.js");
        echo getVersionedScript("modules/stic_Advanced_Web_Forms/custom_views/js/sticControls.js");
        echo "<script src='". getVersionedPath("modules/stic_Advanced_Web_Forms/custom_views/js/sticTemplates.js"). "' defer></script>";
        echo "<link rel='stylesheet' href='". getVersionedPath("modules/stic_Advanced_Web_Forms/custom_views/css/sticControls.css"). "'>";

        // Wizard
        echo getVersionedScript("modules/stic_Advanced_Web_Forms/custom_views/wizard/js/wizard.js");
    }

    public function display()
    {
        // parent::display();

        SticViews::display($this);

        // Assign current user to assifned user if new record
        if (empty($this->bean->id) && empty($this->bean->assigned_user_id)) {
            global $current_user;
            $this->bean->assigned_user_id = $current_user->id;
            $this->bean->assigned_user_name = $current_user->name ?? $current_user->user_name; // Nom complet o login
        }

        $responseCount = 0;
        if (!empty($this->bean->id)) {
            $db = DBManagerFactory::getInstance();
            $formId = $db->quote($this->bean->id);

            $query = "SELECT count(*) FROM stic_f193responses_c 
                      WHERE stic_aa0eb_forms_ida = '$formId' 
                      AND deleted = 0";

            $responseCount = $db->getOne($query) ?? 0;
        }
        $warnings = [];
        $msgWarnings = "";
        if ($this->bean->status === 'public') {
            $warnings[] = "\t· " . translate('LBL_WIZARD_FORM_EDIT_WARNING_PUBLIC', 'stic_Advanced_Web_Forms');
        }
        if ($responseCount > 0) {
            $warnings[] = "\t· " . sprintf(translate('LBL_WIZARD_FORM_EDIT_WARNING_RESPONSES', 'stic_Advanced_Web_Forms'), $responseCount);
        }
        if (count($warnings) > 0) {
        $msgWarnings = translate('LBL_WIZARD_FORM_EDIT_WARNING_TITLE', 'stic_Advanced_Web_Forms') . "\n" .
                       implode("\n", $warnings) . "\n" .
                       translate('LBL_WIZARD_FORM_EDIT_WARNING_PROCEED', 'stic_Advanced_Web_Forms');
        }


        $this->ss->assign('title', $this->getModuleTitle(false));

        require_once "modules/stic_Advanced_Web_Forms/Utils.php";
        $this->ss->assign('enabledModules', json_encode(getEnabledModules()));
        $this->ss->assign('mainThemeColor', getCustomBaseColor());
        $this->ss->assign('msgWarnings', $msgWarnings);
        $this->ss->assign('isAdminUser', $GLOBALS['current_user']->is_admin ? true : false);

        $beanArray = $this->bean->toArray();
        $beanArray['assigned_user_id'] = $this->bean->assigned_user_id;
        $beanArray['assigned_user_name'] = $this->bean->assigned_user_name;
        $this->ss->assign('beanJson', json_encode($beanArray));

        echo $this->ss->fetch('modules/stic_Advanced_Web_Forms/custom_views/wizard/tpl/wizard.tpl');
    }

}
