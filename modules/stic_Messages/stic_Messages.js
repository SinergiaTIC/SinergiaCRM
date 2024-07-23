console.log('patata');

/**
 * 
 * Used as a callback for sending various messages from list view
 */
function onClickMassSendMessagesButton() {
  // sugarListView.get_checks();
  // if(sugarListView.get_checks_count() < 1) {
  //     alert(SUGAR.language.get('app_strings', 'LBL_LISTVIEW_NO_SELECTED'));
  //     return false;
  // }
  // document.MassUpdate.action.value='fromMassUpdate';
  // document.MassUpdate.module.value='stic_Messages';
  // document.MassUpdate.submit();

  let obj = { return_action: "ListView" };
  let jsonString = JSON.stringify(obj);

  openMessagesModal(this, jsonString);
}


// function openCustomModal(buttonModule, parentModule) {
function openMessagesModal(source, paramsJson = '{"return_action":"DetailView"}') {
  debugger;
    // return_action = 'DetailView';
    let params = JSON.parse(paramsJson);
    return_action = params['return_action'];
    if (return_action == '') {
      return_action = 'DetailView';
    }
    
    var relatedId = $('[name="record"]').val();
    var ids = '&ids=';
    if (typeof $(source).attr('data-record-id') !== 'undefined' && $(source).attr('data-record-id') !== '') {
      ids = ids + $(source).attr('data-record-id');
      relatedId = $(source).attr('data-record-id');
    }
    else{
      var inputs = document.MassUpdate.elements;
      for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].name === 'mass[]' && inputs[i].checked) {
          ids = ids + inputs[i].value + ',';
        }
      }
    }

    var targetModule = currentModule;
    if (typeof $(source).attr('data-module') !== 'undefined' && $(source).attr('data-module') !== '') {
        targetModule = $(source).attr('data-module');
    }


    var URL = 'index.php?module=stic_Messages&return_module='+currentModule+'&return_action='+return_action+'&return_id='+relatedId+'&action=ComposeView&in_popup=1&targetModule=' + targetModule + ids + '&relatedModule=' + currentModule + '&relatedId=' + relatedId;
    // var URL = 'index.php?to_pdf=true&module=' + buttonModule + 
    //           '&action=EditView&return_module=' + parentModule + 
    //           '&return_action=DetailView';

    SUGAR.ajaxUI.showLoadingPanel();
    
    $.get(URL, function(data) {
        debugger;
        var panelBody = $('<div>').append(data).find('#EditView').parent();

      var dataPhone = $(source).attr('data-phone');

      // If the attribute data-record-id is present, then we come from subpanel, else we come from mass send or Edit View.
      // if (typeof dataPhone !== 'undefined' && dataPhone !== '') {
        var dataRecordId = $(source).attr('data-record-id');
      if (typeof dataRecordId !== 'undefined' && dataRecordId !== '') {
        panelBody.find('#phone').val(dataPhone);
      }
      else {
        phoneList = '';
        idsList = '';
        targetCount = 0;
        panelBody.find('.phone-compose-view-to-list').each(function () {
          dataPhone = $(this).attr('data-record-phone');
          dataId = $(this).attr('data-record-id');
          if (dataPhone !== '') {
            if (targetCount > 0 ){
              phoneList += ',';
              idsList += ';';
            }
            phoneList += dataPhone;
            idsList += dataId;
            targetCount++;
          }
        });
        panelBody.find('#phone').val(phoneList);
        phoneElement = panelBody.find('#phone')
        phoneElement.attr('readonly', true);
        phoneElement.css('background', '#F8F8F8');
        phoneElement.css('border-color', '#E2E7EB');

        // panelBody.find('#phone').attr('disabled', true);
        panelBody.find('#mass_ids').val(idsList);
      }


      var self = this;
    //   $(self).find('#phone').val(dataPhone);

        
        SUGAR.ajaxUI.hideLoadingPanel();
        
        $('<div>').append(panelBody).dialog({
            modal: true,
            // title: SUGAR.language.get(buttonModule, 'LBL_NEW_FORM_TITLE'),
            title: '',
            width: '80%',
            // buttons: {
            //     'Save': function() {
            //         var form = panelBody.find('form');
            //         $.post(form.attr('action'), form.serialize(), function(response) {
            //             if (response.sugar_body) {
            //                 $(this).dialog('close');
            //                 SUGAR.mySugar.retrieveDashlet();
            //                 if (typeof SUGAR.subpanelUtils != "undefined" && typeof SUGAR.subpanelUtils.loadSubpanels != "undefined") {
            //                     SUGAR.subpanelUtils.loadSubpanels();
            //                 }
            //             }
            //         }, 'json');
            //     },
            //     'Cancel': function() {
            //         $(this).dialog('close');
            //     }
            // }
        });
        $( "#template" ).change(function() {
            console.log('template change');
            $.fn.stic_MessagesComposeView.onTemplateChange()
          });
    });
}

$(function() {
  debugger;
  if (viewType() === 'detail') {

const attr = 'sms-button'
const triggerClass = 'seven-send-sms'
const attachedClass = 'seven-attached'

const recordId = $("input[name=record]")[0].value;

for (const phone of [...document.querySelectorAll('[type=phone]')]) {
    if (phone.getAttribute(attr) !== null) continue

    const to = phone.textContent.trim()
    if (to === '') continue

    // TODOEPS: Incloure la nova icona via font
    const src = 'themes/SuiteP/images/message20.png'
    const alt = SUGAR.language.get(window.module_sugar_grp1, 'LBL_SEVEN_SEND_SMS_VIA')
    phone.insertAdjacentHTML('beforeend',
        `<img alt='${alt}' class='${triggerClass}' data-record-id= '${recordId}' data-record-module= '${module}' data-phone='${to}' src='${src}' title='${alt}' />`)
    phone.setAttribute(attr, 'true')
}

$(`img.${triggerClass}:not(.${attachedClass})`)
    .addClass(attachedClass)
    .on('click', function() {
        $('#seven_to').val($(this).data('to'))

        openMessagesModal(this);
    });
  }
});
