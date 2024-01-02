<?php

class CustomAccountsController extends SugarController
{
    /**
     * This action is triggered when a record is created, added or unlinked from a subpanel.
     * If so, we will run the function updateFieldOnSubpanelChange to update the value of certain fields. 
     */ 
    public function action_SubPanelViewer()
    {
        require_once 'SticInclude/Utils.php';
        $fieldsToUpdate = array(
            'stic_relationship_type_c' => array (
                'type' => 'multienum',
                'list' => 'stic_accounts_relationships_types_list',
            ),
        );
        SticUtils::updateFieldOnSubpanelChange('Accounts', 'stic_accounts_relationships_accounts', $fieldsToUpdate);
    }
}
