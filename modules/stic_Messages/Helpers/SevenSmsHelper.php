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


// SMS Helper class to send SMS messages through Seven provider.
// Info about API can be found at: https://docs.seven.io/en/rest-api/endpoints/sms

require_once('modules/stic_Settings/Utils.php');
require_once('modules/stic_Messages/Helpers/stic_MessagesHelper.php');

class SevenSMSHelper implements stic_MessagesHelper {

    protected bool $active = false;
    protected ?string $apiKey = null;
    protected ?string $sender;

    public function __construct() {
        $active = stic_SettingsUtils::getSetting('seven_active');
        $this->setActive($active);
        
        $apiKey = stic_SettingsUtils::getSetting('seven_api_key');
        $this->setApiKey($apiKey);
        
        $sender = stic_SettingsUtils::getSetting('messages_sender');
        $this->setSender($sender);
    }

    protected function getActive(): bool {
        return $this->active;
    }

    protected function setActive(string $active): self {
        $this->active = '1' === $active;
        return $this;
    }

    protected function getApiKey(): ?string {
        return $this->apiKey;
    }

    protected function setApiKey(string $apiKey): self {
        $this->apiKey = $apiKey;
        return $this;
    }

    protected function getSender(): ?string {
        return $this->sender;
    }

    protected function setSender($sender): self {
        $this->sender = $sender;
        return $this;
    }

    public function sendMessage(?string $from, string $text, string $to): array {
        $to = preg_replace('~[^\d,]~', '', $to); // remove non numeric values

        $result = $this->apiCall($from, $text, $to);
        
        $resultArray = json_decode($result, true);
        if ($resultArray['success'] != 100) {
            return array('code' => stic_Messages::ERROR_NOT_SENT, 'message' => $result);
        }
        if ($resultArray['messages'][0]['success']) {
            return array('code' => stic_Messages::OK, 'message' => 'Message sent');
        }
        else {
            return array('code' => stic_Messages::ERROR_NOT_SENT, 'message' => $result);
        }
    }

    protected function apiCall(?string $from, string $text, string $to): string {
        // if (!$this->getActive()) return [null, null];
        if (!$this->getActive()) return '{"success": "500", "message":"module not active"}';

        $curlOpts = [
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-type: application/json',
                'SentWith: SuiteCRM',
                'X-Api-Key: ' . $this->getApiKey(),
            ],
            CURLOPT_POSTFIELDS => json_encode(compact('from', 'text', 'to')),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 7500,
        ];
        $curl = curl_init('https://gateway.seven.io/api/sms');
        curl_setopt_array($curl, $curlOpts);
        $response = curl_exec($curl);

        if ($response === false) {
            $errorNumber = curl_errno($curl);
            $errorMessage = curl_error($curl);
            $GLOBALS['log']->fatal('Error sending SMS' . __METHOD__ . __LINE__ , $errorNumber, $errorMessage);
            $errorMsg = $errorNumber . '-' . $errorMessage;
            $response = "{'success': 0, 'message': '$errorMsg'}";
        }

        curl_close($curl);

        return $response;
    }
}
