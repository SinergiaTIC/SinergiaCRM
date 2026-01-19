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

/**
 * Helper para enviar mensajes de WhatsApp usando Twilio
 * * Este helper se integra con el sistema existente de stic_Messages
 */

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

    /**
     * Enviar mensaje de WhatsApp
     *
     * @param string|null $sender No se usa (Twilio usa su número configurado)
     * @param string $message Cuerpo del mensaje O el ContentSid (si empieza por HX)
     * @param string $phone Número de destino
     * @return array
     */
    public function sendMessage(?string $sender, string $message, string $phone): array
    {
        if (!$this->isConfigured()) {
            return [
                'code' => stic_Messages::ERROR_NOT_SENT,
                'message' => 'Configuración de Twilio incompleta.'
            ];
        }

        if (empty($phone) || empty($message)) {
            return [
                'code' => stic_Messages::ERROR_NOT_SENT,
                'message' => 'Teléfono o mensaje vacíos.'
            ];
        }

        try {
            $from = 'whatsapp:' . $this->twilioNumber;
            $to = 'whatsapp:' . $this->formatPhoneNumber($phone);

            // Preparar datos base
            $postData = [
                'From' => $from,
                'To' => $to
            ];

            /**
             * Lógica de Plantilla vs Mensaje Directo:
             * Si el mensaje comienza con 'HX' (prefijo de Twilio ContentSid),
             * lo enviamos como plantilla de WhatsApp.
             */
            if (strpos($message, 'HX') === 0) {
                $postData['ContentSid'] = $message;
                // Nota: Si necesitas variables dinámicas, podrías pasar un JSON en el mensaje 
                // y decodificarlo aquí para llenar 'ContentVariables'.
            } else {
                $postData['Body'] = $message;
            }

            $url = $this->apiUrl . '/Accounts/' . $this->sid . '/Messages.json';
            $result = $this->makeTwilioRequest($url, $postData);

            if ($result['success']) {
                $GLOBALS['log']->info('WhatsApp enviado. SID: ' . ($result['data']['sid'] ?? 'N/A'));
                
                return [
                    'code' => stic_Messages::OK,
                    'message' => 'Enviado correctamente',
                    'twilio_sid' => $result['data']['sid'] ?? '',
                    'status' => $result['data']['status'] ?? 'sent'
                ];
            }

            return [
                'code' => stic_Messages::ERROR_NOT_SENT,
                'message' => 'Error Twilio: ' . $result['error']
            ];

        } catch (Exception $e) {
            $GLOBALS['log']->error('WhatsApp Exception: ' . $e->getMessage());
            return [
                'code' => stic_Messages::ERROR_NOT_SENT,
                'message' => $e->getMessage()
            ];
        }
    }

    private function makeTwilioRequest($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_USERPWD, $this->sid . ':' . $this->token);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return ['success' => false, 'error' => $curlError];
        }

        $responseData = json_decode($response, true);

        if ($httpCode >= 200 && $httpCode < 300) {
            return ['success' => true, 'data' => $responseData];
        }

        $errorMessage = $responseData['message'] ?? 'Error desconocido';
        return ['success' => false, 'error' => $errorMessage];
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