/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
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
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

var module = 'stic_Messages';

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


  $.fn.stic_MessagesComposeView.onTemplateSelect = function (args) {
    var confirmed = function (args) {
      var args = JSON.parse(args);
      var form = $('[name="' + args.form_name + '"]');
      $.post('index.php?entryPoint=emailTemplateData', {
        emailTemplateId: args.name_to_value_array.template_id_c
      }, function (jsonResponse) {
        var response = JSON.parse(jsonResponse);
        $("#message").val(response.data.body);
      });
      set_return(args);
    };

    var mb = messageBox();
    mb.setTitle(SUGAR.language.translate('Emails', 'LBL_CONFIRM_APPLY_EMAIL_TEMPLATE_TITLE'));
    mb.setBody(SUGAR.language.translate('stic_Messages', 'LBL_CONFIRM_APPLY_MESSAGES_TEMPLATE_BODY'));
    mb.show();

    var popupId = mb.controls.modal.container.attr('id');
    $('#' + popupId).css('z-index', '25000');

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

  $.fn.stic_MessagesComposeView.onTemplateChange = function (args) {
    var confirmed = function (args) {
      var args = JSON.parse(args);
      var form = $('[name="' + args.form_name + '"]');
      $.post('index.php?entryPoint=emailTemplateData', {
        emailTemplateId: $('#template_id_c').val()
      }, function (jsonResponse) {
        var response = JSON.parse(jsonResponse);
        $("#message").val(response.data.body);
      });
      set_return(args);
    };
    var mb = messageBox();
    mb.setTitle(SUGAR.language.translate('Emails', 'LBL_CONFIRM_APPLY_EMAIL_TEMPLATE_TITLE'));
    mb.setBody(SUGAR.language.translate('Emails', 'LBL_CONFIRM_APPLY_MESSAGES_TEMPLATE_BODY'));
    mb.show();

    var popupId = mb.controls.modal.container.attr('id');
    $('#' + popupId).css('z-index', '25000');

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

  $.fn.stic_MessagesComposeView.onParentSelect = function (args) {
    debugger;
    set_return(args);
    if (args.name_to_value_array.phone_mobile && args.name_to_value_array.phone_mobile !== 'undefined' && args.name_to_value_array.phone_mobile !== '') {
      $("#phone").val(args.name_to_value_array.phone_mobile);  
    }
    if (args.name_to_value_array.phone_office && args.name_to_value_array.phone_office !== 'undefined' && args.name_to_value_array.phone_office !== '') {
      $("#phone").val(args.name_to_value_array.phone_office);  
    }
  };
  $.fn.stic_MessagesComposeView.onParentChange = function (args) {
    debugger;
    if (args.name_to_value_array.phone_mobile && args.name_to_value_array.phone_mobile !== 'undefined' && args.name_to_value_array.phone_mobile !== '') {
      $("#phone").val(args.name_to_value_array.phone_mobile);  
    }
    if (args.name_to_value_array.phone_office && args.name_to_value_array.phone_office !== 'undefined' && args.name_to_value_array.phone_office !== '') {
      $("#phone").val(args.name_to_value_array.phone_office);  
    }
  };

function checkStatus() {
  if($('#status').val() === 'sent') {
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
  debugger;
  console.log('goalchanged');
  let parentName = $('#parent_name').val();
  let parentId = $('#parent_id').val();
  let parentType = $('#parent_type').val();
  // We check the name and not the id because when removed manually the name, the id is not automatically cleared
  if (parentName != null && parentName != '' ) {
    getParentAsync(parentId, parentType, applyParent);
  }

}

function applyParent(parentData) {
  debugger;
  if(parentData!= null) {
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
      debugger;
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

