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

require_once 'include/MVC/View/views/view.list.php';
require_once 'SticInclude/Views.php';

class stic_SignersViewList extends ViewList
{

    public function __construct()
    {
        parent::__construct();

    }

    public function preDisplay()
    {
        global $current_user, $db;
        parent::preDisplay();
        $userPreferenceOrder = $current_user->getPreference('listviewOrder', 'stic_Signers2_STIC_SIGNERS');
        
        SticViews::preDisplay($this);
        
        // Force default order by date_modified DESC if no order is set
        if (empty($userPreferenceOrder['orderBy'])) {
            $_REQUEST['orderBy'] = 'date_modified';
            $_REQUEST['sortOrder'] = 'DESC';
        }

        // When redirected after "Add only" with multiple signers, filter the list
        // to show only the newly added signers, and expose a flag to the template.
        if (!empty($_REQUEST['stic_added_uids'])) {
            $ids = explode(',', $_REQUEST['stic_added_uids']);
            $quoted = [];
            foreach ($ids as $id) {
                $id = trim($id);
                if (!empty($id)) {
                    $quoted[] = "'" . $db->quote($id) . "'";
                }
            }
            if (!empty($quoted)) {
                $this->addedFilterWhere = 'stic_signers.id IN (' . implode(',', $quoted) . ')';
                $this->showingAddedOnly = true;
                $this->addedCount = count($quoted);
            }
        }

    }

    public function processSearchForm()
    {
        parent::processSearchForm();
        if (!empty($this->addedFilterWhere)) {
            if (!empty($this->where)) {
                $this->where = '(' . $this->where . ') AND (' . $this->addedFilterWhere . ')';
            } else {
                $this->where = $this->addedFilterWhere;
            }
        }
    }

    public function display()
    {
        if (!empty($this->showingAddedOnly)) {
            $count = (int) $this->addedCount;
            echo "<div style='margin:8px 0; padding:8px 12px; background:#eaf4ea; border:1px solid #c8e6c9; border-radius:4px; color:#2e7d32;'>"
                . "Mostrando solo el/los firmante(s) añadido(s). "
                . "<a href='index.php?module=stic_Signers&action=index' style='margin-left:8px; text-decoration:underline; font-weight:bold; color:#1565c0; background:transparent;'>Ver todos los firmantes</a>"
                . "</div>";
        }

        parent::display();

        SticViews::display($this);
        echo getVersionedScript("modules/stic_Signers/Utils.js");

        // Write here you custom code
    }

}
