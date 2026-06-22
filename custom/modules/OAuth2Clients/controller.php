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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'modules/OAuth2Clients/controller.php';

class CustomOAuth2ClientsController extends OAuth2ClientsController
{
    /**
     * Redirect existing portal clients to the portal edit view.
     */
    public function action_EditView()
    {
        if (!empty($this->bean->fetched_row) && $this->bean->allowed_grant_type === 'portal_authorization_code') {
            SugarApplication::redirect('index.php?module=OAuth2Clients&action=EditViewPortal&record=' . $this->bean->id);
        }
        parent::action_EditView();
    }

    /** SinergiaCRM — Portal authentication (portal_authorization_code) OAuth2 clients. */
    public function action_EditViewPortal()
    {
        $this->view = 'edit';
    }
}
