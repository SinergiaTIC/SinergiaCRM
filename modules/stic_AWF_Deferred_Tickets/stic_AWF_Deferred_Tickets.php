<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
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
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */


class stic_AWF_Deferred_Tickets extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_AWF_Deferred_Tickets';
    public $object_name = 'stic_AWF_Deferred_Tickets';
    public $table_name = 'stic_awf_deferred_tickets';
    public $importable = false;

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

    public $stic_awf_responses_id_c;
    public $response;
    public $token_hash;
    public $external_transaction_id;
    public $status;
    public $handler_action_id;
    public $context_data;
    public $expiration_date;
	
    // Related Form (through the Response). Values are computed in the bean so the detail view
    // and the list can link to the Form record and the search filter can resolve its widget.
    public $form_id;
    public $form_name;
    // Editing from the UI is disabled
    public $is_automated_save = false;

    public function bean_implements($interface)
    {
        switch($interface)
        {
            case 'ACL':
                return true;
        }

        return false;
    }
	
    /**
     * Enable "edit" only in automated saving.
     * @param string $view
     * @param string $is_owner
     * @param string $in_group
     */
    public function ACLAccess($view, $is_owner = 'not_set', $in_group = 'not_set')
    {
        $editViews = ['edit', 'editview', 'save'];
        if (in_array(strtolower($view), $editViews)) {
            if ($this->is_automated_save) {
                return true;
            }
            return false;
        }
        return parent::ACLAccess($view, $is_owner, $in_group);
    }

    /**
     * Adds the Form related through the Response to the list view data.
     * There is no direct relationship between Tickets and Forms (the chain is
     * Ticket -> Response -> Form), so the value is computed here and exposed
     * through the non-db "form_name"/"form_id" fields.
     *
     * @param array $filter_fields
     * @return array
     */
    public function get_list_view_data($filter_fields = array())
    {
        $data = parent::get_list_view_data();
        $this->computeRelatedForm();
        $data['FORM_NAME'] = $this->form_name;
        $data['FORM_ID'] = $this->form_id;

        return $data;
    }

    /**
     * Fills in the Form related through the Response, also used by the detail view.
     */
    public function fill_in_additional_detail_fields()
    {
        parent::fill_in_additional_detail_fields();
        $this->computeRelatedForm();
    }

    /**
     * Computes the id and name of the Form(s) related to the Response of this ticket.
     * Results are cached per response id within the request.
     */
    protected function computeRelatedForm()
    {
        static $formsByResponse = array();

        if (!isset($this->form_name)) {
            $this->form_name = '';
            $this->form_id = '';
        }

        // The response id may be missing from the row when the Response column is
        // not displayed, so it is fetched from the database when needed.
        if (empty($this->stic_awf_responses_id_c) && !empty($this->id)) {
            $result = $this->db->query('SELECT stic_awf_responses_id_c FROM stic_awf_deferred_tickets WHERE id = ' . $this->db->quoted($this->id));
            $row = $this->db->fetchByAssoc($result);
            $this->stic_awf_responses_id_c = $row['stic_awf_responses_id_c'] ?? '';
        }

        if (empty($this->stic_awf_responses_id_c) || !empty($this->form_id)) {
            return;
        }

        $responseId = $this->stic_awf_responses_id_c;
        if (!array_key_exists($responseId, $formsByResponse)) {
            $query = "SELECT f.id, f.name 
                      FROM stic_awf_forms_stic_awf_responses_c fr 
                        INNER JOIN stic_awf_forms f ON f.id = fr.stic_awf_forms_stic_awf_responsesforms_ida AND f.deleted = 0 
                      WHERE fr.deleted = 0 AND fr.stic_awf_forms_stic_awf_responsesresponses_idb = " . $this->db->quoted($responseId);
            $result = $this->db->query($query);
            $names = array();
            $firstId = '';
            while ($row = $this->db->fetchByAssoc($result)) {
                $names[] = $row['name'];
                if ($firstId === '') {
                    $firstId = $row['id'];
                }
            }
            $formsByResponse[$responseId] = array(implode(', ', $names), $firstId);
        }
        list($this->form_name, $this->form_id) = $formsByResponse[$responseId];
    }
}
