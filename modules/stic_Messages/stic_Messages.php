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
    const OK = 1;
    const ERROR_NO_HELPER_CLASS = 11;
    const ERROR_NOT_SENT = 12;

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
    public $status;
    public $response;


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
        global $current_user;

        if (empty($this->name)){
            $this->fillName();
        }

        $bean = BeanFactory::getBean($this->parent_type, $this->parent_id);

        if (empty($this->message) && !empty($this->template_id_c)) {
            $template = BeanFactory::getBean('EmailTemplates', $this->template_id_c);
            $this->message = $template->body;
        }

        $processedText = $this->replaceTemplateVariables($this->message, $bean);
        $this->message = $processedText;

        $this->assigned_user_id = $current_user->id;

        // If Message is being created or status chenged to "sent"
        if (($this->id === null && $this->status === 'sent') || ($this->status === 'sent' && $this->fetched_row['status'] !== 'sent')) {
            if (!empty($this->phone)){
                $response = $this->sendMessage();
                if ($response['code'] === self::OK) {
                    $this->status = 'sent';
                    $this->response = $response['message'] ?? '';
                    $this->sent_date = $GLOBALS['timedate']->nowDb();
                }
                else {
                    $this->status = 'error';
                    $this->response = $response['message'] ?? '';
                }
            }
            else {
                $this->status = 'error';
                $this->response = 'No phone number';
            }
        }

        // Save the bean
        parent::save($check_notify);
        return $this->id;
    }

    public function fillName($parentType = null, $parentId = null)
    {
        global $current_user, $timedate, $sugar_config;

        $parentType = $parentType?? $this->parent_type;
        $parentId = $parentId ?? $this->parent_id;

        $relatedObjectName = '';
        if (!empty($parentType)){
            $relatedObject = BeanFactory::getBean($parentType, $parentId);
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
        // $userPreferences->retrieve_by_string_fields(array('assigned_user_id' => $current_user->id));
        $userPreferences->loadPreferences();

        // Get the timezone from the user's preferences
        $timezone = $userPreferences->getPreference('timezone');

        // $timedate = new TimeDate();
        // $system_default_timezone = $timedate->getSystemTimezone();

        // $timezone = date_default_timezone_get();
        $date = $date->setTimezone(new DateTimeZone($timezone));
        $date = $timedate->tzUser($date);
        $formatedDate = $date->format($timedate->get_date_time_format($current_user));


        $this->name = $relatedObjectName . ' - ' . $formatedDate . $templateName;
        return $this->name;
    }

    public function sendMessage() {

        // In the list stic_messages_type_list, the keypart is the name of the file containing the helper class.
        $messageHelper = null;
        $file = $this->type;
        if (file_exists('custom/modules/stic_Messages/Helpers/' . $file . '.php')) {
            require_once('custom/modules/stic_Messages/Helpers/' . $file . '.php');
            $messageHelper = new $file; 
        }
        else if (file_exists('modules/stic_Messages/Helpers/' . $file . '.php')) {
            require_once('modules/stic_Messages/Helpers/' . $file . '.php');
            $messageHelper = new $file; 
        }

        if ($messageHelper !== null) {
            $returnCode = $messageHelper->sendMessage($this->sender, $this->message, $this->phone);
        }
        else {
            $returnCode = self::ERROR_NO_HELPER_CLASS;
        }
        return $returnCode;

    }

    public function replaceTemplateVariables($screenText, $bean)
    {
            $macro_nv = array();
    
            $focusName = $bean->module_name;
            $focus = $bean;
    
            /**
             * @var EmailTemplate $emailTemplate
             */
            $emailTemplate = BeanFactory::newBean('EmailTemplates');
            $templateData = $emailTemplate->parse_email_template(
                array(
                    'body' => $screenText,
                ),
                $focusName,
                $focus,
                $macro_nv
            );

            $emailTemplate = BeanFactory::newBean('EmailTemplates');
            if ($focusName === 'Leads') {
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'body' => $templateData['body'],
                    ),
                    'Contacts',
                    $focus,
                    $macro_nv
                );
    
            }
    
        return $templateData['body'];
    }
    

}
