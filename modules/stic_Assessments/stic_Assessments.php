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


#[\AllowDynamicProperties]
class stic_Assessments extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_Assessments';
    public $object_name = 'stic_Assessments';
    public $table_name = 'stic_assessments';
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
    public $areas;
    public $assessment_date;
    public $moment;
    public $scope;
    public $conclusions;
    public $recommendations;
    public $status;
    public $account_id_c;
    public $working_with;
    public $derivation;
    public $next_date;
	
    public function bean_implements($interface)
    {
        switch($interface)
        {
            case 'ACL':
                return true;
        }

        return false;
    }

	public function save($check_notify = true) 
    {
        parent::save($check_notify);
        
        // If the assessment is volunteering and all the fields of the final assessment are filled in
        if (!empty($this->type) && ($this->type == 'volunteering'))
        {
            global $timedate, $current_user;
            $utcTimezone = new DateTimeZone('UTC');
            $utcNow = ($timedate->fromUser($timedate->now(), $current_user))->setTimezone($utcTimezone);

            include_once 'SticInclude/Utils.php';
            $contactBean = SticUtils::getRelatedBeanObject($this, 'stic_assessments_contacts');
            if (!empty($contactBean)) 
            {
                // If the available time field has been updated, the corresponding field of the contact related also is updated.
                if (isset($this->available_time) && (!isset($this->fetched_row['available_time']) || $this->available_time != $this->fetched_row['available_time'])) {
                    $contactBean->time_availability_c = $this->available_time;
                    $contactBean->save();
                }

                // If the assessment is volunteering and all the fields of the final assessment are filled in
                if (!empty($this->resignation_signed) &&
                    !empty($this->resignation_date) && 
                    ($timedate->fromDbFormat($this->resignation_date . ' 00:00:00', TimeDate::DB_DATETIME_FORMAT) <= $utcNow) &&
                    !empty($this->material_returned) &&
                    !empty($this->insurance_canceled)
                ) {
                    if (($contactBean->load_relationship('stic_contacts_relationships_contacts'))) 
                    {            
                        $contactRelationshipBeans = $contactBean->stic_contacts_relationships_contacts->getBeans();
                        foreach ($contactRelationshipBeans as $contactRelationshipBean) {
                            // Deactivate the relationship if it is of a voluntary type
                            if ($contactRelationshipBean->relationship_type == 'volunteer' && $contactRelationshipBean->active) {
                                $contactRelationshipBean->end_date = $utcNow;
                                $contactRelationshipBean->active = false;
                                $contactRelationshipBean->save();
                            }
                        }
                    }
                } else {
                    // If the assessment is volunteering and all the fields of the initial assessment are filled in
                    if (!empty($this->type) && ($this->type == 'volunteering') &&
                        (!empty($this->training_completed) &&
                        !empty($this->has_criminal_certificate) &&
                        !empty($this->needs_financial_aid) &&
                        !empty($this->needs_parental_authorization) &&
                        !empty($this->available_days) &&
                        !empty($this->available_time)
                        )
                    ) {
                        // If the related contact there is no 'volunteer' relationship, a new one is created
                        // Furthermore, it deactivates those active, pre-voluntary relationships that are related to contact
                        if ($contactBean->load_relationship('stic_contacts_relationships_contacts'))
                        {
                            $contactRelationshipBeans = $contactBean->stic_contacts_relationships_contacts->getBeans();
                            $volunteerCount = 0;
                            foreach ($contactRelationshipBeans as $contactRelationshipBean) {
                                // Check if a volunteer relationship already exists
                                if ($contactRelationshipBean->relationship_type == 'volunteer' && $contactRelationshipBean->active) {
                                    $volunteerCount++;
                                }
                                // Deactivate the relationship if it is of a pre-voluntary type
                                if ($contactRelationshipBean->relationship_type == 'pre-volunteer' && $contactRelationshipBean->active) {
                                    $contactRelationshipBean->end_date = $utcNow;
                                    $contactRelationshipBean->active = false;
                                    $contactRelationshipBean->save();
                                }
                            }
                            // If there is no voluntary contact relationship, create a new one
                            if ($volunteerCount == 0) {
                                $relationshipBean = BeanFactory::newBean('stic_Contacts_Relationships');
                                $relationshipBean->relationship_type = 'volunteer';
                                $relationshipBean->stic_contacts_relationships_contactscontacts_ida = $contactBean->id;
                                $relationshipBean->assigned_user_id = $this->assigned_user_id;
                                $relationshipBean->save();
                            }
                        }
                    }
                }
            }
        }
    }
}
