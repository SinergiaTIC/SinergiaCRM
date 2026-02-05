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
 * RedirectSummaryPageAction
 *
 * Terminal action that redirects the user's browser to a page with the summary of the form data
 *
 * Implements ITerminalAction to stop the execution of the flow
 */
class RedirectSummaryPageAction extends HookActionDefinition implements ITerminalAction
{
    public function __construct()
    {
        $this->isActive = true;
        $this->isUserSelectable = true;
        $this->category = 'navigation';
        $this->baseLabel = 'LBL_REDIRECT_SUMMARY_PAGE_ACTION';
    }

    /**
     * Defines the parameters that will be displayed in the wizard.
     */
    public function getParameters(): array
    {
        return [];
    }

    /**
     * Executes the redirect logic.
     */
    public function execute(ExecutionContext $context, FormAction $actionConfig): ActionResult
    {
        $summaryHtml = stic_AWFUtils::generateSummaryHtml($context);
        
        $result = new ActionResult(ResultStatus::OK, $actionConfig, "Redirecting to Summary Page");
        $result->setData(array (
            'summaryHtml' => $summaryHtml
        ));
        return $result;

        if (!defined('sugarEntry') || !sugarEntry) {
            define('sugarEntry', true);
        }
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
        $summaryHtml = $data['summaryHtml'];

        // Clear the output buffer to remove previous warnings or errors
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Avoid showing new errors or warnings
        ini_set('display_errors', 0);
        error_reporting(0);

        echo $summaryHtml;
        exit;
    }

}