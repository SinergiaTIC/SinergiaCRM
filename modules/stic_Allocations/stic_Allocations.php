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
require_once 'modules/stic_Justifications/Utils.php';
require_once 'SticInclude/Utils.php';
#[\AllowDynamicProperties]
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
        $isValidated = filter_var($this->validated, FILTER_VALIDATE_BOOLEAN);
        // If record is blocked, no updates are allowed
        if ($tempFetchedRow && $tempFetchedRow['blocked'] && $isBlocked) {
            $this->showError('LBL_BLOCKED_ALLOCATION_CANNOT_BE_MODIFIED');
            return false;
        }
        
        // Calculate amount 
        $paymentBean = BeanFactory::getBean('stic_Payments', $this->stic_payments_stic_allocations);
        if (empty($paymentBean->{$this->payment_amount_field})) {
            // validate payment field needed is filled
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ':  ' . $allocationsModStrings['LBL_ALLOCATION_NOT_COMPATIBLE']);
            $this->showError('LBL_ALLOCATION_NOT_COMPATIBLE');
            return false;
        }
        else {
            $this->amount = SticUtils::unformatDecimal($paymentBean->{$this->payment_amount_field}) * SticUtils::unformatDecimal($this->percentage) / 100; 
        }
        
        $oldAmount = SticUtils::unformatDecimal($this->fetched_row['amount'] ?? null); 
        $newAmount = SticUtils::unformatDecimal($this->amount);
        $amountChanged = ($oldAmount !== $newAmount); 

        $oldHours = SticUtils::unformatDecimal($this->fetched_row['hours'] ?? null);
        $newHours = SticUtils::unformatDecimal($this->hours);
        $hoursChanged = ($oldHours !== $newHours);

        $validatedBeforeSave = $tempFetchedRow['validated'] ?? false;
        if($validatedBeforeSave && !$isValidated) {
            // Allocation is being un-validated, check if there are justifications linked to it
            $hasBlockedJustifications = stic_JustificationsUtils::allocationHasBlockedJustifications($this);
            if ($hasBlockedJustifications) {
                $this->showError('LBL_CANNOT_UNVALIDATE_ALLOCATION_WITH_BLOCKED_JUSTIFICATIONS');
                return false;
            }
        }


        // Save the bean
        parent::save($check_notify);

        // if ($amountChanged) {
        //     stic_AllocationsUtils::updateJustificationsFromAllocation($this);
        //     stic_AllocationsUtils::updatePayment($this);
        // }

        if ($isValidated && !$validatedBeforeSave) {
            // Allocation has been validated now
            stic_JustificationsUtils::createJustificationsFromAllocation($this); 
            stic_AllocationsUtils::updatePayment($this->stic_payments_stic_allocations);
        }
        else if ($validatedBeforeSave && !$isValidated) {
            // Allocation has been un-validated now
            stic_JustificationsUtils::removeJustificationsFromAllocation($this); 
        }
        else if($tempFetchedRow) {
            stic_JustificationsUtils::reviewJustificationsFromAllocation($this, $amountChanged || $hoursChanged);
        }

        // Payment is only updated if amount has changed and we are not in stic_Payments module to avoid recursion
        if (($amountChanged || $hoursChanged) && ($_REQUEST['current_module'] ?? false) != 'stic_Payments') {
            if (isset($this->stic_payments_stic_allocations)) {
                if ($this->stic_payments_stic_allocations instanceof Link2) {
                    $paymentBean = SticUtils::getRelatedBeanObject($this, 'stic_payments_stic_allocations');
                    $idPayment = $paymentBean->id;
                } else {
                    $idPayment = $this->stic_payments_stic_allocations;
                }
            }
            stic_AllocationsUtils::updatePayment($idPayment);
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
            $paymentBean = BeanFactory::getBean('stic_Payments', $this->stic_payments_stic_allocations);
            if (!empty($paymentBean->id)){
                $nameParts[] = $paymentBean->name;
            }

            $opportunityBean = BeanFactory::getBean('Opportunities', $this->opportunities_stic_allocationsopportunities_ida);
            if (!empty($opportunityBean->id)){
                $nameParts[] = $opportunityBean->name;
            }

            $projectBean = BeanFactory::getBean('Project', $this->project_stic_allocationsproject_ida);
            if (!empty($projectBean->id)){
                $nameParts[] = $projectBean->name;
            }

            $ledgerAccountBean = BeanFactory::getBean('stic_Ledger_Accounts', $this->stic_ledger_accounts_ida);
            if (!empty($ledgerAccountBean->id)){
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

    public function mark_deleted($id) {
        $paymentId = $this->stic_payments_stic_allocations;

        if($this->blocked) {
            $this->showError('LBL_BLOCKED_ALLOCATION_CANNOT_BE_DELETED');
            return false;
        }
        

        stic_JustificationsUtils::removeJustificationsFromAllocation($this);

        parent::mark_deleted($id);

        stic_AllocationsUtils::updatePayment($paymentId);

    }



    protected function showError($labelId) {
        
        global $current_language;
        $allocationsModStrings = return_module_language($current_language, 'stic_Allocations'); // can not be $mod_strings because of different contexts (specially inline edition)

        if (!empty($_REQUEST['sugar_body_only']) || !empty($_REQUEST['to_pdf'])) {
            $errorMsg = $allocationsModStrings[$labelId];
            $jsMsg = json_encode($errorMsg);
            // 2. Output a script to alert the user
            echo "<script>alert($jsMsg);</script>";
            echo "<script>location.reload();</script>";
            // 4. Stop execution
            if (($_REQUEST['current_module'] ?? '') === 'stic_Allocations') {
                exit();
            }
        }

        SugarApplication::appendErrorMessage('<div class="msg-fatal-lock">' . $allocationsModStrings[$labelId] . '</div>');
    }
}