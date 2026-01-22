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

class stic_Justifications extends Basic
{
    public $module_dir = 'stic_Justifications';
    public $object_name = 'stic_Justifications';
    public $table_name = 'stic_justifications';
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
    
    public function __construct()
    {
        parent::__construct();
    }

    public function save($check_notify = false)
    {
        global $current_language;
        $justificationsModStrings = return_module_language($current_language, 'stic_Justifications'); // can not be $mod_strings because of different contexts (specially inline edition)     

        $tempFetchedRow = $this->fetched_row ?? null;
        
        $previousState = $tempFetchedRow ? $tempFetchedRow['status'] : null;
        $currentState = $this->status;
        $previouslyBlocked = $tempFetchedRow ? filter_var($tempFetchedRow['blocked'], FILTER_VALIDATE_BOOLEAN) : null;
        if ($previousState !== 'submitted' && $currentState === 'submitted') {
            $this->blocked = true;
        }
        $isBlocked = filter_var($this->blocked, FILTER_VALIDATE_BOOLEAN);

        if ($previouslyBlocked && $isBlocked) {
            if (!empty($_REQUEST['sugar_body_only']) || !empty($_REQUEST['to_pdf'])) {
                // This is an AJAX request
                $errorMsg = $justificationsModStrings['LBL_BLOCKED_JUSTIFICATION_CANNOT_BE_MODIFIED'];
                $jsMsg = json_encode($errorMsg);

                // 2. Output a script to alert the user
                echo "<script>alert($jsMsg);</script>";
                echo "<script>location.reload();</script>";

                // 4. Stop execution
                exit();
                }
            SugarApplication::appendErrorMessage('<div class="msg-fatal-lock">' . $justificationsModStrings['LBL_BLOCKED_JUSTIFICATION_CANNOT_BE_MODIFIED'] . '</div>');
            return false;
        }

        $this->fillName();

        $allocatedAmount = SticUtils::unformatDecimal($this->amount);
        $percentage = $this->max_allocable_percentage;
        $justifiedAmount = $percentage ? ($allocatedAmount * $percentage) / 100 : null;
        $this->justified_amount = SticUtils::formatDecimalInConfigSettings($justifiedAmount);


        // Save the bean
        parent::save($check_notify);

        if ($previousState !== 'submitted' && $currentState === 'submitted') {
            stic_JustificationsUtils::blockRelatedAllocation($this);
            stic_JustificationsUtils::blockRelatedPayment($this);
        }
        $oldAmount = SticUtils::unformatDecimal($tempFetchedRow['justified_amount'] ?? 0);


        if (!$tempFetchedRow || $oldAmount !== $justifiedAmount) {
            // If amount changed, update related allocations
            stic_JustificationsUtils::updateRelatedOpportunity($this->opportunit01eunities_ida);
            stic_JustificationsUtils::updateRelatedConditions($this->stic_justi13ccditions_ida);
        }

    }

        /**
         * Fill the name field with a concatenation of other fields values
         */
    private function fillName()
    {
        // get Allocation
        $allocation = BeanFactory::getBean('stic_Allocations', $this->stic_alloc8c71cations_ida);
        if ($allocation) {
            $this->name = $allocation->name;
        } else {
            $this->name = $this->date_entered;
        }
    }

    public function mark_deleted($id) {
        $opportunityId = $this->opportunit01eunities_ida;
        
        parent::mark_deleted($id);
        
        stic_JustificationsUtils::updateRelatedConditions($this->stic_justi13ccditions_ida);
        stic_JustificationsUtils::updateRelatedOpportunity($opportunityId);
    }

}