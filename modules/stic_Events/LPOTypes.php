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
 * Class to handle the generation of Lists of Prospects (LPO) based on signature processes.
 * This class provides methods to create LPOs for all signers or only pending signers
 * associated with a specific signature.
 */
class LPOTypes
{

    /**
     * Generate a List of Prospects (LPO) based on the event ID, type, and label.
     *
     * @param string $eventId The ID of the event.
     * @param string $type The type of LPO to generate (e.g., all signers, pending signers).
     * @param string $label A label to include in the LPO name.
     * @return array An array containing the status and details of the created or existing LPO.
     */
    public static function generateLPO($eventId, $type, $label)
    {

        global $current_user, $mod_strings;

        if (empty($eventId)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ':  Event ID is empty.');
            return false;
        }
        // Retrieve the event bean
        $eventBean = BeanFactory::getBean('stic_Events', $eventId);

        if (empty($eventBean)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ':  Event bean not found for ID ' . $eventId);
            return false;
        }

        // Set filter depent on type
        $filter = '';
        switch ($type) {
            case 'stic_Events__registrations_confirmed':
                $sqlForContacts = "SELECT distinct srcc.stic_registrations_contactscontacts_ida
                                    FROM stic_events e
                                    JOIN stic_registrations_stic_events_c srsec on srsec.stic_registrations_stic_eventsstic_events_ida = e.id
                                    JOIN stic_registrations_contacts_c srcc on srcc.stic_registrations_contactsstic_registrations_idb =srsec.stic_registrations_stic_eventsstic_registrations_idb
                                    JOIN stic_registrations r on r.id=srcc.stic_registrations_contactsstic_registrations_idb
                                    WHERE e.id = '{$eventId}'
                                    AND e.deleted=0
                                    AND srsec.deleted=0
                                    AND srcc.deleted=0
                                    AND r.deleted=0
                                    AND r.status = 'confirmed'";
                $sqlForLeads = " SELECT distinct srcl.stic_registrations_leadsleads_ida
                                    FROM stic_events e
                                    JOIN stic_registrations_stic_events_c srsec on srsec.stic_registrations_stic_eventsstic_events_ida = e.id
                                    JOIN stic_registrations_leads_c srcl on srcl.stic_registrations_leadsstic_registrations_idb =srsec.stic_registrations_stic_eventsstic_registrations_idb
                                    JOIN stic_registrations r on r.id=srcl.stic_registrations_leadsstic_registrations_idb
                                    WHERE e.id = '{$eventId}'
                                    AND e.deleted=0
                                    AND srsec.deleted=0
                                    AND srcl.deleted=0
                                    AND r.deleted=0
                                    AND r.status = 'confirmed'";
                $sqlForAccounts = " SELECT distinct srca.stic_registrations_accountsaccounts_ida
                                    FROM stic_events e
                                    JOIN stic_registrations_stic_events_c srsec on srsec.stic_registrations_stic_eventsstic_events_ida = e.id
                                    JOIN stic_registrations_accounts_c srca on srca.stic_registrations_accountsstic_registrations_idb =srsec.stic_registrations_stic_eventsstic_registrations_idb
                                    JOIN stic_registrations r on r.id=srca.stic_registrations_accountsstic_registrations_idb
                                    WHERE e.id = '{$eventId}'
                                    AND e.deleted=0
                                    AND srsec.deleted=0
                                    AND srca.deleted=0
                                    AND r.deleted=0
                                    AND r.status = 'confirmed'";

                break;
            default:
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ':  Unsupported type for LPO creation: ' . $type);
                return false;
        }

        $contactsTargets = $eventBean->db->query($sqlForContacts);
        $leadsTargets = $eventBean->db->query($sqlForLeads);
        $accountsTargets = $eventBean->db->query($sqlForAccounts);

        // Create a unique name for the LPO
        $lpoName = "{$eventBean->name} - ({$label}) - " . date('d-m-Y');

        // check  if LPO with same name exists
        $existingLPO = BeanFactory::getBean('ProspectLists')->get_full_list(
            '',
            "prospect_lists.name = '{$lpoName}' AND prospect_lists.deleted = 0"
        );

        // If no existing LPO, create a new one
        if (empty($existingLPO)) {
            // Create a new LPO (Prospect List)
            $LPOBean = BeanFactory::newBean('ProspectLists');
            $LPOBean->name = $lpoName;
            $LPOBean->list_type = 'default';
            $LPOBean->assigned_user_id = $signatureBean->assigned_user_id ?? $current_user->id;
            $LPOBean->assigned_user_name = $signatureBean->assigned_user_name ?? $current_user->user_name;
            $LPOBean->save();
            
        } else {
            $LPOBean = array_shift($existingLPO);
            
        }

        // Link each contact to the LPO
        $LPOBean->load_relationship('contacts');
        while ($contactTarget = $eventBean->db->fetchByAssoc($contactsTargets)) {
            $LPOBean->contacts->add($contactTarget['stic_registrations_contactscontacts_ida']);
        }
        // Link each lead to the LPO
        $LPOBean->load_relationship('leads');
        while ($leadTarget = $eventBean->db->fetchByAssoc($leadsTargets)) {
            $LPOBean->leads->add($leadTarget['stic_registrations_leadsleads_ida']);
        }
        // Link each account to the LPO
        $LPOBean->load_relationship('accounts');
        while ($accountTarget = $eventBean->db->fetchByAssoc($accountsTargets)) {
            $LPOBean->accounts->add($accountTarget['stic_registrations_accountsaccounts_ida']);
        }
        
        
        return ['status' => 'success', 'lpoId' => $LPOBean->id, 'lpoName' => $LPOBean->name];

    }
}
