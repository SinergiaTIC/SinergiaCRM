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

#[\AllowDynamicProperties]
class OAuth2ClientsViewEdit extends ViewEdit
{
    /** @var string */
    public $formName;

    /** @see SugarView::preDisplay() */
    public function getMetaDataFile()
    {
        $this->setViewType();
        return parent::getMetaDataFile();
    }

    /**
     * Determine which edit view definition to use based on grant type.
     * Adds SinergiaCRM portal (portal_authorization_code) support.
     */
    private function setViewType()
    {
        switch ($this->bean->allowed_grant_type) {
            case 'password':
                $this->type = 'editpassword';
                $this->formName = 'EditPassword';
                break;
            case 'client_credentials':
                $this->type = 'editcredentials';
                $this->formName = 'EditCredentials';
                break;
            case 'portal_authorization_code':
                $this->type = 'editportal';
                $this->formName = 'EditPortal';
                break;
        }
        if (!empty($_REQUEST['action'])) {
            switch ($_REQUEST['action']) {
                case 'EditViewPassword':
                    $this->type = 'editpassword';
                    $this->formName = 'EditPassword';
                    break;
                case 'EditViewCredentials':
                    $this->type = 'editcredentials';
                    $this->formName = 'EditCredentials';
                    break;
                case 'EditViewPortal':
                    $this->type = 'editportal';
                    $this->formName = 'EditPortal';
                    break;
            }
        }
    }

    /** @inheritdoc */
    public function display()
    {
        $this->ev->formName = $this->formName;
        parent::display();
    }
}
