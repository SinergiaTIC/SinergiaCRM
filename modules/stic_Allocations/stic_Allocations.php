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

require_once 'modules/stic_Allocations/Utils.php';

class stic_Allocations extends Basic
{
    public $module_dir = 'stic_Allocations';
    public $object_name = 'stic_Allocations';
    public $table_name = 'stic_allocations';
    public $new_schema = true;
    
    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $created_by;
    public $description;
    public $deleted;
    public $assigned_user_id;
    
    public $validated;
    public $blocked;
    public $type;
    public $date;
    public $payment_amount_field;
    public $percentage;
    public $amount;
    public $hours;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }

        return false;
    }

    /**
     * Overriding SugarBean save function to insert additional logic:
     * 1) Set default values for certain fields if empty
     * 2) Auto-generate name if empty based on allocation type and date
     *
     * @param boolean $check_notify
     * @return void
     */
    public function save($check_notify = false)
    {
        global $current_language;
        $allocationsModStrings = return_module_language($current_language, 'stic_Allocations'); // can not be $mod_strings because of different contexts (specially inline edition)


        $this->fillName();

        $tempFetchedRow = $this->fetched_row ?? null;

        $isBlocked = filter_var($this->blocked, FILTER_VALIDATE_BOOLEAN);
        // If record is blocked, no updates are allowed
        if ($tempFetchedRow['blocked'] && $isBlocked) {
            // TODOEPS
            if (!empty($_REQUEST['sugar_body_only']) || !empty($_REQUEST['to_pdf'])) {
                    // // This is an AJAX request
                    // ob_clean();
                    // header('HTTP/1.1 500 Internal Server Error');
                    // echo "Save aborted: " . $paymentsModStrings['LBL_BLOCKED_PAYMENT_CANNOT_BE_MODIFIED'];
                    // exit;
                    // 1. Sanitize the message for JS
                $errorMsg = $allocationsModStrings['LBL_BLOCKED_ALLOCATION_CANNOT_BE_MODIFIED'];
                $jsMsg = json_encode($errorMsg);

                // 2. Output a script to alert the user
                echo "<script>alert($jsMsg);</script>";
                echo "<script>location.reload();</script>";

                // 4. Stop execution
                exit();
                }
            SugarApplication::appendErrorMessage('<div class="msg-fatal-lock">' . $allocationsModStrings['LBL_BLOCKED_ALLOCATION_CANNOT_BE_MODIFIED'] . '</div>');
            return false;
        }


        
        // Calculate amount 
        $paymentBean = BeanFactory::getBean('stic_Payments', $this->stic_payments_stic_aleb9a);
        $this->amount = $paymentBean->{$this->payment_amount_field} * $this->percentage / 100;
        
        $oldAmount = $this->fetched_row['amount'] ?? null;
        $amountChanged = ($oldAmount !== $this->amount);

        // Save the bean
        parent::save($check_notify);

        if ($amountChanged) {
            stic_AllocationsUtils::updateJustificationsFromAllocation($this);
            stic_AllocationsUtils::updatePayment($this);
        }
    }

    /**
     * Auto-generate the name field based on allocation type and date
     */
    protected function fillName() {
        // Auto name - concatenate allocation type and date
        if (empty($this->name)) {
            global $app_list_strings;

            $nameParts = array();
            
            // Add related payment name if available
            $paymentBean = BeanFactory::getBean('stic_Payments', $this->stic_payments_stic_aleb9a);
            if ($paymentBean){
                $nameParts[] = $paymentBean->name;
            }

            $opportunityBean = BeanFactory::getBean('Opportunities', $this->opportunities_stic_allocationsopportunities_ida);
            if ($opportunityBean){
                $nameParts[] = $opportunityBean->name;
            }

            $projectBean = BeanFactory::getBean('Project', $this->project_stic_allocationsproject_ida);
            if ($projectBean){
                $nameParts[] = $projectBean->name;
            }

            $ledgerAccountBean = BeanFactory::getBean('stic_Ledger_Accounts', $this->stic_ledger_accounts_ida);
            if ($ledgerAccountBean){
                $nameParts[] = $ledgerAccountBean->name;
            }

            // Add allocation type if available
            if (!empty($this->type) && isset($app_list_strings['stic_allocations_types_list'][$this->type])) {
                $nameParts[] = $app_list_strings['stic_allocations_types_list'][$this->type];
            }
            // Add allocation payment_amount_field if available
            if (!empty($this->payment_amount_field) && isset($app_list_strings['stic_allocations_amount_fields_list'][$this->payment_amount_field])) {
                $nameParts[] = $app_list_strings['stic_allocations_amount_fields_list'][$this->payment_amount_field];
            }

            $nameParts[] = $this->percentage . '%';

            // Set the name by joining parts with a hyphen
            $this->name = implode(' - ', $nameParts);
        }
    }
}