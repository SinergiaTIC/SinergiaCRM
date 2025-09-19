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
/* HEADER */
// Set module name
var module = "stic_Messages";
/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    $(document).ready(function() {
      setAutofill(["name"]);
    });
    state = $('#status').val();
    if ($('input[name="record"]').val()) {
      // Status can only be changed through actions
      $('#status').prop('disabled', true);
      $('#status').attr('readonly', true);
      $('#status').css('background', '#F8F8F8');
      $('#status').css('border-color', '#E2E7EB');
    }
    if (state !== 'draft' && $('input[name="record"]').val()) {
      // Some fields canonly be edited when message is in draft
      $('#type').prop('disabled', true);
      $('#type').attr('readonly', true);
      $('#type').css('background', '#F8F8F8');
      $('#type').css('border-color', '#E2E7EB');

      $('#sender').prop('disabled', true);
      $('#sender').attr('readonly', true);
      $('#sender').css('background', '#F8F8F8');
      $('#sender').css('border-color', '#E2E7EB');
      
      $('#phone').prop('disabled', true);
      $('#phone').attr('readonly', true);
      $('#phone').css('background', '#F8F8F8');
      $('#phone').css('border-color', '#E2E7EB');

      $('#message').prop('disabled', true);
      $('#message').attr('readonly', true);
      $('#message').css('background', '#F8F8F8');
      $('#message').css('border-color', '#E2E7EB');

      $('#template').prop('disabled', true);
      $('#template').attr('readonly', true);
      $('#template').css('background', '#F8F8F8');
      $('#template').css('border-color', '#E2E7EB');
      
      $('#btn_clr_template').prop('disabled', true);
      $('#btn_clr_template').attr('readonly', true);
      $('#btn_clr_template').css('background', '#F8F8F8');
      $('#btn_clr_template').css('border-color', '#E2E7EB');

      $('#btn_template').prop('disabled', true);
      $('#btn_template').attr('readonly', true);
      $('#btn_template').css('background', '#F8F8F8');
      $('#btn_template').css('border-color', '#E2E7EB');


    }
    break;

  case "detail":
    // Get record Id 
    recordId = $("#formDetailView input[type=hidden][name=record]").val();
    // Define button content
    if ($("#status").val() != 'sent') {
      var buttons = {
        retry: {
          id: "bt_retry_detailview",
          title: SUGAR.language.get("stic_Messages", "LBL_MASS_RETRY_MESSAGE_BUTTON_TITTLE"),
          // onclick: "window.location='index.php?module=stic_Incorpora&action=fromDetailView&record=" + recordId + "&return_module="+ module +"'"
          onclick: "onClickRetryMessagesButton(recordId)"
        }
      };
      createDetailViewButton(buttons.retry);
    }
    // Field response is only showed when an error is present
    if ($("#status").val() != 'error') {
      $('div[data-field="response"]').hide();
    }
    break;

  case "list":
    var buttons = {
      retry: {
        id: "bt_retryMessage_listview",
        title: SUGAR.language.get("stic_Messages", "LBL_MASS_RETRY_MESSAGE_BUTTON_TITTLE"),
        text: SUGAR.language.get("stic_Messages", "LBL_MASS_RETRY_MESSAGE_BUTTON_TITTLE"),
        onclick: "onClickMassRetryMessagesButton()"
    }
  };

  createListViewButton(buttons.retry);
    break;

  default:
    break;
}




function showMessageBox(title, detail, onOk = null, onCancel = null) {
  var mb = messageBox({backdrop:'static'});
  mb.setTitle(title);
  mb.setBody(detail);
  if (!onCancel){
    mb.hideCancel();
  }
  mb.css('z-index', 26000);
  mb.show();
  mb.on('ok', function () {
    "use strict";
    mb.remove();
    if(onOk){
      onOk();
    }
  });
}



function onClickRetryMessagesButton(recordId) {
  var status = $("#status").val();
  if(status === 'sent') {
    showMessageBox(SUGAR.language.get('stic_Messages', 'LBL_ERROR'), SUGAR.language.get('stic_Messages', 'LBL_ALREADY_SENT'));
  }
  else {
    $.ajax({
      url: "index.php?module=stic_Messages&action=retryOne",
      type:"post",
      dataType: "json",
      async: false,
      data: {
          'recordId':recordId
      },
      success: function(res) {
          if (res.success) {
            showMessageBox(res.title, res.detail,function() {window.location.reload();});
          } else {
              showMessageBox(res.title, res.detail);
          }
      },
      error: function() {
          showMessageBox(SUGAR.language.get('stic_Messages', 'LBL_ERROR'), SUGAR.language.get('stic_Messages', 'LBL_MESSAGE_NOT_SENT'));
      }
    });
  }
}



function onClickMassRetryMessagesButton() {
  // confirmation panel
  var confirmed = function () {
    sugarListView.get_checks();
    if(sugarListView.get_checks_count() < 1) {
        alert(SUGAR.language.get('app_strings', 'LBL_LISTVIEW_NO_SELECTED'));
        return false;
    }
    document.MassUpdate.action.value='Retry';
    document.MassUpdate.module.value='stic_Messages';
    document.MassUpdate.submit();
  };

  var mb = messageBox();
  mb.setTitle(SUGAR.language.translate('stic_Messages', 'LBL_CONFIRM_SEND_BULK_MESSAGES_TITLE'));
  mb.setBody(SUGAR.language.translate('stic_Messages', 'LBL_CONFIRM_APPLY_SEND_BULK_MESSAGES_BODY'));
  mb.css('z-index', 26000);
  mb.show();

  mb.on('ok', function () {
    "use strict";
    confirmed();
    mb.remove();
  });
  mb.on('cancel', function () {
    "use strict";
    mb.remove();
  });

}
