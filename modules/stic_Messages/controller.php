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
                $this->bean->save(!empty($this->bean->notify_on_save));  
            }, $idsArray, $phonesArray);
        }
        else {
            $this->bean->save(!empty($this->bean->notify_on_save));
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

            array_map(function($id, $phone) {
                $newBean = BeanFactory::newBean('stic_Messages');
                $this->bean = $newBean;
                $this->pre_save();
                $this->bean->parent_id = $id;
                $this->bean->parent_type = $_REQUEST['return_module'];
                $this->bean->phone = $phone;
                $this->bean->save(!empty($this->bean->notify_on_save));  
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
            $id = $this->bean->save(!empty($this->bean->notify_on_save));

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

        $focus = BeanFactory::newBean('stic_Messages');
        if ($focus->bean_implements('ACL')) {
            if (!ACLController::checkAccess($focus->module_dir, 'export', true)) {
                ACLController::displayNoAccess();
                sugar_die('');
            }
        }

        // only messages not sent and with direction outbound can be retried
        // WhatsAppWeb messages are excluded from retry
        $baseWhere = "stic_messages.deleted = 0 AND stic_messages.status <> 'sent' AND stic_messages.direction = 'outbound' AND stic_messages.type <> 'WhatsAppWeb'";

        if (isset($_REQUEST['select_entire_list']) && $_REQUEST['select_entire_list'] == '1' && isset($_REQUEST['current_query_by_page'])) {
            require_once 'include/export_utils.php';
            $retArray = generateSearchWhere('stic_Messages', $_REQUEST['current_query_by_page']);
            if (!empty($retArray['where'])) {
                $where = $baseWhere . " AND " . $retArray['where'];
            } else {
                $where = $baseWhere;
            }
        } else {
            $ids = explode(',', $_REQUEST['uid']);
            $idList = implode("','", $ids);
            $where = $baseWhere . " AND stic_messages.id in ('{$idList}')";
        }

        $orderBy = 'stic_messages.date_entered DESC';
        $beans = $focus->get_full_list($orderBy, $where);

        foreach ($beans as $bean) {
            $bean->status = 'sent';
            $bean->save();
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

    public function action_FillDynamicListMessageTemplate() {
        // Determine requested type from the client (flexible: accept helper class or 'whatsapp'/'sms')
        $typeParam = isset($_REQUEST['type']) ? strtolower($_REQUEST['type']) : '';

        if (strpos($typeParam, 'whatsapp') !== false) {
            // fillDynamicListMessageTemplate expects 'whatsapphelper' as key in mapping
            $_REQUEST['type'] = 'whatsapphelper';
        } elseif (strpos($typeParam, 'sms') !== false) {
            $_REQUEST['type'] = 'smssevenhelper';
        } else {
            // default
            $_REQUEST['type'] = 'smssevenhelper';
        }

        // Call the util to populate the app_list_strings
        require_once 'modules/stic_Messages/Utils.php';
        stic_MessagesUtils::fillDynamicListMessageTemplate();

        $list = $GLOBALS['app_list_strings']['dynamic_message_template_list'] ?? array();

        // Convert associative array to list of {id,name}
        $out = array();
        foreach ($list as $id => $name) {
            // skip empty key used for 'None'
            if ($id === '') continue;
            $out[] = array('id' => $id, 'name' => $name);
        }

        header('Content-Type: application/json');
        echo json_encode(array('success' => true, 'data' => $out));
        exit;
    }

    public function action_conversation() {
        global $current_language;
        $mod_strings = return_module_language($current_language, 'stic_Messages');

        $parentId   = $_REQUEST['parent_id']   ?? '';
        $parentType = $_REQUEST['parent_type'] ?? 'Contacts';

        if (empty($parentId)) die('Missing parent_id');

        $db = DBManagerFactory::getInstance();
        $parentIdSafe = $db->quote($parentId);

        $contactPhone = '';
        $parentName = '';
        $contactBean = BeanFactory::getBean($parentType, $parentId);
        if ($contactBean) {
            require_once('modules/stic_Messages/Utils.php');
            $contactPhone = stic_MessagesUtils::getPhoneForMessage($contactBean);
            $parentName = $contactBean->name ?? $contactBean->full_name ?? '';
        }
        $sql = "SELECT id, message, type, status, date_entered, sender, phone, direction,
                    template_id
                FROM stic_messages
                WHERE parent_id = '{$parentIdSafe}'
                AND deleted = 0
                AND type IN ('WhatsAppHelper', 'WhatsApp', 'received')
                ORDER BY date_entered ASC";

        $result = $db->query($sql);
        $messages = [];
        while ($row = $db->fetchByAssoc($result)) {
            $messages[] = $row;
        }

        // Calculate 24h window:
        // Buscar el mensaje más reciente que sea:
        $lastWindowEvent = null;

        foreach (array_reverse($messages) as $msg) {
            if ($msg['type'] === 'received' || $msg['type'] === 'WhatsApp') {
                $lastWindowEvent = $msg['date_entered'];
                break;
            }
            if ($msg['type'] === 'WhatsAppHelper' && !empty($msg['template_id'])) {
                $templateBean = BeanFactory::getBean('EmailTemplates', $msg['template_id']);
                if ($templateBean && !empty($templateBean->stic_whatsapp_twilio_id_c)) {
                    $lastWindowEvent = $msg['date_entered'];
                    break;
                }
            }
        }

        // < 24h since $lastWindowEvent)
        $windowOpen    = false;
        $windowMessage = null;

        if ($lastWindowEvent) {
            $eventTs = (new DateTime($lastWindowEvent, new DateTimeZone('UTC')))->getTimestamp();
            $nowTs   = (new DateTime('now', new DateTimeZone('UTC')))->getTimestamp();
            $diffSeconds = $nowTs - $eventTs;
            $diffH   = ($diffSeconds) / 3600;

            if ($diffH < 24) {
                $windowOpen    = true;
                $secondsLeft = (24 * 3600) - $diffSeconds;
                $hoursLeft   = floor($secondsLeft / 3600);
                $minutesLeft = floor(($secondsLeft % 3600) / 60);
                $windowMessage = sprintf(
                    $mod_strings['LBL_CONVERSATION_WINDOW_OPEN'],
                    $hoursLeft,
                    $minutesLeft
                );
            } else {
                $lastEventFormatted = $GLOBALS['timedate']->to_display_date_time($lastWindowEvent);
                $windowMessage = sprintf(
                    $mod_strings['LBL_CONVERSATION_WINDOW_CLOSED'],
                    $lastEventFormatted
                );
            }
        } else {
            $windowMessage = $mod_strings['LBL_CONVERSATION_NO_HISTORY'];
        }

        // Build URL to create a new stic_Messages record pre-linked to the parent
        $newMessageUrl = 'index.php?module=stic_Messages&action=EditView'
            . '&return_module=' . urlencode($parentType)
            . '&return_id='     . urlencode($parentId)
            . '&parent_type='   . urlencode($parentType)
            . '&parent_id='     . urlencode($parentId)
            . '&parent_name='   . urlencode($parentName);

        require_once('modules/stic_Messages/views/view.conversation.php');
        $view = new stic_MessagesViewConversation();
        $view->messages       = $messages;
        $view->parentName     = $parentName;
        $view->parentId       = $parentId;
        $view->parentType     = $parentType;
        $view->contactPhone   = $contactPhone;
        $view->windowOpen     = $windowOpen;
        $view->windowMessage  = $windowMessage;
        $view->newMessageUrl  = $newMessageUrl;
        $view->modStrings     = $mod_strings;
        $view->display();
        sugar_cleanup();
        exit();
    }    
    public function action_uploadConversationMedia() {
        header('Content-Type: application/json');

        $allowedMimes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'video/mp4', 'video/3gpp',
            'audio/ogg', 'audio/mpeg', 'audio/mp4', 'audio/amr',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ];

        if (empty($_FILES['media']) || $_FILES['media']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'error' => 'No file received']);
            exit();
        }

        $file     = $_FILES['media'];
        $mimeType = mime_content_type($file['tmp_name']);

        if (!in_array($mimeType, $allowedMimes)) {
            echo json_encode(['success' => false, 'error' => 'Tipo de archivo no soportado por WhatsApp: ' . $mimeType]);
            exit();
        }

        $sizeLimit = (strpos($mimeType, 'image/') === 0) ? 5 * 1024 * 1024 : 16 * 1024 * 1024;
        if ($file['size'] > $sizeLimit) {
            $limitMb = $sizeLimit / 1024 / 1024;
            echo json_encode(['success' => false, 'error' => "El archivo supera el límite de {$limitMb}MB"]);
            exit();
        }

        // Create the Note immediately — same pattern as SuiteCRM Emails.
        // parent_id is empty at this point; it will be filled in stic_Messages::save()
        // once the message record has been persisted.
        $note                 = BeanFactory::newBean('Notes');
        $note->parent_type    = 'stic_Messages';
        $note->parent_id      = '';
        $note->name           = $file['name'];
        $note->filename       = $file['name'];
        $note->file_mime_type = $mimeType;
        $note->deleted        = 0;
        $noteId               = $note->save();

        if (empty($noteId)) {
            echo json_encode(['success' => false, 'error' => 'Error al crear el registro del adjunto']);
            exit();
        }

        // Move the uploaded file to upload/{note_id} — the standard SuiteCRM location
        $destPath = rtrim(getcwd(), '/') . '/upload/' . $noteId;
        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            // Roll back the Note if the file could not be moved
            $note->deleted = 1;
            $note->save();
            echo json_encode(['success' => false, 'error' => 'Error al guardar el archivo']);
            exit();
        }

        $GLOBALS['log']->info('stic_Messages: attachment uploaded. note_id=' . $noteId . ' file=' . $file['name']);

        echo json_encode([
            'success' => true,
            'note_id' => $noteId,
            'name'    => $file['name'],
            'mime'    => $mimeType,
        ]);
        exit();
    }
}