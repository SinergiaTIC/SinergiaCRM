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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

use SuiteCRM\Utility\SuiteValidator;

include_once 'include/Exceptions/SugarControllerException.php';
require_once 'modules/stic_Messages/Utils.php';
require_once("modules/AOW_WorkFlow/aow_utils.php");

class stic_MessagesController extends SugarController
{
    // We remap EditView action when no id is present (new record) to use the ComposeView
    protected function remapAction()
    {
        if (!empty($this->action_remap[$this->do_action])) {
            $this->action = $this->action_remap[$this->do_action];
            $this->do_action = $this->action;
        }

        if ($this->do_action == 'EditView' && empty($this->bean->id)) {
            $this->action = 'ComposeView';
            $this->do_action = 'ComposeView';
        }
    }

    public function action_Save() {
        if (isset($_REQUEST['mass_ids']) && $_REQUEST['mass_ids'] !== '') {
            $idsArray = explode(';', $_REQUEST['mass_ids']);
            $phonesArray = explode(',', $_REQUEST['phone']);

            array_map(function($id, $phone) {
                $newBean = BeanFactory::newBean('stic_Messages');
                $this->bean = $newBean;
                $this->pre_save();
                $this->bean->parent_id = $id;
                $this->bean->parent_type = $_REQUEST['return_module'];
                $this->bean->phone = $phone;
                $this->prepareConversationDataForMessage($this->bean);
                $shouldStoreFirstMessage = $this->shouldStoreFirstMessage($this->bean);
                $messageId = $this->bean->save(!empty($this->bean->notify_on_save));
                if ($shouldStoreFirstMessage) {
                    $this->storeFirstMessageInConversation($this->bean->stic_conversations_ida, $messageId);
                }
            }, $idsArray, $phonesArray);
        }
        else {
            $this->prepareConversationDataForMessage($this->bean);
            $shouldStoreFirstMessage = $this->shouldStoreFirstMessage($this->bean);
            $messageId = $this->bean->save(!empty($this->bean->notify_on_save));
            if ($shouldStoreFirstMessage) {
                $this->storeFirstMessageInConversation($this->bean->stic_conversations_ida, $messageId);
            }
            // header('Content-Type: application/json');
            // $this->bean->response
            // echo "{'status': 200, 'message': 'ok'}";
            echo json_encode(array('success' => true, 'number_found' => true));
        }
    }

    public function pre_savePopUp(){
        parent::pre_save();
    }

