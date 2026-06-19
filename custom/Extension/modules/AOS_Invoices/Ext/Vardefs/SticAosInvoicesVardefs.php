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

$dictionary["AOS_Invoices"]["relationships"]["aos_invoices_stic_payments"] = array(
    'lhs_module' => 'AOS_Invoices',
    'lhs_table' => 'aos_invoices',
    'lhs_key' => 'id',
    'rhs_module' => 'stic_Payments',
    'rhs_table' => 'stic_payments',
    'rhs_key' => 'aos_invoices_id_c',
    'relationship_type' => 'one-to-many',
);

$dictionary['AOS_Invoices']['fields']['stic_payments'] = array(
    'name' => 'stic_payments',
    'type' => 'link',
    'relationship' => 'aos_invoices_stic_payments',
    'module' => 'stic_Payments',
    'bean_name' => 'stic_Payments',
    'source' => 'non-db',
    'vname' => 'LBL_STIC_PAYMENTS',
);


