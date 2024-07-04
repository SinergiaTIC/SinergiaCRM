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

function getNotificationsFromParent($params)
{
    $args = func_get_args();
    $return_as_array = isset($args[0]['return_as_array']) ? $args[0]['return_as_array'] : false;
    $parentId = $args[0]['parent_id'] ?? $_REQUEST['record'];
    $parentType = $args[0]['parent_type'];
    $campaignType = "Notification";

    $return_array['select'] =
        " SELECT campaigns.id, campaigns.campaign_type, campaigns.name, campaigns.status, campaigns.start_date, campaigns.end_date" .
        ", pl.id as notification_prospect_list_ids" .
        ", pl.name as notification_prospect_list_names" .
        ", et.name as notification_email_template_name";

    $return_array['from'] = " FROM campaigns ";

    $return_array['where'] =
        " WHERE campaigns.deleted = '0'" .
        " AND campaigns.campaign_type = '$campaignType'" .
        " AND cc.parent_type = '$parentType'" .
        " AND cc.parent_id = '$parentId'";

    $return_array['join'] =
        " LEFT JOIN campaigns_cstm cc on cc.id_c = campaigns.id" .
        " LEFT JOIN prospect_list_campaigns plc on plc.campaign_id = campaigns.id and plc.deleted = '0'" .
        " LEFT JOIN prospect_lists pl on pl.id = plc.prospect_list_id and pl.deleted = '0'" .
        " LEFT JOIN email_marketing em on em.campaign_id = campaigns.id and em.deleted = '0'" .
        " LEFT JOIN email_templates et on et.id = em.template_id and et.deleted = '0'";

    $return_array['join_tables'] = '';

    if ($return_as_array) {
        return $return_array;
    } else {
        return $return_array['select'].' '.$return_array['from'].' '.$return_array['join'].' '.$return_array['where'];
    }
}

function fillCampaignNotificationFields($beanCampaign)
{
    //Fill prospect_list and email_template
    global $db;

    $query =
        " SELECT c.id as campaigns_id" .
        ", pl.id as prospect_lists_ids" .
        ", et.id as email_templates_id" .
        ", em.outbound_email_id as email_marketing_outbound_email_id" .
        ", em.from_name as email_marketing_from_name" .
        ", em.from_addr as email_marketing_from_addr" .
        ", em.reply_to_name as email_marketing_reply_to_name" .
        ", em.reply_to_addr as email_marketing_reply_to_addr" .
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
        $beanCampaign->notification_prospect_list_ids = $row['prospect_lists_ids'];
        $beanCampaign->notification_template_id = $row['email_templates_id'];
        $beanCampaign->notification_outbound_email_id = $row['email_marketing_outbound_email_id'];
        $beanCampaign->notification_from_name = $row['email_marketing_from_name'];
        $beanCampaign->notification_from_addr = $row['email_marketing_from_addr'];
        $beanCampaign->notification_reply_to_name = $row['email_marketing_reply_to_name'];
        $beanCampaign->notification_reply_to_addr = $row['email_marketing_reply_to_addr'];
    }
}

function fillDynamicListsForNotifications()
{
    fillDynamicListProspectList();
    fillDynamicListEmailTemplate();
    fillDynamicOutboundEmailAccounts();
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

function fillDynamicOutboundEmailAccounts()
{
    $outboundEmailsFocus = BeanFactory::newBean('OutboundEmailAccounts');
    $outboundEmails = $outboundEmailsFocus->get_list("name", "", 0, -99, -99);

    //$dynamic_outbound_email_list = array("" => "");
    foreach ($outboundEmails['list'] as $outboundEmail) {
        $dynamic_outbound_email_list[$outboundEmail->id] = $outboundEmail->name;
    }

    $GLOBALS['app_list_strings']['dynamic_outbound_email_list'] = $dynamic_outbound_email_list;
}
