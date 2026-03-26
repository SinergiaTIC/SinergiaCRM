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

/**
 * Entry point to receive Twilio webhooks with incoming WhatsApp messages
 */
class WhatsAppWebhookEntryPoint
{
    public function run()
    {
        try {
            if (!$this->validateWebhookData()) {
                $GLOBALS['log']->error('WhatsAppWebhookEntryPoint: Invalid webhook data - ' . print_r($_POST, true));
                $this->sendResponse(400, 'Invalid webhook data');
                return;
            }

            $messageSid = $_POST['MessageSid'] ?? '';
            $from       = $this->cleanPhoneNumber($_POST['From'] ?? '');
            $body       = $_POST['Body'] ?? '';

            $parentInfo = $this->findContactByPhone($from);

            $message              = BeanFactory::newBean('stic_Messages');
            $message->name        = $this->generateMessageName($parentInfo, $from);
            $message->type        = 'WhatsApp';
            $message->direction   = 'inbound';
            $message->phone       = $from;
            $message->sender      = $from;
            $message->message     = $body;
            $message->status      = 'received';
            $message->response    = "MessageSid: $messageSid";
            $message->sent_date   = $GLOBALS['timedate']->nowDb();

            if ($parentInfo) {
                $message->parent_type = $parentInfo['module'];
                $message->parent_id   = $parentInfo['id'];
            }

            $message->save();

            $this->processMedia($message);

            $this->sendTwiMLResponse();

        } catch (Exception $e) {
            $GLOBALS['log']->error('WhatsAppWebhookEntryPoint: ' . $e->getMessage());
            $this->sendResponse(500, 'Internal server error');
        }
    }

    private function validateWebhookData()
    {
        // Body can legitimately be empty when the incoming message contains
        // only media (image, audio, document, etc.) with no text.
        foreach (['MessageSid', 'From'] as $field) {
            if (empty($_POST[$field])) {
                return false;
            }
        }
        return true;
    }

    private function cleanPhoneNumber($phone)
    {
        return trim(str_replace('whatsapp:', '', $phone));
    }

    private function findContactByPhone($phone)
    {
        $cleanPhone = preg_replace('/[^0-9+]/', '', $phone);

        foreach (['Contacts', 'Users', 'Accounts', 'Employees', 'Leads'] as $module) {
            $result = $this->searchPhoneInModule($module, $cleanPhone);
            if ($result) {
                return $result;
            }
        }

        $GLOBALS['log']->warn("WhatsAppWebhookEntryPoint: No record found for phone: $phone");
        return null;
    }

    private function searchPhoneInModule($module, $phone)
    {
        global $db;

        $bean = BeanFactory::newBean($module);
        if (!$bean) {
            return null;
        }

        $cleanPhone  = preg_replace('/[^0-9]/', '', $phone);
        $phoneFields = ['phone_mobile', 'phone_work', 'phone_home', 'phone_other', 'phone_fax'];
        $conditions  = [];

        foreach ($phoneFields as $field) {
            if (isset($bean->field_defs[$field])) {
                $conditions[] = "REPLACE(REPLACE(REPLACE(REPLACE({$field}, ' ', ''), '-', ''), '.', ''), '+', '') LIKE '%{$cleanPhone}%'";
            }
        }

        if (empty($conditions)) {
            return null;
        }

        $sql    = "SELECT id FROM {$bean->table_name} WHERE deleted = 0 AND (" . implode(' OR ', $conditions) . ") LIMIT 1";
        $result = $db->query($sql);

        if ($row = $db->fetchByAssoc($result)) {
            return ['module' => $module, 'id' => $row['id']];
        }

        return null;
    }

    private function generateMessageName($parentInfo, $from)
    {
        global $app_strings;

        if ($parentInfo) {
            $bean = BeanFactory::getBean($parentInfo['module'], $parentInfo['id']);
            if ($bean) {
                return $bean->name . ' - ' . $GLOBALS['timedate']->nowDb();
            }
        }

        $label = $app_strings['LBL_WHATSAPP_INCOMING_MESSAGE'] ?? 'Incoming WhatsApp message';
        $from_label = $app_strings['LBL_FROM'] ?? 'from';
        return $label . ' ' . $from_label . ' ' . $from . ' - ' . $GLOBALS['timedate']->nowDb();
    }

