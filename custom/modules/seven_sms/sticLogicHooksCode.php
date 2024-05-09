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

class stic_sevenSmsLogicHooks {

    public function before_save(&$bean, $event, $arguments) {

        // TODO Evitar bucle si permetem crear SMS des del mòdul.

        $messageBean = BeanFactory::newBean('stic_Messages');
        $messageBean->type = 'sms';
        $messageBean->direction = 'inbound';
        $messageBean->date_entered = $bean->date_entered;
        $messageBean->modified_user_id = $bean->modified_user_id;
        $messageBean->created_by = $bean->created_by;
        $messageBean->phone = $bean->recipient;
        $messageBean->sender = $bean->sender;
        $messageBean->message = $bean->text;
        $messageBean->template_id_c = $_REQUEST['template']; // Seven SMS does not have the concept "template".We retrieve it dirctly from REQUEST

        // TODO Element relacionat
        if ($bean->employee_id) {
            $messageBean->parent_type = 'Employees';
            $messageBean->parent_id = $bean->employee_id;
        }
        if ($bean->lead_id) {
            $messageBean->parent_type = 'Leads';
            $messageBean->parent_id = $bean->lead_id;
        }
        if ($bean->account_id) {
            $messageBean->parent_type = 'Accounts';
            $messageBean->parent_id = $bean->account_id;
        }
        if ($bean->contact_id) {
            $messageBean->parent_type = 'Contacts';
            $messageBean->parent_id = $bean->contact_id;
        }

        $messageBean->save();

    }
}