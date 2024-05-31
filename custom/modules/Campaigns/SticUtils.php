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
function get_notifications_from_opportunity($params)
{
    $args = func_get_args();
    $parentId = $args[0]['opportunity_id'];
    $parentType = "Opportunities";
    $campaignType = "Notification";

    $return_array['select'] =
        " SELECT campaigns.id, campaigns.name, campaigns.status" .
        ", em.name as email_marketing_name" .
        ", em.date_start as email_marketing_date_start" .
        ", em.status as email_marketing_status" .
        ", et.name as email_templates_name" .
        ", pl.name as prospect_lists_name";

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

    return $return_array;
}
