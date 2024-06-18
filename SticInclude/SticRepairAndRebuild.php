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

require_once 'modules/Administration/QuickRepairAndRebuild.php';
class SticRepairAndRebuild extends RepairAndClear
{
    public function sticUpdateInstances($autoExecute = false, $showOutput = true)
    {
        $this->module_list = array(translate('LBL_ALL_MODULES'));
        $this->execute = $autoExecute;
        $this->show_output = $showOutput;

        $this->clearVardefs();
        $this->clearLanguageCache();
        $this->rebuildExtensions();
        $this->rebuildAuditTables();
        $this->repairDatabase();
        $this->rebuildSDA();
    }

    public function sticOnlyDatabase($autoExecute = false, $showOutput = true)
    {
        $this->module_list = array(translate('LBL_ALL_MODULES'));
        $this->execute = $autoExecute;
        $this->show_output = $showOutput;

        $this->clearVardefs();
        $this->clearLanguageCache();
        $this->repairDatabase();
        $this->rebuildSDA();
    }

    public function sticClearAll($autoExecute = false, $showOutput = true)
    {
        $this->module_list = array(translate('LBL_ALL_MODULES'));
        $this->execute = $autoExecute;
        $this->show_output = $showOutput;

        $this->clearVardefs();
        $this->clearLanguageCache();
        $this->clearTpls();
        $this->clearJsFiles();
        $this->clearJsLangFiles();
        $this->clearDashlets();
        $this->clearSugarFeedCache();
        $this->clearSmarty();
        $this->clearThemeCache();
        $this->clearXMLfiles();
        $this->clearSearchCache();
        $this->clearExternalAPICache();
        $this->rebuildExtensions();
        $this->rebuildAuditTables();
        $this->repairDatabase();
        $this->rebuildSDA();
    }

    public function rebuildSDA()
    {
        // STIC-Custom - 20230901 - jch - STIC#1204
        //               20230919 - jch - STIC#1223
        // Rebuild SinergiaDA views to avoid incorrect table references and errors in mysqldump if SDA is enabled
        $sdaEnabled = $sugar_config['stic_sinergiada']['enabled'] ?? false;
        $rebuildSDAFile = 'SticInclude/SinergiaDARebuild.php';

        if (file_exists($rebuildSDAFile) && $sdaEnabled) {
            if (
                // It is necessary to assess whether $_REQUEST['silent'] == True, since by erase a field, the pass flow
                // twice here with different values and we want the code to be one time. In the case of deleting relationships
                // This value is true and only passes once.
                isset($_REQUEST['silent']) && $_REQUEST['silent'] == 'true' &&
                isset($_REQUEST['action']) && in_array($_REQUEST['action'], ['DeleteField', 'DeleteRelationship'])
            ) {
                $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Action {$_REQUEST['action']}. Rebuilding SinergiaDA");
                require_once 'SticInclude/SinergiaDARebuild.php';
                SinergiaDARebuild::callApiRebuildSDA(true, 'views');
            }
        }
        // EN STIC
    }

}
