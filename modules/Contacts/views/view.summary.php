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

require_once 'modules/Contacts/views/view.detail.php';
require_once 'SticInclude/Views.php';

class ContactsViewSummary extends ContactsViewDetail
{
    public $type = 'summary';

    public function __construct()
    {
        parent::__construct();
        $this->options['show_subpanels'] = true;
    }

    public function preDisplay()
    {
        $metadataFile = $this->getMetaDataFile();
        $this->dv = new DetailView2();
        $this->dv->view = 'summaryview';
        $this->dv->ss =& $this->ss;
        $this->dv->setup($this->module, $this->bean, $metadataFile, get_custom_file_if_exists('include/DetailView/DetailView.tpl'));

        // Point header/footer templates to the standard DetailView ones
        if (!isset($this->dv->defs['templateMeta']['form'])) {
            $this->dv->defs['templateMeta']['form'] = array();
        }
        $this->dv->defs['templateMeta']['form']['headerTpl'] = 'include/DetailView/header.tpl';
        $this->dv->defs['templateMeta']['form']['footerTpl'] = 'include/DetailView/footer.tpl';

        SticViews::preDisplay($this);
    }

    public function display()
    {
        // Visible button bar with the main actions
        $recordId = $this->bean->id;
        echo '<div style="margin:10px 0; text-align:right;">';
        echo '<a class="button" style="padding:8px 20px; margin-left:8px;" href="index.php?module=Contacts&action=EditView&record=' . urlencode($recordId) . '">Editar</a>';
        echo '<a class="button" style="padding:8px 20px; margin-left:8px;" href="index.php?module=Contacts&action=ExtendedDetail&record=' . urlencode($recordId) . '">Ver detalle completo</a>';
        echo '<a class="button" style="padding:8px 20px; margin-left:8px;" href="javascript:void(0)" onclick="var ids=[\'subpanel_list\',\'groupTabs\']; ids.forEach(function(id){var s=document.getElementById(id); if(s){s.style.display=s.style.display===\'none\'?\'\':\'none\';}})">Mostrar/Ocultar subpaneles</a>';
        echo '</div>';

        parent::display();

        SticViews::display($this);
        echo getVersionedScript("custom/modules/Contacts/SticUtils.js");
    }
}