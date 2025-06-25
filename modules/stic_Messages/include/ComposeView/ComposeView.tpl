{*
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
*}

{{sugar_include type="smarty" file=$headerTpl}}
{sugar_include include=$includes}
<form class="compose-view" id="ComposeView" name="ComposeView" method="POST" action="index.php?module=stic_Messages&action=Save">
    <input type="hidden" name="module" value="stic_Messages">
    <input type="hidden" name="action" value="SavePopUp">
    <input type="hidden" name="record" value="{$RECORD}">
    {{* <input type="hidden" name="type" value="out">
    <input type="hidden" name="send" value="1"> *}}
    <input type="hidden" name="return_module" value="{$RETURN_MODULE}">
    <input type="hidden" name="return_action" value="{$RETURN_ACTION}">
    <input type="hidden" name="return_id" value="{$RETURN_ID}">
    <input type="hidden" name="mass_ids" id = "mass_ids" value="">
    <input type="hidden" name="confirmationMessage" id="confirmationMessage" value="{sugar_translate label='LBL_EMAIL_SUCCESS'}">
    <input type="hidden" name="confirmationMessageText" id="confirmationMessageText" value="{sugar_translate label='LBL_MESSAGE_SENT' module='stic_Messages'}">
<div class="content">
<div id="EditView_tabs">
    {*display tabs*}
    {{counter name="tabCount" start=-1 print=false assign="tabCount"}}
    <ul class="nav nav-tabs">
        {{if $useTabs}}
        {{foreach name=section from=$sectionPanels key=label item=panel}}
        {{capture name=label_upper assign=label_upper}}{{$label|upper}}{{/capture}}
        {* if tab *}
        {{if (isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == true)}}
        {*if tab display*}
        {{counter name="tabCount" print=false}}
        {{if $tabCount == '0'}}
        <li role="presentation" class="active">
            <a id="tab{{$tabCount}}" data-toggle="tab" class="hidden-xs">
                {sugar_translate label='{{$label}}' module='{{$module}}'}
            </a>
            {* Count Tabs *}
            {{counter name="tabCountOnlyXS" start=-1 print=false assign="tabCountOnlyXS"}}
            {{foreach name=sectionOnlyXS from=$sectionPanels key=labelOnly item=panelOnlyXS}}
            {{capture name=label_upper_count_only assign=label_upper_count_only}}{{$labelOnly|upper}}{{/capture}}
            {{if (isset($tabDefs[$label_upper_count_only].newTab) && $tabDefs[$label_upper_count_only].newTab == true)}}
                {{counter name="tabCountOnlyXS" print=false}}
            {{/if}}
            {{/foreach}}

            {*
                For the mobile view, only show the first tab has a drop down when:
                * There is more than one tab set
                * When Acton Menu's are enabled
            *}
            <!-- Counting Tabs {{$tabCountOnlyXS}}-->
            <a id="xstab{{$tabCount}}" href="#" class="visible-xs first-tab{{if $tabCountOnlyXS > 0}}-xs{{/if}} dropdown-toggle" data-toggle="dropdown">
                {sugar_translate label='{{$label}}' module='{{$module}}'}
            </a>
            {{if $tabCountOnlyXS > 0}}
            <ul id="first-tab-menu-xs" class="dropdown-menu">
                {{counter name="tabCountXS" start=0 print=false assign="tabCountXS"}}
                {{foreach name=sectionXS from=$sectionPanels key=label item=panelXS}}
                {{capture name=label_upper_xs assign=label_upper_xs}}{{$label|upper}}{{/capture}}
                {{if (isset($tabDefs[$label_upper_xs].newTab) && $tabDefs[$label_upper_xs].newTab == true)}}
                <li role="presentation">
                    <a id="tab{{$tabCountXS}}" data-toggle="tab" onclick="changeFirstTab(this, 'tab-content-{{$tabCountXS}}');">
                        {sugar_translate label='{{$label}}' module='{{$module}}'}
                    </a>
                </li>
                {{counter name="tabCountXS" print=false}}
                {{/if}}
                {{/foreach}}
            </ul>
            {{/if}}
        </li>
        {{else}}
        <li role="presentation" class="hidden-xs">
            <a id="tab{{$tabCount}}"  data-toggle="tab">
                {sugar_translate label='{{$label}}' module='{{$module}}'}
            </a>
        </li>
        {{/if}}
        {{else}}
        {* if panel skip*}
        {{/if}}
        {{/foreach}}
        {{/if}}

    </ul>

    <div class="clearfix"></div>
    {{if $useTabs}}
    <div class="tab-content">
        {{else}}
        <div class="tab-content" style="padding: 0; border: 0;">
            {{/if}}
            {{counter name="tabCount" start=0 print=false assign="tabCount"}}
            {* Loop through all top level panels first *}
            {{if $useTabs}}
            {{foreach name=section from=$sectionPanels key=label item=panel}}
            {{capture name=label_upper assign=label_upper}}{{$label|upper}}{{/capture}}
            {{if isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == true}}
            {{if $tabCount == '0'}}
            <div class="tab-pane-NOBOOTSTRAPTOGGLER active fade in" id='tab-content-{{$tabCount}}'>
                {{include file='themes/SuiteP/include/EditView/tab_panel_content.tpl'}}
            </div>
            {{else}}
            <div class="tab-pane-NOBOOTSTRAPTOGGLER fade" id='tab-content-{{$tabCount}}'>
                {{include file='themes/SuiteP/include/EditView/tab_panel_content.tpl'}}
            </div>
            {{/if}}
             {{counter name="tabCount" print=false}}
            {{/if}}
            {{/foreach}}
            {{else}}
            <div class="tab-pane panel-collapse">&nbsp;</div>
            {{/if}}
        </div>
        {*display panels*}
        <div class="panel-content">
            <div>&nbsp;</div>
            {{counter name="panelCount" start=-1 print=false assign="panelCount"}}
            {{foreach name=section from=$sectionPanels key=label item=panel}}
            {{capture name=label_upper assign=label_upper}}{{$label|upper}}{{/capture}}
            {* if tab *}
            {{if (isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == true && $useTabs)}}
            {*if tab skip*}
            {{else}}
            {* if panel display*}
            {*if panel collasped*}
            {{if (isset($tabDefs[$label_upper].panelDefault) && $tabDefs[$label_upper].panelDefault == "collapsed") }}
            {*collapse panel*}
            {{assign var='collapse' value="panel-collapse collapse"}}
            {{assign var='collapsed' value="collapsed"}}
            {{assign var='collapseIcon' value="glyphicon glyphicon-plus"}}
            {{assign var='panelHeadingCollapse' value="panel-heading-collapse"}}
            {{else}}
            {*expand panel*}
            {{assign var='collapse' value="panel-collapse collapse in"}}
            {{assign var='collapseIcon' value="glyphicon glyphicon-minus"}}
            {{assign var='panelHeadingCollapse' value=""}}
            {{/if}}

            <div class="panel panel-default">
                <div class="panel-heading {{$panelHeadingCollapse}}">
                    <a class="{{$collapsed}}" role="button" data-toggle="collapse-edit" aria-expanded="false">
                        <div class="col-xs-10 col-sm-11 col-md-11">
                            {sugar_translate label='{{$label}}' module='{{$module}}'}
                        </div>
                    </a>
                </div>
                <div class="panel-body {{$collapse}}" id="detailpanel_{{$panelCount}}">
                    <div class="tab-content">
                        {{include file='themes/SuiteP/include/EditView/tab_panel_content.tpl'}}
                    </div>
                </div>
            </div>
            {{/if}}
            {{counter name="panelCount" print=false}}
            {{/foreach}}
        </div>
    </div>
    <div class="attachments">
        <div class="file-attachments"></div>
        <div class="document-attachments"></div>
    </div>
{{sugar_include type='smarty' file=$footerTpl}}
</div>


