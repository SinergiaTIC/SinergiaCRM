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
    /**
     * Processes the Twilio webhook
     */
    public function run()
    {
        try {
            // Initial log for debugging
            $GLOBALS['log']->info('WhatsAppWebhookEntryPoint: Processing started');
            $GLOBALS['log']->info('WhatsAppWebhookEntryPoint: POST data = ' . print_r($_POST, true));

            // Validate that necessary data is present
            if (!$this->validateWebhookData()) {
                $GLOBALS['log']->error('WhatsAppWebhookEntryPoint: Invalid webhook data');
                $this->sendResponse(400, 'Invalid webhook data');
                return;
            }

            // Extract data from Twilio webhook
            $messageSid = $_POST['MessageSid'] ?? '';
            $from = $this->cleanPhoneNumber($_POST['From'] ?? '');
            $to = $this->cleanPhoneNumber($_POST['To'] ?? '');
            $body = $_POST['Body'] ?? '';
            $smsStatus = $_POST['SmsStatus'] ?? '';
            $numMedia = intval($_POST['NumMedia'] ?? 0);

            $GLOBALS['log']->info("WhatsAppWebhookEntryPoint: From=$from, To=$to, Body=$body");

            // Search for related record by phone number in multiple modules
            $parentInfo = $this->findContactByPhone($from);
            
            if (!$parentInfo) {
                $GLOBALS['log']->warn("WhatsAppWebhookEntryPoint: No related record found for phone: $from");
                // You can decide whether to create an orphaned message or ignore it
                // For now, we'll create the message without relationship
            }

            // Create the incoming message record
            $message = BeanFactory::newBean('stic_Messages');
            $message->name = $this->generateMessageName($parentInfo, $from);
            $message->type = 'WhatsApp';
            $message->direction = 'inbound';
            $message->phone = $from;
            $message->sender = $from;
            $message->message = $body;
            $message->status = 'received';
            $message->response = "MessageSid: $messageSid";
            $message->sent_date = $GLOBALS['timedate']->nowDb();
            
            // Relate to the found record (Contact, User, Account, Employee, or Lead) if found
            if ($parentInfo) {
                $message->parent_type = $parentInfo['module'];
                $message->parent_id = $parentInfo['id'];
            }

            // Save the message
            $messageId = $message->save();

            $GLOBALS['log']->info("WhatsAppWebhookEntryPoint: Message saved with ID: $messageId");

            // Respond to Twilio with empty TwiML (200 OK)
            $this->sendTwiMLResponse();

        } catch (Exception $e) {
            $GLOBALS['log']->error('WhatsAppWebhookEntryPoint: Error - ' . $e->getMessage());
            $GLOBALS['log']->error('WhatsAppWebhookEntryPoint: Stack trace - ' . $e->getTraceAsString());
            $this->sendResponse(500, 'Internal server error');
        }
    }

    /**
     * Validates that webhook data is valid
     */
    private function validateWebhookData()
    {
        // Minimum required fields by Twilio
        $requiredFields = ['MessageSid', 'From', 'Body'];
        
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                $GLOBALS['log']->error("WhatsAppWebhookEntryPoint: Required field missing: $field");
                return false;
            }
        }
        
        return true;
    }

    /**
     * Cleans phone number by removing whatsapp: prefix
     */
    private function cleanPhoneNumber($phone)
    {
        // Twilio sends numbers as "whatsapp:+34636642141"
        $phone = str_replace('whatsapp:', '', $phone);
        $phone = trim($phone);
        return $phone;
    }

    /**
     * Searches for a contact, user, account, employee or lead by phone number
     * Returns array with 'module' and 'id', or null if not found
     */
    private function findContactByPhone($phone)
    {
        global $db;
        
        // Clean the number for search (remove non-numeric characters except +)
        $cleanPhone = preg_replace('/[^0-9+]/', '', $phone);
        
        $modulesToSearch = [
            'Contacts',
            'Users',
            'Accounts',
            'Employees',
            'Leads'
        ];
        
        
        $GLOBALS['log']->info("WhatsAppWebhookEntryPoint: Searching phone in modules: " . implode(', ', $modulesToSearch));
        
        // Search in each module until a match is found
        foreach ($modulesToSearch as $module) {
            $result = $this->searchPhoneInModule($module, $cleanPhone);
            if ($result) {
                $GLOBALS['log']->info("WhatsAppWebhookEntryPoint: Found record in $module with ID: {$result['id']}");
                return $result;
            }
        }
        
        $GLOBALS['log']->warn("WhatsAppWebhookEntryPoint: No record found in any module for phone: $phone");
        return null;
    }

    /**
     * Searches for a record in a specific module by phone number
     */
    private function searchPhoneInModule($module, $phone)
    {
        global $db;
        
        $bean = BeanFactory::newBean($module);
        if (!$bean) {
            return null;
        }
        
        $tableName = $bean->table_name;
        
        // Common phone fields
        $phoneFields = ['phone_mobile', 'phone_work', 'phone_home', 'phone_other', 'phone_fax'];
        
        // Build WHERE conditions
        $conditions = array();
        foreach ($phoneFields as $field) {
            // Check if field exists in bean definition
            if (isset($bean->field_defs[$field])) {
                // Clean phone for search (numbers only)
                $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                $conditions[] = "REPLACE(REPLACE(REPLACE(REPLACE({$field}, ' ', ''), '-', ''), '.', ''), '+', '') LIKE '%{$cleanPhone}%'";
            }
        }
        
        if (empty($conditions)) {
            $GLOBALS['log']->warn("WhatsAppWebhookEntryPoint: No phone fields found in module $module");
            return null;
        }
        
        $whereClause = implode(' OR ', $conditions);
        
        $sql = "SELECT id FROM {$tableName} WHERE deleted = 0 AND ({$whereClause}) LIMIT 1";
        
        $GLOBALS['log']->info("WhatsAppWebhookEntryPoint: Searching in $module with SQL: $sql");
        
        $result = $db->query($sql);
        
        if ($row = $db->fetchByAssoc($result)) {
            $GLOBALS['log']->info("WhatsAppWebhookEntryPoint: Found $module with ID: {$row['id']}");
            return array(
                'module' => $module,
                'id' => $row['id']
            );
        }
        
        return null;
    }

    /**
     * Generates a descriptive name for the message
     */
    private function generateMessageName($parentInfo, $from)
    {
        global $app_strings;
        
        $name = $app_strings['LBL_WHATSAPP_INCOMING_MESSAGE'] ?? 'Incoming WhatsApp message';
        
        if ($parentInfo) {
            $bean = BeanFactory::getBean($parentInfo['module'], $parentInfo['id']);
            if ($bean) {
                $name = $bean->name . ' - ' . $GLOBALS['timedate']->nowDb();
            }
        } else {
            $name .= ' ' . ($app_strings['LBL_FROM'] ?? 'from') . ' ' . $from . ' - ' . $GLOBALS['timedate']->nowDb();
        }
        
        return $name;
    }

    /**
     * Sends a valid TwiML response to Twilio
     */
    private function sendTwiMLResponse()
    {
        // If in test mode (called from a simulator), don't send headers or exit
        if (defined('WEBHOOK_TEST_MODE') && WEBHOOK_TEST_MODE === true) {
            return;
        }
        
        header('Content-Type: text/xml');
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response></Response>';
        exit;
    }

    /**
     * Sends an HTTP response with status code
     */
    private function sendResponse($code, $message)
    {
        // If in test mode, only log
        if (defined('WEBHOOK_TEST_MODE') && WEBHOOK_TEST_MODE === true) {
            $GLOBALS['log']->error("WhatsAppWebhookEntryPoint: Response $code - $message");
            return;
        }
        
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => $code >= 200 && $code < 300,
            'message' => $message
        ));
        exit;
    }
}