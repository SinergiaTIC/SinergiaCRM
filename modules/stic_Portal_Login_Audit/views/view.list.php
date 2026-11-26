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

    public function listViewProcess()
    {
        $this->processSearchForm();
        $this->lv->searchColumns = $this->searchForm->searchColumns;

        if (!$this->headers) {
            return;
        }
        if (empty($_REQUEST['search_form_only']) || $_REQUEST['search_form_only'] == false) {
            $this->lv->ss->assign("SEARCH", true);
            $this->lv->ss->assign('savedSearchData', $this->searchForm->getSavedSearchData());
            $this->lv->setup($this->seed, 'include/ListView/ListViewGeneric.tpl', $this->where, $this->params);
            // Read-only audit: strip every edit affordance from the rows —
            // the edit-link pencil (rowAccess drives it and admins bypass
            // ACLs, so denying ACL edit alone cannot hide it) and the
            // inline-edit pencil on every column (incl. parent module).
            if (!empty($this->lv->data['pageData']['rowAccess'])) {
                foreach ($this->lv->data['pageData']['rowAccess'] as $i => $access) {
                    $this->lv->data['pageData']['rowAccess'][$i]['edit'] = false;
                }
            }
            $this->lv->ss->assign('inline_edit', false);
            echo $this->lv->display();
        }
    }
}
