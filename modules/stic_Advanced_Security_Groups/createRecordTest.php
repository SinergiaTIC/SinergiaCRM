<?php

// // $rulesBean1 = BeanFactory::getBean('stic_Advanced_Security_Groups', 'e2f936d5-6247-9cb1-6f30-6595589a206e');
// // var_dump($rulesBean1->inherit_from_modules);
// $rulesBean2 = BeanFactory::getBean('stic_Advanced_Security_Groups', 'e2f936d5-6247-9cb1-6f30-6595589a206e');
// var_dump($rulesBean2->inherit_from_modules);

// die();

// $rulesBean1 = BeanFactory::getBean('Contacts', '6d3e8198-2f32-0b5d-8ac6-652fc2a1a33c');
// var_dump($rulesbean2->inherit_from_modules);die();

// // print_r($rulesBean1->last_name);die();

// $rulesBean2 = BeanFactory::getBean('Contacts', '6d3e8198-2f32-0b5d-8ac6-652fc2a1a33c');
// print_r($rulesbean2->id);die();

global $current_user;
$bean = BeanFactory::newBean($_REQUEST['modulo']);
$bean->set_created_by = false;
$bean->assigned_user_id = 2;
$bean->created_by = 1;
$bean->name = rand();

switch ($_REQUEST['modulo']) {
    case 'stic_Events':
        $bean->stic_centers_stic_eventsstic_centers_ida = '155f0177-315a-6f66-74b3-65818cd1cb8c';
        $bean->stic_events_projectproject_ida = '304778dc-b17b-4bc3-6190-62a209a952fb';

        break;
    case 'stic_Sessions':
        // $bean->contact_id_c = '68a5737b-804e-b330-17a7-6305f57df183';
        $bean->stic_sessions_stic_eventsstic_events_ida = '8ba4c208-a822-9cfa-8611-6596a3a7a821';

        break;
    case 'stic_Contacts_Relationships':
        $bean->stic_contacts_relationships_contactscontacts_ida = '9e840dc9-9544-1649-b346-658ea9bec796';
        $bean->stic_centers_stic_contacts_relationshipsstic_centers_ida = '155f0177-315a-6f66-74b3-65818cd1cb8c';
        $bean->stic_contacts_relationships_projectproject_ida = '304778dc-b17b-4bc3-6190-62a209a952fb';

        break;

    default:
        # code...
        break;
}
$bean->save();
// var_dump($bean);die();
SugarApplication::redirect("index.php?module={$_REQUEST['modulo']}&action=detailview&record={$bean->id}");
