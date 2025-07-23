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

class stic_Signatures extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_Signatures';
    public $object_name = 'stic_Signatures';
    public $table_name = 'stic_signatures';
    public $importable = true;

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
    public $module;
    public $signer_path;
    public $auth_method;
    public $type;
    public $activation_date;
    public $expiration_date;
    public $minimum_signatures;
    public $reminder_frequency;
    public $pdf_audit_page;
    public $generate_pdf;
    public $end_date;
    public $verification_code;
    public $emailtemplate_id_c;
    public $email_template;
    public $pdftemplate_id_c;
    public $pdf_template;
    public $on_behalf_of;
    public $pdf_document;

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }

        return false;
    }

    /**
     * Overriding SugarBean save function to insert additional logic:
     * Build the name of the journal using the name of the center, the date and the type
     *
     * @param boolean $check_notify
     * @return void
     */
    public function save($check_notify = false)
    {

        include_once 'SticInclude/Utils.php';
        global $app_list_strings;

        // Create name if empty
        if (empty($this->name)) {
            $mainModule = $app_list_strings['moduleList'][$this->main_module];
            $type = $app_list_strings['stic_signatures_types_list'][$this->type];
            $this->name = "{$mainModule} - {$type} - {$this->pdf_template}";
        }

        if($this->pdftemplate_id_c ?? false){
            $PDFBean = Beanfactory::getBean('AOS_PDF_Templates', $this->pdftemplate_id_c);
            if ($PDFBean) { 
                $this->main_module = $PDFBean->type;

            }
        }

        



        // Call the generic save() function from the SugarBean class
        parent::save();
    }

}
