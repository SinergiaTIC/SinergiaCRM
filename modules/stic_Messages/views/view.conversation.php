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

class stic_MessagesViewConversation extends SugarView {

    public $messages = [];
    public $parentName = '';
    public $parentId = '';
    public $parentType = '';
    public $contactPhone = '';
    public $windowOpen = false;
    public $windowMessage = '';
    public $newMessageUrl = '';
    public $modStrings = [];
    public $pollDelay = 5000;

    public function display() {
        global $sugar_config, $timedate;

        if (empty($this->ss)) {
            $this->_initSmarty();
        }

        $messages = $this->prepareMessages($this->messages, $timedate);

        $parentNameInitial = '';
        if (!empty($this->parentName)) {
            $parentNameInitial = mb_strtoupper(mb_substr($this->parentName, 0, 1));
        }

        $lastMessageDate = '';
        if (!empty($this->messages)) {
            $lastMsg = end($this->messages);
            $lastMessageDate = $lastMsg['date_entered'] ?? '';
        }

        $pollDelay = $sugar_config['stic_conversation_poll_delay'] ?? 5000;

        $this->ss->assign('messages', $messages);
        $this->ss->assign('parentName', $this->parentName);
        $this->ss->assign('parentNameInitial', $parentNameInitial);
        $this->ss->assign('parentId', $this->parentId);
        $this->ss->assign('parentType', $this->parentType);
        $this->ss->assign('windowOpen', $this->windowOpen);
        $this->ss->assign('windowMessage', $this->windowMessage);
        $this->ss->assign('newMessageUrl', $this->newMessageUrl);
        $this->ss->assign('MOD', $this->modStrings);
        $this->ss->assign('pollDelay', $pollDelay);
        $this->ss->assign('lastMessageDate', $lastMessageDate);
        $this->ss->assign('conversationScriptUrl', getJSPath('modules/stic_Messages/include/ConversationView/ConversationView.js'));

        $this->ss->display('modules/stic_Messages/include/ConversationView/ConversationView.tpl');
    }

    /**
     * Prepare messages with formatted dates, notes and UI flags for the template.
     *
     * @param array $messages Raw messages array
     * @param object $timedate TimeDate instance
     * @return array Prepared messages
     */
    protected function prepareMessages(array $messages, $timedate) {
        if (empty($messages)) {
            return [];
        }

        // Fetch notes for all messages
        $messageIds = array_column($messages, 'id');
        $notesByMessage = [];
        if (!empty($messageIds)) {
            $db = DBManagerFactory::getInstance();
            $idList = implode("','", array_map([$db, 'quote'], $messageIds));
            $notesSql = "SELECT id, parent_id, name, filename, file_mime_type FROM notes WHERE parent_id IN ('{$idList}') AND deleted = 0";
            $notesResult = $db->query($notesSql);
            while ($note = $db->fetchByAssoc($notesResult)) {
                $note['url'] = 'index.php?module=Notes&action=DetailView&record=' . $note['id'];
                $note['is_image'] = (strpos($note['file_mime_type'], 'image/') === 0);
                $notesByMessage[$note['parent_id']][] = $note;
            }
        }

        $lastDate = null;
        $prepared = [];

        foreach ($messages as $msg) {
            $direction = strtolower($msg['direction'] ?? '');
            $status = strtolower($msg['status'] ?? 'sent');

            if (empty($direction)) {
                $direction = 'outbound';
            }

            $isOut = ($direction === 'outbound' || $direction === 'out');
            $isError = ($status === 'error');

            $msgDate = $timedate->to_display_date($msg['date_entered']);
            $msgTime = $timedate->to_display_time($msg['date_entered']);

            $showDateSeparator = false;
            if ($msgDate !== $lastDate) {
                $lastDate = $msgDate;
                $showDateSeparator = true;
            }

            // Ticks
            $tickClass = 'sent';
            $tickSymbol = '✓';
            if ($status === 'delivered') {
                $tickClass = 'delivered';
                $tickSymbol = '✓✓';
            } elseif ($status === 'read') {
                $tickClass = 'read';
                $tickSymbol = '✓✓';
            } elseif ($status === 'error') {
                $tickClass = 'error';
                $tickSymbol = '✗';
            }

            $prepared[] = [
                'id' => $msg['id'],
                'message' => $msg['message'] ?? '',
                'direction' => $direction,
                'status' => $status,
                'is_out' => $isOut,
                'is_error' => $isError,
                'display_date' => $msgDate,
                'display_time' => $msgTime,
                'show_date_separator' => $showDateSeparator,
                'notes' => $notesByMessage[$msg['id']] ?? [],
                'tick_class' => $tickClass,
                'tick_symbol' => $tickSymbol,
                'phone' => $msg['phone'] ?? '',
            ];
        }

        return $prepared;
    }
}
