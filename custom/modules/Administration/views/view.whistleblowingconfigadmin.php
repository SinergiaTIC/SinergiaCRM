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
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * Administration view for the Whistleblowing channel configuration.
 * Shows the enabled checkbox and the 3 texts of the public tabs.
 * The save process is performed by CustomAdministrationController::action_whistleblowingconfig_admin
 */
class ViewWhistleblowingConfigAdmin extends SugarView
{
    /**
     * @see SugarView::_getModuleTitleParams()
     */
    protected function _getModuleTitleParams($browserTitle = false)
    {
        global $mod_strings;

        return array(
            "<a href='index.php?module=Administration&action=index'>" . $mod_strings['LBL_MODULE_NAME'] . '</a>',
            $mod_strings['LBL_WHISTLEBLOWING_CONFIG_TITLE'],
        );
    }

    /**
     * @see SugarView::preDisplay()
     */
    public function preDisplay()
    {
        global $current_user;

        if (!is_admin($current_user)) {
            sugar_die('Unauthorized access to administration.');
        }
    }

    /**
     * @see SugarView::display()
     */
    public function display()
    {
        global $sugar_config;

        $whistleblowingConfig = array(
            'whistleblowing_enabled' => $sugar_config['whistleblowing_enabled'] ?? '0',
            'whistleblowing_recaptcha_enabled' => $sugar_config['whistleblowing_recaptcha_enabled'] ?? '0',
            'whistleblowing_text_about' => $sugar_config['whistleblowing_text_about'] ?? '',
            'whistleblowing_text_confidentiality' => $sugar_config['whistleblowing_text_confidentiality'] ?? '',
            'whistleblowing_text_security' => $sugar_config['whistleblowing_text_security'] ?? '',
            // Link to the internal module list view. Access to this view is restricted
            // by the WHISTLEBLOWING_ALLOWED_USERS setting
            'whistleblowing_url' => 'index.php?module=stic_Whistleblowing',
        );

        $this->ss->assign('MOD', $GLOBALS['mod_strings']);
        $this->ss->assign('APP', $GLOBALS['app_strings']);
        $this->ss->assign('config', $whistleblowingConfig);
        $this->ss->assign('title', $this->getModuleTitle(false));

        echo $this->ss->fetch('custom/modules/Administration/templates/WhistleblowingConfigAdmin.tpl');
    }
}
