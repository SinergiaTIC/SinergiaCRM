<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

#[\AllowDynamicProperties]
class SticGetContactAddresses
{
    public static function getAddresses()
    {
        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ':  Fetching contact addresses');

        if (empty($_REQUEST['contact_id'])) {
            echo json_encode(['error' => 'contact_id is required']);
            return;
        }
        $contact_id = $_REQUEST['contact_id'];

        $contact = BeanFactory::getBean('Contacts', $contact_id);
        if (!$contact || $contact->id !== $contact_id) {
            echo json_encode(['error' => 'Contact not found']);
            return;
        }

        header('Content-Type: application/json');

        if (!$contact->ACLAccess('view')) {
            echo json_encode(['error' => 'Access denied']);
            return;
        }

        echo json_encode([
            'primary_address_street' => $contact->primary_address_street,
            'primary_address_city' => $contact->primary_address_city,
            'primary_address_state' => $contact->primary_address_state,
            'primary_address_postalcode' => $contact->primary_address_postalcode,
            'primary_address_country' => $contact->primary_address_country,
            'alt_address_street' => $contact->alt_address_street,
            'alt_address_city' => $contact->alt_address_city,
            'alt_address_state' => $contact->alt_address_state,
            'alt_address_postalcode' => $contact->alt_address_postalcode,
            'alt_address_country' => $contact->alt_address_country,
            'stic_identification_number_c' => $contact->stic_identification_number_c,
        ]);
    }
}

if ($_REQUEST['entryPoint'] === 'sticGetContactAddresses') {
    SticGetContactAddresses::getAddresses();
}
