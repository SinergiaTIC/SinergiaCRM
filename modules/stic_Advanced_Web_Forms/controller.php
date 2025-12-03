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
    

    public function action_generateFormHtml() 
    {
        require_once "modules/stic_Advanced_Web_Forms/core/includes.php";

        $recordId = $_REQUEST['record'];
        $bean = BeanFactory::getBean('stic_Advanced_Web_Forms', $recordId);
        
        if (!$bean) die("Error: Formulari no trobat");

        // 1. Parsejar Config
        $configArray = json_decode($bean->configuration, true);
        $formConfig = FormConfig::fromJsonArray($configArray);

        // 2. Instanciar Generador
        $generator = new FormHtmlGeneratorService();
        
        // 3. Definir URL de destí (on s'envia el form)
        // Hauria de ser l'EntryPoint de processament
        global $sugar_config;
        $siteUrl = $sugar_config['site_url'];
        $actionUrl = "{$siteUrl}/index.php?entryPoint=stic_AWF_response_handler&form_id={$bean->id}";

        // 4. Generar i retornar
        $html = $generator->generate($formConfig, $bean->id, $actionUrl);
        
        // Netejar qualsevol output previ (molt important per descarregar fitxers nets)
        ob_clean();
        echo $html;
        sugar_cleanup(true);
    }

    /**
     * Genera i mostra la previsualització del formulari.
     * Obre el formulari en mode "aïllat" (sense la interfície de SuiteCRM al voltant).
     */
    public function action_preview()
    {
        // 1. Validació de seguretat bàsica
        if (empty($_REQUEST['record'])) {
            die("Error: Manca l'identificador del registre.");
        }

        // 2. Carregar el Bean del formulari
        $recordId = $_REQUEST['record'];
        $bean = BeanFactory::getBean('stic_Advanced_Web_Forms', $recordId);

        if (!$bean || empty($bean->id)) {
            die("Error: No s'ha trobat el formulari.");
        }

        // 3. Carregar dependències
        // Assegura't de carregar les definicions de classes (FormConfig, etc.) i el Generador
        require_once "modules/stic_Advanced_Web_Forms/core/includes.php"; 
        require_once "modules/stic_Advanced_Web_Forms/core/FormHtmlGeneratorService.php";

        // 4. Parsejar la configuració JSON
        $jsonConfig = $bean->configuration;
        
        // Si el JSON està buit, inicialitzem un array buit per evitar errors
        $configData = json_decode(html_entity_decode($jsonConfig), true) ?? [];

        try {
            // Convertim l'array a objectes tipats (DTOs)
            $formConfig = FormConfig::fromJsonArray($configData);
        } catch (\Throwable $e) {
            die("Error en processar la configuració del formulari: " . $e->getMessage());
        }

        // 5. Definir la URL d'Acció (on s'envia el formulari)
        // Per a la preview, apuntem al EntryPoint real perquè l'usuari pugui provar l'enviament.
        // Si volguessis desactivar l'enviament a la preview, podries posar '#' o una url dummy.
        global $sugar_config;
        $siteUrl = rtrim($sugar_config['site_url'], '/');
        $actionUrl = "{$siteUrl}/index.php?entryPoint=stic_AWF_response_handler&form_id={$bean->id}";

        // 6. Generar l'HTML
        $generator = new FormHtmlGeneratorService();
        
        // Passem el FormConfig i l'ID per al scoping CSS
        $html = $generator->generate($formConfig, $bean->id, $actionUrl);

        // 7. Netejar i Mostrar
        // ob_clean() elimina qualsevol sortida prèvia de SuiteCRM (headers, espais en blanc)
        // per assegurar que només servim l'HTML del formulari.
        if (ob_get_length()) ob_clean();
        
        echo $html;

        // Aturem l'execució de SuiteCRM aquí perquè no imprimeixi el footer del CRM
        sugar_cleanup(true);
    }
}