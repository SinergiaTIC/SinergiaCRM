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

require_once 'include/MVC/View/views/view.edit.php';
require_once 'SticInclude/Views.php';
require_once 'modules/stic_Messages/Utils.php';
require_once('modules/stic_Settings/Utils.php');
class stic_MessagesViewEdit extends ViewEdit
{

    public function __construct()
    {
        parent::__construct();
        $this->useForSubpanel = true;
        $this->useModuleQuickCreateTemplate = true;

        // $this->type = 'compose';
        if(!empty($_GET['in_popup'])&& $_GET['in_popup'] == '1' ||
           !empty($_POST['in_popup'])&& $_POST['in_popup'] == '1'){
            $this->options['show_title'] = false;
            $this->options['show_header'] = false;
            $this->options['show_footer'] = false;
            $this->options['show_javascript'] = false;
            $this->options['show_subpanels'] = false;
            $this->options['show_search'] = false;
        }
    }


    public function preDisplay()
    {
        global $app_list_strings;
        parent::preDisplay();

        SticViews::preDisplay($this);
        stic_MessagesUtils::fillDynamicListMessageTemplate();

        $this->ev->ss->assign('IS_MODAL', isset($_REQUEST['in_popup']) ? $_REQUEST['in_popup'] : false);

        $this->bean->parent_type = !empty($this->bean->parent_type) ? $this->bean->parent_type : ($_REQUEST['relatedModule']??'');
	    $this->bean->parent_id = !empty($this->bean->parent_id)? $this->bean->parent_id : ($_REQUEST['relatedId'] ?? null);
        $this->bean->fill_in_additional_parent_fields();

        $this->bean->sender = stic_SettingsUtils::getSetting('messages_sender') ?? '';

        if (!$this->bean->fetched_row) {
            unset($app_list_strings['stic_messages_status_list']['error']);
        }

        // Write here you custom code

    }

    public function display()
    {
        global $mod_strings;
        $this->bean->info = "<p class='msg-warning'><span style='font-style: italic;'>⚠️{$mod_strings['LBL_INFO_TXT']}.</span></p>";

        // Inject attachment widget HTML into the attachment_widget field placeholder
        $this->bean->attachment_widget = $this->_buildAttachmentWidget($mod_strings);

        parent::display();

        SticViews::display($this);

        echo getVersionedScript("modules/stic_Messages/include/ComposeView/stic_MessagesComposeView.js");
        echo getVersionedScript("modules/stic_Messages/Utils.js");

        // Attachment show/hide logic + upload handler
        echo $this->_buildAttachmentScript($mod_strings);
    }

    /**
     * Builds the HTML for the attachment widget (hidden by default, shown for WhatsApp types).
     */
    private function _buildAttachmentWidget(array $mod_strings): string
    {
        $lblAttach  = htmlspecialchars($mod_strings['LBL_ATTACHMENT']             ?? 'Attachment');
        $lblRemove  = htmlspecialchars($mod_strings['LBL_ATTACHMENT_REMOVE']      ?? 'Remove');
        $lblUploading = htmlspecialchars($mod_strings['LBL_CONVERSATION_UPLOADING'] ?? 'Uploading…');

        return <<<HTML
<div id="stic_attachment_widget" style="display:none;">
    <input type="hidden" id="media_note_id" name="media_note_id" value="">

    <input type="file" id="stic_media_file" style="display:none;"
        accept="image/jpeg,image/png,image/gif,image/webp,
                video/mp4,video/3gpp,
                audio/ogg,audio/mpeg,audio/mp4,audio/amr,
                application/pdf,
                application/msword,
                application/vnd.openxmlformats-officedocument.wordprocessingml.document,
                application/vnd.ms-excel,
                application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">

    <div id="stic_attach_btn_row" style="display:flex;align-items:center;gap:10px;">
        <button type="button" class="button" id="stic_attach_btn"
                onclick="document.getElementById('stic_media_file').click();">
            📎 {$lblAttach}
        </button>
        <span id="stic_attach_uploading" style="display:none;font-size:11px;color:#888;">
            ⏳ {$lblUploading}
        </span>
    </div>

    <div id="stic_attach_preview" style="display:none;margin-top:6px;
         padding:6px 10px;background:#f5f5f5;border-radius:6px;
         font-size:12px;display:none;align-items:center;gap:8px;">
        <span id="stic_attach_icon" style="font-size:20px;">📄</span>
        <img  id="stic_attach_img" style="max-height:48px;max-width:48px;
              border-radius:4px;object-fit:cover;display:none;">
        <span id="stic_attach_name" style="flex:1;"></span>
        <a href="#" id="stic_attach_remove"
           style="color:#e53935;font-size:13px;text-decoration:none;"
           onclick="sticRemoveAttachment();return false;">✕ {$lblRemove}</a>
    </div>
</div>
HTML;
    }

