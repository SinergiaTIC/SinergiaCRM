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


class stic_JustificationsUtils {


    public static function reviewJustificationsFromAllocation($allocation) {

        // get actual justifications and check if they still met conditions
        $justificationBeans = array();
        $linkName = 'stic_allocations_stic_justifications';
        if ($allocation->load_relationship($linkName)) {
            $justificationBeans = $allocation->$linkName->getBeans();
        }
        foreach ($justificationBeans as $justificationBean) {
            $condition = BeanFactory::getBean('stic_Justification_Conditions', $justificationBean->stic_justi13ccditions_ida);
            if (!self::conditionMet($condition, $allocation)) {
                // condition no longer met, delete justification
                $justificationBean->mark_deleted($justificationBean->id);
            }
            else {
                // condition still met, update justification
                stic_JustificationsUtils::updateJustificationFromAllocation($justificationBean, $allocation);
            }
        }

        // check if new justifications must be added
        // Retrieve all justification conditions linked to the project (and Opportunity) of the allocation
        $projectId = $allocation->project_stic_allocationsproject_ida;
        $opportunityId = $allocation->opportunities_stic_allocationsopportunities_ida;
        $conditions = self::getConditionsForProjectAndOpportunity($projectId, $opportunityId, $allocation->date);

        foreach($conditions as $condition) {
            // Evaluate if condition is met based on allocation details
            if (self::conditionMet($condition, $allocation) && !in_array($condition->id, array_map(function($j) { return $j->stic_justi13ccditions_ida; }, $justificationBeans))) {
                self::createJustificationRecord($condition, $allocation);
            }
        }


        // If allocation has changed, we need to check justifications
    }

    public static function updateJustificationFromAllocation($justification, $allocation) {
        $justification->amount = $allocation->amount;
        $justification->hours = $allocation->hours;
        $justification->allocation_type = $allocation->type;
        $justification->max_allocable_percentage = $allocation->percentage;
        $justification->justified_amount = $allocation->amount * $justification->max_allocable_percentage / 100;
        $justification->justified_hours = $allocation->hours;
        $justification->save();
    }


    public static function createNewJustificationsFromJustificationCondition($condition) {
        // get actual justifications from condition
        $justificationBeans = array();
        $linkName = 'stic_justification_conditions_stic_justifications';
        if ($condition->load_relationship($linkName)) {
            $justificationBeans = $condition->$linkName->getBeans();
        }
        $allocationIds = array_map(function($j) { return $j->stic_alloc8c71cations_ida; }, $justificationBeans);
        
        $opportunityId = $condition->opportunit378funities_ida;
        
        // get all alocations to evaluate
        $db = DBManagerFactory::getInstance();
        $query = "
        select psac.project_stic_allocationsstic_allocations_idb as allocationId, osac.opportunities_stic_allocationsopportunities_ida as opportunityId
        from projects_opportunities po 
        join project_stic_allocations_c psac on psac.project_stic_allocationsproject_ida = po.project_id and psac.deleted = 0
        join opportunities_cstm oc on oc.id_c = po.opportunity_id 
        join stic_allocations sa on sa.id = psac.project_stic_allocationsstic_allocations_idb and sa.deleted = 0
        left join opportunities_stic_allocations_c osac on osac.opportunities_stic_allocationsstic_allocations_idb = psac.project_stic_allocationsstic_allocations_idb and osac.deleted = 0
        where po.deleted = 0
        and po.opportunity_id = '$opportunityId'
        and oc.start_date_c <= sa.date
        and (oc.end_date_c is null or oc.end_date_c >= sa.date)
        ";
        
        $result = $db->query($query);
        while ($row = $db->fetchByAssoc($result)) {
            if ($row['opportunityId'] == $opportunityId || empty($row['opportunityId'])) {
                if (!in_array($row['allocationId'], $allocationIds)) {
                    $allocationBean = BeanFactory::getBean('stic_Allocations', $row['allocationId']);
                    if (self::conditionMet($condition, $allocationBean)) {
                        self::createJustificationRecord($condition, $allocationBean);
                    }
                }
            }
        }
    }


    public static function createJustificationsFromAllocation($allocation)
    {
        // Retrieve all justification conditions linked to the project (and Opportunity) of the allocation
        $projectId = $allocation->project_stic_allocationsproject_ida;
        $opportunityId = $allocation->opportunities_stic_allocationsopportunities_ida;
        $allocationDate = $allocation->date;
        $conditions = self::getConditionsForProjectAndOpportunity($projectId, $opportunityId, $allocationDate);

        foreach($conditions as $condition) {
            // Evaluate if condition is met based on allocation details
            if (self::conditionMet($condition, $allocation)) {
                self::createJustificationRecord($condition, $allocation);
            }
        }
    }

