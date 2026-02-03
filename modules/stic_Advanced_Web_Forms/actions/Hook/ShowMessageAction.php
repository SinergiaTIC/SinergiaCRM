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
 * ShowMessageAction
 *
 * Terminal action that redirects the user's browser to a page with a message, maintaining the form style
 *
 * Implements ITerminalAction to stop the execution of the flow
 */
class ShowMessageAction extends HookActionDefinition implements ITerminalAction
{
    public function __construct()
    {
        $this->isActive = false;
        $this->isUserSelectable = true;
        $this->category = 'navigation';
        $this->baseLabel = 'LBL_SHOW_MESSAGE_ACTION';
    }

    /**
     * Defines the parameters that will be displayed in the wizard.
     */
    public function getParameters(): array
    {
        $paramTitle = new ActionParameterDefinition();
        $paramTitle->name = 'title';
        $paramTitle->text = $this->translate('TITLE_TEXT');
        $paramTitle->defaultValue = translate('LBL_THEME_PROCESSED_FORM_TITLE_VALUE', 'stic_Advanced_Web_Forms');
        $paramTitle->type = ActionParameterType::VALUE;
        $paramTitle->dataType = ActionDataType::TEXT;
        $paramTitle->required = true;

        $paramBody = new ActionParameterDefinition();
        $paramBody->name = 'message';
        $paramBody->text = $this->translate('MESSAGE_TEXT');
        $paramBody->defaultValue = translate('LBL_THEME_PROCESSED_FORM_TEXT_VALUE', 'stic_Advanced_Web_Forms');                                                     
        $paramBody->type = ActionParameterType::VALUE;
        $paramBody->dataType = ActionDataType::TEXTAREA; 
        $paramBody->required = true;

        return [$paramTitle, $paramBody];
    }

    /**
     * Executes the logic of the redirect to page with the message.
     */
    public function execute(ExecutionContext $context, FormAction $actionConfig): ActionResult
    {
        if (!defined('sugarEntry') || !sugarEntry) {
            define('sugarEntry', true);
        }

        // Clean the output buffer to remove previous warnings or errors
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Avoid showing new errors or warnings
        ini_set('display_errors', 0);
        error_reporting(0);

        $title = $actionConfig->getResolvedParameter('title');
        $message = $actionConfig->getResolvedParameter('message');

        stic_AWFUtils::renderGenericResponse($context->formConfig, $title, $message);

        // Terminal action: we stop script execution
        exit;

        // This code will not be executed, it is introduced to avoid having execute without return
        return new ActionResult(ResultStatus::OK, $actionConfig);
    }

}