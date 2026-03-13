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
    public $template_id;
    public $parent_type;
    public $parent_id;
    public $status;
    public $response;
    public $media_note_id;
    public $media_url;

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

        // WhatsAppWeb messages cannot be edited once created (they are always sent immediately)
        if ($this->fetched_row && $this->fetched_row['type'] === 'WhatsAppWeb' && !empty($this->id)) {
            // Restore all original values to prevent any modification
            $this->template_id = $this->fetched_row['template_id'];
            $this->message = $this->fetched_row['message'];
            $this->type = $this->fetched_row['type'];
            $this->phone = $this->fetched_row['phone'];
            $this->status = $this->fetched_row['status'];
            $this->sender = $this->fetched_row['sender'];
            // Only allow updating parent relationship
            // parent_type and parent_id can still be updated
        }

        // If message is not in draft, only direction and related to can be changed
        if ($this->fetched_row && $this->fetched_row['status'] !== 'draft' && !empty($this->id)) {
            $this->template_id = $this->fetched_row['template_id'];
            $this->message = $this->fetched_row['message'];
            $this->type = $this->fetched_row['type'];
            $this->phone = $this->fetched_row['phone'];
        }


        // If there is nothing in the message field, assume the template body
        if (empty($this->message) && !empty($this->template_id)) {
            $template = BeanFactory::getBean('EmailTemplates', $this->template_id);
            $this->message = $template->body;
        }

        // Only if we are in mass update, assume the body of the template as the message, otherwise the user may have modified the text
        if ($this->status === 'draft' && ($_REQUEST['massupdate']??false) && $this->template_id !== $this->fetched_row['template']) {
            $template = BeanFactory::getBean('EmailTemplates', $this->template_id);
            $this->message = $template->body;
        }

        if ($this->status === 'sent') {
            $bean = BeanFactory::getBean($this->parent_type, $this->parent_id);
    
            $processedText = $this->replaceTemplateVariables($this->message, $bean);
            $this->message = $processedText;
        }

        $this->assigned_user_id = $current_user->id;

        // For WhatsAppWeb messages, set sender to assigned user name
        if ($this->type === 'WhatsAppWeb') {
            $this->sender = $current_user->name;
        }

        // WhatsAppWeb messages are always sent immediately, never saved as draft
        if ($this->type === 'WhatsAppWeb' && $this->status === 'draft') {
            $this->status = 'sent';
        }
        // Resolve media_note_id from $_REQUEST if not already set
        if (empty($this->media_note_id)) {
            $this->media_note_id = $_REQUEST['media_note_id'] ?? '';
        }

        // If a Note was pre-created during upload and there is an attachment,
        // build a signed public URL for Twilio before calling sendMessage()
        if (!empty($this->media_note_id)) {
            $this->media_url = $this->buildSignedMediaUrl($this->media_note_id);
            $GLOBALS['log']->info('stic_Messages: media_note_id=' . $this->media_note_id . ' signed url=' . $this->media_url);
        }

        // If Message is being created or status changed to "sent"
        if (($this->id === null && $this->status === 'sent') || ($this->status === 'sent' && $this->fetched_row['status'] !== 'sent')) {
            // If type is WhatsAppWeb we don't have a server-side sender: mark as sent and skip helper
            if ($this->type === 'WhatsAppWeb') {
                // mark as sent because user/client will open WhatsApp Web
                $response = array('code' => self::OK, 'message' => 'Sent via WhatsApp Web (client)');
                $this->status = 'sent';
                $this->response = $response['message'];
                $this->sent_date = $GLOBALS['timedate']->nowDb();
            } else {
                if (empty($this->phone) && !empty($this->parent_type) && !empty($this->parent_id)) {
                    require_once('modules/stic_Messages/Utils.php');
                    $parentBean = BeanFactory::getBean($this->parent_type, $this->parent_id);
                    if ($parentBean) {
                        $this->phone = stic_MessagesUtils::getPhoneForMessage($parentBean);
                    }
                }
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
        }

        // Save the bean
        $GLOBALS['log']->info('stic_Messages::save() — saving bean id=' . ($this->id ?? 'NEW'));
        parent::save($check_notify);

        // After save we have $this->id: link the pre-created Note to this message
        if (!empty($this->media_note_id)) {
            $note = BeanFactory::getBean('Notes', $this->media_note_id);
            if ($note && empty($note->parent_id)) {
                $note->parent_id = $this->id;
                // contact_id is a Notes-specific field used by SuiteCRM to show the note
                // in the Contacts/Leads subpanel. Not applicable to Accounts or Employees.
                if (in_array($this->parent_type, ['Contacts', 'Leads'])) {
                    $note->contact_id = $this->parent_id;
                }
                $note->save();
                $GLOBALS['log']->info('stic_Messages: Note ' . $this->media_note_id . ' linked to message ' . $this->id);
            }
            $this->media_note_id = null;
            $this->media_url     = null;
        }

        return $this->id;
    }

    public function fillName($parentType = null, $parentId = null)
    {
        global $current_user, $timedate;

        // Allow send messages from no authenticated contexts as Signature Portal
        if (empty($current_user->id)) {
            // Get first admin active user
            $adminUser = BeanFactory::getBean('Users');
            $adminUser->retrieve_by_string_fields(array('is_admin' => 1, 'status' => 'Active'));
            $current_user = $adminUser;
        }

        $parentType = $parentType?? $this->parent_type;
        $parentId = $parentId ?? $this->parent_id;

        $relatedObjectName = '';
        if (!empty($parentType)){
            $relatedObject = BeanFactory::getBean($parentType, $parentId);
            $relatedObjectName = $relatedObject->name;
        }
        $templateName = '';
        if (!empty($this->template_id)){
            $template = BeanFactory::getBean('EmailTemplates', $this->template_id);
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
        if ($timezone === null) {
            require_once('include/TimeDate.php');
            $timezone =  TimeDate::guessTimezone();;
        }

        $date = $date->setTimezone(new DateTimeZone($timezone));
        $formatedDate = $date->format($timedate->get_date_time_format($current_user));


        $this->name = $relatedObjectName . ' - ' . $formatedDate . $templateName;
        return $this->name;
    }

    /**
     * Builds a short-lived signed URL that allows Twilio (or any external caller)
     * to download the attachment without requiring a SuiteCRM session.
     *
     * The URL is valid for WhatsAppMediaEntryPoint::TOKEN_TTL seconds (5 min),
     * which is more than enough for Twilio to fetch the file.
     *
     * @param string $noteId  UUID of the Notes record whose file should be served
     * @return string         Absolute URL including token and expiry
     */
    public function buildSignedMediaUrl(string $noteId): string
    {
        $expires = time() + 300; // 5 minutes — matches WhatsAppMediaEntryPoint::TOKEN_TTL
        $secret  = $GLOBALS['sugar_config']['unique_key'] ?? '';
        $token   = hash_hmac('sha256', $noteId . $expires, $secret);
        $siteUrl = rtrim($GLOBALS['sugar_config']['site_url'], '/');

        return $siteUrl . '/index.php?entryPoint=sticWhatsappMedia'
            . '&note_id=' . urlencode($noteId)
            . '&expires=' . $expires
            . '&token='   . urlencode($token);
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

        $templateSid = null;
        if (!empty($this->template_id)) {
            $templateBean = BeanFactory::getBean('EmailTemplates', $this->template_id);
            if ($templateBean) {
                $templateSid = $templateBean->stic_whatsapp_twilio_id_c ?? null;
            }
        }

        if ($messageHelper !== null) {
            if ($file === 'WhatsAppHelper') {
                // Build the beans array from the parent record so placeholders can be resolved
                $beans = [];
                if (!empty($this->parent_type) && !empty($this->parent_id)) {
                    $parentBean = BeanFactory::getBean($this->parent_type, $this->parent_id);
                    if ($parentBean) {
                        $beans[] = $parentBean;
                    }
                }

                $messageForHelper = $this->message;
                if (!empty($templateBean) && !empty($templateBean->body)) {
                    $messageForHelper = $templateBean->body;
                }

                $returnCode = $messageHelper->sendMessage($this->sender, $messageForHelper, $this->phone, $templateSid, $beans, $this->media_url ?? null);
            } else {
                $returnCode = $messageHelper->sendMessage($this->sender, $this->message, $this->phone);
            }
        }
        else {
            $returnCode = self::ERROR_NO_HELPER_CLASS;
        }
        return $returnCode;

    }

    public static function replaceTemplateVariables($screenText, $bean)
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
        return html_entity_decode($templateData['body'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    

}