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

class stic_Messages extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_Messages';
    public $object_name = 'stic_Messages';
    public $table_name = 'stic_messages';
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

    public $type;
    public $direction;
    public $phone;
    public $sender;
    public $message;
    public $template_id_c;
    public $parent_type;
    public $parent_id;


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
    public function save($check_notify = false)
    {
        global $sticSavingMessage, $current_user;

        // To avoid loop with LH on seven_sms
        if ($sticSavingMessage){
            return false;
        }
        $sticSavingMessage = true;

        $this->fillName();
        $this->assigned_user_id = $current_user->id;

        if ($this->id === null) {
            $this->sendSMS();
        }

        // Save the bean
        parent::save($check_notify);

    }

    protected function fillName()
    {
        global $current_user, $timedate;
        // Auto name
        // if (empty($this->name)) {
        //     global $app_list_strings;
        //     include_once 'SticInclude/Utils.php';

        //     $contactName = '';
        //     $contactBean = BeanFactory::getBean('Contacts', $this->stic_training_contactscontacts_ida);
        //     if ($contactBean) {
        //         $contactName = $contactBean->first_name . ' ' . $contactBean->last_name;
        //     }

        //     $this->name = $contactName . ' - ' .
        //         $app_list_strings['stic_training_levels_list'][$this->level];

        //     if (!empty($this->course_year)) {
        //         $this->name .= ' - ' . $app_list_strings['stic_training_courses_list'][$this->course_year];
        //     }
        // }
        /*
        Recuperar objecte relacionat
        Recuperar nom del template
        Nom = nom de l'objecte relacionat - data-hora[ - nom del template]
        */
        $relatedObjectName = '';
        if (!empty($this->parent_id)){
            $relatedObject = BeanFactory::getBean($this->parent_type, $this->parent_id);
            $relatedObjectName = $relatedObject->name;
        }
        $templateName = '';
        if (!empty($this->template_id_c)){
            $template = BeanFactory::getBean('EmailTemplates', $this->template_id_c);
            $templateName = ' - ' . $template->name;
        }

        if (empty($this->date_entered)) {
            $this->date_entered = $GLOBALS['timedate']->nowDb();
        }

        $messageDateTime = $this->date_entered;
        if ($userDate = $timedate->fromUser($messageDateTime, $current_user)) {
            $messageDateTime = $userDate->asDb();
        }

        $date = SugarDateTime::createFromFormat(TimeDate::DB_DATETIME_FORMAT, $messageDateTime, new DateTimeZone("UTC"));

        // get user timezone
        $userPreferences = new UserPreference($current_user);
        $userPreferences->retrieve_by_string_fields(array('assigned_user_id' => $current_user->id));

        // Get the timezone from the user's preferences
        $timezone = $userPreferences->getPreference('timezone');

        $date = $date->setTimezone(new DateTimeZone($timezone));
        $formatedDate = $date->format($timedate->get_date_time_format($current_user));


        $this->name = $relatedObjectName . ' - ' . $formatedDate . $templateName;
    }

    public function sendSMS() {
        if ($_POST['module'] === 'stic_Messages') {
            $_POST['number'] = $this->phone;
            $_POST['id'] = $this->parent_id;
            $_POST['module'] = $this->parent_type;
            $_POST['template'] = $this->template_id_c;
            $_POST['message'] = $this->message;

            require 'modules/seven/sendSMS.php';

            // restore overwritten POST values
            $_POST['module'] = 'stic_Messages';
            $_POST['id'] = $this->id;

            // retrieve message once template data applied
            $this->message = $_POST['message'];
        }
    }
}
