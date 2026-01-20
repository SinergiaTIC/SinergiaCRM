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
// Prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once "modules/stic_Advanced_Web_Forms/core/includes.php"; 
require_once "modules/stic_Advanced_Web_Forms/core/FormHtmlGeneratorService.php";

class FormRenderService {

    /**
     * @param string $recordId Id del registro (obligado si configData está vacío)
     * @param bool $isPreview Si es modo de previsualización
     * @param array|null $configData Configuración directa (para Live Preview)
     * @return string El HTML generado
     * @throws Exception
     */
    public function render(string $recordId, bool $isPreview, ?array $configData = null): string
    {
        // Get ConfigData
        if ($configData === null) {
            if (empty($recordId)) {
                throw new Exception("Record ID missing for DB generation.");
            }

            $bean = BeanFactory::getBean('stic_Advanced_Web_Forms', $recordId);
            if (!$bean || empty($bean->id)) {
                throw new Exception("Form not found with id: {$recordId}");
            }

            $jsonConfig = $bean->configuration;
            $decodedJson = html_entity_decode($jsonConfig);
            $configData = json_decode($decodedJson, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $errorMsg = json_last_error_msg();
                $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Invalid JSON: (ID: $recordId): $errorMsg");
                throw new Exception("Form Configuration is corrupted: $errorMsg");
            }
            
            if (!is_array($configData)) {
                $configData = [];
            }
        }

        // Process ConfigData
        try {
            $formConfig = FormConfig::fromJsonArray($configData);
        } catch (\Throwable $e) {
            throw new Exception("Error processing config Form: " . $e->getMessage());
        }

        // Define action url (submit url)
        global $sugar_config;
        $siteUrl = rtrim($sugar_config['site_url'], '/');
        $actionUrl = "{$siteUrl}/index.php?entryPoint=stic_AWF_responseHandler&id={$recordId}";

        // Generate url and return it
        $generator = new FormHtmlGeneratorService();
        $html = $generator->generate($formConfig, $recordId, $actionUrl, $isPreview);
        return $html;
    }
}