    public function action_SavePopUp() {
        global $app_strings, $current_language;
        $mod_strings = return_module_language($current_language, $this->module);

        if (isset($_REQUEST['mass_ids']) && $_REQUEST['mass_ids'] !== '') {
            $idsArray = explode(';', $_REQUEST['mass_ids']);
            $phonesArray = explode(',', $_REQUEST['phone']);

            array_map(function($id, $phone) use ($mod_strings) {
                $newBean = BeanFactory::newBean('stic_Messages');
                $this->bean = $newBean;
                $this->pre_save();
                $this->bean->parent_id = $id;
                $this->bean->parent_type = $_REQUEST['return_module'];
                $this->bean->phone = $phone;
                $conversationValidation = $this->prepareConversationDataForMessage($this->bean);
                if (!$conversationValidation['success']) {
                    $this->returnConversationValidationError($mod_strings);
                }
                $shouldStoreFirstMessage = $this->shouldStoreFirstMessage($this->bean);
                $messageId = $this->bean->save(!empty($this->bean->notify_on_save));
                if ($shouldStoreFirstMessage) {
                    $this->storeFirstMessageInConversation($this->bean->stic_conversations_ida, $messageId);
                }
            }, $idsArray, $phonesArray);
            // If mass send and type is WhatsAppWeb, return an open_url built from the phones/message
            if (isset($_REQUEST['type']) && $_REQUEST['type'] === 'WhatsAppWeb') {
                $phonesRaw = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : '';
                $phonesList = $phonesRaw !== '' ? explode(',', $phonesRaw) : array();
                $text = isset($_REQUEST['message']) ? $_REQUEST['message'] : '';
                $openData = array();
                foreach ($phonesList as $index => $p) {
                    $p = trim($p);
                    if ($p === '') continue;
                    $phoneClean = preg_replace('/\D+/', '', $p);
                    if ($phoneClean === '') continue;
                    
                    $processedText = $text;
                    if (isset($idsArray[$index]) && !empty($idsArray[$index])) {
                        $parentBean = BeanFactory::getBean($_REQUEST['return_module'], $idsArray[$index]);
                        $processedText = stic_Messages::replaceTemplateVariables($text, $parentBean);
                    }
                    
                    $openData[] = array('phone' => $phoneClean, 'text' => $processedText);
                }

                // Clear accidental output and return JSON
                while (ob_get_level()) { ob_end_clean(); }
                header('Content-Type: application/json');
                echo json_encode(array('success' => true, 'type' => 'WhatsAppWeb', 'open_data' => $openData, 'title' => $app_strings['LBL_EMAIL_SUCCESS'], 'detail' => $mod_strings['LBL_WHATSAPP_WEB_SENT']));
                exit;                
            }

            // Clear any accidental output (warnings, HTML, etc.) so the response is pure JSON
            while (ob_get_level()) { ob_end_clean(); }
            header('Content-Type: application/json');
            echo json_encode(array('success' =>  true, 'type' => 'sms', 'title' => $app_strings['LBL_EMAIL_SUCCESS'], 'detail' => $mod_strings['LBL_CHECK_STATUS']));
            exit;
        }
        else {
            $oldStatus = $this->bean->fetched_row['status']??'';

            $conversationValidation = $this->prepareConversationDataForMessage($this->bean);
            if (!$conversationValidation['success']) {
                $this->returnConversationValidationError($mod_strings);
            }

            $shouldStoreFirstMessage = $this->shouldStoreFirstMessage($this->bean);
            $id = $this->bean->save(!empty($this->bean->notify_on_save));
            if ($shouldStoreFirstMessage) {
                $this->storeFirstMessageInConversation($this->bean->stic_conversations_ida, $id);
            }

            if (isset($this->bean->type) && $this->bean->type === 'WhatsAppWeb') {
                $phone = isset($this->bean->phone) ? preg_replace('/\D+/', '', $this->bean->phone) : '';
                $text = isset($this->bean->message) ? $this->bean->message : '';
                
                if (!empty($this->bean->parent_type) && !empty($this->bean->parent_id)) {
                    $parentBean = BeanFactory::getBean($this->bean->parent_type, $this->bean->parent_id);
                    $text = stic_Messages::replaceTemplateVariables($text, $parentBean);
                }
                
                // Clear output buffer before returning JSON to avoid malformed responses
                while (ob_get_level()) { ob_end_clean(); }
                header('Content-Type: application/json');
                echo json_encode(array('success' => true, 'type' => 'WhatsAppWeb', 'phone' => $phone, 'text' => $text, 'id' => $id));
                exit;
            }

            // Ensure response is clean JSON
            while (ob_get_level()) { ob_end_clean(); }
            header('Content-Type: application/json');
            switch ($this->bean->status) {
                case 'sent':
                    if ($this->bean->status !== $oldStatus) {
                        $title = $app_strings['LBL_EMAIL_SUCCESS'];
                        $detail = $mod_strings['LBL_MESSAGE_SENT'];
                    }
                    else {
                        $title = $app_strings['LBL_EMAIL_SUCCESS'];
                        $detail = $mod_strings['LBL_MESSAGE_SAVED'];
                    }
                    break;
                case 'error':
                    $title = $mod_strings['LBL_ERROR'];
                    $detail = $mod_strings['LBL_MESSAGE_NOT_SENT'];
                    break;
                case 'draft':
                    $title = $app_strings['LBL_EMAIL_SUCCESS'];
                    $detail = $mod_strings['LBL_MESSAGE_SAVED'];
                    break;
                default:
                    $title = $mod_strings['LBL_EMAIL_SUCCESS'];
                    $detail = $mod_strings['LBL_MESSAGE_SAVED'];
            }
            echo json_encode(array('success' => $this->bean->status === 'error' ? false : true, 'type' => 'sms', 'title' => $title, 'detail' => $detail, 'id' => $id));
            exit;
        }
    }