    private function sendTwiMLResponse()
    {
        if (defined('WEBHOOK_TEST_MODE') && WEBHOOK_TEST_MODE === true) {
            return;
        }

        header('Content-Type: text/xml');
        echo '<?xml version="1.0" encoding="UTF-8"?><Response></Response>';
        exit;
    }

    private function sendResponse($code, $message)
    {
        if (defined('WEBHOOK_TEST_MODE') && WEBHOOK_TEST_MODE === true) {
            $GLOBALS['log']->error("WhatsAppWebhookEntryPoint: Response $code - $message");
            return;
        }

        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode(['success' => $code >= 200 && $code < 300, 'message' => $message]);
        exit;
    }

    private function processMedia($message)
    {
        $numMedia = (int)($_POST['NumMedia'] ?? 0);
        if ($numMedia === 0) {
            return;
        }

        $twilioCredentials = $this->getTwilioCredentials();

        for ($i = 0; $i < $numMedia; $i++) {
            $mediaUrl = $_POST["MediaUrl{$i}"] ?? '';
            $mediaContentType = $_POST["MediaContentType{$i}"] ?? 'application/octet-stream';

            if (empty($mediaUrl)) {
                continue;
            }

            $this->downloadAndSaveMedia($message, $mediaUrl, $mediaContentType, $twilioCredentials);
        }
    }

    private function getTwilioCredentials()
    {
        $db = DBManagerFactory::getInstance();
        $result = $db->query("SELECT name, value FROM stic_settings WHERE name IN ('twilio_sid', 'twilio_token')");
        
        $sid = '';
        $token = '';
        
        while ($row = $db->fetchByAssoc($result)) {
            if ($row['name'] === 'twilio_sid') {
                $sid = $row['value'];
            } elseif ($row['name'] === 'twilio_token') {
                $token = $row['value'];
            }
        }

        return ['sid' => $sid, 'token' => $token];
    }

    private function downloadAndSaveMedia($message, $mediaUrl, $mediaContentType, $twilioCredentials)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $mediaUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, $twilioCredentials['sid'] . ':' . $twilioCredentials['token']);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $mediaData = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200 || empty($mediaData)) {
                $GLOBALS['log']->error("WhatsAppWebhookEntryPoint: Failed to download media from {$mediaUrl}, HTTP code: {$httpCode}");
                return;
            }

            $extension = $this->getExtensionFromMimeType($mediaContentType);
            $filename = 'whatsapp_received_' . time() . '_' . mt_rand(1000, 9999) . $extension;

            $note = BeanFactory::newBean('Notes');
            $note->name = $filename;
            $note->filename = $filename;
            $note->file_mime_type = $mediaContentType;
            $note->parent_type = 'stic_Messages';
            $note->parent_id = $message->id;
            $note->assigned_user_id = $message->assigned_user_id;
            $note->save();

            $uploadDir = rtrim(getcwd(), '/') . '/upload/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $filePath = $uploadDir . $note->id;
            file_put_contents($filePath, $mediaData);

            $GLOBALS['log']->info("WhatsAppWebhookEntryPoint: Media saved - {$filename} for message {$message->id}");

        } catch (Exception $e) {
            $GLOBALS['log']->error('WhatsAppWebhookEntryPoint: Error saving media - ' . $e->getMessage());
        }
    }

    private function getExtensionFromMimeType($mimeType)
    {
        $mimeToExtension = [
            'image/jpeg' => '.jpg',
            'image/png' => '.png',
            'image/gif' => '.gif',
            'image/webp' => '.webp',
            'audio/ogg' => '.ogg',
            'audio/mpeg' => '.mp3',
            'audio/wav' => '.wav',
            'video/mp4' => '.mp4',
            'video/3gpp' => '.3gp',
            'application/pdf' => '.pdf',
        ];

        return $mimeToExtension[$mimeType] ?? '.bin';
    }
}

$entryPoint = new WhatsAppWebhookEntryPoint();
$entryPoint->run();