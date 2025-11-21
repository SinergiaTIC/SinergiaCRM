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

include_once "modules/stic_Advanced_Web_Forms/actions/coreActions.php";

/**
 * SendEmailToDataBlockAction
 *
 * Acción que envía un email al registro procesado (Persona, Interesado, Usuario o Organización) contenido en un Bloque de Datos.
 */
class SendEmailToDataBlockAction extends HookBeanActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->isUserSelectable = true;
        $this->isCommon = true;

        $this->category = 'email';
        $this->baseLabel = 'LBL_SEND_EMAIL_TO_DATABLOCK_ACTION';
    }

    /**
     * Módulos soportados por la acción
     */
    protected function getSupportedModules(): array {
        return ['Contacts', 'Users', 'Prospects', 'Leads', 'Accounts'];
    }

    /**
     * Nombre del parámetro que contiene el bloque de datos.
     * @return string
     */
    protected function getDataBlockParameterText(): string {
        return $this->translate('RECIPIENT_BLOCK_TEXT');
    }

    /**
     * La descripción (help text) del parámetro de bloque de datos.
     * @return string
     */
    protected function getDataBlockParameterDescription(): string {
        return $this->translate('RECIPIENT_BLOCK_DESC');
    }

    /**
     * getCustomParameters()
     * Definición de los parámetroes ADICIONALES que son necesarios para la acción
     * El parámtreo del Bloque de Datos principal lo pide la clase padre.
     */
    protected function getCustomParameters(): array
    {
        // La Plantilla de Email a usar
        $paramTemplate = new ActionParameterDefinition();
        $paramTemplate->name = 'email_template';
        $paramTemplate->text = $this->translate('TEMPLATE_TEXT'); 
        $paramTemplate->description = $this->translate('TEMPLATE_DESC');
        $paramTemplate->type = ActionParameterType::CRM_RECORD;
        $paramTemplate->supportedModules = ['EmailTemplates'];
        $paramTemplate->required = true;

        return [$paramTemplate];
    }


    public function executeWithBean(ExecutionContext $context, FormAction $actionConfig, SugarBean $bean, DataBlockResolved $block): ActionResult
    {
        // Obtención de los parámetros adicionales (ParameterResolver asegura que no sean nulos porque son obligatorios)
        
        /** @var ?BeanReference $templateRef */
        $templateRef = $actionConfig->getResolvedParameter('email_template');
        if (!$templateRef) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Email template parameter is missing.");
        }

        // Obtenemos el email del Bean. Asumimos el campo estandard 'email1'
        $emailAddress = $bean->email1 ?? null;
        // Validamos que el email es correcto
        if (empty($emailAddress) || !filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "DataBlock '{$block->dataBlock->name}' does not have a valid 'email1' field ('{$emailAddress}').");
        }

        // Enviamos el email
        try {
            AWF_Utils::sendTemplateEmail($emailAddress, $templateRef->beanId, $bean, $context);
        } catch (\Exception $e) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Error sending email: " . $e->getMessage());
        }

        // Notificación del resultado
        $actionResult = new ActionResult(ResultStatus::OK, $actionConfig, "Email sent to: {$emailAddress}");
        $dataToLog = ['email_sent_to' => $emailAddress, 'template_id' => $templateRef->beanId];
        $actionResult->registerBeanModificationFromBlock($bean, $block, BeanModificationType::UPDATED, $dataToLog);

        return $actionResult;
    }

}