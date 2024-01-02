<?php

class stic_Payment_CommitmentsLogicHooks {

    public function before_save(&$bean, $event, $arguments) {
        global $app_list_strings;
        include_once 'SticInclude/Utils.php';

        // Create name for contact if empty
        if (!empty($bean->stic_payment_commitments_contactscontacts_ida)) {
            if (empty($bean->name)) {
                $relatedBean = SticUtils::getRelatedBeanObject($bean, 'stic_payment_commitments_contacts');
                $bean->name = $relatedBean->first_name . ' ' . $relatedBean->last_name . ' - ' . $app_list_strings['stic_payments_methods_list'][$bean->payment_method] . ' - ' . $bean->amount;
            }
        }

        // Create name for account if empty
        elseif (!empty($bean->stic_payment_commitments_accountsaccounts_ida)) {
            if (empty($bean->name)) {
                global $app_list_strings;
                $relatedBean = SticUtils::getRelatedBeanObject($bean, 'stic_payment_commitments_accounts');
                $bean->name = $relatedBean->name . ' - ' . $app_list_strings['stic_payments_methods_list'][$bean->payment_method] . ' - ' . $bean->amount;
            }
        }

        // Set annualized_fee
        include_once 'modules/stic_Payment_Commitments/Utils.php';
        $bean->annualized_fee = stic_Payment_CommitmentsUtils::getAnnualizedFee($bean);

        // Generation of the mandate and the date of signature when appropriate in direct payment methods
        if ($bean->payment_method == 'direct_debit') {

            // Generate mandate if empty
            if (empty($bean->mandate)) {
                $bean->mandate = substr(mt_rand(100000000, 999999999), 0, 8);
            }

            // In case the account number or mandate has changed and there is an account number
            if (!empty($bean->bank_account) && ($bean->bank_account != $bean->fetched_row['bank_account'] || ($bean->mandate != $bean->fetched_row['mandate'] && !empty($bean->mandate)))) {
                // If mandate is empty or has not been modified by the user, we generate it
                if (empty($bean->mandate) || ($bean->mandate == $bean->fetched_row['mandate'])) {
                    $bean->mandate = substr(mt_rand(100000000, 999999999), 0, 8);
                }
                // The signature date is updated in all cases where the mandate or account number has changed
                $bean->signature_date = date("Y-m-d");
            }
        }

        // Set active/inactive status
        if (
            (empty($bean->first_payment_date) || $bean->first_payment_date <= date("Y-m-d"))
            && (empty($bean->end_date) || $bean->end_date > date("Y-m-d"))
        ) {
            $bean->active = true;
        } else {
            $bean->active = false;
        }

    }

    public function after_save(&$bean, $event, $arguments) {

        // Create initial payments, only if it is a new record (and not modified)
        if ($bean->fetched_row == false) {
            stic_Payment_CommitmentsUtils::createInitialPayments($bean);
        }

    }

}
