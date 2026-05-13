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

/* ── Inject CSS ── */
(function() {
    var css = '*{box-sizing:border-box;margin:0;padding:0}' +
        'body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;background:#e5ddd5;display:flex;flex-direction:column;height:100vh;overflow:hidden}' +
        '.wa-header{background:#075e54;color:#fff;padding:14px 16px;display:flex;align-items:center;gap:12px;flex-shrink:0;box-shadow:0 2px 4px rgba(0,0,0,.3)}' +
        '.wa-header .avatar{width:40px;height:40px;background:#25d366;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:bold;color:#fff;flex-shrink:0}' +
        '.wa-header .info .name{font-size:16px;font-weight:600}' +
        '.wa-header .info .phone{font-size:12px;opacity:.75;margin-top:2px}' +
        '.wa-body{flex:1;overflow-y:auto;padding:12px 16px;display:flex;flex-direction:column;gap:6px}' +
        '.date-separator{text-align:center;margin:10px 0}' +
        '.date-separator span{background:#fff8c5;border-radius:8px;padding:3px 10px;font-size:11px;color:#555;box-shadow:0 1px 2px rgba(0,0,0,.15)}' +
        '.bubble{max-width:75%;padding:8px 10px 6px;border-radius:8px;font-size:13.5px;line-height:1.45;position:relative;word-break:break-word;box-shadow:0 1px 2px rgba(0,0,0,.18)}' +
        '.bubble.out{align-self:flex-end;background:#dcf8c6;border-bottom-right-radius:2px}' +
        '.bubble.in{align-self:flex-start;background:#fff;border-bottom-left-radius:2px}' +
        '.bubble.error{align-self:flex-end;background:#ffe0e0;border-bottom-right-radius:2px}' +
        '.bubble .text{margin-bottom:4px}' +
        '.bubble .meta{display:flex;align-items:center;justify-content:flex-end;gap:4px;font-size:10px;color:#888;white-space:nowrap}' +
        '.tick{font-size:12px}' +
        '.tick.sent{color:#aaa}' +
        '.tick.delivered{color:#aaa}' +
        '.tick.read{color:#34b7f1}' +
        '.tick.error{color:#e53935}' +
        '.wa-footer{background:#f0f0f0;border-top:1px solid #ddd;padding:8px 12px;flex-shrink:0}' +
        '.footer-inner{display:flex;flex-direction:column;gap:6px}' +
        '.window-status{font-size:11px;display:flex;align-items:center;gap:5px;padding:3px 6px;border-radius:6px}' +
        '.window-status.open{color:#1a7a3a;background:#e6f9ed}' +
        '.window-status.closed{color:#a00;background:#fdecea}' +
        '.window-icon{font-size:10px}' +
        '.window-closed-hint{font-size:11px;color:#888;text-align:center;padding:4px 0 2px}' +
        '.input-row{display:flex;gap:8px;align-items:flex-end}' +
        '.input-row textarea{flex:1;border:none;border-radius:20px;padding:9px 14px;font-size:14px;resize:none;outline:none;max-height:100px;overflow-y:auto;line-height:1.4}' +
        '.input-row button{background:#25d366;border:none;border-radius:50%;width:44px;height:44px;color:#fff;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .2s}' +
        '.input-row button:hover{background:#1da851}' +
        '.input-row button:disabled{background:#aaa;cursor:not-allowed}' +
        '.empty-state{text-align:center;color:#888;margin:auto;font-size:14px}' +
        '.attach-btn{background:none;border:none;color:#555;font-size:22px;cursor:pointer;padding:0 4px;line-height:44px;flex-shrink:0}' +
        '.attach-btn:hover{color:#075e54}' +
        '.attachment-preview{display:flex;align-items:center;gap:8px;background:#fff;border-radius:10px;padding:6px 10px;font-size:12px;color:#333;margin-bottom:4px}' +
        '.attachment-preview .preview-img{max-height:48px;max-width:48px;border-radius:4px;object-fit:cover}' +
        '.attachment-preview .remove-attach{margin-left:auto;cursor:pointer;color:#e53935;font-size:16px;line-height:1}' +
        '.uploading-indicator{font-size:11px;color:#888;padding:2px 4px}' +
        '.btn-new-message{display:inline-flex;align-items:center;gap:6px;margin-top:8px;padding:9px 18px;background:#075e54;color:#fff;border:none;border-radius:20px;font-size:13px;font-weight:600;cursor:pointer;text-decoration:none;transition:background .2s;align-self:center}' +
        '.btn-new-message:hover{background:#054d44}' +
        '.btn-new-message .btn-icon{font-size:16px}' +
        '.bubble-attachment{margin-top:6px}' +
        '.attachment-bubble-img{max-width:220px;max-height:220px;border-radius:6px;display:block;object-fit:cover;cursor:pointer}' +
        '.attachment-bubble-file{display:inline-flex;align-items:center;gap:6px;padding:6px 10px;background:rgba(0,0,0,.06);border-radius:8px;font-size:12px;color:#333;text-decoration:none}' +
        '.attachment-bubble-file:hover{background:rgba(0,0,0,.12)}';
    var style = document.createElement('style');
    style.textContent = css;
    document.head.appendChild(style);
})();

/**
 * Opens a WhatsApp conversation window for a given record.
 * Can be called from any module included in stic_Messages messageable modules.
 *
 * @param {String} recordId   The bean id
 * @param {String} parentType The module name (Contacts, Leads, Accounts, Employees...)
 */
function openWhatsAppConversation(recordId, parentType) {
    var parentName = $("#formDetailView input[type=hidden][name=full_name]").val()
                  || $("h1.module-title-text").text().trim()
                  || '';
    var url = 'index.php?module=stic_Messages&action=conversation'
            + '&parent_id='   + encodeURIComponent(recordId)
            + '&parent_type=' + encodeURIComponent(parentType)
            + '&parent_name=' + encodeURIComponent(parentName);
    window.open(
        url,
        'whatsapp_conv_' + recordId,
        'width=480,height=700,resizable=yes,scrollbars=yes'
    );
}

/* ── Conversation View (popup) ── */

(function() {
    var waBody = document.getElementById('waBody');
    if (waBody) {
        waBody.scrollTop = 9999999;
    }
})();

var _pendingMediaNoteId = null;
var _pendingMediaName = null;
var _pendingMediaMime = null;

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

    if (!text && !_pendingMediaNoteId) {
        return;
    }

    sendBtn.disabled = true;

    var formData = new FormData();
    formData.append('module',      'stic_Messages');
    formData.append('action',      'Save');
    formData.append('type',        'WhatsAppHelper');
    formData.append('status',      'sent');
    formData.append('message',     text);
    formData.append('parent_type', CONVERSATION.parentType);
    formData.append('parent_id',   CONVERSATION.parentId);

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
