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


global $mod_strings;
       
if (ACLController::checkAccess('TemplateSectionLine', 'edit', true)) {
    $module_menu[]=array("index.php?module=TemplateSectionLine&action=EditView&return_module=TemplateSectionLine&return_action=index", $mod_strings['LNK_NEW_RECORD'],"Create", 'TemplateSectionLine');
}

if (ACLController::checkAccess('TemplateSectionLine', 'list', true)) {
    $module_menu[]=array("index.php?module=TemplateSectionLine&action=index&return_module=TemplateSectionLine&return_action=DetailView", $mod_strings['LNK_LIST'],"List", 'TemplateSectionLine');
}

if (ACLController::checkAccess('Campaigns', 'list', true)) {
    $module_menu[]=	array(
        "index.php?module=Campaigns&action=index&return_module=Campaigns&return_action=index",
        $mod_strings['LNK_CAMPAIGN_LIST'],"List", 'Campaigns'
    );
}

if (ACLController::checkAccess('EmailTemplates', 'edit', true)) {
    $module_menu[] = array("index.php?module=EmailTemplates&action=EditView&return_module=EmailTemplates&return_action=DetailView",$mod_strings['LNK_NEW_EMAIL_TEMPLATE'],"View_Create_Email_Templates","Emails");
}

if (ACLController::checkAccess('EmailTemplates', 'list', true)) {
    $module_menu[] = array("index.php?module=EmailTemplates&action=index",$mod_strings['LNK_EMAIL_TEMPLATE_LIST'],"View_Email_Templates", 'Emails');
}