    /**
     * Builds the inline JS for the attachment widget.
     */
    private function _buildAttachmentScript(array $mod_strings): string
    {
        $errorUpload  = json_encode($mod_strings['LBL_CONVERSATION_ERROR_UPLOAD']  ?? 'Error uploading file');
        $errorUnknown = json_encode($mod_strings['LBL_CONVERSATION_ERROR_UNKNOWN'] ?? 'Unknown error');

        // WhatsApp helper class names that should show the attachment widget
        $whatsappTypes = json_encode(['WhatsAppHelper', 'WhatsAppWeb']);

        return <<<JS
<script>
(function () {
    var WHATSAPP_TYPES = {$whatsappTypes};

    // ── Show/hide widget based on type field ──────────────────────────────
    function sticToggleAttachment() {
        var typeEl  = document.getElementById('type');
        var widget  = document.getElementById('stic_attachment_widget');
        if (!typeEl || !widget) return;
        var val = typeEl.value || '';
        if (WHATSAPP_TYPES.indexOf(val) !== -1) {
            widget.style.display = 'block';
        } else {
            widget.style.display = 'none';
            sticRemoveAttachment();          // clear any pending file
        }
    }

    // Run on load and on every change of the type dropdown
    document.addEventListener('DOMContentLoaded', function () {
        sticToggleAttachment();
        var typeEl = document.getElementById('type');
        if (typeEl) typeEl.addEventListener('change', sticToggleAttachment);
    });

    // ── File selected ─────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        var fileInput = document.getElementById('stic_media_file');
        if (!fileInput) return;
        fileInput.addEventListener('change', function () {
            if (!this.files || !this.files[0]) return;
            var file = this.files[0];

            document.getElementById('stic_attach_uploading').style.display = 'inline';
            document.getElementById('stic_attach_btn').disabled = true;

            var formData = new FormData();
            formData.append('module', 'stic_Messages');
            formData.append('action', 'uploadConversationMedia');
            formData.append('media',  file);

            fetch('index.php', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                document.getElementById('stic_attach_uploading').style.display = 'none';
                document.getElementById('stic_attach_btn').disabled = false;

                if (!data.success) {
                    alert({$errorUpload} + ': ' + (data.error || {$errorUnknown}));
                    fileInput.value = '';
                    return;
                }

                // Store note_id — the only field the form needs to submit
                document.getElementById('media_note_id').value = data.note_id;

                sticShowAttachPreview(file, data.name);
            })
            .catch(function (err) {
                document.getElementById('stic_attach_uploading').style.display = 'none';
                document.getElementById('stic_attach_btn').disabled = false;
                alert({$errorUpload} + ': ' + err);
                fileInput.value = '';
            });
        });
    });

    // ── Preview ───────────────────────────────────────────────────────────
    window.sticShowAttachPreview = function (file, name) {
        document.getElementById('stic_attach_name').textContent = name;
        var img  = document.getElementById('stic_attach_img');
        var icon = document.getElementById('stic_attach_icon');
        if (file.type.startsWith('image/')) {
            var reader = new FileReader();
            reader.onload = function (e) {
                img.src = e.target.result;
                img.style.display  = 'inline-block';
                icon.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            img.style.display  = 'none';
            icon.style.display = 'inline-block';
        }
        var preview = document.getElementById('stic_attach_preview');
        preview.style.display = 'flex';
    };

    // ── Remove ────────────────────────────────────────────────────────────
    window.sticRemoveAttachment = function () {
        document.getElementById('media_note_id').value = '';
        var fi = document.getElementById('stic_media_file');
        if (fi) fi.value = '';
        var preview = document.getElementById('stic_attach_preview');
        if (preview) preview.style.display = 'none';
        var img = document.getElementById('stic_attach_img');
        if (img) { img.src = ''; img.style.display = 'none'; }
    };
})();
</script>
JS;
    }
}