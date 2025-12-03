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
    /**
     * Handles the 'saveDraft' action to Save current configuration in bean
     */
    public function action_saveDraft()
    {
        // Ensure return json 
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            echo json_encode(['success' => false, 'message' => 'Missing or invalid data']);
            sugar_cleanup(true);
        }

        $bean = null;
        if (empty($data['bean']['id'])) {
            // Create the bean
            $bean = BeanFactory::newBean('stic_Advanced_Web_Forms');
            $bean->id = $data['bean']['id'];
        } else {
            // Load the bean
            $bean = BeanFactory::getBean('stic_Advanced_Web_Forms', $data['bean']['id']);
        }

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
        $bean->configuration = $data['config'];
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
     * Handles the 'getServerActions' action to retrieve all server actions (HOOK and DEFERRED)
     */
    public function action_getServerActions()
    {
        // Ensure return json 
        header('Content-Type: application/json');

        require_once "modules/stic_Advanced_Web_Forms/core/includes.php";
        $serverActions = ActionDiscoveryService::discoverActions([ActionType::HOOK, ActionType::DEFERRED]);

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
    
    private function generateForm(string $recordId, bool $isPreview, mixed $configData=null) {
        if (empty($recordId)) {
            $GLOBALS['log']->error("Line ".__LINE__.": ".__METHOD__.": Record missing");
            die("record missing");
        }

        if ($configData == null) {
            // Generate Form from Bean
            $bean = BeanFactory::getBean('stic_Advanced_Web_Forms', $recordId);
            if (!$bean || empty($bean->id)) {
                $GLOBALS['log']->error("Line ".__LINE__.": ".__METHOD__.": Form not found with id: {$recordId}");
                die("Form not found");
            }

            $jsonConfig = $bean->configuration;
            $configData = json_decode(html_entity_decode($jsonConfig), true) ?? [];
        }

        require_once "modules/stic_Advanced_Web_Forms/core/includes.php"; 
        require_once "modules/stic_Advanced_Web_Forms/core/FormHtmlGeneratorService.php";

        try {
            $formConfig = FormConfig::fromJsonArray($configData);
        } catch (\Throwable $e) {
            $GLOBALS['log']->error("Line ".__LINE__.": ".__METHOD__.": Processing config Form: {$e->getMessage()}");
            die("Error en processing config Form: " . $e->getMessage());
        }

        // $actionUrl = "javascript:void(0);";  // Dummy url
        // if (!$isPreview) {
            global $sugar_config;
            $siteUrl = rtrim($sugar_config['site_url'], '/');
            $actionUrl = "{$siteUrl}/index.php?entryPoint=stic_AWF_response_handler&form_id={$recordId}";
        // }

        $generator = new FormHtmlGeneratorService();
        $html = $generator->generate($formConfig, $recordId, $actionUrl, $isPreview);

        if (ob_get_length()) {
            ob_clean();
        }        
        echo $html;

        sugar_cleanup(true);
    }


    /**
     * Handles the 'renderForm' action to retrieve the form HTML
     */
    public function action_renderForm() 
    {
        $this->generateForm($_REQUEST['record'] ?? '', false);
    }

    /**
     * Handles the 'renderPreviewForm' action to retrieve the form HTML in preview mode
     */
    public function action_renderPreviewForm()
    {
        $this->generateForm($_REQUEST['record'] ?? '', true);
    }

    /**
     * Handles the 'renderPreview' action to retrieve the form HTML from JSON (for Live Preview)
     */
    public function action_renderPreview()
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (!$data || !isset($data['config'])) {
            $GLOBALS['log']->error("Line ".__LINE__.": ".__METHOD__.": No config data received");
            die("Error: No config data received");
        }

        $formId = $data['id'] ?? 'preview'; 
        try {
            $configData = json_decode($data['config'], true);
            $this->generateForm($formId, true, $configData);
        } catch (\Throwable $e) {
            $GLOBALS['log']->error("Line ".__LINE__.": ".__METHOD__.": Generating Form preview: {$e->getMessage()}");
            die("Generating Form preview: " . $e->getMessage());
        }
    }

    /**
     * Handles the 'checkStatus' action to check form status from remote js.
     * Retorna JSON: { active: true/false, message: "..." }
     */
    public function action_checkStatus()
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *'); // Allow calls from remote webs
        
        $id = $_REQUEST['id'] ?? '';
        
        $db = DBManagerFactory::getInstance();
        $status = $db->getOne("SELECT status FROM stic_advanced_web_forms WHERE id = " . $db->quote($id));

        // Only 'public' is active
        $isActive = ($status === 'public');
        $message = ($status === 'closed') 
            ? translate('LBL_FORM_CLOSED_MESSAGE', 'stic_Advanced_Web_Forms') 
            : translate('LBL_FORM_UNAVAILABLE_MESSAGE', 'stic_Advanced_Web_Forms');

        echo json_encode(['active' => $isActive, 'message' => $message]);
        sugar_cleanup(true);
    }
}