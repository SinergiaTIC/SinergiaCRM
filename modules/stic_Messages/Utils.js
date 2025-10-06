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

var sticViewType = originView = viewType();
if ((sticViewType == 'detail' || sticViewType == 'list') && $("select[name='parent_type']").length > 0) {
  sticViewType = 'edit';
}

switch (sticViewType) {
  case "edit":
  case "quickcreate":
  case "popup":
    $(document).ready(function() {
      setAutofill(["name"]);
      state = $('#status').val();
      addEditCreateTemplateLinks();
      if ($('#EditView input[name="record"]').val()) {
        // Status can only be changed through actions
        $('#status').prop('disabled', true);
        $('#status').attr('readonly', true);
        $('#status').css('background', '#F8F8F8');
        $('#status').css('border-color', '#E2E7EB');
      }
      if (state !== 'draft' && $('#EditView input[name="record"]').val()) {
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

        $('#template_id').prop('disabled', true);
        $('#template_id').attr('readonly', true);
        $('#template_id').css('background', '#F8F8F8');
        $('#template_id').css('border-color', '#E2E7EB');

        $("#template_id_edit_link").addClass("ui-state-disabled");
        $("#template_id_create_link").addClass("ui-state-disabled");


      }

      if($("#mass_ids").val()) {
        $('#parent_name').prop('disabled', true);
        $('#parent_name').attr('readonly', true);
        $('#parent_name').css('background', '#F8F8F8');
        $('#parent_name').css('border-color', '#E2E7EB');

        $('#parent_type').prop('disabled', true);
        $('#parent_type').attr('readonly', true);
        $('#parent_type').css('background', '#F8F8F8');
        $('#parent_type').css('border-color', '#E2E7EB');

        $('#btn_parent_name').prop('disabled', true);
        $('#btn_parent_name').css('background-color', '#F8F8F8 !important');
        $('#btn_clr_parent_name').prop('disabled', true);
        $('#btn_clr_parent_name').css('background-color', '#F8F8F8 !important');
      }

      $("#template_id").on("change paste keyup", template_change);
      if ($("#template_id").val() == "") {
        $("#template_id_edit_link").hide();
      } else {
        $("#template_id_edit_link").show();
      }
    });

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

function onTemplateSelect(args) {
    var confirmed = function (args) {
      var args = JSON.parse(args);
      $.post('index.php?entryPoint=emailTemplateData', {
        // emailTemplateId: args.name_to_value_array.template_id_c
        // emailTemplateId: args.name_to_value_array.template_id
        emailTemplateId: $("#template_id").val()
      }, function (jsonResponse) {
        var response = JSON.parse(jsonResponse);
        $("#message").val(response.data.body);
      });
      set_return(args);
    };

    var mb = messageBox();
    mb.setTitle(SUGAR.language.translate('Emails', 'LBL_CONFIRM_APPLY_EMAIL_TEMPLATE_TITLE'));
    mb.setBody(SUGAR.language.translate('stic_Messages', 'LBL_CONFIRM_APPLY_MESSAGES_TEMPLATE_BODY'));
    mb.css('z-index', 26000);
    mb.show();

    var args = JSON.stringify(args);

    mb.on('ok', function () {
      "use strict";
      confirmed(args);
      mb.remove();
    });

    mb.on('cancel', function () {
      "use strict";
      mb.remove();
    });
  };

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

function addEditCreateTemplateLinks() {
  if ($("#template_id_edit_link").length == 0) {
    var $select = $("#template_id");
    var $div = $select.parent();

    $select.css("width","50%");

    var editText = SUGAR.language.translate("app_strings", "LNK_EDIT");
    var $editLink = $('<a href="#" id="template_id_edit_link" style="margin-left:10px;">'+editText+'</a>').on("click", function(e) {
      e.preventDefault();
      edit_email_template_form();
    });
    $div.append($editLink);

    var createText = SUGAR.language.translate("app_strings", "LNK_CREATE");
    var $createLink = $('<a href="#" id="template_id_create_link" style="margin-left:10px;">'+createText+'</a>').on("click", function(e) {
      e.preventDefault();
      open_email_template_form();
    });
    $div.append($createLink);
  }
}

function open_email_template_form() {
  URL = "index.php?module=EmailTemplates&action=EditView&type=sms";
  URL += "&inboundEmail=false&show_js=1";

  windowName = 'email_template';
  windowFeatures = 'width=800' + ',height=600' + ',resizable=1,scrollbars=1';

  win = window.open(URL, windowName, windowFeatures);
  if (window.focus) {
      // put the focus on the popup if the browser supports the focus() method
      win.focus();
  }
}

function edit_email_template_form() {
  URL = "index.php?module=EmailTemplates&action=EditView&type=sms";

  var field = document.getElementById('template_id');
  if (field.options[field.selectedIndex].value != 'undefined') {
      URL += "&record=" + field.options[field.selectedIndex].value;
  }
  URL += "&inboundEmail=null&show_js=1";

  windowName = 'email_template';
  windowFeatures = 'width=800' + ',height=600' + ',resizable=1,scrollbars=1';

  win = window.open(URL, windowName, windowFeatures);
  if (window.focus) {
      // put the focus on the popup if the browser supports the focus() method
      win.focus();
  }
}

function refresh_email_template_list(template_id, template_name) {
  var field = document.getElementById('template_id');
  var bfound = 0;
  for (var i = 0; i < field.options.length; i++) {
      if (field.options[i].value == template_id) {
          if (field.options[i].selected == false) {
              field.options[i].selected = true;
          }
          field.options[i].text = template_name;
          bfound = 1;
      }
  }
  //add item to selection list.
  if (bfound == 0) {
      var newElement = document.createElement('option');
      newElement.text = template_name;
      newElement.value = template_id;
      field.options.add(newElement);
      newElement.selected = true;
  }
  template_change();
}

function template_change() {
  console.log('template_chage #' + $("#template_id").val() +'#');
  if ($("#template_id").val() == "") {
    $("#template_id_edit_link").hide();
  } else {
    $("#template_id_edit_link").show();
  }
  if ($("#template_id").val()) {
    updateMessageBox();
  }
}

function updateMessageBox (args) {
    var confirmed = function (args) {
      var args = JSON.parse(args);

      $.post('index.php?entryPoint=emailTemplateData', {
        emailTemplateId: $('#template_id').val()
      }, function (jsonResponse) {
        var response = JSON.parse(jsonResponse);
        $("#message").val(response.data.body);
      });
      set_return(args);
    };

    var mb = messageBox();
    mb.setTitle(SUGAR.language.translate('Emails', 'LBL_CONFIRM_APPLY_EMAIL_TEMPLATE_TITLE'));
    mb.setBody(SUGAR.language.translate('Emails', 'LBL_CONFIRM_APPLY_MESSAGES_TEMPLATE_BODY'));
    mb.css('z-index', 26000);
    mb.show();

    mb.on('ok', function () {
      "use strict";
      var id=$('#emails_email_templates_idb').val();
      var name=$('#emails_email_templates_name').val();
      args = JSON.stringify({"form_name":"ComposeView","name_to_value_array":{"emails_email_templates_idb": id,"emails_email_templates_name": name}})
      confirmed(args);
      mb.remove();
    });

    mb.on('cancel', function () {
      "use strict";
      mb.remove();
    });
  }

