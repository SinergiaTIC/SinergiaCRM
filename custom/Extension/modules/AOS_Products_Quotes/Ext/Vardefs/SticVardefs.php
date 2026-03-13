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
$dictionary['AOS_Products_Quotes']['fields']['item_description']['rows'] = '2'; // Make textarea fields shorter
$dictionary['AOS_Products_Quotes']['fields']['description']['rows'] = '2'; // Make textarea fields shorter

// Inline edition definition:
$dictionary['AOS_Products_Quotes']['fields']['name']['inline_edit'] = 0;
$dictionary['AOS_Products_Quotes']['fields']['group_name']['inline_edit'] = 0;
$dictionary['AOS_Products_Quotes']['fields']['parent_name']['inline_edit'] = 0;
$dictionary['AOS_Products_Quotes']['fields']['number']['inline_edit'] = 0;
$dictionary['AOS_Products_Quotes']['fields']['product_qty']['inline_edit'] = 0;
$dictionary['AOS_Products_Quotes']['fields']['product']['inline_edit'] = 0;
$dictionary['AOS_Products_Quotes']['fields']['part_number']['inline_edit'] = 0;
$dictionary['AOS_Products_Quotes']['fields']['product_list_price']['inline_edit'] = 0;
$dictionary['AOS_Products_Quotes']['fields']['product_unit_price']['inline_edit'] = 0;
$dictionary['AOS_Products_Quotes']['fields']['product_discount']['inline_edit'] = 0;
$dictionary['AOS_Products_Quotes']['fields']['discount']['inline_edit'] = 0;
$dictionary['AOS_Products_Quotes']['fields']['vat_amt']['inline_edit'] = 0;
$dictionary['AOS_Products_Quotes']['fields']['vat']['inline_edit'] = 0;
$dictionary['AOS_Products_Quotes']['fields']['product_total_price']['inline_edit'] = 0;

$dictionary["AOS_Products_Quotes"]["fields"]["verifactu_aeat_operation_type_c"] = array(
    'id' => 'AOS_Products_Quotesverifactu_aeat_operation_type_c',
    'name' => 'verifactu_aeat_operation_type_c',
    'vname' => 'LBL_VERIFACTU_AEAT_OPERATION_TYPE',
    'custom_module' => 'AOS_Products_Quotes',
    'required' => false,
    'source' => 'custom_fields',
    'type' => 'enum',
    'massupdate' => '0',
    'default' => NULL,
    'no_default' => false,
    'default' => 'S',
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
    'options' => 'stic_products_quotes_operation_type_list',
    'studio' => 'visible',
    'dependency' => NULL,
    'popupHelp' => 'LBL_VERIFACTU_AEAT_OPERATION_TYPE_HELP',
);
