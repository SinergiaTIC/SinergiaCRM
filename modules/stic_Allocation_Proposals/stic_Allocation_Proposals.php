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

require_once 'modules/stic_Allocation_Proposals/Utils.php';

#[\AllowDynamicProperties]
class stic_Allocation_Proposals extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_Allocation_Proposals';
    public $object_name = 'stic_Allocation_Proposals';
    public $table_name = 'stic_allocation_proposals';
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
    public $proposal_status;
    public $proposal_date;
    public $amount;
    public $proposal_type;
    public $priority;
    public $notes;
    public $approval_date;
    public $approved_by;

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
     * 2) Auto-generate name if empty based on proposal type and date
     *
     * @param boolean $check_notify
     * @return void
     */
    public function save($check_notify = false)
    {
        $this->fillName();

        // Save the bean
        parent::save($check_notify);

        stic_Allocation_ProposalsUtils::recalculateAllocations($this);
    }

    /**
     * Auto-generate the name field based on proposal type and date
     */
    protected function fillName() {
        // Auto name - concatenate proposal type and date
        if (empty($this->name)) {
            global $app_list_strings;

            $nameParts = array();
            
            $projectBean = BeanFactory::getBean('Project', $this->project_stic_allocation_proposalsproject_ida);
            if ($projectBean->id){
                $nameParts[] = $projectBean->name;
            }

            $opportunityBean = BeanFactory::getBean('Opportunities', $this->opportunities_stic_allocation_proposalsopportunities_ida);
            if ($opportunityBean->id){
                $nameParts[] = $opportunityBean->name;
            }    

            $ledgerAccountBean = BeanFactory::getBean('stic_Ledger_Accounts', $this->stic_ledger_accounts_ida);
            if ($ledgerAccountBean->id){
                $nameParts[] = $ledgerAccountBean->name;
            }

            // Add proposal type if available
            if (!empty($this->proposal_type) && isset($app_list_strings['stic_proposal_type_list'][$this->proposal_type])) {
                $nameParts[] = $app_list_strings['stic_proposal_type_list'][$this->proposal_type];
            }
            // Add proposal payment_amount_field if available
            if (!empty($this->payment_amount_field) && isset($app_list_strings['stic_allocations_amount_fields_list'][$this->payment_amount_field])) {
                $nameParts[] = $app_list_strings['stic_allocations_amount_fields_list'][$this->payment_amount_field];
            }

            $nameParts[] = $this->percentage . '%';

            // Set the name by joining parts with a hyphen
            $this->name = implode(' - ', $nameParts);
        }
    }

    public function mark_deleted($id) {
        // load allocations relationship to check if any allocation is blocked
        $this->load_relationship('stic_allocation_proposals_stic_allocations');
        $allocations = $this->stic_allocation_proposals_stic_allocations->getBeans();
        if(count($allocations) > 0) {
            $this->showError('LBL_ALLOCATION_PROPOSAL_HAS_ALLOCATIONS_CANNOT_DELETE');
            return false;
        }

        parent::mark_deleted($id);

    }


    protected function showError($labelId) {
        
        global $current_language;
        $allocationsModStrings = return_module_language($current_language, 'stic_Allocation_Proposals'); // can not be $mod_strings because of different contexts (specially inline edition)

        if (!empty($_REQUEST['sugar_body_only']) || !empty($_REQUEST['to_pdf'])) {
            $errorMsg = $allocationsModStrings[$labelId];
            $jsMsg = json_encode($errorMsg);
            // 2. Output a script to alert the user
            echo "<script>alert($jsMsg);</script>";
            echo "<script>location.reload();</script>";
            // 4. Stop execution
            if (($_REQUEST['current_module'] ?? '') === 'stic_Allocation_Proposals') {
                exit();
            }
        }

        SugarApplication::appendErrorMessage('<div class="msg-fatal-lock">' . $allocationsModStrings[$labelId] . '</div>');
    }

}