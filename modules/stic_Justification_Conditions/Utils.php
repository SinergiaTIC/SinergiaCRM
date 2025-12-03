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


class stic_Justification_ConditionsUtils
{
    public static function createJustificationsFromAllocation($allocation)
    {
        // Retrieve all justification conditions linked to the project (and Opportunity) of the allocation
        $projectId = $allocation->project_stic_allocationsproject_ida;
        $opportunityId = $allocation->opportunities_stic_allocationsopportunities_ida;
        $conditions = self::getConditionsForProjectAndOpportunity($projectId, $opportunityId);

        foreach($conditions as $condition) {
            // Evaluate if condition is met based on allocation details
            if (self::conditionMet($condition, $allocation)) {
                //create Justification record
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
                $justification->stic_ledger_accounts_ida = $allocation->stic_ledger_accounts_ida;
                $justification->opportunit01eunities_ida = $condition->opportunit378funities_ida;
                
                // TODOEPS: Si posem projecte, copiar-lo aquí

                $justification->save();
            }
        }
    }

    public static function conditionMet($condition, $allocation)
    {
        if ($condition->stic_ledger_accounts_ida == $allocation->stic_ledger_accounts_ida &&
            $condition->allocation_type == $allocation->type) {
            return true;
        }
        return false;
    }

    public static function getConditionsForProjectAndOpportunity($projectId, $opportunityId)
    {

        // TODOEPS: revisar si es millor fer-ho amb SugarQuery

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
            WHERE osjcc.opportunit378funities_ida IN ({$opportunities})
            AND sjc.active = 1
            AND sjc.deleted = 0
            AND osjcc.deleted = 0
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

/**
 * 
 * Tindria sentit que una condició de justificació estigués lligada a un Projecte també?
 * 
 * 
 * Si la imputació té Subvenció lligada, agafem condicions d'aquella subvenció
 * 
 * Si la imputació no té Subvenció lligada, agafem les subvencions lligades al projecte i les seves condicions
 * 
 * 
 */




        // $conditionBean = BeanFactory::newBean('stic_Justification_Conditions');
        // $query = new SugarQuery();
        // $query->from($conditionBean);
        // $query->where()->equals('deleted', 0);

        // $projectCondition = $query->where()->queryOr();
        // if (!empty($projectId)) {
        //     $projectCondition->equals('project_stic_justification_conditionsproject_ida', $projectId);
        // }
        // if (!empty($opportunityId)) {
        //     $projectCondition->equals('opportunities_stic_justification_conditionsopportunities_ida', $opportunityId);
        // }

        // $results = $query->execute();

        // foreach ($results as $row) {
        //     $condition = BeanFactory::getBean('stic_Justification_Conditions', $row['id']);
        //     if ($condition) {
        //         $conditions[] = $condition;
        //     }
        // }



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