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
        $currentUser = $GLOBALS['current_user'];
        $timedate    = $GLOBALS['timedate'];

        // i18n helper — falls back to the key name if the label is missing
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
            <style>
                * { box-sizing: border-box; margin: 0; padding: 0; }

                body {
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                    background: #e5ddd5;
                    display: flex;
                    flex-direction: column;
                    height: 100vh;
                    overflow: hidden;
                }

                /* ── Header ── */
                .wa-header {
                    background: #075e54;
                    color: #fff;
                    padding: 14px 16px;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    flex-shrink: 0;
                    box-shadow: 0 2px 4px rgba(0,0,0,.3);
                }
                .wa-header .avatar {
                    width: 40px; height: 40px;
                    background: #25d366;
                    border-radius: 50%;
                    display: flex; align-items: center; justify-content: center;
                    font-size: 18px; font-weight: bold; color: #fff;
                    flex-shrink: 0;
                }
                .wa-header .info .name  { font-size: 16px; font-weight: 600; }
                .wa-header .info .phone { font-size: 12px; opacity: .75; margin-top: 2px; }

                /* ── Conversation area ── */
                .wa-body {
                    flex: 1;
                    overflow-y: auto;
                    padding: 12px 16px;
                    display: flex;
                    flex-direction: column;
                    gap: 6px;
                }

                /* ── Date separator ── */
                .date-separator {
                    text-align: center;
                    margin: 10px 0;
                }
                .date-separator span {
                    background: #fff8c5;
                    border-radius: 8px;
                    padding: 3px 10px;
                    font-size: 11px;
                    color: #555;
                    box-shadow: 0 1px 2px rgba(0,0,0,.15);
                }

                /* ── Bubble base ── */
                .bubble {
                    max-width: 75%;
                    padding: 8px 10px 6px;
                    border-radius: 8px;
                    font-size: 13.5px;
                    line-height: 1.45;
                    position: relative;
                    word-break: break-word;
                    box-shadow: 0 1px 2px rgba(0,0,0,.18);
                }

                /* Outbound (enviado — derecha) */
                .bubble.out {
                    align-self: flex-end;
                    background: #dcf8c6;
                    border-bottom-right-radius: 2px;
                }

                /* Inbound (recibido — izquierda) */
                .bubble.in {
                    align-self: flex-start;
                    background: #fff;
                    border-bottom-left-radius: 2px;
                }

                /* Error */
                .bubble.error {
                    align-self: flex-end;
                    background: #ffe0e0;
                    border-bottom-right-radius: 2px;
                }

                .bubble .text { margin-bottom: 4px; }

                .bubble .meta {
                    display: flex;
                    align-items: center;
                    justify-content: flex-end;
                    gap: 4px;
                    font-size: 10px;
                    color: #888;
                    white-space: nowrap;
                }

                /* Ticks de estado */
                .tick { font-size: 12px; }
                .tick.sent     { color: #aaa; }
                .tick.delivered{ color: #aaa; }
                .tick.read     { color: #34b7f1; }
                .tick.error    { color: #e53935; }

                /* ── Footer (envío) ── */
                .wa-footer {
                    background: #f0f0f0;
                    border-top: 1px solid #ddd;
                    padding: 8px 12px;
                    flex-shrink: 0;
                }
                .footer-inner {
                    display: flex;
                    flex-direction: column;
                    gap: 6px;
                }
                .window-status {
                    font-size: 11px;
                    display: flex;
                    align-items: center;
                    gap: 5px;
                    padding: 3px 6px;
                    border-radius: 6px;
                }
                .window-status.open   { color: #1a7a3a; background: #e6f9ed; }
                .window-status.closed { color: #a00; background: #fdecea; }
                .window-icon { font-size: 10px; }

                .window-closed-hint {
                    font-size: 11px;
                    color: #888;
                    text-align: center;
                    padding: 4px 0 2px;
                }
                .input-row {
                    display: flex;
                    gap: 8px;
                    align-items: flex-end;
                }
                .input-row textarea {
                    flex: 1;
                    border: none;
                    border-radius: 20px;
                    padding: 9px 14px;
                    font-size: 14px;
                    resize: none;
                    outline: none;
                    max-height: 100px;
                    overflow-y: auto;
                    line-height: 1.4;
                }
                .input-row button {
                    background: #25d366;
                    border: none;
                    border-radius: 50%;
                    width: 44px; height: 44px;
                    color: #fff;
                    font-size: 20px;
                    cursor: pointer;
                    display: flex; align-items: center; justify-content: center;
                    flex-shrink: 0;
                    transition: background .2s;
                }
                .input-row button:hover    { background: #1da851; }
                .input-row button:disabled { background: #aaa; cursor: not-allowed; }
                .empty-state {
                    text-align: center;
                    color: #888;
                    margin: auto;
                    font-size: 14px;
                }
                .attach-btn {
                    background: none;
                    border: none;
                    color: #555;
                    font-size: 22px;
                    cursor: pointer;
                    padding: 0 4px;
                    line-height: 44px;
                    flex-shrink: 0;
                }
                .attach-btn:hover { color: #075e54; }

                .attachment-preview {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    background: #fff;
                    border-radius: 10px;
                    padding: 6px 10px;
                    font-size: 12px;
                    color: #333;
                    margin-bottom: 4px;
                }
                .attachment-preview .preview-img {
                    max-height: 48px;
                    max-width: 48px;
                    border-radius: 4px;
                    object-fit: cover;
                }
                .attachment-preview .remove-attach {
                    margin-left: auto;
                    cursor: pointer;
                    color: #e53935;
                    font-size: 16px;
                    line-height: 1;
                }
                .uploading-indicator {
                    font-size: 11px;
                    color: #888;
                    padding: 2px 4px;
                }
                .btn-new-message {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    margin-top: 8px;
                    padding: 9px 18px;
                    background: #075e54;
                    color: #fff;
                    border: none;
                    border-radius: 20px;
                    font-size: 13px;
                    font-weight: 600;
                    cursor: pointer;
                    text-decoration: none;
                    transition: background .2s;
                    align-self: center;
                }
                .btn-new-message:hover { background: #054d44; }
                .btn-new-message .btn-icon { font-size: 16px; }
                .bubble-attachment {
                    margin-top: 6px;
                }
                .attachment-bubble-img {
                    max-width: 220px;
                    max-height: 220px;
                    border-radius: 6px;
                    display: block;
                    object-fit: cover;
                    cursor: pointer;
                }
                .attachment-bubble-video {
                    max-width: 220px;
                    border-radius: 6px;
                    display: block;
                }
                .attachment-bubble-audio {
                    width: 200px;
                    display: block;
                }
                .attachment-bubble-file {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 6px 10px;
                    background: rgba(0,0,0,0.06);
                    border-radius: 8px;
                    font-size: 12px;
                    color: #333;
                    text-decoration: none;
                }
                .attachment-bubble-file:hover {
                    background: rgba(0,0,0,0.12); 
                }
            </style>
        </head>
        <body>

        <!-- Header -->
        <div class="wa-header">
            <div class="avatar"><?= mb_strtoupper(mb_substr($this->parentName, 0, 1)) ?></div>
            <div class="info">
                <div class="name"><?= htmlspecialchars($this->parentName) ?></div>
                <?php if (!empty($this->messages)): ?>
                    <div class="phone"><?= htmlspecialchars($this->messages[0]['phone'] ?? '') ?></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Conversation -->
        <div class="wa-body" id="waBody">
        <?php if (empty($this->messages)): ?>
            <div class="empty-state"><?= $lbl('LBL_CONVERSATION_NO_MESSAGES') ?></div>
        <?php else:
            $lastDate = null;
            foreach ($this->messages as $msg):
                $direction = strtolower($msg['direction'] ?? '');
                $type     = strtolower($msg['type'] ?? '');
                $status   = strtolower($msg['status'] ?? 'sent');
                
                if (empty($direction)) {
                    $direction = ($type === 'whatsapp' || $type === 'received') ? 'inbound' : 'outbound';
                }
                
                $isOut     = ($direction === 'outbound' || $direction === 'out');
                $isError   = ($status === 'error');

                // Fecha del mensaje
                $msgDate   = $timedate->to_display_date($msg['date_entered']);
                $msgTime   = $timedate->to_display_time($msg['date_entered']);

                // Separador de fecha
                if ($msgDate !== $lastDate):
                    $lastDate = $msgDate;
        ?>
            <div class="date-separator"><span><?= htmlspecialchars($msgDate) ?></span></div>
        <?php endif; ?>

            <div class="bubble <?= $isError ? 'error' : ($isOut ? 'out' : 'in') ?>">
                <div class="text"><?= nl2br(htmlspecialchars($msg['message'] ?? '')) ?></div>
                <div class="meta">
                    <span><?= htmlspecialchars($msgTime) ?></span>
                    <?php if ($isOut): ?>
                        <?php
                        $tickClass = 'sent';
                        $tickSymbol = '✓';
                        if ($status === 'delivered') { $tickClass = 'delivered'; $tickSymbol = '✓✓'; }
                        elseif ($status === 'read')  { $tickClass = 'read';      $tickSymbol = '✓✓'; }
                        elseif ($status === 'error') { $tickClass = 'error';     $tickSymbol = '✗'; }
                        ?>
                        <span class="tick <?= $tickClass ?>"><?= $tickSymbol ?></span>
                    <?php endif; ?>
                </div>
            </div>

        <?php endforeach; endif; ?>
        </div>

        <!-- Footer de envío -->
        <div class="wa-footer">
            <?php if ($this->windowOpen): ?>
                <!-- Ventana abierta: permitir envío libre -->
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
                        <button class="attach-btn" onclick="document.getElementById('mediaFile').click()" title="<?= $lbl('LBL_CONVERSATION_ATTACH') ?>">📎</button>
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
                <!-- Ventana cerrada: informar y ofrecer botón al módulo de mensajes -->
                <div class="footer-inner">
                    <div class="window-status closed">
                        <span class="window-icon">🔴</span>
                        <?= htmlspecialchars($this->windowMessage) ?>
                    </div>
                    <div class="window-closed-hint">
                        <?= $lbl('LBL_CONVERSATION_WINDOW_CLOSED_HINT') ?>
                    </div>
                    <?php if (!empty($this->newMessageUrl)): ?>
                    <a href="<?= htmlspecialchars($this->newMessageUrl) ?>" class="btn-new-message">
                        <span class="btn-icon">✉</span>
                        <?= $lbl('LBL_CONVERSATION_NEW_MESSAGE_BTN') ?>
                    </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <script>
            var _pendingMediaNoteId = null;
            var _pendingMediaMime = null;

            // Scroll al final al cargar
            (function() {
                document.getElementById('waBody').scrollTop = 9999999;
            })();

            function handleFileSelected(input) {
                if (!input.files || !input.files[0]) return;
                var file = input.files[0];

                document.getElementById('uploadingIndicator').style.display = 'block';
                document.getElementById('attachmentPreview').style.display  = 'none';

                var formData = new FormData();
                formData.append('module', 'stic_Messages');
                formData.append('action', 'uploadConversationMedia');
                formData.append('media',  file);

                fetch('index.php', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    document.getElementById('uploadingIndicator').style.display = 'none';
                    if (!data.success) {
                        alert(SUGAR.language.get('stic_Messages', 'LBL_CONVERSATION_ERROR_UPLOAD') + ': ' + (data.error || SUGAR.language.get('stic_Messages', 'LBL_CONVERSATION_ERROR_UNKNOWN')));
                        input.value = '';
                        return;
                    }
                    _pendingMediaNoteId = data.media_note_id;
                    _pendingMediaName = data.name;
                    _pendingMediaMime = data.mime;
                    showAttachmentPreview(file, data.name);
                })
                .catch(function(err) {
                    document.getElementById('uploadingIndicator').style.display = 'none';
                    alert(SUGAR.language.get('stic_Messages', 'LBL_CONVERSATION_ERROR_UPLOAD') + ': ' + err);
                    input.value = '';
                });
            }

            function showAttachmentPreview(file, name) {
                var preview = document.getElementById('attachmentPreview');
                var img     = document.getElementById('previewImg');
                var icon    = document.getElementById('previewIcon');

                document.getElementById('previewName').textContent = name;

                if (file.type.startsWith('image/')) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                        img.style.display  = 'block';
                        icon.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                } else {
                    img.style.display  = 'none';
                    icon.style.display = 'block';
                }

                preview.style.display = 'flex';
            }

            function removeAttachment() {
                _pendingMediaNoteId = null;
                _pendingMediaName = null;
                _pendingMediaMime = null;
                document.getElementById('mediaFile').value           = '';
                document.getElementById('attachmentPreview').style.display = 'none';
                document.getElementById('previewImg').src            = '';
            }
            function sendMessage() {
                var text    = document.getElementById('msgText').value.trim();
                var sendBtn = document.getElementById('sendBtn');

                if (!text && !_pendingMediaNoteId) return;

                sendBtn.disabled = true;

                var formData = new FormData();
                formData.append('module',      'stic_Messages');
                formData.append('action',      'Save');
                formData.append('type',        'WhatsAppHelper');
                formData.append('status',      'sent');
                formData.append('message',     text);
                formData.append('parent_type', '<?= addslashes($this->parentType) ?>');
                formData.append('parent_id',   '<?= addslashes($this->parentId) ?>');

                if (_pendingMediaNoteId) {
                    formData.append('media_note_id',   _pendingMediaNoteId);
                    formData.append('media_note_name',  _pendingMediaName);
                    formData.append('media_note_mime',  _pendingMediaMime);
                }

                fetch('index.php', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                })
                .then(function(r) { return r.text(); })
                .then(function() { location.reload(); })
                .catch(function(err) {
                    alert(SUGAR.language.get('stic_Messages', 'LBL_CONVERSATION_ERROR_SEND') + ': ' + err);
                    sendBtn.disabled = false;
                });
            }
        </script>

        </body>
        </html>
        <?php
    }
}