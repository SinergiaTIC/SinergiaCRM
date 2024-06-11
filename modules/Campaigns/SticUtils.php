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
function fillCampaignNotificationFields($beanCampaign) {
    //Fill prospect_list and email_template
    global $db;

    $query = 
    " SELECT c.id as campaigns_id, et.id as email_templates_id, pl.id as prospect_lists_id" . 
    " FROM campaigns c" .
    " LEFT JOIN campaigns_cstm cc on cc.id_c = c.id" .
    " LEFT JOIN prospect_list_campaigns plc on plc.campaign_id = c.id and plc.deleted = '0'" .
    " LEFT JOIN prospect_lists pl on pl.id = plc.prospect_list_id and pl.deleted = '0'" .
    " LEFT JOIN email_marketing em on em.campaign_id = c.id and em.deleted = '0'" .
    " LEFT JOIN email_templates et on et.id = em.template_id and et.deleted = '0'" .
    " WHERE c.id = '{$beanCampaign->id}'" .
    " LIMIT 1";

    $result = $db->query($query);
    if ($row = $db->fetchByAssoc($result)) {
        $beanCampaign->email_template = $row['email_templates_id'];
        $beanCampaign->prospect_list =  $row['prospect_lists_id'];
    }
}


function getLangStrings()
{
    $html = "";
    // Load related lang strings
    $moduleNames = array('Campaigns');
    foreach ($moduleNames as $moduleName) {
        if (!is_file("cache/jsLanguage/{$moduleName}/{$GLOBALS['current_language']}.js")) {
            require_once 'include/language/jsLanguage.php';
            jsLanguage::createModuleStringsCache($moduleName, $GLOBALS['current_language']);
        }
        $html .= getVersionedScript("cache/jsLanguage/{$moduleName}/{$GLOBALS['current_language']}.js", $GLOBALS['sugar_config']['js_lang_version']);
    }
    return $html;
}

function fillDynamicListsForNotifications()
{
    fillDynamicListProspectList();
    fillDynamicListEmailTemplate();
}

function fillDynamicListProspectList()
{
    $prospectListsFocus = BeanFactory::newBean('ProspectLists');
    $prospectLists = $prospectListsFocus->get_list("name", "prospect_lists.list_type='default'", 0, -99, -99);

    $dynamic_prospect_list_list = array("" => "");
    foreach ($prospectLists['list'] as $prospectList) {
        $dynamic_prospect_list_list[$prospectList->id] = $prospectList->name;
    }

    $GLOBALS['app_list_strings']['dynamic_prospect_list_list'] = $dynamic_prospect_list_list;
}

function fillDynamicListEmailTemplate()
{
    $emailTemplatesFocus = BeanFactory::newBean('EmailTemplates');
    $emailTemplates = $emailTemplatesFocus->get_list("name", "", 0, -99, -99);

    $dynamic_email_template_list = array("" => "");
    foreach ($emailTemplates['list'] as $emailTemplate) {
        $dynamic_email_template_list[$emailTemplate->id] = $emailTemplate->name;
    }

    $GLOBALS['app_list_strings']['dynamic_email_template_list'] = $dynamic_email_template_list;
}