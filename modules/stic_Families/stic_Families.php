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

class stic_Families extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_Families';
    public $object_name = 'stic_Families';
    public $table_name = 'stic_families';
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
    public $code;

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }

        return false;
    }

    /**
     * Override the bean's save function to assign an auto-incrementing value to the code field when a new record is created
     *
     * @param boolean $check_notify
     * @return void
     */
    public function save($check_notify = true)
    {
        global $db, $timedate, $current_user;
        // On new families, define family code
        // Warning: the lines below retrieve the last existing code in the db in order to calculate
        // the code for the newly created family because the value of the field code is not available
        // until the record is created in the db (it is an autoincrement db field). This may cause
        // problems of crossing code assignation in case of concurrent families creation.
        if (empty($this->id)) {
            // Get last assigned code
            $query = "SELECT code FROM stic_families ORDER BY code DESC LIMIT 1";
            $result = $db->query($query, true);
            $row = $db->fetchByAssoc($result);
            $lastNum = $row['code'];
            if (!isset($lastNum) || empty($lastNum)) {
                $lastNum = 0;
            }
            $this->code = $lastNum + 1;
        }

        // Set active/inactive status
        $start = $this->start_date;
        $end = $this->end_date;
		
        if ($userDate = $timedate->fromUserDate($start, false, $current_user)) {
		$start = $userDate->asDBDate();
        } 
	
        if ($userDate = $timedate->fromUserDate($end, false, $current_user)) {
		$end = $userDate->asDBDate();
        } 

        if (   (empty($start) || $start <= date("Y-m-d"))
            && (empty($end)   || $end > date("Y-m-d"))) 
        {
            $this->active = true;
        } else {
            $this->active = false;
        }

        // Save the bean
        parent::save($check_notify);
    }
}
