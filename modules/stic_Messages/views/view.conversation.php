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

    public function display() {
        $currentUser = $GLOBALS['current_user'];
        $timedate    = $GLOBALS['timedate'];
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Conversación WhatsApp — <?= htmlspecialchars($this->parentName) ?></title>
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
            <div class="empty-state">No hay mensajes WhatsApp con este contacto.</div>
        <?php else:
            $lastDate = null;
            foreach ($this->messages as $msg):
                $direction = strtolower($msg['direction'] ?? 'outbound');
                $status    = strtolower($msg['status']    ?? 'sent');
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
                    <div class="input-row">
                        <textarea id="msgText"
                            placeholder="Escribe un mensaje..."
                            rows="1"
                            onInput="this.style.height='auto';this.style.height=this.scrollHeight+'px';"
                            onKeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendMessage();}">
                        </textarea>
                        <button id="sendBtn" onclick="sendMessage()" title="Enviar">➤</button>
                    </div>
                </div>
            <?php else: ?>
                <!-- Ventana cerrada: solo informar -->
                <div class="footer-inner">
                    <div class="window-status closed">
                        <span class="window-icon">🔴</span>
                        <?= htmlspecialchars($this->windowMessage) ?>
                    </div>
                    <div class="window-closed-hint">
                        Para iniciar conversación usa una plantilla verificada desde el módulo de mensajes.
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <script>
        // Scroll al final al cargar
        (function() {
            var body = document.getElementById('waBody');
            body.scrollTop = body.scrollHeight;
        })();

        function sendMessage() {
            var text    = document.getElementById('msgText').value.trim();
            var sendBtn = document.getElementById('sendBtn');
            if (!text) return;

            sendBtn.disabled = true;

            var formData = new FormData();
            formData.append('module',      'stic_Messages');
            formData.append('action',      'Save');
            formData.append('type',        'WhatsAppHelper');
            formData.append('direction',   'outbound');
            formData.append('status',      'sent');
            formData.append('message',     text);
            formData.append('parent_type', '<?= addslashes($this->parentType) ?>');
            formData.append('parent_id',   '<?= addslashes($this->parentId) ?>');
            formData.append('sugar_token', '<?= $_SESSION['sugar_token'] ?? '' ?>');

            fetch('index.php', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(function(r) { return r.text(); })
            .then(function() {
                // Recargar la conversación para mostrar el nuevo mensaje
                location.reload();
            })
            .catch(function(err) {
                alert('Error al enviar: ' + err);
                sendBtn.disabled = false;
            });
        }
        </script>

        </body>
        </html>
        <?php
    }
}