    public function action_RetryOne() {
        global $app_strings, $mod_strings;

        $id = $_REQUEST['recordId'];
        $bean = BeanFactory::getBean('stic_Messages', $id);
        
        // WhatsAppWeb messages cannot be retried
        if ($bean->type === 'WhatsAppWeb') {
            echo json_encode(array(
                'success' => false, 
                'title' => $mod_strings['LBL_ERROR'], 
                'detail' => $mod_strings['LBL_WHATSAPP_WEB_RETRY'],
                'id' => $id
            ));
            exit;
        }
        
        $bean->status = 'sent';
        $bean->save();

        $title = $bean->status !== 'error' ? $app_strings['LBL_EMAIL_SUCCESS'] : $mod_strings['LBL_ERROR'];
        $detail = $bean->status !== 'error' ? $mod_strings['LBL_MESSAGE_SENT'] : $mod_strings['LBL_MESSAGE_NOT_SENT'];
        echo json_encode(array('success' => $bean->status === 'error' ? false : true, 'title' => $title, 'detail' => $detail, 'id' => $id));
        exit;
    }
    public function action_Retry(){

        $where = '';

        $db = DBManagerFactory::getInstance();
        // only messages not sent and with direction outbound can be retried
        // WhatsAppWeb messages are excluded from retry
        $sql = "SELECT id,name,`type`,direction,phone,sender,message,status  FROM stic_messages WHERE deleted = 0 and status <> 'sent' and direction = 'outbound' and `type` <> 'WhatsAppWeb'";
        if (isset($_REQUEST['select_entire_list']) && $_REQUEST['select_entire_list'] == '1' && isset($_REQUEST['current_query_by_page'])) {
            require_once 'include/export_utils.php';
            $retArray = generateSearchWhere('stic_Messages', $_REQUEST['current_query_by_page']);
            if (!empty($retArray['where'])) {
                $where = " AND " . $retArray['where'];
            }
        } else {
            $ids = explode(',', $_REQUEST['uid']);
            $idList = implode("','", $ids);
            $where = " AND id in ('{$idList}')";
        }

        $focus = BeanFactory::newBean('stic_Messages');
        if ($focus->bean_implements('ACL')) {
            if (!ACLController::checkAccess($focus->module_dir, 'export', true)) {
                ACLController::displayNoAccess();
                sugar_die('');
            }

            $accessWhere = $focus->buildAccessWhere('export');
            if (!empty($accessWhere)) {
                $where .= empty($where) ? $accessWhere : ' AND ' . $accessWhere;
            }
        }

        $sql .= $where;
        $result = $db->query($sql);

        while ($row = $db->fetchByAssoc($result)) {
            $bean = BeanFactory::getBean('stic_Messages', $row['id']);
            // Double check to prevent WhatsAppWeb retry
            if ($bean->type !== 'WhatsAppWeb') {
                $bean->status = 'sent';
                $bean->save();
            }
        }

        SugarApplication::redirect("index.php?module=stic_Messages&action=index");
    }

    public function action_ComposeView() {
        $this->view = 'edit';
        // For viewing the Compose as modal from other modules we need to load the stic_Messages language strings
        if (isset($_REQUEST['in_popup']) && $_REQUEST['in_popup']) {
            if (!is_file('cache/jsLanguage/stic_Messages/' . $GLOBALS['current_language'] . '.js')) {
                require_once('include/language/jsLanguage.php');
                jsLanguage::createModuleStringsCache('stic_Messages', $GLOBALS['current_language']);
            }
            echo '<script src="cache/jsLanguage/stic_Messages/'. $GLOBALS['current_language'] . '.js"></script>';
        }


        // Building and running the query that retrieves all the record that were selected in ListView
        if(!empty($_REQUEST['targetModule'])){
            $bean = BeanFactory::getBean($_REQUEST['targetModule']);
            $phoneFieldName = stic_MessagesUtils::getPhoneFieldNameForMessage($bean->module_name);
            $nameFieldName = stic_MessagesUtils::getNameFieldNameForMessage($bean->module_name);
            $moduleTable = $bean->table_name;
            $moduleName = $bean->module_name;
            $sql = "SELECT id, $phoneFieldName as phone, $nameFieldName as name FROM {$moduleTable} WHERE {$moduleTable}.deleted=0";
            $where = '';
            if (isset($_REQUEST['select_entire_list']) && $_REQUEST['select_entire_list'] == '1' && isset($_REQUEST['current_query_by_page'])) {
                require_once 'include/export_utils.php';
                $retArray = generateSearchWhere($moduleName, $_REQUEST['current_query_by_page']);
                $where = '';
                if (!empty($retArray['where'])) {
                    $where = " AND " . $retArray['where'];
                }
            } else {
                $ids = explode(',', rtrim($_REQUEST['ids'], ','));
                $idList = implode("','", $ids);
                $where = " AND id in ('{$idList}')";
            }
            $sql .= $where;
            $db = DBManagerFactory::getInstance();
            $resultado = $db->query($sql);
            unset($ids);
            $ids = array();
    
            while ($row = $db->fetchByAssoc($resultado)) {
                // Building the Summary count table
                $idLine = '<input type="hidden" class="phone-compose-view-to-list" ';
                $idLine .= 'data-record-module="' . $_REQUEST['targetModule'] . '" ';
                $idLine .= 'data-record-id="' . $row['id'] . '" ';
                $idLine .= 'data-record-name="' . $row['name'] . '" ';
                $idLine .= 'data-record-phone="' . $row['phone'] . '">';
                echo $idLine;
            }
        }
    }

