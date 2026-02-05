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
 * RedirectAction
 *
 * Terminal action that redirects the user's browser to a specific URL
 * Optionally, it can attach form data to the request via GET or POST.
 *
 * Implements ITerminalAction to stop the execution of the flow
 */
class RedirectAction extends HookActionDefinition implements ITerminalAction
{
    public function __construct()
    {
        $this->isActive = true;
        $this->isUserSelectable = true;
        $this->category = 'navigation';
        $this->baseLabel = 'LBL_REDIRECT_ACTION';
    }

    /**
     * Defines the parameters that will be displayed in the wizard.
     */
    public function getParameters(): array
    {
        // The target URL (required)
        $paramUrl = new ActionParameterDefinition();
        $paramUrl->name = 'url';
        $paramUrl->text = $this->translate('URL_TEXT'); 
        $paramUrl->description = $this->translate('URL_DESC');
        $paramUrl->type = ActionParameterType::VALUE;
        $paramUrl->dataType = ActionDataType::URL;
        $paramUrl->required = true;

        // The send method (GET / POST)
        $paramMethod = new ActionParameterDefinition();
        $paramMethod->name = 'method';
        $paramMethod->text = $this->translate('METHOD_TEXT');
        $paramMethod->description = $this->translate('METHOD_DESC');
        $paramMethod->type = ActionParameterType::VALUE;
        $paramMethod->dataType = ActionDataType::SELECT;
        $paramMethod->required = true;
        $paramMethod->defaultValue = 'POST';
        $paramMethod->options = [
            new ActionParameterOption('GET', $this->translate('METHOD_GET_TEXT')),
            new ActionParameterOption('POST', $this->translate('METHOD_POST_TEXT'))
        ];
        
        // The fields to send (optional)
        $paramFields = new ActionParameterDefinition();
        $paramFields->name = 'fields_to_send';
        $paramFields->text = $this->translate('FIELDS_TEXT');
        $paramFields->description = $this->translate('FIELDS_DESC');
        $paramFields->type = ActionParameterType::FIELD_LIST;
        $paramFields->required = false; 

        return [$paramUrl, $paramMethod, $paramFields];
    }

    /**
     * Executes the redirect logic.
     */
    public function execute(ExecutionContext $context, FormAction $actionConfig): ActionResult
    {
        // Get the parameters
        $url = $actionConfig->getResolvedParameter('url');
        $method = $actionConfig->getResolvedParameter('method'); 
        $fields_to_send = $actionConfig->getResolvedParameter('fields_to_send') ?? [];

        $result = new ActionResult(ResultStatus::OK, $actionConfig, "Redirecting to {$url}");
        $result->setData(array (
            'url' => $url,
            'method' => $method,
            'fields_to_send' => $fields_to_send
        ));
        return $result;
    }

    /**
     * Called only if execute() was successful.
     * This is where the 'exit', 'header' or HTML is rendered, losing control of execution.
     * 
     * @param ExecutionContext $context Execution context of the action
     * @param ActionResult Result of the execution of the action (last ActionResult)
     */
    public function performTerminal(ExecutionContext $context, ActionResult $executionResult): void {
        // Recover parameters from $executionResult
        $data = $executionResult->getData();
        $url = $data['url'];
        $method = $data['method'];
        $fields_to_send = $data['fields_to_send'];

        // Execute the redirect
        if ($method === 'POST' && !empty($fields_to_send)) {
            $this->redirectWithPost($url, $fields_to_send);
            exit;
        } else {
            if (!empty($fields_to_send)) {
                // Add data to the URL
                $queryString = http_build_query($fields_to_send);
                $separator = strpos($url, '?') === false ? '?' : '&';
                $url .= $separator . $queryString;
            }
            
            // Redirect
            header("Location: " . $url);
            exit;
        }
    }

    /**
     * Función de ayuda para imprimir un formulario html para enviar automáticamente
     */
    private function redirectWithPost(string $url, array $data): void
    {
        echo '<html>';
        echo '<head><title>'.$this->translate('REDIRECTING').'</title></head>';
        echo '<body onload="document.forms[\'redirectForm\'].submit();">';
        echo '<form name="redirectForm" action="' . htmlspecialchars($url) . '" method="POST">';
        foreach ($data as $key => $value) {
            if (is_scalar($value)) {
                echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
            }
        }
        echo '<noscript><input type="submit" value="'.$this->translate('SUBMIT_BUTTON').'"></noscript>';
        echo '</form>';
        echo '</body>';
        echo '</html>';
    }
}