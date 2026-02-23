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

require_once 'include/MVC/View/views/view.detail.php';
require_once 'SticInclude/Views.php';

class stic_SignersViewDetail extends ViewDetail
{

    public function __construct()
    {
        parent::__construct();

    }

    public function preDisplay()
    {
        global $app_list_strings, $mod_strings;

        parent::preDisplay();

        // Set record name for display if record ID is present
        if ($this->bean->record_id != "") {
            $this->bean->record_name = "{$app_list_strings['moduleList'][$this->bean->record_type]}<br><a href=\"index.php?module={$this->bean->record_type}&action=DetailView&record={$this->bean->record_id}\">{$this->bean->record_name}</a>";
        }
        

        // Set pdf_document field for display
        if ($this->bean->status =='signed') {
            $this->bean->pdf_document = "<a href=\"index.php?entryPoint=sticSign&signatureAction=downloadSignedPdf&signerId={$this->bean->id}\">" . $mod_strings['LBL_DOWNLOAD_PDF_SIGNATURE'] . " <span class='glyphicon glyphicon-download'></span></a>";
        } else {
            $this->bean->pdf_document = "<span>" . $mod_strings['LBL_NO_PDF_SIGNATURE'] . "</span>";
        }

        SticViews::preDisplay($this);

        echo getVersionedScript("SticInclude/js/Utils.js");

    }

    public function display()
    {
        parent::display();

        SticViews::display($this);

        echo getVersionedScript("modules/stic_Signers/Utils.js");

        // Write here you custom code
    }

}
