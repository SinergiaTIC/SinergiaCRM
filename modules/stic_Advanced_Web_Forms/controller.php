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
    private function _saveBeanFromData($data)
    {
        if (empty($data['bean']['id'])) {
            $bean = BeanFactory::newBean('stic_Advanced_Web_Forms');
        } else {
            $bean = BeanFactory::getBean('stic_Advanced_Web_Forms', $data['bean']['id']);
        }

        if (!$bean) {
            throw new Exception("Record not found or could not be created");
        }

        // Update all fields in vardef
        foreach ($bean->field_defs as $fieldName => $def) {
            if (isset($data['bean'][$fieldName])) {
                // Skip protected fields
                if (in_array($fieldName, ['id','date_entered','date_modified','deleted'])) {
                    continue;
                }
                $value = $data['bean'][$fieldName];
                if (($fieldName === 'start_date' || $fieldName === 'end_date') && !empty($value)) {
                    // Convert the frontend format to DB: 2026-02-28T14:30 -> 2026-02-28 14:30:00
                    $value = str_replace('T', ' ', $value);
                    if (strlen($value) === 16) { // YYYY-MM-DD HH:MM
                        $value .= ':00';
                    }
                }
                $bean->$fieldName = $value;
            }
        }

        // Update config_json with new config
        if (isset($data['config'])) {
             $bean->configuration = $data['config'];
        }
        
        $bean->save();
        return $bean;
    }

    /**
     * Handles the 'saveDraft' action to Save current configuration in bean
     */
    public function action_saveDraft()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        try {
            $bean = $this->_saveBeanFromData($data);
            echo json_encode(['success' => true, 'id' => $bean->id]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }

        sugar_cleanup(true);
    }

    /**
     * Handles the 'finalizeConfig' action to Finish bean edition
     */
    public function action_finalizeConfig()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        try {
            $bean = $this->_saveBeanFromData($data);
            $redirectUrl = 'index.php?module=stic_Advanced_Web_Forms&action=index';
            echo json_encode(['success' => true, 'id' => $bean->id, 'redirectUrl' => $redirectUrl]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        
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
        $result = getModuleInformation($_REQUEST['getmodule'], json_decode(html_entity_decode($_REQUEST['getavailablemodules']),true));
        $resultStr = json_encode($result, JSON_UNESCAPED_UNICODE);
        echo $resultStr;

        sugar_cleanup(true);
    }

    /**
     * Handles the 'getRecordsTextById' action to retrieve the text to be shown for a list of Ids of given module 
     */
    public function action_getRecordsTextById() {
        // Ensure return json 
        header('Content-Type: application/json');

        require_once "modules/stic_Advanced_Web_Forms/Utils.php";
        $result = getRecordsTextById($_REQUEST['reqmodule'], json_decode(html_entity_decode($_REQUEST['reqids']),true));
        $resultStr = json_encode($result, JSON_UNESCAPED_UNICODE);
        echo $resultStr;

        sugar_cleanup(true);
    }


    /**
     * Handles the 'getDefinedActions' action to retrieve all actions defined in the Advanced Web Forms system
     */
    public function action_getDefinedActions()
    {
        // Ensure return json 
        header('Content-Type: application/json');

        require_once "modules/stic_Advanced_Web_Forms/core/includes.php";
        $serverActions = ActionDiscoveryService::discoverActions();

        $actionDTOs = [];
        foreach ($serverActions as $actionDef) {
            try {
                $actionDTOs[] = new ActionDefinitionDTO($actionDef);
            } catch (\Throwable $t) {
                $GLOBALS['log']->error("Line ".__LINE__.": ".__METHOD__.": Error processing ActionDefinitionDTO for the action {$actionDef->getName()}: " . $t->getMessage());
            }
        }

        $resultStr = json_encode($actionDTOs, JSON_UNESCAPED_UNICODE);
        echo $resultStr;

        sugar_cleanup(true);
    }
    
    private function renderOutput(string $recordId, bool $isPreview, ?array $configData = null)
    {
        require_once 'modules/stic_Advanced_Web_Forms/core/FormRenderService.php';

        if (ob_get_length()) { 
            ob_clean(); 
        }
        
        try {
            $service = new FormRenderService();
            echo $service->render($recordId, $isPreview, $configData);
        } catch (Exception $e) {
            $GLOBALS['log']->error("Line ".__LINE__.": ".__METHOD__. "Error rendering form: ". $e->getMessage());
            die($e->getMessage());
        }

        sugar_cleanup(true);
    }

    /**
     * Handles the 'renderForm' action to retrieve the form HTML
     */
    public function action_renderForm() 
    {
        $this->renderOutput($_REQUEST['record'] ?? '', false);
    }

    /**
     * Handles the 'renderPreviewForm' action to retrieve the form HTML in preview mode
     */
    public function action_renderPreviewForm()
    {
        $this->renderOutput($_REQUEST['record'] ?? '', true);
    }

    /**
     * Handles the 'renderPreview' action to retrieve the form HTML from JSON (for Live Preview)
     */
    public function action_renderPreview()
    {
        // Live Preview from sent JSON
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (!$data || !isset($data['config'])) {
            die("Error: No config data received");
        }

        $formId = $data['id'] ?? 'preview'; 
        $configData = json_decode($data['config'], true);

        $this->renderOutput($formId, true, $configData);
    }
}