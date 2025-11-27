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
$dictionary['AOS_Invoices']['fields']['description']['rows'] = '2'; // Make textarea fields shorter

// Mass update fields definition:
$dictionary['AOS_Invoices']['fields']['billing_account']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['billing_contact']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['billing_address_street']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['billing_address_city']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['billing_address_state']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['billing_address_postalcode']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['billing_address_country']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['shipping_address_street']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['shipping_address_city']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['shipping_address_state']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['shipping_address_postalcode']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['shipping_address_country']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['quote_number']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['quote_date']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['invoice_date']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['due_date']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['status']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['status']['default'] = 'draft'; 

// Inline edition definition:
$dictionary['AOS_Invoices']['fields']['number']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['billing_address_street']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['shipping_address_street']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['currency_id']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['line_items']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['total_amt']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['discount_amount']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['subtotal_amount']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['shipping_amount']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['shipping_tax_amt']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['tax_amount']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['total_amount']['inline_edit'] = 0;


// AEAT Verifactu fields
$dictionary['AOS_Invoices']['fields']['verifactu_hash_c'] = array(
    'id' => 'AOS_Invoicesverifactu_hash_c',
    'name' => 'verifactu_hash_c',
    'vname' => 'LBL_VERIFACTU_HASH',
    'custom_module' => 'AOS_Invoices',
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
    'inline_edit' => 0,
    'importable' => 1,
    'massupdate' => 0,
    'reportable' => 1,
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => 1,
    'merge_filter' => 'selected',
    'studio' => 'visible',
    'popupHelp' => 'LBL_VERIFACTU_HASH_HELP',
);

$dictionary['AOS_Invoices']['fields']['verifactu_previous_hash_c'] = array(
    'id' => 'AOS_Invoicesverifactu_previous_hash_c',
    'name' => 'verifactu_previous_hash_c',
    'vname' => 'LBL_VERIFACTU_PREVIOUS_HASH',
    'custom_module' => 'AOS_Invoices',
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
    'inline_edit' => 0,
    'importable' => 1,
    'massupdate' => 0,
    'reportable' => 1,
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => 1,
    'merge_filter' => 'selected',
    'studio' => 'visible',
    'popupHelp' => 'LBL_VERIFACTU_PREVIOUS_HASH_HELP',
);

$dictionary["AOS_Invoices"]["fields"]["verifactu_qr_data_c"] = array(
    'required' => false,
    'name' => 'verifactu_qr_data_c',
    'vname' => 'LBL_VERIFACTU_QR_DATA',
    'type' => 'text',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => false,
    'inline_edit' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'studio' => 'visible',
    'dbType' => 'text',
    'border' => '',
    'width' => '120',
    'height' => '',
    'popupHelp' => 'LBL_VERIFACTU_QR_DATA_HELP',
);

$dictionary["AOS_Invoices"]["fields"]["verifactu_aeat_status_c"] = array(
    'id' => 'AOS_Invoicesverifactu_aeat_status_c',
    'name' => 'verifactu_aeat_status_c',
    'vname' => 'LBL_VERIFACTU_AEAT_STATUS',
    'custom_module' => 'AOS_Invoices',
    'required' => false,
    'source' => 'custom_fields',
    'type' => 'enum',
    'massupdate' => '0',
    'default' => NULL,
    'no_default' => false,
    'default' => 'pending',
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '2',
    'inline_edit' => 0,
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'enabled',
    'len' => 100,
    'size' => '20',
    'options' => 'stic_invoices_verifactu_aeat_status_list',
    'studio' => 'visible',
    'dependency' => NULL,
    'popupHelp' => 'LBL_VERIFACTU_AEAT_STATUS_HELP',
);

$dictionary['AOS_Invoices']['fields']['verifactu_aeat_response_c'] = array(
    'id' => 'AOS_Invoicesverifactu_aeat_response_c',
    'name' => 'verifactu_aeat_response_c',
    'vname' => 'LBL_VERIFACTU_AEAT_RESPONSE',
    'custom_module' => 'AOS_Invoices',
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
    'inline_edit' => 0,
    'importable' => 1,
    'massupdate' => 0,
    'reportable' => 1,
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => 1,
    'merge_filter' => 'selected',
    'studio' => 'visible',
    'popupHelp' => 'LBL_VERIFACTU_AEAT_RESPONSE_HELP',
);

$dictionary['AOS_Invoices']['fields']['verifactu_cancel_id_c'] = array(
    'id' => 'AOS_Invoicesverifactu_cancel_id_c',
    'name' => 'verifactu_cancel_id_c',
    'vname' => 'LBL_VERIFACTU_CANCEL_ID',
    'custom_module' => 'AOS_Invoices',
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
    'inline_edit' => 0,
    'importable' => 1,
    'massupdate' => 0,
    'reportable' => 1,
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => 1,
    'merge_filter' => 'selected',
    'studio' => 'visible',
    'popupHelp' => 'LBL_VERIFACTU_CANCEL_ID_HELP',
);

$dictionary['AOS_Invoices']['fields']['verifactu_csv_c'] = array(
    'id' => 'AOS_Invoicesverifactu_csv_c',
    'name' => 'verifactu_csv_c',
    'vname' => 'LBL_VERIFACTU_CSV',
    'custom_module' => 'AOS_Invoices',
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
    'inline_edit' => 0,
    'importable' => 1,
    'massupdate' => 0,
    'reportable' => 1,
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => 1,
    'merge_filter' => 'selected',
    'studio' => 'visible',
    'popupHelp' => 'LBL_VERIFACTU_CSV_HELP',
);

$dictionary['AOS_Invoices']['fields']['verifactu_submitted_at_c'] = array(
    'id' => 'AOS_Invoicesverifactu_submitted_at_c',
    'name' => 'verifactu_submitted_at_c',
    'vname' => 'LBL_VERIFACTU_SUBMITTED_AT',
    'custom_module' => 'AOS_Invoices',
    'source' => 'custom_fields',
    'comments' => '',
    'help' => '',
    'type' => 'datetime',
    'required' => 0,
    'audited' => 0,
    'unified_search' => 0,
    'default' => null,
    'no_default' => 0,
    'inline_edit' => 0,
    'importable' => 1,
    'massupdate' => 0,
    'reportable' => 1,
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => 1,
    'merge_filter' => 'selected',
    'studio' => 'visible',
    'popupHelp' => 'LBL_VERIFACTU_SUBMITTED_AT_HELP',
);