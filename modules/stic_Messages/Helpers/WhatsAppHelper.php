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

// WhatsApp Helper class to send WhatsApp messages through Twilio provider.

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('modules/stic_Settings/Utils.php');
require_once('modules/stic_Messages/Helpers/stic_MessagesHelper.php');

class WhatsAppHelper implements stic_MessagesHelper {
    
    protected ?string $sid;
    protected ?string $token;
    protected ?string $twilioNumber;
    private $apiUrl = 'https://api.twilio.com/2010-04-01';

    public function __construct()
    {
        $sid = stic_SettingsUtils::getSetting('twilio_sid');
        $this->setSid($sid ?? '');

        $token = stic_SettingsUtils::getSetting('twilio_token');
        $this->setToken($token ?? '');

        $twilioNumber = stic_SettingsUtils::getSetting('twilio_number');
        $this->setTwilioNumber($twilioNumber ?? '');
    }

    protected function getSid(): ?string {
        return $this->sid;
    }

    protected function setSid(string $sid): self {
        $this->sid = $sid;
        return $this;
    }

    protected function getToken(): ?string {
        return $this->token;
    }

    protected function setToken(string $token): self {
        $this->token = $token;
        return $this;
    }

    protected function getTwilioNumber(): ?string {
        return $this->twilioNumber;
    }

    protected function setTwilioNumber($twilioNumber): self {
        $this->twilioNumber = $twilioNumber;
        return $this;
    }

    public function sendMessage(?string $sender, string $message, string $phone): array
    {
        $phone = $this->formatPhoneNumber($phone);
        
        $result = $this->apiCall($sender, $message, $phone);
        
        $resultArray = json_decode($result, true);
        
        if (!isset($resultArray['success']) || !$resultArray['success']) {
            return [
                'code' => stic_Messages::ERROR_NOT_SENT, 
                'message' => $result
            ];
        }
        
        if (isset($resultArray['data']['sid'])) {
            $GLOBALS['log']->info('WhatsApp enviado. SID: ' . $resultArray['data']['sid']);
            return [
                'code' => stic_Messages::OK,
                'message' => 'Message sent',
                'twilio_sid' => $resultArray['data']['sid'],
                'status' => $resultArray['data']['status'] ?? 'sent'
            ];
        } else {
            return [
                'code' => stic_Messages::ERROR_NOT_SENT, 
                'message' => $result
            ];
        }
    }

    protected function apiCall(?string $sender, string $message, string $phone): string
    {
        if (!$this->isConfigured()) {
            return json_encode([
                'success' => false, 
                'message' => 'Configuración de Twilio incompleta'
            ]);
        }

        if (empty($phone) || empty($message)) {
            return json_encode([
                'success' => false, 
                'message' => 'Teléfono o mensaje vacíos'
            ]);
        }

        $from = 'whatsapp:' . $this->twilioNumber;
        $to = 'whatsapp:' . $phone;

        $postData = [
            'From' => $from,
            'To' => $to
        ];

        if (strpos($message, 'HX') === 0) {
            $postData['ContentSid'] = $message;
        } else {
            $postData['Body'] = $message;
        }

        $url = $this->apiUrl . '/Accounts/' . $this->sid . '/Messages.json';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_USERPWD, $this->sid . ':' . $this->token);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 7500);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false) {
            $errorNumber = curl_errno($ch);
            $errorMessage = curl_error($ch);
            curl_close($ch);
            
            $GLOBALS['log']->fatal('Error sending WhatsApp ' . __METHOD__ . __LINE__, $errorNumber, $errorMessage);
            $errorMsg = $errorNumber . '-' . $errorMessage;
            return json_encode([
                'success' => false, 
                'message' => $errorMsg
            ]);
        }

        curl_close($ch);

        $responseData = json_decode($response, true);

        if ($httpCode >= 200 && $httpCode < 300) {
            return json_encode([
                'success' => true, 
                'data' => $responseData
            ]);
        }

        $errorMessage = $responseData['message'] ?? 'Error desconocido';
        return json_encode([
            'success' => false, 
            'message' => $errorMessage
        ]);
    }

    private function formatPhoneNumber($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (substr($phone, 0, 1) !== '+') {
            $phone = '+' . $phone;
        }
        return $phone;
    }

    private function isConfigured()
    {
        return !empty($this->sid) && !empty($this->token) && !empty($this->twilioNumber);
    }

    public function validateConfig()
    {
        $errors = [];
        if (empty($this->sid)) $errors[] = 'Account SID ausente';
        if (empty($this->token)) $errors[] = 'Auth Token ausente';
        if (empty($this->twilioNumber)) $errors[] = 'Número Twilio ausente';
        return $errors;
    }
}