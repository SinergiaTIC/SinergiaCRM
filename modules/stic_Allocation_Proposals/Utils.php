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
class stic_Allocation_ProposalsUtils
{
    public static function createAllocationsFromPayment($paymentBean)
    {
        global $current_user;

        global $current_language;

            // get Payment Commitment Bean
        include_once 'modules/stic_Payment_Commitments/stic_Payment_Commitments.php';
        $pcBean = new stic_Payment_Commitments();
        $pcBean->retrieve($paymentBean->stic_paymebfe2itments_ida);
        if (empty($pcBean->id)) {
            return;
        }
        // retrieve all allocation proposals linked to the payment commitment
        $allocationProposalBeans = array();
        $linkName = 'stic_allocation_proposals';
        if ($pcBean->load_relationship($linkName)) {
            $allocationProposalBeans = $pcBean->$linkName->getBeans();
        }
        // validate all payment fields needed are filled
        foreach ($allocationProposalBeans as $allocationProposalBean) {
            if (empty($paymentBean->{$allocationProposalBean->payment_amount_field})) {
                $allocationsModStrings = return_module_language($current_language, 'stic_Allocation_Proposals');
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ':  ' . $allocationsModStrings['LBL_ALLOCATION_PROPOSAL_NOT_COMPATIBLE']);
                if (!empty($_REQUEST['sugar_body_only']) || !empty($_REQUEST['to_pdf'])) {
                    // // This is an AJAX request
                    // ob_clean();
                    // header('HTTP/1.1 500 Internal Server Error');
                    // echo "Save aborted: " . $allocationsModStrings['LBL_ALLOCATION_PROPOSAL_NOT_COMPATIBLE'];
                    // exit;
                    $errorMsg = $allocationsModStrings['LBL_ALLOCATION_PROPOSAL_NOT_COMPATIBLE'];
                    $jsMsg = json_encode($errorMsg);

                    // 2. Output a script to alert the user
                    echo "<script>alert($jsMsg);</script>";
                    echo "<script>location.reload();</script>";

                    // 4. Stop execution
                    exit();
                }
                SugarApplication::appendErrorMessage('<div class="msg-fatal-lock">' . $allocationsModStrings['LBL_ALLOCATION_PROPOSAL_NOT_COMPATIBLE'] . '</div>');
                // SugarApplication::redirect("index.php?module={$remittance->bean->module_dir}&action=DetailView&record={$remittance->bean->id}");
                // SugarApplication::redirect("index.php?module={$paymentBean->module_dir}&action=EditView&record={$paymentBean->id}");

                return false;
            }
        }

        // create allocations for each allocation proposal
        foreach ($allocationProposalBeans as $allocationProposalBean) {
            include_once 'modules/stic_Allocations/stic_Allocations.php';
            $allocationBean = new stic_Allocations();
            // $allocationBean->name = 'Allocation from Payment ' . $paymentBean->name . ' to Proposal ' . $allocationProposalBean->name;
            $allocationBean->stic_payments_stic_aleb9a = $paymentBean->id;
            $allocationBean->stic_allocation_propo424d = $allocationProposalBean->id;
            $allocationBean->allocation_date = $paymentBean->payment_date;
            $allocationBean->percentage = format_number($allocationProposalBean->percentage, 2, 2);
            $allocationBean->hours = $allocationProposalBean->hours;
            $allocationBean->type = $allocationProposalBean->type;
            $allocationBean->date = $paymentBean->justification_date;
            $allocationBean->payment_amount_field = $allocationProposalBean->payment_amount_field;
            // $allocationBean->amount = ($paymentBean->amount * $allocationProposalBean->percentage) / 100;
            $allocationBean->amount = ($paymentBean->{$allocationProposalBean->payment_amount_field} * $allocationProposalBean->percentage) / 100;
            $allocationBean->stic_ledger_accounts_ida = $allocationProposalBean->stic_ledger_accounts_ida;
            $allocationBean->opportunities_stic_allocationsopportunities_ida = $allocationProposalBean->opportunities_stic_allocation_proposalsopportunities_ida;
            $allocationBean->project_stic_allocationsproject_ida = $allocationProposalBean->project_stic_allocation_proposalsproject_ida;
            $allocationBean->assigned_user_id = $current_user->id;
            $allocationBean->save();
        }

        return true;
    }
}