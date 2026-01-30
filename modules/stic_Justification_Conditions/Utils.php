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


class stic_Justification_ConditionsUtils {
    public static function deleteJustifications($condition) {
        $link = $condition->get_linked_beans('stic_justification_conditions_stic_justifications', 'stic_Justifications');
        foreach ($link as $justification) {
            if ($justification->status != 'submitted') {
                $justification->mark_deleted($justification->id);
            }
        }
    }

    public static function deleteJustificationsForOpportunity($opportunity) {
        $conditions = $opportunity->get_linked_beans('opportunities_stic_justification_conditions', 'stic_Justification_Conditions');
        foreach ($conditions as $condition) {
            stic_Justification_ConditionsUtils::deleteJustifications($condition);
        }
    }

    public static function updateJustificationsForOpportunity($opportunity) {
        $conditions = $opportunity->get_linked_beans('opportunities_stic_justification_conditions', 'stic_Justification_Conditions');
        foreach ($conditions as $condition) {
            stic_Justification_ConditionsUtils::deleteJustifications($condition);
            stic_JustificationsUtils::createNewJustificationsFromJustificationCondition($condition);
        }
    }

    public static function blockCondition($condition) {
        $condition->blocked = true;
        $condition->save();
    }
}