    public function action_getParentPhone() {
        $parentId = $_POST["parentId"];
        $parentType = $_POST["parentType"];
        
        require_once('modules/stic_Messages/Utils.php');
        $phoneFieldName = stic_MessagesUtils::getPhoneFieldNameForMessage($parentType);
        $tableName = stic_MessagesUtils::gettableNameForMessage($parentType);

        $response = array();
        $response['code'] = 'No data';
        $db = DBManagerFactory::getInstance();

        $sql = "SELECT {$phoneFieldName} as phone FROM {$tableName} WHERE id = '{$parentId}'";

        $result = $db->query($sql);
        if($row = $result->fetch_assoc()) {
            $response['code'] = 'OK';
            $response['data']['phone'] = $row['phone'];
        }
        else {
            $response['data']['phone'] = '';
        }

        echo json_encode($response);
        exit;
    }

    protected function action_getPhoneField() {
        $module = $_REQUEST['aow_module'];
        $aow_field = $_REQUEST['aow_newfieldname'];

        if (isset($_REQUEST['view'])) {
            $view = $_REQUEST['view'];
        } else {
            $view= 'EditView';
        }

        if (isset($_REQUEST['aow_value'])) {
            $value = $_REQUEST['aow_value'];
        } else {
            $value = '';
        }

        switch ($_REQUEST['aow_type']) {
            case 'Record Phone':
                echo '';
                break;
            case 'Related Field':
                $rel_field_list = stic_MessagesUtils::getRelatedMessageableFields($module);
                if ($view == 'EditView') {
                    echo "<select type='text'  name='$aow_field' id='$aow_field' title='' tabindex='116'>". get_select_options_with_id($rel_field_list, $value) ."</select>";
                } else {
                    echo $rel_field_list[$value];
                }
                break;
            case 'Specify User':
                echo getModuleField('Accounts', 'assigned_user_name', $aow_field, $view, $value);
                break;
            case 'Users':
                echo getAssignField($aow_field, $view, $value);
                break;
            case 'Phone':
            default:
                if ($view == 'EditView') {
                    echo "<input type='text' name='$aow_field' id='$aow_field' size='25' title='' tabindex='116' value='$value'>";
                } else {
                    echo $value;
                }
                break;
        }
        die;
    }


    /**
     * Create and return a conversation id for the current message
     */
    protected function createConversationForMessage($messageBean)
    {
        global $current_user;

        $conversationBean = BeanFactory::newBean('stic_Conversations');
        $conversationBean->subject = mb_substr(trim(strip_tags((string)$messageBean->message)), 0, 255);
        $conversationBean->assigned_user_id = !empty($messageBean->assigned_user_id) ? $messageBean->assigned_user_id : $current_user->id;

        if (!empty($messageBean->parent_type) && $messageBean->parent_type === 'Contacts' && !empty($messageBean->parent_id)) {
            $conversationBean->contact_name = $messageBean->parent_name;
            $conversationBean->contacts_ida = $messageBean->parent_id;
        }

        $conversationBean->save();
        return $conversationBean->id;
    }

    /**
     * Normalize conversation data before saving a message
     */
    protected function prepareConversationDataForMessage($messageBean)
    {
        $type = $messageBean->type ?? ($_REQUEST['type'] ?? '');

        if ($type !== 'conversation') {
            $messageBean->new_conversation = 0;
            $messageBean->stic_conversations_stic_messages_name = '';
            $messageBean->stic_conversations_ida = '';
            return array('success' => true);
        }

        $newConversation = !empty($_REQUEST['new_conversation']) || !empty($messageBean->new_conversation);
        $messageBean->new_conversation = $newConversation ? 1 : 0;

        if ($newConversation) {
            if (empty($messageBean->stic_conversations_ida)) {
                $messageBean->stic_conversations_ida = $this->createConversationForMessage($messageBean);
            }
            return array('success' => true);
        }

        if (empty($messageBean->stic_conversations_ida) && !empty($_REQUEST['stic_conversations_ida'])) {
            $messageBean->stic_conversations_ida = $_REQUEST['stic_conversations_ida'];
        }

        if (empty($messageBean->stic_conversations_ida)) {
            return array('success' => false);
        }

        return array('success' => true);
    }

