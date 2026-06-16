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

require_once 'modules/Accounts/views/view.detail.php';
require_once 'SticInclude/Views.php';

class CustomAccountsViewDetail extends AccountsViewDetail
{
    public function __construct()
    {
        parent::__construct();
    }

    public function preDisplay()
    {
        parent::preDisplay();

        SticViews::preDisplay($this);

        // Write here the SinergiaCRM code that must be executed for this module and view
    }

    public function display()
    {
        parent::display();

        SticViews::display($this);

        // Portal: inject configured apps for invitation buttons
        require_once 'SticInclude/Portal/ConfigUtils.php';
        $allCfg = SticPortalConfigUtils::getAll();
        $clients = ($allCfg['PORTAL_APPS'] ? @unserialize(htmlspecialchars_decode($allCfg['PORTAL_APPS']), ["allowed_classes" => false]) : []) ?: [];
        echo "<script>STIC.portalClients = " . json_encode($clients) . ";</script>\n";
        
        // Portal Actions popup
        echo '<div id="portalActionsPopup" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:99999;justify-content:center;align-items:center;">
        <div style="background:#fff;border-radius:8px;padding:30px;max-width:480px;width:90%;box-shadow:0 4px 20px rgba(0,0,0,0.3);">
            <h3 style="margin:0 0 15px 0;font-size:16px;">Portal Actions</h3>
            <div style="margin-bottom:15px;">
                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;color:#555;">Action</label>
                <select id="portalActionType" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;font-size:13px;color:#333;background:#fff;">
                    <option value="invitation">Send Invitation Email</option>
                    <option value="pwreset">Send Password Reset</option>
                </select>
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;color:#555;">Target App for Redirect</label>
                <select id="portalAppSelect" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;font-size:13px;color:#333;background:#fff;"></select>
            </div>
            <div style="margin-bottom:20px;padding:8px 12px;background:#e3f2fd;border-radius:4px;font-size:11px;color:#555;">
                <i class="glyphicon glyphicon-info-sign"></i>
                Admin access required. Configure apps in <a href="index.php?module=Administration&action=sticportalconfig" target="_blank">Administration → Portal Configuration</a>.
            </div>
            <div style="text-align:right;">
                <button onclick="closePortalActionsPopup()" style="padding:8px 16px;border:1px solid #ddd;background:#f5f5f5;border-radius:4px;cursor:pointer;margin-right:8px;font-size:13px;">Cancel</button>
                <button onclick="executePortalAction()" style="padding:8px 16px;background:#1976d2;color:#fff;border:none;border-radius:4px;cursor:pointer;font-size:13px;">Execute</button>
            </div>
        </div></div>';
        
        echo getVersionedScript("custom/modules/Accounts/SticUtils.js");


        require_once('modules/stic_Messages/Utils.php');
        stic_MessagesUtils::echoIsMessagesModuleActive();

        // Write here the SinergiaCRM code that must be executed for this module and view
    }
}
