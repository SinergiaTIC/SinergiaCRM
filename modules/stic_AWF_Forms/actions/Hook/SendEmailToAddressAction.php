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

include_once "modules/stic_AWF_Forms/actions/coreActions.php";

/**
 * SendEmailToAddressAction
 *
 * Action that sends an email to a custom address.
 */
class SendEmailToAddressAction extends HookActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->isUserSelectable = true;
        $this->defaultContinueOnError = true;
        $this->category = 'communication';
        $this->baseLabel = 'LBL_SEND_EMAIL_TO_ADDRESS_ACTION';
    }

    /**
     * Defines the parameters that will be displayed in the wizard.
     */
    public function getParameters(): array
    {
        // The email address to send
        $paramEmail = new ActionParameterDefinition();
        $paramEmail->name = 'email';
        $paramEmail->text = $this->translate('EMAIL_TEXT'); 
        $paramEmail->type = ActionParameterType::VALUE;
        $paramEmail->dataType = ActionDataType::EMAIL;
        $paramEmail->required = true;

        // The email template to use
        $paramTemplate = new ActionParameterDefinition();
        $paramTemplate->name = 'email_template';
        $paramTemplate->text = $this->translate('TEMPLATE_TEXT'); 
        $paramTemplate->type = ActionParameterType::CRM_RECORD;
        $paramTemplate->supportedModules = ['EmailTemplates'];
        $paramTemplate->required = true;

        return [$paramEmail, $paramTemplate];
    }

    /**
     * Executes the send email logic.
     */
    public function execute(ExecutionContext $context, FormAction $actionConfig): ActionResult
    {
        $emailAddress = $actionConfig->getResolvedParameter('email');
        // Validate that the email is correct
        if (empty($emailAddress) || !filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Email parameter is not valid or missing ('{$emailAddress}').");
        }
        
        /** @var ?BeanReference $templateRef */
        $templateRef = $actionConfig->getResolvedParameter('email_template');
        if (!$templateRef) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Email template parameter is missing.");
        }

        // Send the email
        try {
            stic_AWFUtils::sendTemplateEmail($emailAddress, $templateRef->beanId, $context);
        } catch (\Exception $e) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Error sending email: " . $e->getMessage());
        }

        // Result notification
        $actionResult = new ActionResult(ResultStatus::OK, $actionConfig, "Email sent to: {$emailAddress}");
        
        return $actionResult;
    }

}