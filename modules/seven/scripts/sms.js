    // STIC-Custom EPS 20240404
    // callback function used in the Popup that select events
    // function openSelectPopup(module, field) {
    //     var popupRequestData = {
    //         call_back_function: "callbackSelectPopup",
    //         form_name: "form_filters",
    //         field_to_name_array: {
    //             id: field + "_id",
    //             name: field + "_name",
    //         },
    //     };
    //     open_popup(module, 600, 400, "", true, false, popupRequestData);
    // }
    // // Clears a single filter, name and id fields
    // function clearRow(form, field) {
    //     SUGAR.clearRelateField(form, field + '_name', field + '_id');
    // }
    // var fromPopupReturn = false;
    // // callback function used after the Popup that select events
    // function callbackSelectPopup(popupReplyData) {
    //     fromPopupReturn = true;
    //     var nameToValueArray = popupReplyData.name_to_value_array;
    //     // It fills the data of the events
    //     Object.keys(nameToValueArray).forEach(function(key, index) {
    //         $('#'+key).val(nameToValueArray[key]);
    //     }, nameToValueArray);
    // }

    // END STIC-Custom


$(function() {

    // const notificationDialog = $('#seven_notification').dialog({
    //     autoOpen: false,
    //     buttons: {
    //         OK() {
    //             $(this).dialog('close')
    //         },
    //     },
    // })

    // const composeSmsDialog = $('#seven_sms_form').dialog({
    //     autoOpen: false,
    //     modal: true,
    // }).on('submit', e => {
    //     debugger;
    //     if($("#seven_text").val() == '' && $("#seven_template_id").val() == '') {
    //         notificationDialog
    //         .text(SUGAR.language.get(module, "LBL_TEMPLATE_OR_TEXT_FILLED"))
    //         .dialog('open')
    //         return false;
    //     }

    //     e.preventDefault()

    //     postSMS()
    // })




    // function postSMS() {
    //     const inputs = composeSmsDialog.serializeArray()
    //     const getValue = name => inputs.find(input => input.name === name).value

    //     const data = {
    //         from: getValue('seven_from'),
    //         id: getValue('bean_id'),
    //         message: getValue('seven_text'),
    //         // STIC-Custom EPS 20240404
    //         template: getValue('seven_template_id'),
    //         // ENS STIC-Custom
    //         module: getValue('module'),
    //         number: getValue('seven_to'),
    //     }

    //     return $.ajax({
    //         data,
    //         dataType: 'json',
    //         error(jqXHR, textStatus, errorThrown) {
    //             SUGAR.ajaxUI.showErrorMessage(errorThrown)
    //         },
    //         async success([json, bean]) {
    //             composeSmsDialog.dialog('close')
    //             notificationDialog
    //                 .text(JSON.stringify(json, null, 2))
    //                 .dialog('open')

    //             document.getElementById('seven_sms_history')
    //                 .insertAdjacentHTML('beforeend', `<div>
    //                 <span class='suitepicon suitepicon-action-right'></span>
    //                 ${bean.text}
    //                 <small>${bean.date_entered}</small>
    //             </div>`)
    //         },
    //         type: 'POST',
    //         // STIC-Custom EPS 20240404
    //         // url: '/index.php?entryPoint=seven',
    //         url: 'index.php?entryPoint=seven',
    //         // END STIC-Custom
    //     })
    // }

    // window.seven_suitecrm = {
    //     openSmsDialog() {
    //         composeSmsDialog.dialog('open')
    //     },
    // }

    debugger;
    const attr = 'sms-button'
    const triggerClass = 'seven-send-sms'
    const attachedClass = 'seven-attached'

    for (const phone of [...document.querySelectorAll('[type=phone]')]) {
        if (phone.getAttribute(attr) !== null) continue

        const to = phone.textContent.trim()
        if (to === '') continue

        const src = '/themes/SuiteP/images/p_icon_email_address_32.png'
        const alt = SUGAR.language.get(window.module_sugar_grp1, 'LBL_SEVEN_SEND_SMS_VIA')
        phone.insertAdjacentHTML('beforeend',
            `<img alt='${alt}' class='${triggerClass}' data-to='${to}' src='${src}' title='${alt}'
                 data-phone='${to}'
                 data-module='${module}'
                 data-record-id='${recordId}'/>`)
        phone.setAttribute(attr, 'true')
    }


    let obj = { return_action: "DetailView" };
    let jsonString = JSON.stringify(obj);

    $(`img.${triggerClass}:not(.${attachedClass})`)
        .addClass(attachedClass)
        .on('click', function() {
            $('#seven_to').val($(this).data('to'))

            // seven_suitecrm.openSmsDialog()
            openMessagesModal(this, jsonString);
        })
})
