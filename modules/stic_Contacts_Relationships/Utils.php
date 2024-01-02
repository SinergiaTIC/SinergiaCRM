<?php

class stic_Contacts_RelationshipsUtils {
    /**
     * Indicates if the relationship is active.
     *   1. If start_date is future returns false
     *   2. If end_date is null or future returns true
     *
     * @param Object $CRBean stic_Contacts_Relationships bean
     * @return boolean
     */
    public static function isActive($CRBean) {

        // Calculate if relationship is active using similar pattern that in setRelationshipType function
        $today = date("Y-m-d");
        $start = $CRBean->start_date;
        $end = $CRBean->end_date;

        if (
            (empty($start) || $start <= $today)
            && (empty($end) || $end > $today)
        ) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Calculate and save Contacts relationship_type field based upon active records in Contacts Relationships module
     * @param string $contactId
     * @return mixed False if error, true if ok
     */
    public static function setRelationshipType($contactId) {
        $updateRelTypeQuery =
            "UPDATE contacts_cstm
            SET contacts_cstm.stic_relationship_type_c =
            (
                SELECT 
                if(
                    stic_contacts_relationships.relationship_type = '' || stic_contacts_relationships.relationship_type IS NULL,
                    '^^',
                    GROUP_CONCAT(
                        DISTINCT 
                        concat('^', stic_contacts_relationships.relationship_type, '^')
                        ORDER BY
                        stic_contacts_relationships.relationship_type ASC
                    )
                )
                FROM stic_contacts_relationships
                JOIN stic_contacts_relationships_contacts_c
                ON stic_contacts_relationships_contacts_c.stic_contae394onships_idb = stic_contacts_relationships.id
                AND stic_contacts_relationships_contacts_c.stic_contacts_relationships_contactscontacts_ida = '$contactId'
                WHERE stic_contacts_relationships.deleted = 0
                    AND stic_contacts_relationships_contacts_c.deleted = 0
                    AND (isnull(stic_contacts_relationships.start_date)
                        OR stic_contacts_relationships.start_date IS NULL
                        OR stic_contacts_relationships.start_date <= CURRENT_DATE()
                        )
                    AND (isnull(stic_contacts_relationships.end_date)
                        OR stic_contacts_relationships.end_date IS NULL
                        OR stic_contacts_relationships.end_date > CURRENT_DATE()
                        )
            )
            WHERE contacts_cstm.id_c = '$contactId'";

        $db = DBManagerFactory::getInstance();
        $result = $db->query($updateRelTypeQuery);
        if (!$result) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . 'Database error while updating the Relationship type field for the Contact Id: ' . $contactId);
            return false;
        }
        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . 'Relationship type field for the Contact Id ' . $contactId . ' has been updated.');
        return true;
    }
}
