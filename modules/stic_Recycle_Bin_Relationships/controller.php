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

/**
 * Controller for the stic_Recycle_Bin_Relationships module.
 *
 * This module is a child table of stic_Recycle_Bin: every row is created,
 * read and updated exclusively by the parent's hook and restore action.
 * It is never meant to be accessed directly from the UI as a standalone
 * module — it only appears as a subpanel under stic_Recycle_Bin records.
 *
 * For that reason, direct UI access (list/detail/edit views) is blocked
 * here and the user is redirected to the parent module. The SubPanelViewer
 * AJAX action is still allowed because it is how the subpanel renders.
 */
#[\AllowDynamicProperties]
class stic_Recycle_Bin_RelationshipsController extends SugarController
{
    /**
     * List of actions that are part of the subpanel AJAX flow.
     * These must NOT be blocked, otherwise the subpanel breaks.
     */
    private $allowedActions = array(
        'subpanelviewer',
        'subpanelajax',
    );

    public function preProcess()
    {
        $action = strtolower($this->action ?? '');
        if (!in_array($action, $this->allowedActions, true)) {
            header('Location: index.php?module=stic_Recycle_Bin&action=index');
            sugar_cleanup(true);
        }
        SugarController::preProcess();
    }
}
