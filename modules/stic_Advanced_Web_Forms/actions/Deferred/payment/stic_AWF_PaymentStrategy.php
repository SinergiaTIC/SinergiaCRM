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

abstract class stic_AWF_PaymentStrategy
{
    protected ?string $suffix = null;

    protected string $configType = ''; // 'TPV', 'STRIPE'...
    protected string $configKeyPrefix = ''; // 'TPV', 'TPVCECA', 'STRIPE'...

    protected ?array $settings = null; // Cache with loaded configurations from DB

    /**
     * Configure suffix to load alternative constants
     * Ex. Football...
     */
    public function setSuffix(string $suffix): void {
        $this->suffix = $suffix;
    }

    /**
    * Loads the configurations and resolves the values.
    * @param array $keys List of keys without prefix (ex: ['MERCHANT_CODE'])
    * @return array
    */
    protected function getConfigValues(array $keys): array {
        require_once "modules/stic_Settings/Utils.php";

        if ($this->settings === null) {
            $this->settings = stic_SettingsUtils::getSettingsByType($this->configType);
            if ($this->settings === null) {
                $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Could not load settings related to ". $this->configType);
                return [];
            }
        }

        $resolvedValues = array();
        $prefix = $this->configKeyPrefix; // Ex: 'TPV'

        foreach ($keys as $key) {
            // General Key: {PREFIX}_{KEY}  => TPV_MERCHANT_CODE
            $defaultConfigKey = $prefix . '_' . $key;
            // Initial value
            $value = isset($this->settings[$defaultConfigKey]) ? $this->settings[$defaultConfigKey] : null;

            // Key with Suffix: {PREFIX}_ALT_{SUFFIX}_{KEY} => TPV_ALT_FOOTBALL_MERCHANT_CODE
            if ($this->suffix) {
                $altConfigKey = $prefix . '_ALT_' . $this->suffix . '_' . $key;
                if (isset($this->settings[$altConfigKey]) && $this->settings[$altConfigKey] !== '') {
                    $value = $this->settings[$altConfigKey];
                }
            }
            $resolvedValues[$key] = $value;
        }

        return $resolvedValues;
    }


    abstract public function initiate(ExecutionContext $context, FormAction $actionConfig): ActionResult;
    abstract public function resolve(ExecutionContext $context, WebhookResult $webhookData): ActionResult;
    abstract public function isDeferred(): bool;
}