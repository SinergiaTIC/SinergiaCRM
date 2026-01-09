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

/**
 * Class to check the relationship of Person to People Relations
 */
class checkAndUpdateVolunteerDocuments extends DataCheckFunction
{

    /**
     * Receive an SQL proposal and modify it with the particularities necessary for the function.
     * Most functions should overwrite this method.
     * @param $actionBean Bean of the action in which the function is being executed.
     * @param $proposedSQL Array generated automatically (if possible) with the keys select, from, where and order_by.
     * @return string
     */
    public function prepareSQL(stic_Validation_Actions $actionBean, $proposedSQL)
    {
        // Return the documents related to a person who has some relationship with a volunteer, not active and not deleted person, and at the same time, said person does not have any other relationship with a volunteer, active and not deleted person.
        $sql = "SELECT d.* 
                FROM documents d
                JOIN documents_cstm dcstm
                    ON d.id = dcstm.id_c
                JOIN documents_contacts dc
                    ON d.id = dc.document_id AND dc.deleted = 0
                WHERE d.deleted = 0
                AND d.status_id = 'Active'
                AND (dcstm.stic_category_c = 'insurance' OR (dcstm.stic_category_c = 'certificate' AND dcstm.stic_subcategory_c = 'certificate_sexual_offences'))";

        return $sql;
    }

    /**
     * DoAction function
     * Perform the action defined in the function
     * @param $records Set of records on which the validation action is to be applied
     * @param $actionBean stic_Validation_Actions Bean of the action in which the function is being executed.
     * @return boolean It will return true in case of success and false in case of error.
     */
    public function doAction($records, stic_Validation_Actions $actionBean) 
    {
        global $app_list_strings;
        $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Update the status of volunteering documents for contacts who have ended their volunteering relationship.");

        // VERIFY WHETHER THE CONTACT RELATED TO EACH DOCUMENT IS VOLUNTARY OR NOT
        // Array to store the checked Contacts to avoid checking multiple times the relationships of the same Contact
        $contactChecked = array();

        // Array to store the Documents to be updated after checking the relationships of the related Contacts
        $documentsToUpdate = array();

        while ($row = array_pop($records)) 
        {
            include_once 'SticInclude/Utils.php';
            $documentBean = BeanFactory::getBean('Documents', $row['id']);

            // Get the related Contact bean
            $contactBean = SticUtils::getRelatedBeanObject($documentBean, 'contacts');
            
            // If the Contact bean is not empty and has not been checked yet
            if (!empty($contactBean) && !array_key_exists($contactBean->id, $contactChecked)) 
            {
                // Get the active and volunteer contact relationships related to the contact
                $query = "stic_contacts_relationships.active = 1 AND stic_contacts_relationships.relationship_type = 'volunteer'";
                $contactRelationshipBeans = $contactBean->get_linked_beans(
                    'stic_contacts_relationships_contacts',
                    '',
                    '',
                    0,
                    0,
                    0,
                    $query,
                );

                if (!empty($contactRelationshipBeans)) {
                    $contactChecked[$contactBean->id] = 'VOLUNTARY';
                } else {
                    $contactChecked[$contactBean->id] = 'NOT_VOLUNTARY';
                }
            }
            if ($contactChecked[$contactBean->id] == 'NOT_VOLUNTARY') {
                $documentsToUpdate[] = $documentBean;
            }
        }

        // UPDATE THE CORRESPONDING DOCUMENTS AND REPORT
        // It will indicate the number of updated documents
        $updated = 0;

        foreach ($documentsToUpdate as $documentBean) 
        {
            // Update document status to expired
            $documentBean->status_id = 'Expired';
            $documentBean->save();
    
            // Create the validation results
            $log =  $this->getLabel('LOG') . ": " . $app_list_strings[$documentBean->field_defs['status_id']['options']][$documentBean->status_id];
            $data = array(
                'name' => $this->getLabel('NAME'),
                'stic_validation_actions_id' => $actionBean->id,
                'log' => '<div>' . $log . '</div>',
                'parent_type' => $this->functionDef['module'],
                'parent_id' => $documentBean->id,
                'reviewed' => 'not_necessary',   
                'assigned_user_id' => $row['assigned_user_id'],
            );
            $this->logValidationResult($data);
            $updated++;
        }

        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": Updated Records: [{$updated}]");

        // Report_always
        if (!$updated && $actionBean->report_always) {
            $log = $this->getLabel('NO_ROWS');
            $data = array(
                'name' => $this->getLabel('NAME'),
                'stic_validation_actions_id' => $actionBean->id,
                'log' => '<div>' . $log . '</div>',
                'reviewed' => 'not_necessary',              
                'assigned_user_id' => $current_user->id,
            );
            $this->logValidationResult($data);
        }

        return true;
    }
}
