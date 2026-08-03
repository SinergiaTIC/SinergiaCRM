<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
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
 * You should have received a copy of the GNU Affero General Public License along
 * with this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/MVC/View/views/view.list.php';
require_once 'SticInclude/Views.php';

class stic_RecycleBinViewList extends ViewList
{
    public function __construct()
    {
        parent::__construct();
    }

    public function preDisplay()
    {
        parent::preDisplay();

        SticViews::preDisplay($this);
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
            $this->injectComputedColumns();
            $savedSearchName = empty($_REQUEST['saved_search_select_name']) ? '' : (' - ' . $_REQUEST['saved_search_select_name']);
            echo $this->lv->display();
            $this->injectMassRestoreScript();
        }
    }

    public function display()
    {
        parent::display();

        SticViews::display($this);
    }

    /**
     * Echoes a self-contained <script> block that:
     *  - Marks each row's checkbox with data-restored="1" when the row is already
     *    restored, so the bulk action can warn the user.
     *  - Defines window.sticRecycleBinMassRestore() which validates the selection
     *    and posts a new form to action=mass_restore with the selected uids.
     *
     * @return void
     */
    private function injectMassRestoreScript()
    {
        global $log;

        $alertNoSelected = translate('LBL_LISTVIEW_NO_SELECTED', 'stic_RecycleBin');
        $alertAllAlready = translate('LBL_MASS_RESTORE_ALL_ALREADY_RESTORED', 'stic_RecycleBin');
        $confirmMixed = translate('LBL_MASS_RESTORE_MIXED_CONFIRM', 'stic_RecycleBin');

        $restoredMap = array();
        if (!empty($this->lv->data['data']) && is_array($this->lv->data['data'])) {
            foreach ($this->lv->data['data'] as $row) {
                if (empty($row['ID']) || !self::isValidId($row['ID'])) {
                    continue;
                }
                $restoredMap[] = array(
                    'id' => $row['ID'],
                    'restored' => !empty($row['RECYCLE_RESTORED']) ? 1 : 0,
                );
            }
        }

        $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': injecting mass restore script with ' . count($restoredMap) . ' rows');

        echo '<script type="text/javascript">'
            . '(function(){'
            . 'var restoredData=' . json_encode($restoredMap) . ';'
            . 'function sticAttachRestored(){'
            . 'for(var i=0;i<restoredData.length;i++){'
            . 'var cb=document.querySelector("input[name=\\"mass[]\\"][value=\\""+restoredData[i].id+"\\"]");'
            . 'if(cb){cb.setAttribute("data-restored",String(restoredData[i].restored));}'
            . '}'
            . '}'
            . 'if(document.readyState==="loading"){document.addEventListener("DOMContentLoaded",sticAttachRestored);}else{sticAttachRestored();}'
            . 'window.sticRecycleBinMassRestore=function(){'
            . 'if(typeof sugarListView==="undefined"||!sugarListView.get_checks()||sugarListView.get_checks_count()<1){alert(' . json_encode($alertNoSelected) . ');return false;}'
            . 'var checked=document.querySelectorAll("input[name=\\"mass[]\\"]:checked");'
            . 'var already=0;for(var i=0;i<checked.length;i++){if(checked[i].getAttribute("data-restored")==="1"){already++;}}'
            . 'if(already>0&&already===checked.length){alert(' . json_encode($alertAllAlready) . ');return false;}'
            . 'if(already>0&&!window.confirm(' . json_encode($confirmMixed) . ')){return false;}'
            . 'var f=document.createElement("form");f.method="POST";f.action="index.php";'
            . 'var m=document.createElement("input");m.name="module";m.type="hidden";m.value="stic_RecycleBin";f.appendChild(m);'
            . 'var a=document.createElement("input");a.name="action";a.type="hidden";a.value="mass_restore";f.appendChild(a);'
            . 'var u=document.createElement("textarea");u.name="uid";u.style.display="none";'
            . 'var ids=[];for(var j=0;j<checked.length;j++){ids.push(checked[j].value);}'
            . 'u.value=ids.join(",");f.appendChild(u);'
            . 'document.body.appendChild(f);f.submit();return false;'
            . '};'
            . '})();'
            . '</script>';
    }

    /**
     * Enriches the list view data with computed columns:
     *  - RELATIONSHIP_COUNT: comma-separated module labels with total count.
     *  - RECYCLE_USER_*_NAME: user_name lookup for the deleted_by / restored_by users.
     *
     * @return void
     */
    private function injectComputedColumns()
    {
        global $db, $log;

        if (empty($this->lv->data['data']) || !is_array($this->lv->data['data'])) {
            return;
        }

        $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': computing columns for ' . count($this->lv->data['data']) . ' rows');

        $this->injectRelationshipCounts($db);
        $this->injectUserNames($db);
    }

    /**
     * Aggregates relationship counts grouped by related module for the current page
     * and writes a translated summary into the RELATIONSHIP_COUNT column.
     *
     * @param object $db Database instance
     * @return void
     */
    private function injectRelationshipCounts($db)
    {
        global $app_list_strings;

        $recycleBinIds = array();
        foreach ($this->lv->data['data'] as $row) {
            if (!empty($row['ID']) && self::isValidId($row['ID'])) {
                $recycleBinIds[] = $db->quoted($row['ID']);
            }
        }

        if (empty($recycleBinIds)) {
            foreach ($this->lv->data['data'] as &$dataRow) {
                $dataRow['RELATIONSHIP_COUNT'] = '';
            }
            unset($dataRow);
            return;
        }

        $idList = implode(',', $recycleBinIds);
        $countResult = $db->query(
            "SELECT recyclebin_id, recycle_related_module, COUNT(*) AS rel_count
             FROM stic_recycle_bin_relationships
             WHERE recyclebin_id IN ($idList) AND deleted = 0
             GROUP BY recyclebin_id, recycle_related_module
             ORDER BY recyclebin_id, rel_count DESC, recycle_related_module"
        );
        $relModules = array();
        $relTotals = array();
        while ($r = $db->fetchByAssoc($countResult)) {
            $binId = $r['recyclebin_id'];
            $relModules[$binId][] = $r['recycle_related_module'];
            $relTotals[$binId] = isset($relTotals[$binId]) ? $relTotals[$binId] + (int)$r['rel_count'] : (int)$r['rel_count'];
        }

        foreach ($this->lv->data['data'] as &$dataRow) {
            $binId = $dataRow['ID'];
            if (empty($relModules[$binId])) {
                $dataRow['RELATIONSHIP_COUNT'] = '';
                continue;
            }
            $modules = array_values(array_unique($relModules[$binId]));
            $translated = array();
            foreach ($modules as $moduleKey) {
                if (!self::isValidModule($moduleKey)) {
                    continue;
                }
                $translated[] = !empty($app_list_strings['moduleList'][$moduleKey])
                    ? $app_list_strings['moduleList'][$moduleKey]
                    : $moduleKey;
            }
            $total = $relTotals[$binId];
            if (count($translated) <= 3) {
                $dataRow['RELATIONSHIP_COUNT'] = implode(', ', $translated) . ' (' . $total . ')';
            } else {
                $first = array_slice($translated, 0, 3);
                $dataRow['RELATIONSHIP_COUNT'] = implode(', ', $first) . ' (+' . (count($translated) - 3) . ' / ' . $total . ')';
            }
        }
        unset($dataRow);
    }

    /**
     * Looks up user_name for the deleted_by / restored_by user IDs and writes the
     * resolved names into the corresponding *_NAME columns.
     *
     * @param object $db Database instance
     * @return void
     */
    private function injectUserNames($db)
    {
        $userIds = array();
        foreach ($this->lv->data['data'] as $row) {
            if (!empty($row['RECYCLE_USER_DELETED_ID']) && self::isValidId($row['RECYCLE_USER_DELETED_ID'])) {
                $userIds[$row['RECYCLE_USER_DELETED_ID']] = true;
            }
            if (!empty($row['RECYCLE_USER_RESTORED_ID']) && self::isValidId($row['RECYCLE_USER_RESTORED_ID'])) {
                $userIds[$row['RECYCLE_USER_RESTORED_ID']] = true;
            }
        }

        if (empty($userIds)) {
            return;
        }

        $idList = implode(',', array_map(array($db, 'quote'), array_keys($userIds)));
        $userResult = $db->query(
            "SELECT id, user_name FROM users WHERE id IN ($idList) AND deleted = 0"
        );
        $userNames = array();
        while ($u = $db->fetchByAssoc($userResult)) {
            $userNames[$u['id']] = $u['user_name'];
        }

        foreach ($this->lv->data['data'] as &$dataRow) {
            if (!empty($dataRow['RECYCLE_USER_DELETED_ID'])) {
                $dataRow['RECYCLE_USER_DELETED_NAME'] = isset($userNames[$dataRow['RECYCLE_USER_DELETED_ID']])
                    ? $userNames[$dataRow['RECYCLE_USER_DELETED_ID']]
                    : '';
            }
            if (!empty($dataRow['RECYCLE_USER_RESTORED_ID'])) {
                $dataRow['RECYCLE_USER_RESTORED_NAME'] = isset($userNames[$dataRow['RECYCLE_USER_RESTORED_ID']])
                    ? $userNames[$dataRow['RECYCLE_USER_RESTORED_ID']]
                    : '';
            }
        }
        unset($dataRow);
    }

    /**
     * Validates a SugarCRM-style UUID.
     *
     * @param string $id ID to validate
     * @return bool true if the value matches the UUID pattern
     */
    private static function isValidId($id)
    {
        return is_string($id) && preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $id) === 1;
    }

    /**
     * Validates a module name.
     *
     * @param string $module Module name
     * @return bool true if the value is a safe module name
     */
    private static function isValidModule($module)
    {
        return is_string($module) && preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $module) === 1;
    }
}
