<?php

class stic_Contacts_RelationshipsLogicHooks
{

    public function before_save(&$bean, $event, $arguments)
    {
        // Create name if empty
        if (empty($bean->name)) {
            include_once 'SticInclude/Utils.php';
            global $app_list_strings;
            $relatedBean = SticUtils::getRelatedBeanObject($bean, 'stic_contacts_relationships_contacts');
            $bean->name = $relatedBean->first_name . ' ' . $relatedBean->last_name . ' - ' . $app_list_strings['stic_contacts_relationships_types_list'][$bean->relationship_type];
        }

         // Set active/inactive status
         include_once 'modules/stic_Contacts_Relationships/Utils.php';
         $bean->active=stic_Contacts_RelationshipsUtils::isActive($bean);
    }

    public function after_save(&$bean, $event, $arguments)
    {
        // Update active relationship types in Contacts in case of date or relationship type changes
        if ($bean->start_date != $bean->fetched_row['start_date']
            || $bean->end_date != $bean->fetched_row['end_date']
            || $bean->relationship_type != $bean->fetched_row['relationship_type']
        ) {
            include_once 'SticInclude/Utils.php';
            if ($contactBean = SticUtils::getRelatedBeanObject($bean, 'stic_contacts_relationships_contacts')) {
                include_once 'modules/stic_Contacts_Relationships/Utils.php';
                stic_Contacts_RelationshipsUtils::setRelationshipType($contactBean->id);
                // Related with STIC#744 
                $contactBean->retrieve();
            }
            else {
                $GLOBALS['log']->error('Line '.__LINE__.': '.__METHOD__.': ' . 'The related Contact bean is empty');
            }
        }              
    }

    public function manage_relationships(&$bean, $event, $arguments)
    {
        // Update active relationship types in Contacts in case of relationship changes between Contacts and Contacts_Relationships
        if ($arguments['related_module'] == 'Contacts') {
            switch ($event) {
                case 'after_relationship_delete':
                case 'after_relationship_add':
                    if ($arguments['related_id']) {
                        $contactBean = BeanFactory::getBean('Contacts', $arguments['related_id']);
                        include_once 'modules/stic_Contacts_Relationships/Utils.php';
                        stic_Contacts_RelationshipsUtils::setRelationshipType($contactBean->id);
                        // Related with STIC#744 
                        $contactBean->retrieve();                        
                    }
                    else {
                        $GLOBALS['log']->error('Line '.__LINE__.': '.__METHOD__.': ' . 'The related Contact Id is empty');
                    }
                    break;
                default:
                    break;
            }
        }
    }

}
