<?php

$dictionary['User']['fields']['inc_reference_group_c'] = array(
    'id' => 'Usersinc_reference_group_c',
    'name' => 'inc_reference_group_c',
    'vname' => 'LBL_INC_REFERENCE_GROUP',
    'custom_module' => 'Users',
    'source' => 'custom_fields',
    'comments' => '',
    'help' => '',
    'type' => 'enum',
    'len' => '100',
    'size' => '20',
    'options' => 'stic_incorpora_reference_group_list',
    'required' => 0,
    'audited' => 0,
    'unified_search' => 0,
    'default' => null,
    'no_default' => 0,
    'inline_edit' => 1,
    'importable' => 1,
    'massupdate' => 1,
    'reportable' => 1,
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '2',
    'merge_filter' => 'enabled',
    'studio' => 'visible',
    'dependency' => 0,
);

$dictionary['User']['fields']['inc_reference_entity_c'] = array(
    'id' => 'Usersinc_reference_entity_c',
    'name' => 'inc_reference_entity_c',
    'vname' => 'LBL_INC_REFERENCE_ENTITY',
    'custom_module' => 'Users',
    'source' => 'custom_fields',
    'comments' => '',
    'help' => '',
    'type' => 'dynamicenum',
    'len' => '100',
    'size' => '20',
    'options' => 'stic_incorpora_reference_entity_list',
    'parentenum' => 'inc_reference_group_c',
    'required' => 0,
    'audited' => 0,
    'unified_search' => 0,
    'default' => null,
    'no_default' => 0,
    'inline_edit' => 1,
    'importable' => 1,
    'massupdate' => 1,
    'reportable' => 1,
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '2',
    'merge_filter' => 'enabled',
    'studio' => 'visible',
    'dependency' => 0,
);

$dictionary["User"]["fields"]["inc_reference_officer_c"] = array(
    'id' => 'Usersinc_reference_officer_c',
    'name' => 'inc_reference_officer_c',
    'vname' => 'LBL_INC_REFERENCE_OFFICER',
    'custom_module' => 'Users',
    'required' => 0,
    'source' => 'custom_fields',
    'type' => 'int',
    'massupdate' => '0',
    'default' => NULL,
    'no_default' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 1,
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '2',
    'audited' => 0,
    'reportable' => 1,
    'unified_search' => 0,
    'merge_filter' => 'enabled',
    'len' => '255',
    'size' => '20',
    'enable_range_search' => 0,
    'disable_num_format' => NULL,
);

$dictionary['User']['fields']['inc_incorpora_user_c'] = array(
    'id' => 'Usersinc_incorpora_user_c',
    'name' => 'inc_incorpora_user_c',
    'vname' => 'LBL_INC_INCORPORA_USER',
    'custom_module' => 'Users',
    'source' => 'custom_fields',
    'comments' => '',
    'help' => '',
    'type' => 'varchar',
    'len' => '255',
    'size' => '20',
    'required' => 0,
    'audited' => 0,
    'unified_search' => 0,
    'default' => null,
    'no_default' => 0,
    'inline_edit' => 1,
    'importable' => 1,
    'massupdate' => 0,
    'reportable' => 1,
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '2',
    'merge_filter' => 'selected',
    'studio' => 'visible',
);

// There is an error editing this field inline
// STIC#291
$dictionary['User']['fields']['UserType']['inline_edit'] = 0; 


// Virtual fields for Kreporter

// This file add a calculated field to the EmailAddress module in Kreports.
// This field gets all the Email Addresses related to a record, and shows them concatenated.
// This is necessary because Kreports only shows the primary_email_address by default
// STIC#354

$dictionary['User']['fields']['emails_list'] = array(
    'name' => 'emails_list',
    'vname' => 'LBL_KREPORTER_EMAILS_LIST',
    'type' => 'kreporter',
    'source' => 'non-db',
    'kreporttype' => 'varchar',
    'eval' => array(
        'presentation' => array(
            'eval' => 'SELECT GROUP_CONCAT(ea.email_address) FROM email_addresses ea JOIN email_addr_bean_rel eabr ON ea.id = eabr.email_address_id WHERE eabr.deleted = 0 AND eabr.bean_id = {t}.id',
        ),
        'selection' => array(
            'starts' => 'exists(SELECT ea.email_address FROM email_addresses ea JOIN email_addr_bean_rel eabr ON ea.id = eabr.email_address_id WHERE eabr.deleted = 0 AND eabr.bean_id = {t}.id AND ea.email_address LIKE \'{p1}%\')',
            'notstarts' => 'exists(SELECT ea.email_address FROM email_addresses ea JOIN email_addr_bean_rel eabr ON ea.id = eabr.email_address_id WHERE eabr.deleted = 0 AND eabr.bean_id = {t}.id AND ea.email_address NOT LIKE \'{p1}%\')',
            'contains' => 'exists(SELECT ea.email_address FROM email_addresses ea JOIN email_addr_bean_rel eabr ON ea.id = eabr.email_address_id WHERE eabr.deleted = 0 AND eabr.bean_id = {t}.id AND ea.email_address LIKE \'%{p1}%\')',
            'notcontains' => 'exists(SELECT ea.email_address FROM email_addresses ea JOIN email_addr_bean_rel eabr ON ea.id = eabr.email_address_id WHERE eabr.deleted = 0 AND eabr.bean_id = {t}.id AND ea.email_address NOT LIKE \'%{p1}%\')',
        ),
    ),
);

$dictionary['User']['fields']['status']['massupdate'] = 1;
$dictionary['User']['fields']['reports_to_name']['massupdate'] = 1;
$dictionary['User']['fields']['editor_type']['massupdate'] = 1;
