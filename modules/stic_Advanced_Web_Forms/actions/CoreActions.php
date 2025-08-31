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
// Prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

include_once __DIR__."../core/actions/stic_AWF_ActionParameter.php";
include_once __DIR__."../core/actions/stic_AWF_ActionResult.php";
include_once __DIR__."../core/actions/stic_AWF_WebhookResult.php";
include_once __DIR__."../core/actions/stic_AWF_ActionModifiedBean.php";

include_once __DIR__."../core/actions/stic_AWF_ActionInterface.php";
include_once __DIR__."../core/actions/stic_AWF_Executable_ActionInterface.php";
include_once __DIR__."../core/actions/stic_AWF_Scoped_ActionInterface.php";


include_once __DIR__."../core/actions/UI/stic_AWF_UI_ActionInterface.php";


include_once __DIR__."../core/actions/DataProvider/stic_AWF_DataProvider_ActionInterface.php";


include_once __DIR__."../core/actions/Hook/stic_AWF_Hook_ActionInterface.php";
include_once __DIR__."../core/actions/Hook/stic_AWF_Hook_Bean_ActionInterface.php";
include_once __DIR__."../core/actions/Hook/stic_AWF_Deferred_ActionInterface.php";
include_once __DIR__."../core/actions/Hook/stic_AWF_Deferred_ManualReviewInterface.php";


include_once __DIR__."../core/actions/Group/stic_AWF_Group_ActionInterface.php";