{literal}
<script type="text/javascript">
    console.log('cccc');
    $(function(){
        console.log('dddd');
        //debugger;
        
        const myButtons = $('[id="SAVE"]');
        xxx = function(event) {
            event.preventDefault();
            // _form = document.getElementById('EditView');
            // _form.action.value='SavePopUp'; 
            if(check_form('EditView')){
                // const form = document.getElementById('EditView');
                //const formData = new FormData(form);
                //const formData = "{'assessmentId': 4444}";
                const formDataArray = $('#EditView').serializeArray();
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

                const jsonString = JSON.stringify(formObject, null, 2);
               debugger;
               function getFormDataAsObject($form) {
                    var unindexed_array = $form.serializeArray();
                    var indexed_array = {};

                    $.map(unindexed_array, function(n, i) {
                        indexed_array[n['name']] = n['value'];
                    });

                    return indexed_array;
                }
               var formData = getFormDataAsObject($('#EditView'));
               var formDataJson = JSON.stringify(formData);
               var serialized = $('#EditView').serialize();
                $.ajax({
                    url: "index.php?module=stic_Messages&action=savePopUp",
                    type:"post",
                    dataType: "json",
                    async: false,
                    /*
                    data: {
                        'parent_type':$("#parent_type").val(),
                        'parent_id':$("#parent_id").val()
                    },
                    */
                     data: formData,
                    success: function(res) {
                        debugger;
                        if (res.success) {
                            console.log('good');
                            //SUGAR.showMessageBox('Mesage sent', 'Message sent detail', 'alert');
                            var mb = messageBox({backdrop:'static'});
                            // mb.hideHeader();    
                            //mb.setTitle($("#confirmationMessage").val());
                            mb.setTitle(res.title);
                            mb.hideCancel();
                            // mb.setBody('Message sent');
                            //mb.setBody($("#confirmationMessageText").val());
                            mb.setBody(res.detail);
                            mb.css('z-index', 26000)
                            mb.show();
                            mb.on('ok', function () {
                                "use strict";
                                console.log('asdsa');
                                mb.remove();
                                var baseUrl = window.location.href.split('?')[0];
                                var returnModule = $('#EditView [name="return_module"]').val();
                                var returnAction = $('#EditView [name="return_action"]').val();
                                var returnId = $('#EditView [name="return_id"]').val();
                                if(!returnId && res.id) {
                                    returnId = res.id;
                                }
                                var newUrl = baseUrl + '?module=' + encodeURIComponent(returnModule)
                                    + '&action=' + encodeURIComponent(returnAction)
                                    + (returnId ? '&record=' + encodeURIComponent(returnId) : '');
                                console.log("Redirecting to: " + newUrl);
                                window.location.href = newUrl;
                            });
                        } else {
                            console.log("Error in the controller", res);
                            var mb = messageBox({backdrop:'static'});
                            // mb.hideHeader();    
                            //mb.setTitle('Error');
                            mb.setTitle(res.title);
                            mb.hideCancel();
                            //mb.setBody('There was an error: Message not sent');
                            mb.setBody(res.detail);
                            mb.css('z-index', 26000)
                            mb.show();
                            mb.on('ok', function () {
                                "use strict";
                                console.log('asdsa');
                                mb.remove();
                                var baseUrl = window.location.href.split('?')[0];
                                var returnModule = $('#EditView [name="return_module"]').val();
                                var returnAction = $('#EditView [name="return_action"]').val();
                                var returnId = $('#EditView [name="return_id"]').val();
                                if(!returnId && res.id) {
                                    returnId = res.id;
                                }
                                var newUrl = baseUrl + '?module=' + encodeURIComponent(returnModule)
                                    + '&action=' + encodeURIComponent(returnAction)
                                    + (returnId ? '&record=' + encodeURIComponent(returnId) : '');
                                console.log("Redirecting to: " + newUrl);
                                window.location.href = newUrl;
                            });
                        }
                    },
                    error: function() {
                        debugger;
                        console.log("Error send Request");
                        var mb = messageBox({backdrop:'static'});
                            // mb.hideHeader();    
                            mb.setTitle('Error');
                            mb.hideCancel();
                            mb.setBody('There was an error: Message not sent');
                            mb.css('z-index', 26000)
                            mb.show();
                            mb.on('ok', function () {
                                "use strict";
                                console.log('asdsa');
                                mb.remove();
                            });
                    }
                });


            } else {
                alert('ko');
                /*
                SUGAR.alerts.show('save-success', {
                    level: 'success', // Green pop-up
                    title: 'Success',
                    messages: data.message,
                    autoClose: true
                });
                */
                // Potentially close the modal or refresh the page
                // $('#myModal').modal('hide');
            }
            return false;
        }
        // var _form = document.getElementById('EditView'); _form.action.value='Save'; if(check_form('EditView'))SUGAR.ajaxUI.submitForm(_form);return false;
        myButtons.removeAttr('onclick');
        myButtons.on('click', xxx);

    });
