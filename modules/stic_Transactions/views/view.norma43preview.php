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

class stic_TransactionsViewNorma43Preview extends SugarView
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

        // Save mapping in session if it comes from the previous step
        if (isset($_REQUEST['mapping'])) {
            $_SESSION['norma43_mapping'] = $_REQUEST['mapping'];
        }

        require_once 'SticInclude/Utils.php';
        
        if (isset($_SESSION['norma43_summary'])) {
            $data = $_SESSION['norma43_summary'];

            // Loop to format new movements
            if (!empty($data['new_movements'])) {
                foreach ($data['new_movements'] as &$mov) {
                    // Format the amount using your function
                    if (class_exists('SticUtils')) {
                        $mov['amount_formatted'] = SticUtils::formatDecimalInConfigSettings($mov['amount'], true) . ' €';
                    } else {
                        $mov['amount_formatted'] = number_format($mov['amount'], 2, ',', '.') . ' €';
                    }
                    
                    // Format the date from YYYY-MM-DD to DD-MM-YYYY
                    if (!empty($mov['transaction_date'])) {
                        $date = new DateTime($mov['transaction_date']);
                        $mov['transaction_date_formatted'] = $date->format('d-m-Y');
                    } else {
                        $mov['transaction_date_formatted'] = ''; // If there is no date, save an empty string
                    }

                    if(!empty($mov['payment_method'])) {
                        $mov['payment_method'] = $app_list_strings['stic_payments_methods_list'][$mov['payment_method']];
                    } else {
                        $mov['payment_method'] = $app_list_strings['stic_payments_methods_list'][''];
                    }
                }
            }
            unset($mov); // Remove the reference

            // Loop to format duplicates
            if (!empty($data['duplicates'])) {
                foreach ($data['duplicates'] as &$dup) {
                    // Format the amount
                    if (class_exists('SticUtils')) {
                        $dup['amount_formatted'] = SticUtils::formatDecimalInConfigSettings($dup['amount'], true) . ' €';
                    } else {
                         $dup['amount_formatted'] = number_format($dup['amount'], 2, ',', '.') . ' €';
                    }
                    
                    // Format the date
                    if (!empty($dup['transaction_date'])) {
                        $date = new DateTime($dup['transaction_date']);
                        $dup['transaction_date_formatted'] = $date->format('d-m-Y');
                    } else {
                        $dup['transaction_date_formatted'] = '';
                    }

                    if(!empty($dup['payment_method'])) {
                        $dup['payment_method'] = $app_list_strings['stic_payments_methods_list'][$dup['payment_method']];
                    } else {
                        $dup['payment_method'] = $app_list_strings['stic_payments_methods_list'][''];
                    }
                }
            }
            unset($dup); // Remove the reference

            $mod_strings_financial_product = return_module_language($current_language, 'stic_Financial_Products');

            // Assign variables to the template
            $this->ss->assign('DATA', $data);
            $this->ss->assign('APP_LIST', $app_list_strings);
            $this->ss->assign('APP', $app_strings);
            $this->ss->assign('MOD', $mod_strings);
            $this->ss->assign('PRODUCT_STRINGS', $mod_strings_financial_product);
        } else {
            // If there is no data in the session, redirect to the upload step
            SugarApplication::redirect('index.php?module=stic_Transactions&action=uploadNorma43');
        }
        $this->ss->display('modules/stic_Transactions/tpls/norma43preview.tpl');
    }
}
