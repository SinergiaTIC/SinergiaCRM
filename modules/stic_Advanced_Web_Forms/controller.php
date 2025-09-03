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

    public function action_saveDraft()
    {
        // Ensure return json 
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || empty($data['bean']['id'])) {
            echo json_encode(['success' => false, 'message' => 'Missing or invalid data']);
            sugar_cleanup(true);
        }

        // Load the bean
        $bean = BeanFactory::getBean('stic_Advanced_Web_Forms', $data['bean']['id']);
        if (!$bean) {
            echo json_encode(['success' => false, 'message' => 'Record not found']);
            sugar_cleanup(true);
        }

        // Update all fields in vardef
        foreach ($bean->field_defs as $fieldName => $def) {
            if (isset($data['bean'][$fieldName])) {
                // Except critical fields
                if (in_array($fieldName, ['id','date_entered','date_modified','deleted'])) {
                    continue;
                }
                $bean->$fieldName = $data['bean'][$fieldName];
            }
        }

        // Update config_json with new config
        $bean->config_json = json_encode($data['config']);
        // $bean->status = 'draft';
        $bean->save();

        echo json_encode(['success' => true, 'id' => $bean->id, 'step' => $data['step'] ?? null]);
        sugar_cleanup(true);
    }

    /**
     * Handles the 'getModuleInformation' action to retrieve module relationships and fields.
     */
    public function action_getModuleInformation()
    {
        // Ensure return json 
        header('Content-Type: application/json');

        require_once "modules/stic_Advanced_Web_Forms/Utils.php";
        echo json_encode(getModuleInformation($_REQUEST['getmodule']));

        sugar_cleanup(true);
    }
}