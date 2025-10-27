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

require_once 'include/MVC/View/views/view.edit.php';
require_once 'SticInclude/Views.php';
require_once 'modules/stic_Messages/Utils.php';
require_once('modules/stic_Settings/Utils.php');
class stic_MessagesViewEdit extends ViewEdit
{

    public function __construct()
    {
        parent::__construct();
        $this->useForSubpanel = true;
        $this->useModuleQuickCreateTemplate = true;

        // $this->type = 'compose';
        if(!empty($_GET['in_popup'])&& $_GET['in_popup'] == '1' ||
           !empty($_POST['in_popup'])&& $_POST['in_popup'] == '1'){
            $this->options['show_title'] = false;
            $this->options['show_header'] = false;
            $this->options['show_footer'] = false;
            $this->options['show_javascript'] = false;
            $this->options['show_subpanels'] = false;
            $this->options['show_search'] = false;
        }
    }


    public function preDisplay()
    {
        global $app_list_strings;
        parent::preDisplay();

        SticViews::preDisplay($this);
        stic_MessagesUtils::fillDynamicListMessageTemplate();

        $this->ev->ss->assign('IS_MODAL', isset($_REQUEST['in_popup']) ? $_REQUEST['in_popup'] : false);

        $this->bean->parent_type = !empty($this->bean->parent_type) ? $this->bean->parent_type : ($_REQUEST['relatedModule']??'');
	    $this->bean->parent_id = !empty($this->bean->parent_id)? $this->bean->parent_id : ($_REQUEST['relatedId'] ?? null);
        $this->bean->fill_in_additional_parent_fields();

        $this->bean->sender = stic_SettingsUtils::getSetting('messages_sender') ?? '';

        if (!$this->bean->fetched_row) {
            unset($app_list_strings['stic_messages_status_list']['error']);
        }

        // Write here you custom code

    }

    public function display()
    {
        global $mod_strings;
        $this->bean->info = "<p class='msg-warning'><span style='font-style: italic;'>⚠️{$mod_strings['LBL_INFO_TXT']}.</span></p";
        parent::display();

        SticViews::display($this);

        echo getVersionedScript("modules/stic_Messages/include/ComposeView/stic_MessagesComposeView.js");
        echo getVersionedScript("modules/stic_Messages/Utils.js");

        // Write here you custom code

    }
}