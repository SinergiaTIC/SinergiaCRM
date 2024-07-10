console.log('patata');

// function openCustomModal(buttonModule, parentModule) {
function openCustomModal(source, paramsJson) {

    console.log('tercer');

    var relatedId = $(source).attr('data-record-id');

    var targetModule = currentModule;
    if ($(source).attr('data-module') !== '') {
        targetModule = $(source).attr('data-module');
    }


    var URL = 'index.php?module=stic_Messages&return_module='+currentModule+'&return_action=DetailView&return_id='+relatedId+'&action=ComposeView&in_popup=1&targetModule=' + targetModule + '&relatedModule=' + currentModule + '&relatedId=' + relatedId;
    // var URL = 'index.php?to_pdf=true&module=' + buttonModule + 
    //           '&action=EditView&return_module=' + parentModule + 
    //           '&return_action=DetailView';

    SUGAR.ajaxUI.showLoadingPanel();
    
    $.get(URL, function(data) {
        debugger;
        var panelBody = $('<div>').append(data).find('#EditView').parent();

      var dataPhone = $(source).attr('data-phone');
      panelBody.find('#phone').val(dataPhone);

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