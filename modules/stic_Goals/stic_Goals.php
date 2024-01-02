<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
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
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

class stic_Goals extends Basic {
    public $new_schema = true;
    public $module_dir = 'stic_Goals';
    public $object_name = 'stic_Goals';
    public $table_name = 'stic_goals';
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
    public $start_date;
    public $expected_end_date;
    public $status;
    public $origin;
    public $level;
    public $area;
    public $subarea;
    public $actual_end_date;
    public $follow_up;

    public function bean_implements($interface) {
        switch ($interface) {
        case 'ACL':
            return true;
        }

        return false;
    }

    // Set main common SQL for both custom subpanels (destination & origin goals)
    private $customSubpanelSQL =
        "SELECT
            stic_goals.id as stic_goals_id,
            concat_ws(' ', contacts.first_name, contacts.last_name) as stic_goals_contacts_name,
            contacts.id as stic_goals_contactscontacts_ida,
            stic_registrations.name as stic_goals_stic_registrations_name,
            stic_registrations.id as stic_goals_stic_registrationsstic_registrations_ida,
            stic_assessments.name as stic_goals_stic_assessments_name,
            stic_assessments.id as stic_goals_stic_assessmentsstic_assessments_ida ,
            project.name as stic_goals_project_name,
            project.id as stic_goals_projectproject_ida,
            stic_goals.*
        FROM
            stic_goals
        JOIN stic_goals_stic_goals_c on
            stic_goals.id = stic_goals_stic_goals_c.@@relationSide@@  AND stic_goals.deleted =0
            left join stic_goals_contacts_c on stic_goals_contacts_c.stic_goals_contactsstic_goals_idb = stic_goals.id AND stic_goals_contacts_c.deleted=0
            left join contacts on contacts.id=stic_goals_contacts_c.stic_goals_contactscontacts_ida AND contacts.deleted=0
            left join stic_goals_project_c on stic_goals_project_c.stic_goals_projectstic_goals_idb =stic_goals.id AND stic_goals_project_c.deleted=0
            left join project on project.id=stic_goals_project_c.stic_goals_projectproject_ida AND project.deleted=0
            left join stic_goals_stic_assessments_c on stic_goals_stic_assessments_c.stic_goals_stic_assessmentsstic_goals_idb=stic_goals.id AND stic_goals_stic_assessments_c.deleted=0
            left join stic_assessments on stic_assessments.id=stic_goals_stic_assessments_c.stic_goals_stic_assessmentsstic_assessments_ida AND stic_assessments.deleted=0
            left join stic_goals_stic_registrations_c on stic_goals_stic_registrations_c.stic_goals_stic_registrationsstic_goals_idb =stic_goals.id AND stic_goals_stic_registrations_c.deleted=0
            left join stic_registrations on stic_registrations.id=stic_goals_stic_registrations_c.stic_goals_stic_registrationsstic_registrations_ida AND stic_registrations.deleted=0
        WHERE
            stic_goals_stic_goalsstic_goals_ida != stic_goals_stic_goalsstic_goals_idb
        AND stic_goals_stic_goals_c.deleted=0";

    /**
     * Create a query string for select Goals in destination side using the where conditions for destination side
     *
     * @return string final query
     */
    public function getSticGoalsSticGoalsDestinationSide() {
        $idQuoted = $this->db->quoted($this->id);

        // Add conditions to query
        $query = $this->customSubpanelSQL . " " .
            "AND stic_goals_stic_goals_c.stic_goals_stic_goalsstic_goals_ida = $idQuoted
             AND stic_goals.id != $idQuoted";

        // Replace relationship field name side
        $query = str_replace('@@relationSide@@', 'stic_goals_stic_goalsstic_goals_idb', $query);

        return $query;
    }

    /**
     * Create a query string for select Goals in origin side using the where conditions for origin side
     * @return string final query
     */
    public function getSticGoalsSticGoalsOriginSide() {
        $idQuoted = $this->db->quoted($this->id);
        
        // Add conditions to query
        $query = $this->customSubpanelSQL . " " .
            "AND stic_goals_stic_goals_c.stic_goals_stic_goalsstic_goals_idb = $idQuoted
             AND stic_goals.id != $idQuoted";
        
        // Replace relationship field name side
        $query = str_replace('@@relationSide@@', 'stic_goals_stic_goalsstic_goals_ida', $query);
        return $query;
    }

}