    /**
     * Check whether first message must be stored in conversation
     */
    protected function shouldStoreFirstMessage($messageBean)
    {
        $type = $messageBean->type ?? ($_REQUEST['type'] ?? '');
        $conversationId = $messageBean->stic_conversations_ida ?? '';

        if ($type !== 'conversation' || empty($conversationId)) {
            return false;
        }

        $conversationBean = BeanFactory::getBean('stic_Conversations', $conversationId);
        if (empty($conversationBean) || empty($conversationBean->id)) {
            return false;
        }

        if (!empty($conversationBean->stic_messages_id)) {
            return false;
        }

        if ($conversationBean->load_relationship('stic_conversations_stic_messages')) {
            $messageIds = $conversationBean->stic_conversations_stic_messages->get();
            return empty($messageIds);
        }

        return true;
    }

    /**
     * Persist first message id in conversation when empty
     */
    protected function storeFirstMessageInConversation($conversationId, $messageId)
    {
        if (empty($conversationId) || empty($messageId)) {
            return;
        }

        $conversationBean = BeanFactory::getBean('stic_Conversations', $conversationId);
        if (empty($conversationBean) || empty($conversationBean->id)) {
            return;
        }

        if (empty($conversationBean->stic_messages_id)) {
            $conversationBean->stic_messages_id = $messageId;
            $conversationBean->save();
        }
    }

    /**
     * Return JSON validation error for conversation-related saves
     */
    protected function returnConversationValidationError($mod_strings)
    {
        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => false,
            'type' => 'sms',
            'title' => $mod_strings['LBL_ERROR'],
            'detail' => $mod_strings['LBL_NEW_CONVERSATION_HELP'],
        ));
        exit;
    }

    /**
     * Get latest non-deleted message from conversation
     */
    protected function getLatestConversationMessage($conversationBean)
    {
        if (!$conversationBean->load_relationship('stic_conversations_stic_messages')) {
            return null;
        }

        $latestMessage = null;
        $latestMessageTs = -1;
        $relatedMessages = $conversationBean->stic_conversations_stic_messages->getBeans();

        foreach ($relatedMessages as $relatedMessage) {
            if (empty($relatedMessage) || !empty($relatedMessage->deleted)) {
                continue;
            }

            $currentTs = !empty($relatedMessage->date_entered) ? strtotime($relatedMessage->date_entered) : false;
            $currentTs = $currentTs !== false ? $currentTs : -1;

            if ($currentTs > $latestMessageTs) {
                $latestMessage = $relatedMessage;
                $latestMessageTs = $currentTs;
            }
        }

        return $latestMessage;
    }

    /**
     * Fill parent data from linked contact when missing
     */
    protected function fillConversationParentFromContact($conversationBean, &$data)
    {
        if (!empty($data['parent_id']) || !empty($data['parent_type'])) {
            return;
        }

        if (!$conversationBean->load_relationship('contacts_stic_conversations')) {
            return;
        }

        $contactIds = $conversationBean->contacts_stic_conversations->get();
        if (empty($contactIds) || empty($contactIds[0])) {
            return;
        }

        $contactBean = BeanFactory::getBean('Contacts', $contactIds[0]);
        if (!empty($contactBean) && !empty($contactBean->id)) {
            $data['parent_id'] = $contactBean->id;
            $data['parent_type'] = 'Contacts';
            $data['parent_name'] = $contactBean->name;
        }
    }

    /**
     * Check if response contains enough conversation data
     */
    protected function hasConversationData($data)
    {
        return !empty($data['sender'])
            || !empty($data['parent_id'])
            || !empty($data['parent_type'])
            || !empty($data['parent_name']);
    }

    /**
     * AJAX endpoint to retrieve sender/parent from conversation
     */
    public function action_getConversationData() {
        $conversationId = $_POST['conversationId'] ?? '';

        $response = array();
        $response['code'] = 'No data';
        $response['data'] = array(
            'sender' => '',
            'parent_id' => '',
            'parent_type' => '',
            'parent_name' => '',
        );

        if (!empty($conversationId)) {
            $conversationBean = BeanFactory::getBean('stic_Conversations', $conversationId);

            if (!empty($conversationBean) && !empty($conversationBean->id)) {
                $latestMessage = $this->getLatestConversationMessage($conversationBean);
                if ($latestMessage) {
                    $response['data']['sender'] = $latestMessage->sender ?? '';
                    $response['data']['parent_id'] = $latestMessage->parent_id ?? '';
                    $response['data']['parent_type'] = $latestMessage->parent_type ?? '';
                    $response['data']['parent_name'] = $latestMessage->parent_name ?? '';
                }

                $this->fillConversationParentFromContact($conversationBean, $response['data']);

                if ($this->hasConversationData($response['data'])) {
                    $response['code'] = 'OK';
                }
            }
        }

        echo json_encode($response);
        exit;
    }

}