<?php

require_once 'modules/Contacts/controller.php';
class CustomContactsController extends ContactsController
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
                'list' => 'stic_contacts_relationships_types_list',
            ),
        );
        SticUtils::updateFieldOnSubpanelChange('Contacts', 'stic_contacts_relationships_contacts', $fieldsToUpdate);
    }

    /**
     * Allow call the function directly ContactsUtils::calculateContactsAge using a url like
     * <server_url>/index.php?module=Contacts&action=calculateContactsAge
     *
     * @return void
     */
    public function action_calculateContactsAge() {
        require_once 'custom/modules/Contacts/SticUtils.php';
        ContactsUtils::calculateContactsAge();
        SugarApplication::redirect('index.php?module=Contacts&action=index');

    }
}