    public static function createJustificationRecord($condition, $allocation) {
        $justification = BeanFactory::newBean('stic_Justifications');
        $justification->status = 'Pending';
        $justification->blocked = false;
        $justification->reference = '';
        $justification->amount = $allocation->amount;
        $justification->hours = $allocation->hours;
        $justification->allocation_type = $allocation->type;
        $justification->assigned_user_id = $allocation->assigned_user_id; // TODOEPS : revisar si cal canviar l'assignatari
        $justification->max_allocable_percentage = $allocation->percentage;
        $justification->justified_amount = $allocation->amount * $justification->max_allocable_percentage / 100;
        $justification->justified_hours = $allocation->hours;
        $justification->stic_justi13ccditions_ida = $condition->id;
        $justification->stic_alloc8c71cations_ida = $allocation->id;
        $justification->ledger_group = $condition->ledger_group;
        $justification->subgroup = $condition->subgroup;
        $justification->account = $condition->account;
        $justification->subaccount = $condition->subaccount;
        $justification->opportunit01eunities_ida = $condition->opportunit378funities_ida;
        // TODOEPS: Si posem projecte, copiar-lo aquí
        $justification->save();
    }

    public static function conditionMet($condition, $allocation)
    {

        // get the ledger_account from allocation
        $ledgerAccountId = $allocation->stic_ledger_accounts_ida;
        $ledgerAccountBean = BeanFactory::getBean('stic_Ledger_Accounts', $ledgerAccountId);

        // Check if condition matches allocation's ledger account and type
        if ($condition->allocation_type != $allocation->type) {
            return false;
        }
        if ($condition->ledger_group != $ledgerAccountBean->ledger_group) {
            return false;
        }
        if (!empty($condition->subgroup) && $condition->subgroup != $ledgerAccountBean->subgroup) {
            return false;
        }
        if (!empty($condition->account) && $condition->account != $ledgerAccountBean->account) {
            return false;
        }
        if (!empty($condition->stic_ledger_accounts_ida) && $condition->stic_ledger_accounts_ida != $allocation->stic_ledger_accounts_ida) {
            return false;
        }

        return true;
    }

    public static function getConditionsForProjectAndOpportunity($projectId, $opportunityId, $allocationDate)
    {
        // TODOEPS: Si incorporem Projecte a les condicions, caldrà revisar aquesta funció

        $conditions = array();

        $db = DBManagerFactory::getInstance();

        if (empty($opportunityId)) {
            $opportunities = self::getOpportunitiesFromProject($projectId);
        }
        else {
            $opportunities = "'{$opportunityId}'";
        }

        $sql = "
            SELECT sjc.id
            FROM stic_justification_conditions sjc 
            JOIN opportunities_stic_justification_conditions_c osjcc ON sjc.id = osjcc.opportunita6e5ditions_idb
            join opportunities o on o.id = osjcc.opportunit378funities_ida 
            join opportunities_cstm oc on oc.id_c = o.id
            WHERE osjcc.opportunit378funities_ida IN ({$opportunities})
            AND oc.start_date_c <= " . $db->quoted($allocationDate) . "
            AND (oc.end_date_c IS NULL OR oc.end_date_c >= " . $db->quoted($allocationDate) . ")
            AND sjc.active = 1
            AND sjc.deleted = 0
            AND osjcc.deleted = 0
            AND o.deleted = 0
        ";

        $result = $db->query($sql);

        if(!$result) {
            $GLOBALS['log']->fatal('###EPS###' . __METHOD__ . __LINE__ ,);
        }

        while ($row = $db->fetchByAssoc($result)) {
            $condition = BeanFactory::getBean('stic_Justification_Conditions', $row['id']);
            if ($condition) {
                $conditions[] = $condition;
            }
        }

        return $conditions;
    }

    public static function removeJustificationsFromAllocation($allocation)
    {
        // retrieve all allocations linked to the payment
        $justificationBeans = array();
        $linkName = 'stic_allocations_stic_justifications';
        if ($allocation->load_relationship($linkName)) {
            $justificationBeans = $allocation->$linkName->getBeans();
        }
        // delete each allocation
        foreach ($justificationBeans as $justificationBean) {
            $justificationBean->mark_deleted($justificationBean->id);
        }
    }

    protected static function getOpportunitiesFromProject($projectId)
    {

        $db = DBManagerFactory::getInstance();

        $sql = "
            SELECT group_concat(opportunity_id separator \"','\") as opportunitiesList
            FROM projects_opportunities po 
            WHERE project_id = " . $db->quoted($projectId) . " 
            AND deleted = 0
            GROUP BY project_id 
        ";

        $result = $db->getOne($sql);

        if(!empty($result)) {
            return "'" . $result . "'";
        }
        // TODOEPS
        // Check if result is false?

        return $result;
    }

}