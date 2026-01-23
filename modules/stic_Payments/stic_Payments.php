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
include_once 'modules/stic_Allocations/Utils.php';

#[\AllowDynamicProperties]
class stic_Payments extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_Payments';
    public $object_name = 'stic_Payments';
    public $table_name = 'stic_payments';
    public $importable = true;

    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $SecurityGroups;
    public $payment_type;
    public $bank_account;
    public $amount;
    public $currency_id;
    public $payment_method;
    public $transaction_type;
    public $mandate;
    public $segmentation;
    public $in_kind_description;
    public $banking_concept;
    public $status;
    public $m182_excluded;
    public $sepa_rejected_reason;
    public $rejection_date;
    public $c19_rejected_reason;
    public $payment_date;

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
     * 1) Build the name of the payment using the name of the PC and the payment date
     * 2) Get the mandate from the PC and set it in the payment
     * 3) Create a call record for unpaid payments
     *
     * @param boolean $check_notify
     * @return void
     */
    public function save($check_notify = false)
    {

        include_once 'SticInclude/Utils.php';
        include_once 'modules/stic_Payments/Utils.php';

        global $current_language;
        $paymentsModStrings = return_module_language($current_language, 'stic_Payments'); // can not be $mod_strings because of different contexts (specially inline edition)

        // Get payment commitment bean. Depending on the context (editview, subpanel, workflow, etc.)
        // stic_paymebfe2itments_ida will be an string that contains the id of the related payment
        // commitment or will be an object of type Link2, so let's manage it properly.
        if (isset($this->stic_paymebfe2itments_ida)) {
            if ($this->stic_paymebfe2itments_ida instanceof Link2) {
                $PCBean = SticUtils::getRelatedBeanObject($this, 'stic_payments_stic_payment_commitments');
            } else {
                $PCBean = BeanFactory::getBean('stic_Payment_Commitments', $this->stic_paymebfe2itments_ida);
            }
        }

        if (isset($PCBean)) {
            global $timedate;
            
            // Get userDate object from user format or from database format 
            $userDate = $timedate->fromDBDate(SticUtils::formatDateForDatabase($this->payment_date));
            
            // Create name if empty
            if (empty($this->name)) {
                if ($userDate) {
                    $this->name = $PCBean->name . ' - ' . $userDate->asDBDate();
                } else {
                    // The payment is created in any context where the format of the date-type fields is bad formed.
                    $this->name = $PCBean->name . ' - ' . $this->payment_date;
                }
            }

            // Get mandate from payment commitment if empty (for manual payment creation)
            if (empty($this->mandate) && $this->payment_method == 'direct_debit') {
                $this->mandate = $PCBean->mandate;
            }

            // Create call if unpaid
            if ($this->status == 'unpaid' && $this->fetched_row['status'] != 'unpaid') {
                stic_PaymentsUtils::generateCallFromUnpaid($this);
            }
        }

        // Since the value of `fetched_row` is reset in the case of audited fields, 
        // we will save its contents in a variable to be used after running the `Save` method.
        $tempFetchedRow = $this->fetched_row ?? null;


        if (!isset($this->justification_date) || empty($this->justification_date)) {
            $this->justification_date = $this->payment_date;
        }

        $isBlocked = filter_var(($this->blocked ?? false), FILTER_VALIDATE_BOOLEAN);
        $isAllocated = filter_var(($this->allocated ?? false), FILTER_VALIDATE_BOOLEAN);
        // If record is blocked, no updates are allowed
        if ($tempFetchedRow && $tempFetchedRow['blocked'] && $isBlocked) {
            $this->showError('LBL_BLOCKED_PAYMENT_CANNOT_BE_MODIFIED');
            return false;
        }

        $anyamountChanged = $this->anyAmountChanged(); // Check must be done before involing parent:save()

        if ($isAllocated && $tempFetchedRow && !$tempFetchedRow['allocated']) {
            // If changing from not allocated to allocated, validate allocations from payment (dry run)
            $this->generateAllocationsFromPayment(true);
        }

        // If payment is allocated and any amount field has changed, validate allocations from payment (dry run)
        if ($isAllocated && $anyamountChanged) {
            $this->updateAllocationsFromPayment(true);
        }

        // If changing from allocated to not allocated, delete allocations from payment
        if (!empty($tempFetchedRow['allocated']) && $tempFetchedRow['allocated'] && !$isAllocated) {
            $anyBlocked = stic_AllocationsUtils::paymentHasBlockedAllocations($this);
            if ($anyBlocked) {
                $this->showError('LBL_CANNOT_DEALLOCATE_PAYMENT_BLOCKED_ALLOCATIONS');
                return false;
            }
        }

        
        // Call the generic save() function from the SugarBean class
        parent::save();

        // If changing from not allocated to allocated, generate allocations from payment
        if (empty($tempFetchedRow['allocated']) && $isAllocated) {
            $this->generateAllocationsFromPayment();
            stic_PaymentsUtils::updateAllocationPercentage($this);
        }

        // If payment is allocated and any amount field has changed, update allocations from payment
        if (!empty($tempFetchedRow['allocated']) && $isAllocated && $anyamountChanged) {
            $hasValidatedAllocations = stic_AllocationsUtils::paymentHasValidatedAllocations($this);
            $this->updateAllocationsFromPayment(false);
            stic_PaymentsUtils::updateAllocationPercentage($this);
        }

        if (isset($PCBean) && isset($userDate)) {
        
            // Recalculate the field paid_annualized_fee if applicable.
            // Check if the status, amount, or payment_date fields have changed or if it is a new record.            
            if (
                (!empty($tempFetchedRow['status']) && $this->status != $tempFetchedRow['status'])
                || (!empty($tempFetchedRow['status']) && SticUtils::unformatDecimal($this->amount) != SticUtils::unformatDecimal($tempFetchedRow['amount']))
                || (!empty($tempFetchedRow['payment_date']) && $userDate->asDBDate() != $tempFetchedRow['payment_date'])
                || empty($this->fetched_row)
            ) {
                // Recalculate the paid_annualized_fee field.
                require_once 'modules/stic_Payment_Commitments/Utils.php';
                stic_Payment_CommitmentsUtils::setPaidAnnualizedFee($PCBean);
            }
        }

        // If changing from allocated to not allocated, delete allocations from payment
        if (!empty($tempFetchedRow['allocated']) && $tempFetchedRow['allocated'] && !$isAllocated) {
            $this->deleteAllocationsFromPayment();
            stic_PaymentsUtils::updateAllocationPercentage($this);
        }
    }


    protected function anyAmountChanged() {
        // filter from $this->field_defs only the fields of type 'currency' or 'decimal'
        foreach ($this->field_defs as $fieldName => $fieldDef) {
            if (isset($fieldDef['type']) && ($fieldDef['type'] == 'currency' || $fieldDef['type'] == 'decimal')) {
                // check if the field value has changed
                $currentValue = SticUtils::unformatDecimal($this->$fieldName);
                $previousValue = SticUtils::unformatDecimal($this->fetched_row[$fieldName] ?? 0);
                if ($currentValue != $previousValue) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function deleteAllocationsFromPayment() {
        stic_AllocationsUtils::deleteAllocationsFromPayment($this);
    }

    protected function generateAllocationsFromPayment($dryrun = false) {
        return stic_AllocationsUtils::createAllocationsFromPayment($this, $dryrun);
    }

    protected function updateAllocationsFromPayment($dryrun = false) {
        return stic_AllocationsUtils::updateAllocationsFromPayment($this, $dryrun);
    }


    /**
     * Overriding SugarBean save_relationship_changes function to insert additional logic:
     * 1) Remove previous relationship with contact/account when needed
     * 2) Get the contact/account from the payment commitment and set it in the payment
     *
     * @param bool $is_update
     * @param array $exclude
     * @return void
     */
    public function save_relationship_changes($is_update, $exclude = array())
    {
        include_once 'SticInclude/Utils.php';

        // If parent payment commitment has changed...
        // STIC Custom 20250416 JBL - Fix Warnings and TypeErrors
        // https://github.com/SinergiaTIC/SinergiaCRM/pull/315
        // if (!empty($this->stic_paymebfe2itments_ida) && (trim($this->stic_paymebfe2itments_ida) != trim($this->rel_fields_before_value['stic_paymebfe2itments_ida']))) {
        if (!empty($this->stic_paymebfe2itments_ida)) {
            $payment_commitment_id = '';
            if (is_string($this->stic_paymebfe2itments_ida) || 
                (is_object($this->stic_paymebfe2itments_ida) && 
                 method_exists($this->stic_paymebfe2itments_ida, '__toString'))) {
                $payment_commitment_id = (string)$this->stic_paymebfe2itments_ida;
            }
            $payment_commitment_id_before = '';
            if (isset($this->rel_fields_before_value['stic_paymebfe2itments_ida'])) {
                if (is_string($this->rel_fields_before_value['stic_paymebfe2itments_ida']) ||
                    (is_object($this->rel_fields_before_value['stic_paymebfe2itments_ida']) && 
                     method_exists($this->rel_fields_before_value['stic_paymebfe2itments_ida'], '__toString'))) {
                    $payment_commitment_id_before = (string)$this->rel_fields_before_value['stic_paymebfe2itments_ida'];
                }
            }
            if (trim($payment_commitment_id) != trim($payment_commitment_id_before)) {
        // END STIC Custom
                // Get new parent payment commitment bean
                $PCBean = BeanFactory::getBean('stic_Payment_Commitments', $this->stic_paymebfe2itments_ida);
                // Get payment commmitment related contact (usual case)
                $contactId = SticUtils::getRelatedBeanObject($PCBean, 'stic_payment_commitments_contacts')->id ?? null; ; 
                if (!empty($contactId)) {
                    // Remove previous relationship with an account, if any
                    // (a payment can only be related with a single contact or account, not both)
                    $this->stic_payments_accountsaccounts_ida = '';
                    // Set the relationship between payment and contact
                    $this->stic_payments_contactscontacts_ida = $contactId;
                } else {
                    // Get payment commitment related account
                    $accountId = SticUtils::getRelatedBeanObject($PCBean, 'stic_payment_commitments_accounts')->id ?? null;
                    if (!empty($accountId)) {
                        // Remove previous relationship with a contact, if any
                        // (a payment can only be related with a single contact or account, not both)
                        $this->stic_payments_contactscontacts_ida = '';
                        // Set the relationship between payment and account
                        $this->stic_payments_accountsaccounts_ida = $accountId;
                    }
                }
            }
        }

        // Call the generic save_relationship_changes() function from the SugarBean class
        parent::save_relationship_changes($is_update, $exclude);
    }



    /**
     * overrides SugarBean's method.
     * @param string id ID
     */
    public function call_custom_logic($event, $arguments = null)
    {
        // Recalculate the field paid_annualized_fee if applicable.
        // capture before_relationship_delete & after_relationship_add events
        if (in_array($event, ['after_relationship_delete', 'after_relationship_add'])) {
            if (isset($arguments['related_module']) && $arguments['related_module'] == 'stic_Payment_Commitments') {
                $PCBean = $arguments['related_bean'];
                require_once 'modules/stic_Payment_Commitments/Utils.php';
                stic_Payment_CommitmentsUtils::setPaidAnnualizedFee($PCBean);
            }
        }

        parent::call_custom_logic($event, $arguments);
    }


    protected function showError($labelId) {
        
        global $current_language;
        $paymentsModStrings = return_module_language($current_language, 'stic_Payments'); // can not be $mod_strings because of different contexts (specially inline edition)

        if (!empty($_REQUEST['sugar_body_only']) || !empty($_REQUEST['to_pdf'])) {
            // // This is an AJAX request
            $errorMsg = $paymentsModStrings[$labelId];
            $jsMsg = json_encode($errorMsg);
            // 2. Output a script to alert the user
            echo "<script>alert($jsMsg);</script>";
            echo "<script>location.reload();</script>";
            // 4. Stop execution
            exit();
        }
        SugarApplication::appendErrorMessage('<div class="msg-fatal-lock">' . $paymentsModStrings[$labelId] . '</div>');

    }

    public function mark_deleted($id) {

        if($this->blocked) {
            $this->showError('LBL_BLOCKED_PAYMENT_CANNOT_BE_DELETED');
            return false;
        }
        
        parent::mark_deleted($id);
    }


}
