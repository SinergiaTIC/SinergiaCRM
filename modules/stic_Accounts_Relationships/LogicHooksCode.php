<?php

class stic_Accounts_RelationshipsLogicHooks
{

    public function before_save(&$bean, $event, $arguments)
    {
        // Create name if empty
        if (empty($bean->name)) {
            include_once 'SticInclude/Utils.php';
            global $app_list_strings;
            $relatedBean = SticUtils::getRelatedBeanObject($bean, 'stic_accounts_relationships_accounts');
            $bean->name = $relatedBean->name . ' - ' . $app_list_strings['stic_accounts_relationships_types_list'][$bean->relationship_type];
        }

        // Set active/inactive status
        include_once 'modules/stic_Accounts_Relationships/Utils.php';
        $bean->active=stic_Accounts_RelationshipsUtils::isActive($bean);
    }

    public function after_save(&$bean, $event, $arguments)
    {
        // Update active relationship types in Accounts in case of date or relationship type changes
        if ($bean->start_date != $bean->fetched_row['start_date']
            || $bean->end_date != $bean->fetched_row['end_date']
            || $bean->relationship_type != $bean->fetched_row['relationship_type']
        ) {
            include_once 'SticInclude/Utils.php';
            if ($accountBean = SticUtils::getRelatedBeanObject($bean, 'stic_accounts_relationships_accounts')) {
                include_once 'modules/stic_Accounts_Relationships/Utils.php';
                stic_Accounts_RelationshipsUtils::setRelationshipType($accountBean->id);
                // Related wih STIC#744
                $accountBean->retrieve();
            } else {
                $GLOBALS['log']->error('Line '.__LINE__.': '.__METHOD__.': ' . 'The related Account bean is empty');
            }
        }
    }

    public function manage_relationships(&$bean, $event, $arguments)
    {
        // Update active relationship types in Accounts in case of relationship changes between Accounts and Accounts_Relationships
        if ($arguments['related_module'] == 'Accounts') {
            switch ($event) {
                case 'after_relationship_delete':
                case 'after_relationship_add':
                    if ($arguments['related_id']) {
                        $accountBean = BeanFactory::getBean('Accounts', $arguments['related_id']);
                        include_once 'modules/stic_Accounts_Relationships/Utils.php';
                        stic_Accounts_RelationshipsUtils::setRelationshipType($accountBean->id);
                        // Related with STIC#744 
                        $accountBean->retrieve();
                    } else {
                        $GLOBALS['log']->error('Line '.__LINE__.': '.__METHOD__.': ' . ' The related Account Id is empty');
                    }
                    break;
                default:
                    break;
            }
        }
    }

}
