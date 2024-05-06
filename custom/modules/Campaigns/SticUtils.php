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
    $opportunityId = $args[0]['opportunity_id'];
    $campaignType = "Notification";

    $return_array['select'] = " SELECT campaigns.*";
    $return_array['from'] = " FROM campaigns";
    $return_array['where'] = " WHERE campaigns.deleted = '0' AND campaigns.campaign_type = '$campaignType'";
    $return_array['join'] =
        " INNER JOIN opportunities_campaigns_1_c opp_camp ON" .
        "  opp_camp.opportunities_campaigns_1campaigns_idb = campaigns.id AND opp_camp.deleted = '0'".
        " INNER JOIN opportunities opp ON".
        "  opp.id = opp_camp.opportunities_campaigns_1opportunities_ida AND opp.deleted = '0' AND opp.id = '$opportunityId'";

    $return_array['join_tables'] = '';
    return $return_array;
}
