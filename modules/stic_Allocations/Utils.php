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

require_once 'SticInclude/Utils.php';

class stic_AllocationsUtils {

    public static function updateAllocationsFromPayment($paymentBean, $dryrun= false) {
        global $current_user;

        global $current_language;
        $allocationsModStrings = return_module_language($current_language, 'stic_Allocations');

        // retrieve all allocations linked to the payment
        $allocationBeans = array();
        $linkName = 'stic_allocations';
        if ($paymentBean->load_relationship($linkName)) {
            $allocationBeans = $paymentBean->$linkName->getBeans();
        }
        foreach ($allocationBeans as $allocationBean) {
            if (empty($paymentBean->{$allocationBean->payment_amount_field})) {
                // validate payment field needed is filled
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ':  ' . $allocationsModStrings['LBL_ALLOCATION_NOT_COMPATIBLE']);
                if (!empty($_REQUEST['sugar_body_only']) || !empty($_REQUEST['to_pdf'])) {
                    // // This is an AJAX request
                    // ob_clean();
                    // header('HTTP/1.1 500 Internal Server Error');
                    // echo "Save aborted: " . $allocationsModStrings['LBL_ALLOCATION_NOT_COMPATIBLE'];
                    // exit;
                    $errorMsg = $allocationsModStrings['LBL_ALLOCATION_NOT_COMPATIBLE'];
                    $jsMsg = json_encode($errorMsg);

                    // 2. Output a script to alert the user
                    echo "<script>alert($jsMsg);</script>";
                    echo "<script>location.reload();</script>";

                    // 4. Stop execution
                    exit();
                }
                SugarApplication::appendErrorMessage('<div class="msg-fatal-lock">' . $allocationsModStrings['LBL_ALLOCATION_NOT_COMPATIBLE'] . '</div>');
            }
            else {
                // update allocation amount from payment field if not blocked
                if(!$allocationBean->blocked && !$dryrun) {
                    $allocationBean->amount = SticUtils::unformatDecimal($paymentBean->{$allocationBean->payment_amount_field}) * SticUtils::unformatDecimal($allocationBean->percentage) / 100;
                    $allocationBean->save();
                }
            }

        }
        return true;
    }

    public static function updatePayment($allocationBean) {
        // retrieve payment linked to the allocation
        $paymentBean = BeanFactory::getBean('stic_Payments', $allocationBean->stic_payments_stic_aleb9a);
        if ($paymentBean) {
            require_once 'modules/stic_Payments/Utils.php';
            stic_PaymentsUtils::updateAllocationPercentage($paymentBean);
        }
    }

    public static function updateJustificationsFromAllocation($allocationBean) {
        // Retrieve justifications linked to the allocation 
        $justificationBeans = array();
        $linkName = 'stic_allocations_stic_justifications';
        if ($allocationBean->load_relationship($linkName)) {
            $justificationBeans = $allocationBean->$linkName->getBeans();
        }

        foreach ($justificationBeans as $justificationBean) {
            // update justification amount from allocation if needed
            $justificationBean->amount = $allocationBean->amount;
            $justificationBean->save();
        }

        return true;
    }

    public static function calculateTotalAllocatedAmount($paymentId) {
        global $db;

        $query = "SELECT SUM(amount) as total_allocated
                  FROM stic_allocations
                  WHERE deleted = 0 AND payment_id = " . $db->quoted($paymentId);

        $result = $db->query($query);
        $row = $db->fetchByAssoc($result);

        return $row['total_allocated'] ?? 0;
    }

    public static function deleteAllocationsFromPayment($paymentBean)
    {
        // retrieve all allocations linked to the payment
        $allocationBeans = array();
        $linkName = 'stic_allocations';
        if ($paymentBean->load_relationship($linkName)) {
            $allocationBeans = $paymentBean->$linkName->getBeans();
        }
        // delete each allocation
        foreach ($allocationBeans as $allocationBean) {
            $allocationBean->mark_deleted($allocationBean->id);
            self::deleteJustificationsFromAllocation($allocationBean);
        }
    }

    public static function deleteJustificationsFromAllocation($allocationBean)
    {
        // retrieve all justifications linked to the allocation
        $justificationBeans = array();
        $linkName = 'stic_allocations_stic_justifications';
        if ($allocationBean->load_relationship($linkName)) {
            $justificationBeans = $allocationBean->$linkName->getBeans();
        }
        // delete each justification
        foreach ($justificationBeans as $justificationBean) {
            $justificationBean->mark_deleted($justificationBean->id);
        }
    }

    public static function allocationsFromPayment($paymentBean) {
        // retrieve all allocations linked to the payment
        $allocationBeans = array();
        $linkName = 'stic_allocations';
        if ($paymentBean->load_relationship($linkName)) {
            $allocationBeans = $paymentBean->$linkName->getBeans();
        }
        return $allocationBeans;
    }


    public static function paymentHasValidatedAllocations($paymentBean)
    {
        // retrieve all allocations linked to the payment
        $allocationBeans = self::allocationsFromPayment($paymentBean);
        // check if any allocation is validated
        foreach ($allocationBeans as $allocationBean) {
            if ($allocationBean->validated) {
                return true;
            }
        }
        return false;
    }
}