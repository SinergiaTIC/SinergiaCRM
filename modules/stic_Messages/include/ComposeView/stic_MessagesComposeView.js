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

var module = 'stic_Messages';
if (!($("#mass_ids").length > 0) || $("#mass_ids").val() == ''){
  addToValidateCallback(
    getFormName(),
    "parent_id",
    "related",
    true,
    SUGAR.language.get(module, "LBL_LIST_RELATED_TO"),
    function() {
        return true;
    }
  );
  addRequiredMark('parent_id', 'conditional-required');
}



(function ($) {
  /**
   *
   * @param options
   * @returns {jQuery|HTMLElement}
   * @constructor
   */
  $.fn.stic_MessagesComposeView = function (options) {
    "use strict";
    var self = $(this);

    /**
     * @return string UUID
     */
    self.generateID = function () {
      "use strict";
      var characters = ['a', 'b', 'c', 'd', 'e', 'f', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
      var format = '0000000-0000-0000-0000-00000000000';
      return Array.prototype.map.call(format, function ($obj) {
        var min = 0;
        var max = characters.length - 1;

        if ($obj === '0') {
          var index = Math.round(Math.random() * (max - min) + min);
          $obj = characters[index];
        }

        return $obj;
      }).toString().replace(/(,)/g, '');
    };


    /**
     * Constructor
     */
    self.construct = function () {
      "use strict";

      if (self.length === 0) {
        console.error('stic_MessagesComposeView - Invalid Selector');
        return;
      }

      if (self.attr('id').length === 0) {
        console.warn('stic_MessagesComposeView - expects element to have an id. stic_MessagesComposeView has generated one.');
        self.attr('id', self.generateID());
      }


      $(self).trigger("constructstic_MessagesComposeView", [self]);
    };

    /**
     * @destructor
     */
    self.destruct = function () {
      
      return true;
    };

    self.construct();

    return $(self);
  };


  $.fn.stic_MessagesComposeView.showAjaxErrorMessage = function (response) {
    var message = '';
    $.each(response.errors, function (i, v) {
      message = message + v.title;
    });
    var mb = messageBox();
    mb.setBody(message);
    mb.show();

    mb.on('ok', function () {
      "use strict";
      mb.remove();
    });

    mb.on('cancel', function () {
      "use strict";
      mb.remove();
    });
  };


  $.fn.stic_MessagesComposeView.onParentSelect = function (args) {
    set_return(args);
    if ($("#status").val() == 'draft') {
      if (args.name_to_value_array.phone_mobile && args.name_to_value_array.phone_mobile !== 'undefined' && args.name_to_value_array.phone_mobile !== '') {
        $("#phone").val(args.name_to_value_array.phone_mobile);  
      }
      if (args.name_to_value_array.phone_office && args.name_to_value_array.phone_office !== 'undefined' && args.name_to_value_array.phone_office !== '') {
        $("#phone").val(args.name_to_value_array.phone_office);  
      }
    }
  };
  $.fn.stic_MessagesComposeView.onParentChange = function (args) {
    if ($("#status").val() == 'draft') {
      if (args.name_to_value_array.phone_mobile && args.name_to_value_array.phone_mobile !== 'undefined' && args.name_to_value_array.phone_mobile !== '') {
        $("#phone").val(args.name_to_value_array.phone_mobile);  
      }
      if (args.name_to_value_array.phone_office && args.name_to_value_array.phone_office !== 'undefined' && args.name_to_value_array.phone_office !== '') {
        $("#phone").val(args.name_to_value_array.phone_office);  
      }
    }
  };

function checkStatus() {
  if($('#status').val() === 'sent' && !$('#EditView input[name="record"]').val()) {
    $('input.button.primary').val(SUGAR.language.get('app_strings', 'LBL_EMAIL_SEND'));
  } 
  else {
    $('input.button.primary').val(SUGAR.language.get('app_strings', 'LBL_SAVE_BUTTON_LABEL'));
  }
}
  
  $('#status').on('change', checkStatus);
  checkStatus();

}(jQuery));

YAHOO.util.Event.addListener('parent_id','change',parentIdChanged);

function parentIdChanged() {
  let parentId = $('#parent_id').val();
  let parentType = $('#parent_type').val();

  if ((parentId !== null && parentId !== '')) {
    getParentAsync(parentId, parentType, applyParent);
  }

}

function applyParent(parentData) {
  if(parentData!= null && ($("#status").val() === 'draft' || !$('#EditView input[name="record"]').val())) {
    $('#phone').val(parentData['phone']);
  }
}

function getParentAsync(parentId, parentType, callbackFunction) {
  $.ajax({
    url: "index.php?module=stic_Messages&action=getParentPhone",
    type: "post",
    dataType: "json",
    data: {
      "parentId": parentId,
      "parentType": parentType
    },
    success: function(resultado) {
      if (resultado.code == 'OK') {
        callbackFunction(resultado.data);
      }
      else if (resultado.code == 'No data') {
        console.log('No data');
        return null;
      }
      else {
        console.log('Error:', resultado.code);
      }
    }
  });
}

$(function () {
  const myButtons = $('[id="SAVE"]');
  saveMessage = function (event) {
    event.preventDefault();
    if (check_form("EditView")) {
      const formDataArray = $("#EditView").serializeArray();
      const formObject = {};

      $.each(formDataArray, function (i, field) {
        if (formObject[field.name]) {
          if (!Array.isArray(formObject[field.name])) {
            formObject[field.name] = [formObject[field.name]];
          }
          formObject[field.name].push(field.value);
        } else {
          formObject[field.name] = field.value;
        }
      });

      function getFormDataAsObject($form) {
        var unindexed_array = $form.serializeArray();
        var indexed_array = {};

        $.map(unindexed_array, function (n, i) {
          indexed_array[n["name"]] = n["value"];
        });

        return indexed_array;
      }
      var formData = getFormDataAsObject($("#EditView"));
      formData.action='SavePopUp';

      $.ajax({
        url: "index.php?module=stic_Messages&action=savePopUp",
        type: "post",
        dataType: "json",
        async: false,
        data: formData,
        success: function (res) {
          var _openedWhatsAppByClient = false;
          if (res.open_url) {
            window.open(res.open_url, '_blank');
            _openedWhatsAppByClient = true;
          }
          if (res.open_urls && Array.isArray(res.open_urls)) {
            res.open_urls.forEach(function(url) {
              window.open(url, '_blank');
            });
            _openedWhatsAppByClient = true;
          }
          var displayTitle = res.title;
          var displayDetail = res.detail;
          if (_openedWhatsAppByClient) {
            displayTitle = res.title || SUGAR.language.get('stic_Messages', 'LBL_MESSAGE_SENT');
            displayDetail = SUGAR.language.get('stic_Messages', 'LBL_WHATSAPP_WEB_SENT');
          }
          showMessageBox(displayTitle, displayDetail, function () {            
            var baseUrl = window.location.href.split("?")[0];
            var returnModule = $('#EditView [name="return_module"]').val();
            var returnAction = $('#EditView [name="return_action"]').val();
            var returnId = $('#EditView [name="return_id"]').val();
            if (!returnId && res.id) {
              returnId = res.id;
            }
            var newUrl =
              baseUrl +
              "?module=" +
              encodeURIComponent(returnModule) +
              "&action=" +
              encodeURIComponent(returnAction) +
              (returnId ? "&record=" + encodeURIComponent(returnId) : "");
          if($("#status").val() == 'draft') {
            window.location.href = newUrl;
          }
          else {
            showMessageBox(res.title, res.detail, function () {
              window.location.href = newUrl;
            });
          }
        },
        error: function () {
          showMessageBox(
            SUGAR.language.get('stic_Messages', 'LBL_ERROR'),
            SUGAR.language.get('stic_Messages', 'LBL_MESSAGE_NOT_SENT'),
            function () {
              var baseUrl = window.location.href.split("?")[0];
              var returnModule = $('#EditView [name="return_module"]').val();
              var returnAction = $('#EditView [name="return_action"]').val();
              var returnId = $('#EditView [name="return_id"]').val();
              if (returnId) {
                var newUrl =
                  baseUrl +
                  "?module=" +
                  encodeURIComponent(returnModule) +
                  "&action=" +
                  encodeURIComponent(returnAction) +
                  (returnId ? "&record=" + encodeURIComponent(returnId) : "");
                window.location.href = newUrl;
              }
            }
          );
        },
      });
    }
    return false;
  };
  // var _form = document.getElementById('EditView'); _form.action.value='Save'; if(check_form('EditView'))SUGAR.ajaxUI.submitForm(_form);return false;
  myButtons.removeAttr("onclick");
  myButtons.on("click", saveMessage);
});
