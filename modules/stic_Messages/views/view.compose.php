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

require_once 'include/MVC/View/views/view.edit.php';
require_once 'SticInclude/Views.php';
require_once('modules/stic_Settings/Utils.php');
class stic_MessagesViewCompose extends ViewEdit
{

    /**
     * @var stic_Message $bean
     */
    public $bean;

    /**
     * stic_MessagesViewCompose constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->type = 'compose';
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

    /**
     * @see SugarView::preDisplay()
     */
    public function preDisplay()
    {
        parent::preDisplay();
        SticViews::preDisplay($this);
        $this->ev->ss->assign('RETURN_MODULE', isset($_REQUEST['return_module']) ? $_REQUEST['return_module'] : '');
        $this->ev->ss->assign('RETURN_ACTION', isset($_REQUEST['return_action']) ? $_REQUEST['return_action'] : '');
        $this->ev->ss->assign('RETURN_ID', isset($_REQUEST['return_id']) ? $_REQUEST['return_id'] : '');
        $this->ev->ss->assign('IS_MODAL', isset($_REQUEST['in_popup']) ? $_REQUEST['in_popup'] : false);

        $this->bean->parent_type = $_REQUEST['relatedModule'];
	    $this->bean->parent_id = $_REQUEST['relatedId'] ?? null;

        global $sugar_config;
        $this->bean->sender = stic_SettingsUtils::getSetting('messages_sender') ?? '';

        $metadataFile = $this->getMetaDataFile();
        $this->ev->setup(
            $this->module,
            $this->bean,
            $metadataFile,
            get_custom_file_if_exists('modules/stic_Messages/include/ComposeView/ComposeView.tpl')
        );
    }

    public function display()
    {
        parent::display();

        SticViews::display($this);

        echo getVersionedScript("modules/stic_Messages/Utils.js");
        echo getVersionedScript("modules/stic_Messages/include/ComposeView/stic_MessagesComposeView.js");
        // echo getVersionedScript("cache/include/javascript/sugar_grp_yui_widgets.js");
        
        // Write here you custom code

    }
}
