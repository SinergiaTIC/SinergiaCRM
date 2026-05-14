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
class SticCleanConfig
{
    /**
     * Cleans and updates configuration in `config_override.php`.
     *
     * This function is executed to clean and update specific configurations in the `config_override.php` file.
     * It can be invoked via a call from wget to the specified URL.
     *
     * @return bool Returns true if the function successfully executes.
     */
    public static function cleanConfig()
    {
        /**
         * We can run this clean_config code calling from wget 'https://xxxxx.sinergiacrm.org/index.php?entryPoint=sticCleanConfig'
         *
         **/
        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ':  Running stic_clean_config');
        require_once 'modules/Configurator/Configurator.php';
        $configurator = new Configurator();

        $configurator->config['developerMode'] = false;
        $configurator->config['logger']['level'] = 'error';
        $configurator->config['logger']['file']['ext'] = '.log';
        $configurator->config['logger']['file']['name'] = 'suitecrm';
        $configurator->config['logger']['file']['dateFormat'] = '%F %T';
        $configurator->config['logger']['file']['maxSize'] = '1MB';
        $configurator->config['logger']['file']['maxLogs'] = 10;
        $configurator->config['logger']['file']['suffix'] = '';
        $configurator->config['aod']['enable_aod'] = false;
        $configurator->saveConfig();

        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ':  Task stic_clean_config run successfully');
        
        echo 'config_override.php file has been modified';

        return true;
    }

}
if ($_REQUEST['entryPoint'] === 'sticCleanConfig') {
    SticCleanConfig::cleanConfig();
}