</script>
{/literal}

{if !$IS_MODAL}

    {literal}

        <script type="text/javascript">

        var selectTab = function(tab) {
            $('#EditView_tabs div.tab-content div.tab-pane-NOBOOTSTRAPTOGGLER').hide();
            $('#EditView_tabs div.tab-content div.tab-pane-NOBOOTSTRAPTOGGLER').eq(tab).show().addClass('active').addClass('in');
        };

        var selectTabOnError = function(tab) {
            selectTab(tab);
            $('#EditView_tabs ul.nav.nav-tabs li').removeClass('active');
            $('#EditView_tabs ul.nav.nav-tabs li a').css('color', '');

            $('#EditView_tabs ul.nav.nav-tabs li').eq(tab).find('a').first().css('color', 'red');
            $('#EditView_tabs ul.nav.nav-tabs li').eq(tab).addClass('active');

        };

        var selectTabOnErrorInputHandle = function(inputHandle) {
            var tab = $(inputHandle).closest('.tab-pane-NOBOOTSTRAPTOGGLER').attr('id').match(/^detailpanel_(.*)$/)[1];
            selectTabOnError(tab);
        };


        $(function(){
            $('#EditView_tabs ul.nav.nav-tabs li > a[data-toggle="tab"]').click(function(e){
                if(typeof $(this).parent().find('a').first().attr('id') != 'undefined') {
                    var tab = parseInt($(this).parent().find('a').first().attr('id').match(/^tab(?<number>(.)*)$/)[1]);
                    selectTab(tab);
                }
            });

            $('a[data-toggle="collapse-edit"]').click(function(e){
                if($(this).hasClass('collapsed')) {
                  // Expand panel
                    // Change style of .panel-header
                    $(this).removeClass('collapsed');
                    // Expand .panel-body
                    $(this).parents('.panel').find('.panel-body').removeClass('in').addClass('in');
                } else {
                  // Collapse panel
                    // Change style of .panel-header
                    $(this).addClass('collapsed');
                    // Collapse .panel-body
                    $(this).parents('.panel').find('.panel-body').removeClass('in').removeClass('in');
                }
            });
        });
        </script>

    {/literal}

    <script>
    console.log('bbb');
        {* Compose view has a TEMP ID in case you want to display multi instance of the ComposeView *}
      $( "#template" ).change(function() {ldelim}
          $.fn.stic_MessagesComposeView.onTemplateChange()
      {rdelim});
    //   $( "#parent_name" ).change(function() {ldelim}
    //     console.log('parent-name');
    //       $.fn.stic_MessagesComposeView.onParentChange()
    //   {rdelim});
    //   $( "#parent_id" ).change(function() {ldelim}
    //         console.log('parent-id');
    //       $.fn.stic_MessagesComposeView.onParentChange()
    //   {rdelim});
    </script>
    {/if}
</form>
