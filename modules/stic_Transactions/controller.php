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

class stic_TransactionsController extends SugarController
{
    /**
     * Step 1: Upload file
     */
    public function action_uploadNorma43()
    {
        $this->view = 'norma43upload';
    }

    /**
     * Step 2: Process file
     */
    public function action_loadNorma43()
    {
        require_once 'modules/stic_Transactions/importNorma43.php';

        // Analyze file
        $summary = Norma43::importNorma43(true);

        if (isset($summary['success']) && !$summary['success']) {
            $_SESSION['norma43_error'] = $summary['error'];
            SugarApplication::redirect('index.php?module=stic_Transactions&action=uploadNorma43');
            return;
        }

        $_SESSION['norma43_summary'] = $summary;

        // Get parsed accounts for mapping
        SugarApplication::redirect('index.php?module=stic_Transactions&action=previewNorma43');
    }

    /**
     * Step 3: Final preview
     */
    public function action_previewNorma43()
    {
        $this->view = 'norma43preview';
    }

    /**
     * Step 4: Save the transactions
     */
    public function action_executeFinalImport()
    {
        require_once 'modules/stic_Transactions/importNorma43.php';
        
        // Check if we should allow file duplicates, skip only crm duplicates
        $allowFileDuplicates = !empty($_POST['allow_file_duplicates']) && $_POST['allow_file_duplicates'] === '1';
        
        if ($allowFileDuplicates) {
            // Import all transactions except database duplicates, file duplicates will be imported
            Norma43::finalizeImportSkipDatabaseDuplicates();
        } else {
            // Standard import: skip ALL duplicates (both file and crm)
            Norma43::finalizeImport([]);
        }

        SugarApplication::redirect('index.php?module=stic_Transactions&action=index&import_status=completed');
    }
}
