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
class stic_AllocationsUtils {
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
}