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

/**
 * Report not authorized recurring payment commitments. Conditions:
 *   - Payment date is prior to first day of the last month (don't report payments in current or last months)
 *   - No value in the field that identifies recurrence
 *   - Periodicity other than punctual
 *   - Payment method is card or paypal
 *   - Payment status is pending
 */
class ReportExpiringCards extends DataCheckFunction 
{
    /**
     * Receive an SQL proposal and modify it with the particularities necessary for the function.
     * Most functions should overwrite this method.
     * @param $actionBean Bean of the action in which the function is being executed.
     * @param $proposedSQL Array generated automatically (if possible) with the keys select, from, where and order_by.
     * @return string
     */
    public function prepareSQL(stic_Validation_Actions $actionBean, $proposedSQL) 
    {

        $sql = "SELECT spc.name, spc.id, spc.card_expiry_date, assigned_user_id
            FROM stic_payment_commitments spc 
            where payment_method = 'card'
            and (end_date is null or end_date > concat(lpad(date_format(card_expiry_date, '%y'), 2, '0'), lpad(month(card_expiry_date), 2, '0')))
            and card_expiry_date <= concat(lpad(date_format(CURRENT_DATE(), '%y'), 2, '0'), lpad(month(CURRENT_DATE()), 2, '0'))
            and periodicity <> 'punctual'
            and deleted = 0
        ";

        return $sql;
    }

    /**
     * DoAction function
     * Perform the action defined in the function
     * @param $records Set of records on which the validation action is to be applied 
     * @param $actionBean stic_Validation_Actions Bean of the action in which the function is being executed.
     * @return boolean It will return true in case of success and false in case of error.
     */
    public function doAction($records, stic_Validation_Actions $actionBean) 
    {
        global $app_list_strings;
        // It will indicate if records with errors have been found.
        $errors = 0;
        
        $resultInfo = $this->getLabel('RESULT_INFO'); // keep here
        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": Reporting ExpiringCards (stic_Payment_Commitments) field SQL results: " . $resultInfo);
        
        while ($row = array_pop($records)) 
        {
            // Set payment commitment end date
            $PCBean = Beanfactory::getBean('stic_Payment_Commitments', $row['id']);

            $paymentMethod=$app_list_strings['stic_payments_methods_list'][$PCBean->payment_method]; 
            $resultInfo = str_replace('@expiring_date@',$row['card_expiry_date'],$resultInfo);

            $errorMsg = '<span style="color:#e85d04;">' . $resultInfo . '</span>';
            $data = array(
                'name' => $this->getLabel('NAME') . ' - ' . $resultInfo,
                'stic_validation_actions_id' => $actionBean->id,
                'log' => '<div>' . $errorMsg . '</div>',
                'parent_type' => $this->functionDef['module'],
                'parent_id' => $row['id'],
                'reviewed' => 'no',   
                'assigned_user_id' => $row['assigned_user_id'],
            );
            $this->logValidationResult($data);
            $errors++;
        }
        
        // Report_always
        global $current_user;
        if (!$errors && $actionBean->report_always) {
            $errorMsg = $this->getLabel('NO_ERRORS');
            $data = array(
                'name' => $errorMsg,
                'stic_validation_actions_id' => $actionBean->id,
                'log' => '<div>' . $errorMsg . '</div>',
                'reviewed' => 'not_necessary',              
                'assigned_user_id' => $current_user->id, // In this message we indicate the administrator user
            );
            $this->logValidationResult($data);
        }

        return true;
    }
}
