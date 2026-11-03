<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2026 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by
 * the Free Software Foundation.
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

require_once 'include/MVC/View/views/view.list.php';

class stic_Portal_Login_AuditViewList extends ViewList
{
    public function preDisplay()
    {
        global $current_user;

        if (!is_admin($current_user)) {
            sugar_die('Unauthorized access to portal login audit.');
        }

        require_once 'SticInclude/Portal/ConfigUtils.php';
        SticPortalConfigUtils::purgeOldAudit();

        parent::preDisplay();
    }

    public function listViewPrepare()
    {
        parent::listViewPrepare();
        // Read-only list: no bulk selection, no bulk actions dropdown and no
        // mass update form. This also keeps the pagination buttons URL-based:
        // with checkboxes rendered but no MassUpdate form, sListView.save_checks
        // (their onclick) would fail and pagination would stop working.
        $this->params['massupdate'] = false;
        $this->lv->multiSelect = false;
        $this->lv->show_action_dropdown = false;
    }
}
