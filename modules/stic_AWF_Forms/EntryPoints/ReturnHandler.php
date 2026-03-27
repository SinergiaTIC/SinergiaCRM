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
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once "modules/stic_AWF_Forms/core/includes.php";
require_once "modules/stic_AWF_Deferred_Tickets/stic_AWF_Deferred_Tickets.php";
require_once "modules/stic_Web_Forms/Catcher/FormConfig.php";

/**
 * EntryPoint: ReturnHandler
 * Handles the user return from external platform (like a payment gateway after payment attempt).
 * Displays appropriate page based on ticket status: success, error, waiting, or cancelled.
 *
 * Flow:
 *   1. Read token from request
 *   2. Find ticket by token_hash
 *   3. If not found: show error page
 *   4. If found: show page based on ticket status
 *   5. Load form config for custom messages if available
 */
class ReturnHandler
{
    public function run(): void
    {
        $token = $_REQUEST['token'] ?? '';
        $status = $_REQUEST['status'] ?? '';

        if (empty($token)) {
            $this->renderErrorPage('Invalid request: missing token');
            return;
        }

        $ticket = BeanFactory::getBean('stic_AWF_Deferred_Tickets');
        $ticket->retrieve_by_string_fields(['token_hash' => $token]);

        if (empty($ticket->id)) {
            $this->renderErrorPage('Invalid token or expired session');
            return;
        }

        $ticketStatus = $ticket->status ?? 'pending';

        if (!empty($status)) {
            $ticketStatus = $status;
        }

        $formConfig = null;
        $formBean = null;
        $formId = null;

        if (!empty($ticket->form_id)) {
            $formId = $ticket->form_id;
        } elseif (!empty($ticket->stic_awf_responses_id_c)) {
            $responseBean = BeanFactory::getBean('stic_AWF_Responses', $ticket->stic_awf_responses_id_c);
            if ($responseBean && !empty($responseBean->id)) {
                $responseBean->load_relationship('stic_69c1s_responses');
                $relatedForms = $responseBean->stic_69c1s_responses->getBeans();
                if (!empty($relatedForms)) {
                    $formBean = reset($relatedForms);
                    $formId = $formBean->id ?? null;
                }
            }
        }

        if (empty($formId) && !empty($ticket->context_data)) {
            $contextData = json_decode($ticket->context_data, true);
            $formId = $contextData['form_id'] ?? null;
        }

        if ($formId) {
            $formBean = BeanFactory::getBean('stic_AWF_Forms', $formId);
            if ($formBean && !empty($formBean->configuration)) {
                $formConfig = FormConfig::fromJsonArray(
                    json_decode(html_entity_decode($formBean->configuration), true)
                );
            }
        }

        switch ($ticketStatus) {
            case 'resolved':
            case 'success':
                $this->renderSuccessPage($formConfig, $ticket);
                break;
            case 'failed':
            case 'error':
                $this->renderErrorPage(null, $formConfig, $ticket);
                break;
            case 'pending':
            case 'processing':
                $this->renderWaitingPage($formConfig, $ticket);
                break;
            case 'cancelled':
                $this->renderCancelledPage($formConfig, $ticket);
                break;
            default:
                $this->renderErrorPage('Unknown payment status', $formConfig, $ticket);
        }
    }

    private function renderSuccessPage(?FormConfig $formConfig, stic_AWF_Deferred_Tickets $ticket): void
    {
        $title = $formConfig->layout->success_return_title ?? 'Payment Successful';
        $message = $formConfig->layout->success_return_text ?? 'Thank you for your payment. Your transaction has been completed successfully.';

        $this->renderPage($title, $message, 'success', $ticket);
    }

    private function renderErrorPage(?string $customMessage, ?FormConfig $formConfig = null, stic_AWF_Deferred_Tickets $ticket = null): void
    {
        if ($customMessage) {
            $title = 'Payment Failed';
            $message = $customMessage;
        } else {
            $title = $formConfig->layout->error_return_title ?? 'Payment Failed';
            $message = $formConfig->layout->error_return_text ?? 'There was a problem processing your payment. Please try again or contact support.';
        }

        $this->renderPage($title, $message, 'error', $ticket);
    }

    private function renderWaitingPage(?FormConfig $formConfig, stic_AWF_Deferred_Tickets $ticket): void
    {
        $title = $formConfig->layout->waiting_return_title ?? 'Processing Payment';
        $message = $formConfig->layout->waiting_return_text ?? 'Your payment is being processed. This may take a few moments. Please do not close this page.';

        $this->renderPage($title, $message, 'waiting', $ticket);
    }

    private function renderCancelledPage(?FormConfig $formConfig, stic_AWF_Deferred_Tickets $ticket): void
    {
        $title = $formConfig->layout->cancelled_return_title ?? 'Payment Cancelled';
        $message = $formConfig->layout->cancelled_return_text ?? 'Your payment was cancelled. You can try again or contact support.';

        $this->renderPage($title, $message, 'cancelled', $ticket);
    }

    private function renderPage(string $title, string $message, string $type, ?stic_AWF_Deferred_Tickets $ticket): void
    {
        $siteUrl = $this->getSiteUrl();
        
        $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 40px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        .success .icon { color: #28a745; }
        .error .icon { color: #dc3545; }
        .waiting .icon { color: #ffc107; }
        .cancelled .icon { color: #6c757d; }
        h1 {
            margin: 0 0 15px;
            font-size: 24px;
            color: #333;
        }
        p {
            color: #666;
            line-height: 1.6;
            margin: 0;
        }
        .retry-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
        }
        .retry-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container {$type}">
        <div class="icon">{$this->getIcon($type)}</div>
        <h1>{$title}</h1>
        <p>{$message}</p>
HTML;

        if ($type === 'error' && $ticket && $formId) {
            $retryUrl = $siteUrl . '/index.php?module=stic_AWF_Forms&action=RenderForm&id=' . $formId;
            $html .= '<a href="' . htmlspecialchars($retryUrl) . '" class="retry-btn">Try Again</a>';
        }

        $html .= <<<HTML
    </div>
</body>
</html>
HTML;

        echo $html;
    }

    private function getIcon(string $type): string
    {
        switch ($type) {
            case 'success':
                return '✓';
            case 'error':
                return '✕';
            case 'waiting':
                return '⏳';
            case 'cancelled':
                return '⊘';
            default:
                return '?';
        }
    }

    private function getSiteUrl(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $protocol . '://' . $host;
    }
}

$handler = new ReturnHandler();
$handler->run();
