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


class stic_AWF_Incoming_Events extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_AWF_Incoming_Events';
    public $object_name = 'stic_AWF_Incoming_Events';
    public $table_name = 'stic_awf_incoming_events';
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

    public $token;
    public $external_transaction_id;
    public $raw_payload;
    public $headers;
    public $status;
    public $processing_attempts;
    public $last_error_message;
    public $date_received;
    public $date_processed;

    // STIC-Custom 20261120 JB - Related Response and Form. There is no direct relationship
    // between Incoming Events and Responses: the link is resolved through the Deferred Ticket
    // matched by token or external transaction id. The values are computed in the bean (see
    // fill_in_additional_detail_fields/get_list_view_data) so the detail view and the list can
    // link to the records and the search filters can resolve their widgets.
    public $response_id;
    public $response_name;
    public $form_id;
    public $form_name;

    // STIC-Custom 20261120 JB - Records are managed by the AWF engine: editing from the UI is disabled
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
    // END STIC-Custom

    // STIC-Custom 20261120 JB - Related Response and Form in list and detail views
    /**
     * Adds the Response and the Form related through the matched Deferred Ticket
     * to the list view data.
     *
     * @param array $filter_fields
     * @return array
     */
    public function get_list_view_data($filter_fields = array())
    {
        $data = parent::get_list_view_data();
        $this->computeLinkedResponseAndForm();
        $data['RESPONSE_NAME'] = $this->response_name;
        $data['RESPONSE_ID'] = $this->response_id;
        $data['FORM_NAME'] = $this->form_name;
        $data['FORM_ID'] = $this->form_id;

        return $data;
    }

    /**
     * Fills in the Response and the Form related through the matched Deferred Ticket,
     * also used by the detail view.
     */
    public function fill_in_additional_detail_fields()
    {
        parent::fill_in_additional_detail_fields();
        $this->computeLinkedResponseAndForm();
    }

    /**
     * Computes the ids and names of the Response and the Form related to this event.
     * The Deferred Ticket is matched by token or external transaction id, the same way
     * the AWF engine does when processing the event. Results are cached per record id
     * within the request.
     */
    protected function computeLinkedResponseAndForm()
    {
        static $formDataByEvent = array();

        if (!isset($this->response_name)) {
            $this->response_name = '';
            $this->response_id = '';
            $this->form_name = '';
            $this->form_id = '';
        }

        if (empty($this->id) || !empty($this->response_id)) {
            return;
        }

        // The token and the external transaction id may be missing from the row when
        // they are not displayed in the list view, so they are fetched when needed.
        if (empty($this->token) && empty($this->external_transaction_id)) {
            $result = $this->db->query(
                'SELECT token, external_transaction_id FROM stic_awf_incoming_events WHERE id = ' . $this->db->quoted($this->id)
            );
            $row = $this->db->fetchByAssoc($result);
            $this->token = $row['token'] ?? '';
            $this->external_transaction_id = $row['external_transaction_id'] ?? '';
        }

        if (!array_key_exists($this->id, $formDataByEvent)) {
            $conditions = array();
            if (!empty($this->token)) {
                $conditions[] = 'k.token_hash = ' . $this->db->quoted($this->token);
            }
            if (!empty($this->external_transaction_id)) {
                $conditions[] = 'k.external_transaction_id = ' . $this->db->quoted($this->external_transaction_id);
            }

            $formDataByEvent[$this->id] = array('', '', '', '');
            if (!empty($conditions)) {
                $query = 'SELECT r.id AS response_id, r.name AS response_name, f.id AS form_id, f.name AS form_name '
                    . 'FROM stic_awf_deferred_tickets k '
                    . 'INNER JOIN stic_awf_responses r '
                    . 'ON r.id = k.stic_awf_responses_id_c AND r.deleted = 0 '
                    . 'LEFT JOIN stic_awf_forms_stic_awf_responses_c fr '
                    . 'ON fr.stic_awf_forms_stic_awf_responsesresponses_idb = r.id AND fr.deleted = 0 '
                    . 'LEFT JOIN stic_awf_forms f '
                    . 'ON f.id = fr.stic_awf_forms_stic_awf_responsesforms_ida AND f.deleted = 0 '
                    . 'WHERE k.deleted = 0 '
                    . 'AND (' . implode(' OR ', $conditions) . ')';
                $result = $this->db->query($query);
                $responseData = array('', '');
                $formIds = array();
                $formNames = array();
                while ($row = $this->db->fetchByAssoc($result)) {
                    if ($responseData[0] === '') {
                        $responseData = array($row['response_id'], $row['response_name']);
                    }
                    if (!empty($row['form_id']) && !in_array($row['form_id'], $formIds, true)) {
                        $formIds[] = $row['form_id'];
                        $formNames[] = $row['form_name'];
                    }
                }
                $formDataByEvent[$this->id] = array(
                    $responseData[0],
                    $responseData[1],
                    $formIds[0] ?? '',
                    implode(', ', $formNames),
                );
            }
        }
        list($this->response_id, $this->response_name, $this->form_id, $this->form_name) = $formDataByEvent[$this->id];
    }
    // END STIC-Custom
}
