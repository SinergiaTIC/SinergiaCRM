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
        // $this->setActive($sugar_config['seven_active'] ?? false);
        $active = stic_SettingsUtils::getSetting('seven_active');
        $this->setActive($active);
        
        // $this->setApiKey($sugar_config['seven_api_key'] ?? '');
        $apiKey = stic_SettingsUtils::getSetting('seven_api_key');
        $this->setApiKey($apiKey);
        
        
        // $this->setSender($sugar_config['seven_sender'] ?? '');
        $sender = stic_SettingsUtils::getSetting('messages_sender');
        $this->setSender($sender);
    }

    public function getActive(): bool {
        return $this->active;
    }

    public function setActive(string $active): self {
        $this->active = '1' === $active;
        return $this;
    }

    public function getApiKey(): ?string {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): self {
        $this->apiKey = $apiKey;
        return $this;
    }

    public function getSender(): ?string {
        return $this->sender;
    }

    public function setSender($sender): self {
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
            return array('code' => stic_Messages::OK);
        }
        else {
            return array('code' => stic_Messages::ERROR_NOT_SENT, 'message' => $result);
        }
    }

    public function apiCall(?string $from, string $text, string $to): string {
        if (!$this->getActive()) return [null, null];

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
        curl_close($curl);

        return $response;
    }
}
