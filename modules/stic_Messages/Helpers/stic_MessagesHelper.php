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

/**
 * Abstract base class for message helpers (SMS, WhatsApp, etc.)
 * 
 * Provides common functionality for all message providers.
 * New providers must extend this class and implement the abstract methods.
 */
abstract class stic_MessagesHelper {

    /**
     * Whether this helper is active and configured
     */
    protected bool $active = false;

    /**
     * Provider configuration loaded from settings
     */
    protected array $config = [];

    // -------------------------------------------------------------------------
    // Abstract methods - each provider MUST implement these
    // -------------------------------------------------------------------------

    /**
     * Returns the provider name identifier (e.g., 'sms', 'whatsapp').
     * Used as default for getHelperType() and getTemplateType().
     */
    abstract protected function getProviderName(): string;

    /**
     * Returns the list of required setting keys for this provider.
     * Used by loadConfig() to fetch settings from stic_Settings.
     */
    abstract protected function getRequiredSettings(): array;

    /**
     * Performs the actual API call to send the message.
     * 
     * @param array $params Associative array with:
     *   - 'from': Sender identifier
     *   - 'text': Message body
     *   - 'to': Recipient phone number
     *   - Plus any provider-specific params
     * 
     * @return array Result with 'code' and 'message' keys
     */
    abstract protected function performApiCall(array $params): array;

    /**
     * Returns UI behavior configuration for JavaScript.
     * 
     * Used by Utils.js to determine field locking, status handling,
     * and other UI behaviors without hardcoding provider names.
     * 
     * @return array Associative array with:
     *   - 'lockSender': bool - Lock sender field on edit
     *   - 'lockMessageOnTemplate': bool - Lock message field when template selected
     *   - 'fixedStatus': string|null - Fixed status value (e.g., 'sent', 'redirected')
     *   - 'canRetry': bool - Allow retry on error
     *   - 'hideAttachment': bool - Hide attachment field
     *   - 'allowedStatus': array - List of allowed status values
     */
    abstract public function getUIConfig(): array;

    // -------------------------------------------------------------------------
    // Concrete methods - common implementation for all providers
    // -------------------------------------------------------------------------

    /**
     * Returns the helper type identifier.
     * Defaults to provider name, can be overridden if needed.
     */
    public function getHelperType(): string {
        return $this->getProviderName();
    }

    /**
     * Returns the template type used by this helper.
     * Defaults to provider name, can be overridden if needed.
     */
    public function getTemplateType(): string {
        return $this->getProviderName();
    }

    /**
     * Returns true if this helper passes template body to the external provider.
     * Override in subclasses that need this behavior (e.g., WhatsApp with Twilio templates).
     */
    public function passesTemplateBodyToProvider(): bool {
        return false;
    }

    /**
     * Sends a message through the provider.
     * 
     * @param string|null $from Sender identifier
     * @param string $text Message body
     * @param string $to Recipient phone number
     * @param mixed ...$args Additional provider-specific arguments
     * 
     * @return array Result with 'code' and 'message' keys
     */
    public function sendMessage(?string $from, string $text, string $to, ...$args): array {
        if (!$this->isActive()) {
            return $this->buildError(translate('LBL_HELPER_MODULE_NOT_ACTIVE', 'stic_Messages'));
        }

        $params = $this->buildSendParams($from, $text, $to, ...$args);
        return $this->performApiCall($params);
    }

    // -------------------------------------------------------------------------
    // Protected utility methods
    // -------------------------------------------------------------------------

    /**
     * Loads configuration from stic_Settings based on getRequiredSettings().
     */
    protected function loadConfig(): void {
        require_once('modules/stic_Settings/Utils.php');
        
        $this->config = [];
        foreach ($this->getRequiredSettings() as $settingKey) {
            $this->config[$settingKey] = stic_SettingsUtils::getSetting($settingKey);
        }
    }

    /**
     * Checks if this helper is active and properly configured.
     * Override in subclasses for custom active checks.
     */
    protected function isActive(): bool {
        return $this->active;
    }

    /**
     * Builds the parameters array for performApiCall().
     * Override in subclasses to add provider-specific parameters.
     * 
     * @param string|null $from
     * @param string $text
     * @param string $to
     * @param mixed ...$args
     * @return array
     */
    protected function buildSendParams(?string $from, string $text, string $to, ...$args): array {
        return [
            'from' => $from,
            'text' => $text,
            'to' => $to,
        ];
    }

    /**
     * Builds a standardized error result array.
     * 
     * @param string $message Error message
     * @return array ['code' => ERROR_NOT_SENT, 'message' => $message]
     */
    protected function buildError(string $message): array {
        return [
            'code' => stic_Messages::ERROR_NOT_SENT,
            'message' => $message,
        ];
    }

    /**
     * Builds a standardized success result array.
     * 
     * @param string $message Success message
     * @param array $extra Additional data to include
     * @return array ['code' => OK, 'message' => $message, ...$extra]
     */
    protected function buildSuccess(string $message, array $extra = []): array {
        return array_merge([
            'code' => stic_Messages::OK,
            'message' => $message,
        ], $extra);
    }

    /**
     * Logs an info message.
     */
    protected function logInfo(string $message): void {
        $GLOBALS['log']->info($message);
    }

    /**
     * Logs an error message.
     */
    protected function logError(string $message): void {
        $GLOBALS['log']->error($message);
    }

    /**
     * Logs a fatal error message.
     */
    protected function logFatal(string $message): void {
        $GLOBALS['log']->fatal($message);
    }
}
