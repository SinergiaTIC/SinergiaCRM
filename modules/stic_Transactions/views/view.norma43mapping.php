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

require_once 'SticInclude/Views.php';

class stic_TransactionsViewNorma43Mapping extends SugarView
{
    public function __construct()
    {
        parent::__construct();
    }

    public function preDisplay()
    {
        parent::preDisplay();

        SticViews::preDisplay($this);

        // Write here you custom code

    }

    public function display()
    {
        global $app_list_strings, $current_language, $mod_strings, $app_strings;

        // Verify that there is data in session
        if (!isset($_SESSION['norma43_summary']) || !isset($_SESSION['norma43_parsed_accounts'])) {
            SugarApplication::redirect('index.php?module=stic_Transactions&action=uploadNorma43');
            return;
        }

        require_once 'modules/stic_Transactions/importNorma43.php';

        // Retrieve data from session
        $summary = $_SESSION['norma43_summary'];
        $parsedAccounts = $_SESSION['norma43_parsed_accounts'];

        // Get the first product and the first movement as example
        $firstAccount = !empty($parsedAccounts) ? $parsedAccounts[0] : null;
        $firstMovement = (!empty($firstAccount['movements'])) ? $firstAccount['movements'][0] : null;

        $mod_strings_financial_product = return_module_language($current_language, 'stic_Financial_Products');

        // Prepare data of the bank account
        $accountData = [];
        if ($firstAccount) {
            $accountData = [
                'start_date' => [
                    'label' => $mod_strings_financial_product['LBL_START_DATE'],
                    'value' => $firstAccount['start_date'] ?? 'N/A',
                    'norma_field' => 'start_date'
                ],
                'iban' => [
                    'label' => $mod_strings_financial_product['LBL_IBAN'],
                    'value' => $firstAccount['iban'] ?? 'N/A',
                    'norma_field' => 'iban'
                ],
                'account_name' => [
                    'label' => $mod_strings_financial_product['LBL_NAME'],
                    'value' => $firstAccount['account_name'] ?? 'N/A',
                    'norma_field' => 'name'
                ],
                'entity' => [
                    'label' => $mod_strings_financial_product['LBL_ENTITY'],
                    'value' => Norma43::parseEntity($firstAccount['entity_code']) ?? 'N/A',
                    'norma_field' => 'entity'
                ],
                'type' => [
                    'label' => $mod_strings_financial_product['LBL_TYPE'],
                    'value' => $firstAccount['type'] ?? $app_list_strings['stic_financial_products_types_list']['current_account'],
                    'norma_field' => 'type'
                ],
                'initial_balance' => [
                    'label' => $mod_strings_financial_product['LBL_INITIAL_BALANCE'],
                    'value' => number_format($firstAccount['initial_balance'] ?? 0, 2, ',', '.') . ' €',
                    'norma_field' => 'initial_balance'
                ],
                'final_balance' => [
                    'label' => $mod_strings_financial_product['LBL_CURRENT_BALANCE'],
                    'value' => number_format($firstAccount['final_balance'] ?? $firstAccount['initial_balance'] ?? 0, 2, ',', '.') . ' €',
                    'norma_field' => 'final_balance'
                ]
            ];
        }

        // Prepare data of the first transaction
        $movementData = [];
        if ($firstMovement) {
            $mapped = Norma43::mapMovementData($firstMovement);
            $movementData = [
                'transaction_date' => [
                    'label' => $mod_strings['LBL_TRANSACTION_DATE'],
                    'value' => $mapped['transaction_date'] ?? 'N/A',
                    'norma_field' => 'operation_date'
                ],
                'name' => [
                    'label' => $mod_strings['LBL_NAME'],
                    'value' => $mapped['name'] ?? 'N/A',
                    'norma_field' => 'complementary'
                ],
                'amount' => [
                    'label' => $mod_strings['LBL_AMOUNT'],
                    'value' => $mapped['amount_formatted'] ?? 'N/A',
                    'norma_field' => 'amount'
                ],
                'type' => [
                    'label' => $mod_strings['LBL_TYPE'],
                    'value' => $mapped['type'] === 'income' ? 'Ingreso' : 'Gasto',
                    'norma_field' => 'type'
                ],
                'payment_method' => [
                    'label' => $mod_strings['LBL_PAYMENT_METHOD'],
                    'value' => $app_list_strings['stic_payments_methods_list'][$mapped['payment_method']] ?? $app_list_strings['stic_payments_methods_list'][''],
                    'norma_field' => 'payment_method'
                ]
            ];
        }

        // Getting available fields from stic_Financial_Products module
        $productBean = BeanFactory::newBean('stic_Financial_Products');
        $productFields = [];
        foreach ($productBean->field_defs as $fieldName => $fieldDef) {
            if (!empty($fieldDef['name']) && empty($fieldDef['source']) && $fieldName !== 'id') {
                $label = !empty($fieldDef['vname']) ? translate($fieldDef['vname'], 'stic_Financial_Products') : $fieldName;
                $productFields[$fieldName] = $label;
            }
        }

        // Getting available fields from stic_Transactions module
        $transactionBean = BeanFactory::newBean('stic_Transactions');
        $transactionFields = [];
        foreach ($transactionBean->field_defs as $fieldName => $fieldDef) {
            if (!empty($fieldDef['name']) && empty($fieldDef['source']) && $fieldName !== 'id') {
                $label = !empty($fieldDef['vname']) ? translate($fieldDef['vname'], 'stic_Transactions') : $fieldName;
                $transactionFields[$fieldName] = $label;
            }
        }

        // Sugested mappings based on common field names (Financial Product)
        $suggestedProductMapping = [
            'start_date' => 'start_date',
            'iban' => 'iban',
            'account_name' => 'name',
            'type' => 'type',
            'initial_balance' => 'initial_balance',
            'final_balance' => 'current_balance',
            'entity' => 'entity'
        ];

        // Sugested mappings based on common field names (Transaction)
        $suggestedTransactionMapping = [
            'transaction_date' => 'transaction_date',
            'name' => 'document_name',
            'amount' => 'amount',
            'type' => 'type',
            'payment_method' => 'payment_method'
        ];

        // Assign variables to Smarty
        $this->ss->assign('ACCOUNT_DATA', $accountData);
        $this->ss->assign('MOVEMENT_DATA', $movementData);
        $this->ss->assign('PRODUCT_FIELDS', $productFields);
        $this->ss->assign('TRANSACTION_FIELDS', $transactionFields);
        $this->ss->assign('SUGGESTED_PRODUCT_MAPPING', $suggestedProductMapping);
        $this->ss->assign('SUGGESTED_TRANSACTION_MAPPING', $suggestedTransactionMapping);
        $this->ss->assign('APP_LIST', $app_list_strings);
        $this->ss->assign('APP', $app_strings);
        $this->ss->assign('MOD', $mod_strings);
        $this->ss->assign('PRODUCT_STRINGS', $mod_strings_financial_product);

        $this->ss->display('modules/stic_Transactions/tpls/norma43mapping.tpl');
    }
}
