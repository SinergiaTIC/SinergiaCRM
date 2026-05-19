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

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class stic_MessagesViewConversation extends SugarView {

    public $messages      = [];
    public $parentName    = '';
    public $parentId      = '';
    public $parentType    = '';
    public $contactPhone  = '';
    public $windowOpen    = false;
    public $windowMessage = '';
    public $newMessageUrl = '';
    public $modStrings    = [];

    public function display() {
        $timedate = $GLOBALS['timedate'];

        $lbl = function(string $key): string {
            return htmlspecialchars($this->modStrings[$key] ?? $key);
        };
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?= $lbl('LBL_CONVERSATION_TITLE') ?> — <?= htmlspecialchars($this->parentName) ?></title>
        </head>
        <body>

        <div class="wa-header">
            <div class="avatar"><?= mb_strtoupper(mb_substr($this->parentName, 0, 1)) ?></div>
            <div class="info">
                <div class="name"><?= htmlspecialchars($this->parentName) ?></div>
                <?php if (!empty($this->messages)): ?>
                    <div class="phone"><?= htmlspecialchars($this->messages[0]['phone'] ?? '') ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="wa-body" id="waBody">
        <?php if (empty($this->messages)): ?>
            <div class="empty-state"><?= $lbl('LBL_CONVERSATION_NO_MESSAGES') ?></div>
        <?php else:
            $messageIds = array_column($this->messages, 'id');
            $notesByMessage = [];
            if (!empty($messageIds)) {
                $db = DBManagerFactory::getInstance();
                $idList = implode("','", array_map([$db, 'quote'], $messageIds));
                $notesSql = "SELECT id, parent_id, name, filename, file_mime_type FROM notes WHERE parent_id IN ('{$idList}') AND deleted = 0";
                $notesResult = $db->query($notesSql);
                while ($note = $db->fetchByAssoc($notesResult)) {
                    $notesByMessage[$note['parent_id']][] = $note;
                }
            }

            $lastDate = null;
            foreach ($this->messages as $msg):
                $direction = strtolower($msg['direction'] ?? '');
                $type = strtolower($msg['type'] ?? '');
                $status = strtolower($msg['status'] ?? 'sent');

                if (empty($direction)) {
                    $direction = ($type === 'whatsapp' || $type === 'received') ? 'inbound' : 'outbound';
                }

                $isOut = ($direction === 'outbound' || $direction === 'out');
                $isError = ($status === 'error');
                $msgNotes = $notesByMessage[$msg['id']] ?? [];

                $msgDate = $timedate->to_display_date($msg['date_entered']);
                $msgTime = $timedate->to_display_time($msg['date_entered']);

                if ($msgDate !== $lastDate):
                    $lastDate = $msgDate;
        ?>
            <div class="date-separator"><span><?= htmlspecialchars($msgDate) ?></span></div>
        <?php endif; ?>

            <div class="bubble <?= $isError ? 'error' : ($isOut ? 'out' : 'in') ?>">
                <div class="text"><?= nl2br(htmlspecialchars($msg['message'] ?? '')) ?></div>
                <?php if (!empty($msgNotes)): ?>
                    <?php foreach ($msgNotes as $note): ?>
                        <?php
                        $isImage = strpos($note['file_mime_type'], 'image/') === 0;
                        $noteUrl = 'index.php?module=Notes&action=DetailView&record=' . $note['id'];
                        ?>
                        <?php if ($isImage): ?>
                            <div class="bubble-attachment">
                                <img class="attachment-bubble-img" src="upload/<?= $note['id'] ?>" onclick="window.open('<?= $noteUrl ?>')">
                            </div>
                        <?php else: ?>
                            <div class="bubble-attachment">
                                <a class="attachment-bubble-file" href="upload/<?= $note['id'] ?>" target="_blank">
                                    📄 <?= htmlspecialchars($note['filename'] ?? $note['name']) ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div class="meta">
                    <span><?= htmlspecialchars($msgTime) ?></span>
                    <?php if ($isOut): ?>
                        <?php
                        $tickClass = 'sent';
                        $tickSymbol = '✓';
                        if ($status === 'delivered') { $tickClass = 'delivered'; $tickSymbol = '✓✓'; }
                        elseif ($status === 'read') { $tickClass = 'read'; $tickSymbol = '✓✓'; }
                        elseif ($status === 'error') { $tickClass = 'error'; $tickSymbol = '✗'; }
                        ?>
                        <span class="tick <?= $tickClass ?>"><?= $tickSymbol ?></span>
                    <?php endif; ?>
                </div>
            </div>

        <?php endforeach; endif; ?>
        </div>

        <div class="wa-footer">
            <?php if ($this->windowOpen): ?>
                <div class="footer-inner">
                    <div class="window-status open">
                        <span class="window-icon">🟢</span>
                        <?= htmlspecialchars($this->windowMessage) ?>
                    </div>
                    <div id="attachmentPreview" style="display:none;" class="attachment-preview">
                        <img id="previewImg" class="preview-img" style="display:none;">
                        <span id="previewIcon" style="font-size:24px;display:none;">📄</span>
                        <span id="previewName"></span>
                        <span class="remove-attach" onclick="removeAttachment()" title="<?= $lbl('LBL_CONVERSATION_REMOVE_ATTACHMENT') ?>">✕</span>
                    </div>
                    <div id="uploadingIndicator" class="uploading-indicator" style="display:none;">⏳ <?= $lbl('LBL_CONVERSATION_UPLOADING') ?></div>
                    <div class="input-row">
                        <input type="file" id="mediaFile" style="display:none;"
                            accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/3gpp,audio/ogg,audio/mpeg,audio/mp4,audio/amr,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                            onchange="handleFileSelected(this)">
                        <button class="attach-btn" onclick="document.getElementById('mediaFile').click()" title="<?= $lbl('LBL_ATTACHMENT') ?>">📎</button>
                        <textarea id="msgText"
                            placeholder="<?= $lbl('LBL_CONVERSATION_PLACEHOLDER') ?>"
                            rows="1"
                            onInput="this.style.height='auto';this.style.height=this.scrollHeight+'px';"
                            onKeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendMessage();}">
                        </textarea>
                        <button id="sendBtn" onclick="sendMessage()" title="<?= $lbl('LBL_CONVERSATION_SEND') ?>">➤</button>
                    </div>
                </div>
            <?php else: ?>
                <div class="footer-inner">
                    <div class="window-status closed">
                        <span class="window-icon">🔴</span>
                        <?= htmlspecialchars($this->windowMessage) ?>
                    </div>
                    <div class="window-closed-hint">
                        <?= $lbl('LBL_CONVERSATION_WINDOW_CLOSED_HINT') ?>
                    </div>
                    <?php if (!empty($this->newMessageUrl)): ?>
                    <a href="<?= htmlspecialchars($this->newMessageUrl) ?>" class="btn-new-message"
                       onclick="window.opener.location.href=this.href;window.close();return false;">
                        <span class="btn-icon">✉</span>
                        <?= $lbl('LBL_CONVERSATION_NEW_MESSAGE_BTN') ?>
                    </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <script>
            var CONVERSATION = {
                parentType: '<?= addslashes($this->parentType) ?>',
                parentId:   '<?= addslashes($this->parentId) ?>'
            };
        </script>
        <?= getVersionedScript('modules/stic_Messages/include/ConversationView/ConversationView.js') ?>

        </body>
        </html>
        <?php
    }
}
