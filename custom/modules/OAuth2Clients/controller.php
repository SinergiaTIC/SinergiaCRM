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

class OAuth2ClientsController extends SugarController
{
    /**
     * @inheritdoc
     */
    public function action_EditView()
    {
        if (empty($_REQUEST['record']) || !$this->bean->fetched_row) {
            parent::action_EditView();
            return;
        }
        switch ($this->bean->allowed_grant_type) {
            case 'password':
                SugarApplication::redirect('index.php?module=OAuth2Clients&action=EditViewPassword&record=' . $this->bean->id);
                break;
            case 'client_credentials':
                SugarApplication::redirect('index.php?module=OAuth2Clients&action=EditViewCredentials&record=' . $this->bean->id);
                break;
            case 'portal_authorization_code':
                SugarApplication::redirect('index.php?module=OAuth2Clients&action=EditViewPortal&record=' . $this->bean->id);
                break;
            default:
                parent::action_EditView();
                break;
        }
    }

    public function action_EditViewPassword()
    {
        $this->view = 'edit';
    }

    public function action_EditViewCredentials()
    {
        $this->view = 'edit';
    }

    /** SinergiaCRM — Portal authentication (portal_authorization_code) OAuth2 clients. */
    public function action_EditViewPortal()
    {
        $this->view = 'edit';
    }
}
