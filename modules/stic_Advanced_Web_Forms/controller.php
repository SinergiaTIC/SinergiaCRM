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

require_once('include/MVC/Controller/SugarController.php');
#[\AllowDynamicProperties]
class stic_Advanced_Web_FormsController extends SugarController
{

    // Endpoints saveDraft/finalizeConfig/getConfig

    // public function action_newsletterlist()
    // {
    //     $this->view = 'newsletterlist';
    // }

    // public function process()
    // {
    //     if ($this->action == 'EditView' && empty($_REQUEST['record'])) {
    //         $this->action = 'WizardHome';
    //     } else {
    //         if ($this->action == 'EditView' && !empty($_REQUEST['record'])) {
    //             // Show Send Email and Summary
    //             $this->action = 'WizardHome';
    //             // modules/Campaigns/WizardHome.php isWizardSummary
    //             $_REQUEST['action'] = 'WizardHome';
    //         }
    //     }
    //     parent::process();
    // }
}
