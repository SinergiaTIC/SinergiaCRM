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


class PaymentRouterAction extends DeferredActionDefinition implements ITerminalAction
{
    // Map payment prefixes to special treatment
    // From stic_payments_methods_list: Payment platform Strategies
    public static $specialStrategies = array(
        'bizum' => 'stic_AWF_RedsysStrategy',
        'stripe' => 'stic_AWF_StripeStrategy',
        'card' => 'stic_AWF_RedsysStrategy',
        'ceca_card' => 'stic_AWF_CecaStrategy',
        'paypal' => 'stic_AWF_PaypalStrategy',
    );

    /**
     * Returns array('strategy_class' => string, 'base_method' => string, 'suffix' => string|null)
     */
    public static function parse($methodValue)
    {
        // Special logic detection (Prefixes)
        foreach (self::$specialStrategies as $prefix => $class) {
            // "card" or "card_futbol"
            if ($methodValue === $prefix || strpos($methodValue, $prefix . '_') === 0) {
                $suffix = null;
                if (strlen($methodValue) > strlen($prefix)) {
                    $suffix = strtoupper(substr($methodValue, strlen($prefix) + 1));
                }
                return array(
                    'strategy_class' => $class,
                    'base_method' => $prefix,
                    'suffix' => $suffix
                );
            }
        }

        // Default tratment (Catch-All) 'cash', 'transfer', 'check'...
        return array(
            'strategy_class' => 'stic_AWF_OfflineStrategy',
            'base_method' => $methodValue, 
            'suffix' => null
        );
    }
}