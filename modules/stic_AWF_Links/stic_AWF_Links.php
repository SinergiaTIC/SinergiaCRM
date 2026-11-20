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


class stic_AWF_Links extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_AWF_Links';
    public $object_name = 'stic_AWF_Links';
    public $table_name = 'stic_awf_links';
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
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $SecurityGroups;
    public $sequence_number;
    public $parent_name;
    public $parent_type;
    public $parent_id;
    public $record_action;
    public $submitted_data;

    public $is_automated_save = false;
    
    public $parent_type_translated;
	
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
     * Adds the name of the Form related through the Response to the list view data.
     * There is no direct relationship between Links and Forms (the chain is
     * Link -> Response -> Form), so the value is computed here and exposed
     * through the non-db "form_name" field.
     *
     * @param array $filter_fields
     * @return array
     */
    public function get_list_view_data($filter_fields = array())
    {
        $data = parent::get_list_view_data();
        list($data['FORM_NAME'], $data['FORM_ID']) = $this->getRelatedForm();

        return $data;
    }

    /**
     * Returns the name(s) and the id of the Form(s) related to the Response(s) of
     * this link. Results are cached per record id within the request.
     *
     * @return array [0] => comma separated list of form names, [1] => form id
     *               (the first one when there are several), empty when there is none.
     */
    protected function getRelatedForm()
    {
        static $formsByLink = array();

        if (empty($this->id)) {
            return array('', '');
        }

        if (!array_key_exists($this->id, $formsByLink)) {
            $query = "SELECT f.id, f.name 
                      FROM stic_awf_responses_stic_awf_links_c lr 
                        INNER JOIN stic_awf_forms_stic_awf_responses_c fr 
                            ON fr.stic_awf_forms_stic_awf_responsesresponses_idb = lr.stic_awf_responses_stic_awf_linksresponses_ida 
                            AND fr.deleted = 0 
                        INNER JOIN stic_awf_forms f 
                            ON f.id = fr.stic_awf_forms_stic_awf_responsesforms_ida 
                            AND f.deleted = 0 
                      WHERE lr.deleted = 0 AND lr.stic_awf_responses_stic_awf_linkslinks_idb = " . $this->db->quoted($this->id);
            $result = $this->db->query($query);
            $names = array();
            $firstId = '';
            while ($row = $this->db->fetchByAssoc($result)) {
                $names[] = $row['name'];
                if ($firstId === '') {
                    $firstId = $row['id'];
                }
            }
            $formsByLink[$this->id] = array(implode(', ', $names), $firstId);
        }

        return $formsByLink[$this->id];
    }
}
