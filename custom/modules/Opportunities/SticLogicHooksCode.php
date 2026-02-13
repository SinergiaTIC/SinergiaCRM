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

class OpportunitiesLogicHooks {
    public function before_save(&$bean, $event, $arguments) {
        $tempFetchedRow = $bean->fetched_row ?? null;
        $oldAmount = SticUtils::unformatDecimal($tempFetchedRow['stic_amount_awarded_c'] ?? null);
        $newAmount = SticUtils::unformatDecimal($bean->stic_amount_awarded_c);
        if ($oldAmount !== null && $newAmount !== $oldAmount) {
            // $bean->stic_justified_percentage_c = formatDecimalInConfigSettings(($bean->stic_amount_awarded_c > 0) ? (SticUtils::unformatDecimal($bean->stic_justified_amount_c) / $newAmount) * 100 : 0, true);
            $bean->stic_justified_percentage_c = ($bean->stic_amount_awarded_c > 0) ? (SticUtils::unformatDecimal($bean->stic_justified_amount_c) / $newAmount) * 100 : 0;
        }
    }

    public function after_save(&$bean, $event, $arguments) {
        if ($this->justificationDatesChanged($bean)) {
            // Update linked justification conditions
            require_once 'modules/stic_Justification_Conditions/Utils.php';
            stic_Justification_ConditionsUtils::updateJustificationsForOpportunity($bean);
        }
    }

    protected function justificationDatesChanged(&$bean) {
        $tempFetchedRow = $bean->fetched_row ?? null;
        if (!$tempFetchedRow) {
            return false; // New record, so dates are considered unchanged
        }
        $startDateChanged = $bean->stic_start_date_c !== $tempFetchedRow['stic_start_date_c'];
        $endDateChanged = $bean->stic_end_date_c !== $tempFetchedRow['stic_end_date_c'];
        return $startDateChanged || $endDateChanged;
    }

}
