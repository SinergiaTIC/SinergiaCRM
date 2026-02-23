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

/**
 * Represents the stic_Signatures module in SinergiaCRM.
 * This class extends SugarCRM's Basic class and defines the structure and
 * custom logic for signature records, including automatic naming and
 * linking with PDF templates.
 */
class stic_Signatures extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_Signatures';
    public $object_name = 'stic_Signatures';
    public $table_name = 'stic_signatures';
    public $importable = true;

    // Properties corresponding to database fields
    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $SecurityGroups;
    public $status;
    public $main_module; // Corrected from 'module' to 'main_module' based on usage
    public $signer_path;
    public $auth_method;
    public $type;
    public $activation_date;
    public $expiration_date;
    public $minimum_signatures;
    public $pdf_audit_page;
    public $end_date;
    public $verification_code;
    public $emailtemplate_id_c;
    public $email_template;
    public $pdftemplate_id_c;
    public $pdf_template;
    public $on_behalf_of;
    public $pdf_document;
    public $show_SubPanelTopButtonListView = false;


    /**
     * Checks if the bean implements a specific interface.
     *
     * @param string $interface The name of the interface to check.
     * @return bool True if the bean implements the interface, false otherwise.
     */
    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }
        return false;
    }

    /**
     * Overrides the SugarBean save function to insert additional logic.
     * Automatically generates the `name` field if it's empty, combining
     * the main module, type, and PDF template name. It also updates the
     * `main_module` based on the selected PDF template's type.
     *
     * @param boolean $check_notify Whether to check for notifications.
     * @return void
     */
    public function save($check_notify = false)
    {
        include_once 'SticInclude/Utils.php';
        global $app_list_strings;

        // Create name if empty
        if (empty($this->name)) {
            $moduleName = $app_list_strings['moduleListSingular'][$this->object_name];
            $dateCreated = (new TimeDate())->to_display_date_time((new TimeDate())->nowDb(), false);
            $this->name = "{$moduleName} - {$this->pdf_template} - {$dateCreated}h";
        }

        // If a PDF template is linked, set the main_module based on the PDF template's type
        if (!empty($this->pdftemplate_id_c)) {
            $PDFBean = BeanFactory::getBean('AOS_PDF_Templates', $this->pdftemplate_id_c);
            if ($PDFBean) {
                $this->main_module = $PDFBean->type;
            }
        }

        // If assigned_user_id is empty, set it to the current user
        if(empty($this->assigned_user_id) && empty($this->fetched_row)) {
            $this->assigned_user_id = $GLOBALS['current_user']->id;
        }

        // Call the generic save() function from the the parent (SugarBean) class
        parent::save($check_notify);
    }

        /**
     * Retrieves the stic_Signers records associated with a specific Signature for use in a subpanel.
     *
     * @return string The SQL query string to fetch the required stic_Signers records.
     */
    public function getSticSignersForSignature()
    {
        $signature_id = $_REQUEST['record'];
        if (empty($signature_id)) {
            return '';
        }

        // Construct the SQL query
        $query = "
            SELECT * FROM (
                SELECT
                    stic_signers.id,
                    stic_signers.name,
                    stic_signers.date_entered,
                    stic_signers.date_modified,
                    stic_signers.modified_user_id,
                    stic_signers.created_by,
                    stic_signers.description,
                    stic_signers.deleted,
                    stic_signers.assigned_user_id,
                    stic_signers.status,
                    stic_signers.signature_date,
                    stic_signers.parent_type,
                    stic_signers.parent_id,
                    stic_signers.record_name,
                    stic_signers.record_type,
                    stic_signers.record_id,
                    CONCAT_WS(' ', c1.first_name, c1.last_name) as on_behalf_of_id
                FROM stic_signers
                JOIN stic_signatures_stic_signers_c rel
                    ON rel.stic_signatures_stic_signersstic_signers_idb = stic_signers.id
                LEFT JOIN contacts c1 ON c1.id = stic_signers.contact_id_c -- to get on_behalf_of_id
                WHERE rel.stic_signatures_stic_signersstic_signatures_ida = '{$signature_id}'
                    AND stic_signers.deleted = 0
                    AND rel.deleted = 0
                ) AS stic_signers
        ";
        return $query;
    }
}
