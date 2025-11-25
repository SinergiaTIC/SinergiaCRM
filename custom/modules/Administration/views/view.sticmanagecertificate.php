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

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/SugarView.php');

class ViewSticManageCertificate extends SugarView
{
    public function preDisplay()
    {
        global $current_user;
        if (!is_admin($current_user)) {
            sugar_die("Acceso no autorizado.");
        }
        parent::preDisplay();
    }

    public function display()
    {
        global $mod_strings, $app_strings, $timedate;

        // Paths to certificate and metadata files
        $certPath = 'custom/certificates/cert_encrypted.bin';
        $metadataPath = 'custom/certificates/cert_metadata.json';
        
        $certExists = file_exists($certPath);
        
        // Read metadata if certificate exists
        $metadata = null;
        if ($certExists && file_exists($metadataPath)) {
            $metadataJson = file_get_contents($metadataPath);
            $metadata = json_decode($metadataJson, true);
            
            // Format upload date for display
            if (!empty($metadata['upload_date'])) {
                $uploadDate = DateTime::createFromFormat('Y-m-d H:i:s', $metadata['upload_date']);
                if ($uploadDate) {
                    $metadata['upload_date_formatted'] = $uploadDate->format('d/m/Y H:i:s');
                }
            }
        }

        $this->ss->assign('MOD', $mod_strings);
        $this->ss->assign('APP', $app_strings);
        $this->ss->assign('CERT_EXISTS', $certExists);
        $this->ss->assign('CERT_METADATA', $metadata);
        
        // Message handling
        if (isset($_REQUEST['msg'])) {
            $this->ss->assign('MESSAGE', $mod_strings[$_REQUEST['msg']] ?? '');
        }

        echo $this->getModuleTitle(false);
        $this->ss->display('custom/modules/Administration/templates/SticManageCertificate.tpl');
    }
}