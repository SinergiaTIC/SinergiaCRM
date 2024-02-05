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
var module = "stic_Custom_Views";

/* INCLUDES */

/* VALIDATION DEPENDENCIES */

/* VALIDATION CALLBACKS */

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    $(document).ready(function () { 
      initializeEditFields();
      initializeSelectize();
    });
    break;

  case "detail":
    $(document).ready(function() {
      initializeQuickCreateCustomization();
    });
    break;

  case "list":
    $(document).ready(function () {
      disableListMenuActions();
    });
    break;

  default:
    break;
}

function setFieldsInForm(node, init, order) {
  var form = node.querySelector('form');
  if (form) {
    // Create init input
    var input = document.createElement("input");
    input.setAttribute('type', 'hidden');
    input.setAttribute('name', 'init');
    input.setAttribute('value', init);
    form.appendChild(input);

    // Set value in customization_order input if is not set
    if (!$("#customization_order").val()) {
      $("#customization_order").val(order);
    }
  }
}

function initializeQuickCreateCustomization() {
  // When appears a new QuickCreate form in subpanel
  //  - set the correct value in init field
  //  - set customization_order if not set (for new items)
  
  // Observer for new elements
  var observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
        mutation.addedNodes.forEach(function(newNode) {
          if (newNode.nodeType === 1) {
            if (newNode.id === "subpanel_stic_custom_view_customizations_newDiv") {
              var nextOrder = 1;
              if ($("#list_subpanel_stic_custom_view_customizations .subpanel-table tbody tr.footable-empty").length == 0) {
                nextOrder = $("#list_subpanel_stic_custom_view_customizations .subpanel-table").find("tbody tr").length - 1;
              }
              if ($("#customization_order").val()=="0") {
                setFieldsInForm(newNode, 1, nextOrder);
              } else {
                setFieldsInForm(newNode, 0, nextOrder);
              }
            }
          }
        });
      }
    });
  });
  
  // Set observer for subpanel_list
  var subpanelList = document.getElementById('subpanel_list');
  var config = { childList: true, subtree: true };
  observer.observe(subpanelList, config);
}

function initializeEditFields() {
  // Hide module selector, show label with module name
  sticCustomView.editview.field("view_module").readonly().bold();

  // Hide view selector, show label with view name
  sticCustomView.editview.field("view_type").readonly().bold();

  // Set initial name
  sticCustomView.editview.field("name").input().editor.val(
    sticCustomView.editview.field("view_module").input().text() + " - " +
    sticCustomView.editview.field("view_type").input().text() + " - " +
    sticCustomView.editview.field("customization_name").input().text()
  );

  // Hide name, show label with name
  sticCustomView.editview.field("name").readonly().bold();

  // Update name when any change on customization_name
  sticCustomView.editview.field("customization_name").input().editor.on("change paste keyup", function() {
    sticCustomView.editview.field("name").input().editor.val(
      sticCustomView.editview.field("view_module").input().text() + " - " +
      sticCustomView.editview.field("view_type").input().text() + " - " +
      sticCustomView.editview.field("customization_name").input().text()
    );
    sticCustomView.editview.field("name").input().editor.change();
  });
}

function initializeSelectize() {
  var config = { placeholder: '' };
  $('select#security_groups').selectize(config);
  $('select#roles').selectize(config);
  $('select#user_type').selectize(config);
}

function disableListMenuActions() {
  // disable some list menu actions
  var selectorsToKeep = ['#massupdate_listview_top', '#export_listview_top', '#delete_listview_top'];

  // remove duplicate massive link which has not a uniq id
  $('#actionLinkTop > li > ul > li:nth-child(2) > a#massupdate_listview_top').closest('li').remove();

  $('ul#actionLinkTop li.sugar_action_button ul li').each(function () {
    var containsSelector = false;
    for (var i = 0; i < selectorsToKeep.length; i++) {
      if ($(this).find(selectorsToKeep[i]).length > 0) {
        containsSelector = true;
        break;
      }
    }
    if (!containsSelector) {
      $(this).remove();
    }
  });
}

/// Code for Actions and Conditions
function translateCondition(label) {
  return SUGAR.language.get('stic_Custom_View_Conditions', label);
}

function translateAction(label) {
  return SUGAR.language.get('stic_Custom_View_Actions', label);
}

var condln = 0;
var condln_count = 0;
var condprefix = 'sticCustomView_Condition';
var condId = condprefix+'Lines';

var actln = 0;
var actln_count = 0;
var actprefix = 'sticCustomView_Action';
var actId = actprefix+'Lines';


/**
 * Lines
 */

function getDeleteButton(prefix, ln, functionName) {
  var html = 
    "<button type='button' class='button' id='"+prefix+"delete"+ln+"' onclick='"+functionName+"("+ln+")'>"+
      "<span class='suitepicon suitepicon-action-minus'></span>"+
    "</button><br>"+
    "<input type='hidden' name='"+prefix+"deleted["+ln+"]' id='"+prefix+"deleted"+ln+"' value='0'>"+
    "<input type='hidden' name='"+prefix+"id["+ln+"]' id='"+prefix+"id"+ln+"' value=''>";
  return html;
}

/**
 * ACTIONS
 */

/**
 * Action Table Header
 */
function insertActionLinesHeader(){
  $('#'+actId).append(
    '<thead id="'+actId+'_head"><tr>' + 
      '<th style="width:75px;"></th>' + // Remove button
      '<th>'+translateAction('LBL_TYPE')+'</th>'+
      '<th>'+translateAction('LBL_ELEMENT')+'</th>'+
      '<th>'+translateAction('LBL_ACTION')+'</th>'+
      '<th>'+translateAction('LBL_VALUE')+'</th>'+
      '<th>'+translateAction('LBL_ELEMENT_SECTION')+'</th>'+
    '</thead>');
}

function insertActionLine(){
  var ln = actln;
  var id = actId;
  var prefix = actprefix;

  if(!$("#"+id+"_head").length){
      insertActionLinesHeader();
  }
  $("#"+id+"_head").show();

  tablebody = document.createElement("tbody");
  tablebody.id = prefix+"body"+ln;
  $('#'+id).append(tablebody);

  //Create row
  var x = tablebody.insertRow(-1);
  x.id = prefix+ln;
  x.insertCell(-1).id=prefix+'Cell'+'delete'+ln;   // Delete button
  x.insertCell(-1).id=prefix+'Cell'+'type'+ln;     // Action Type
  x.insertCell(-1).id=prefix+'Cell'+'element'+ln;  // Element
  x.insertCell(-1).id=prefix+'Cell'+'action'+ln;   // Action
  x.insertCell(-1).id=prefix+'Cell'+'value'+ln;    // Value
  x.insertCell(-1).id=prefix+'Cell'+'section'+ln;  // Section

  // Initial fills

  // Delete button
  $("#"+prefix+'Cell'+'delete'+ln).html(getDeleteButton(prefix, ln, "markActionLineDeleted"));

  // Action type
  $("#"+prefix+'Cell'+'type'+ln).html(
  "<select name='"+prefix+"type["+ln+"]' id='"+prefix+"type"+ln+"'>"+
    view_module_action_map.actionTypes.options+
  "</select>"+
  "<span id='"+prefix+"type"+"_label"+ln+"' ></span>");
  
  $("#"+prefix+'type'+ln).on("change", function(){onActionTypeChanged(ln);});
  $("#"+prefix+'type'+ln).val(null);
  $("#"+prefix+'type'+ln).change();

  $('.edit-view-field #'+prefix+'Lines').find('tbody').last().find('select').change(function () {
    $(this).find('td').last().removeAttr("style");
    $(this).find('td').height($(this).find('td').last().height() + 8);
  });

  actln++;
  actln_count++;

  return ln;
}
function loadActionLine(action) {
  var prefix = actprefix;
  var ln = 0;

  ln = insertActionLine();
  for(var a in action) {
    $("#"+prefix+a+ln).val(action[a]);
    $("#"+prefix+a+ln).change();
  }

  if (action['value'] instanceof Array) {
    action['value'] = JSON.stringify(action['value'])
  }
}

function markActionLineDeleted(ln){
  // collapse line; update deleted value
  $("#"+actprefix+'body'+ln).hide();
  $("#"+actprefix+'deleted'+ln).val('1');
  $("#"+actprefix+'delete'+ln).prop("onclick", null).off("click");

  actln_count--;
  if(actln_count <= 0){
    $("#"+actId+"_head").hide();
  }
}

function onActionTypeChanged(ln){
  var type = $("#"+actprefix+'type'+ln).val();
  if(type==""||type==null){
    // Reset next selectors
    $("#"+actprefix+'Cell'+'element'+ln).html("");
    $("#"+actprefix+'Cell'+'action'+ln).html("");
    $("#"+actprefix+'Cell'+'value'+ln).html("");
    $("#"+actprefix+'Cell'+'section'+ln).html("");
  } else {
    // Create next selector
    // Element selector
    $("#"+actprefix+'Cell'+'element'+ln).html(
      "<select type='text' name='"+actprefix+"element["+ln+"]' id='"+actprefix+"element"+ln+"'>"+
        view_module_action_map.actionTypes[type].elements.options+
      "</select>");

    $("#"+actprefix+'element'+ln).on("change", function(){onActionElementChanged(ln);});
    $("#"+actprefix+'element'+ln).val(null);
    $("#"+actprefix+'element'+ln).change();
  }
}
function onActionElementChanged(ln) {
  var type = $("#"+actprefix+'type'+ln).val();
  var element = $("#"+actprefix+'element'+ln).val();
  if(type==""||type==null) {
    $("#"+prefix+'type'+ln).change();
  } else if(element==""||element==null){
    // Reset next selectors
    $("#"+actprefix+'Cell'+'action'+ln).html("");
    $("#"+actprefix+'Cell'+'value'+ln).html("");
    $("#"+actprefix+'Cell'+'section'+ln).html("");
  } else {
    // Create next selector
    // Action selector
    $("#"+actprefix+'Cell'+'action'+ln).html(
      "<select type='text' name='"+actprefix+"action["+ln+"]' id='"+actprefix+"action"+ln+"'>"+
        view_module_action_map.actionTypes[type].actions.options+
      "</select>");

    $("#"+actprefix+'action'+ln).on("change", function(){onActionChanged(ln);});
    $("#"+actprefix+'action'+ln).val(null);
    $("#"+actprefix+'action'+ln).change();
  }
}
function onActionChanged(ln) {
  var type = $("#"+actprefix+'type'+ln).val();
  var element = $("#"+actprefix+'element'+ln).val();
  var action = $("#"+actprefix+'action'+ln).val();
  if(type==""||type==null) {
    $("#"+prefix+'type'+ln).change();
  } else if(element==""||element==null){
    $("#"+prefix+'element'+ln).change();
  } else if(action==""||action==null){
    // Reset next selectors
    $("#"+actprefix+'Cell'+'value'+ln).html("");
    $("#"+actprefix+'Cell'+'section'+ln).html("");
  } else {
    //Create next selector
    // Value editor
    if(type=='field_modification' && action=='fixed_value'){
      $("#"+actprefix+'Cell'+'value'+ln).html(decodeURIComponent(escape(atob(view_field_map[element].editor_base64))));
    } else {
      $("#"+actprefix+'Cell'+'value'+ln).html(decodeURIComponent(escape(atob(view_action_editor_map[action].editor_base64))));
    }
    $("#"+actprefix+'Cell'+'value'+ln).children().attr("name", actprefix+"value["+ln+"]");
    $("#"+actprefix+'Cell'+'value'+ln).children().attr("id", actprefix+"value"+ln);
    $("#"+actprefix+'Cell'+'value'+ln).children().attr('style', 'width: 90% !important');

    // Section selector
    $("#"+actprefix+'Cell'+'section'+ln).html(
      "<select type='text' name='"+actprefix+"section["+ln+"]' id='"+actprefix+"section"+ln+"'>"+
        view_module_action_map.actionTypes[type].actions[action].sections.options+
      "</select>");
    if($("#"+actprefix+"section"+ln).children().length<=1){
      $("#"+actprefix+"section"+ln).hide();
      $("#"+actprefix+'Cell'+'section'+ln).append("<p>"+$("#"+actprefix+"section"+ln).text()+"</p>");
    }
  }
}

/**
 * CONDITIONS
 */

/**
 * Condition Table Header
 */
function insertConditionLinesHeader(){
  $('#'+condId).append(
    '<thead id="'+condId+'_head"><tr>' + 
      '<th style="width:75px;"></th>' + // Remove button
      '<th>'+translateCondition('LBL_FIELD')+'</th>'+
      '<th>'+translateCondition('LBL_OPERATOR')+'</th>'+
      '<th>'+translateCondition('LBL_VALUE')+'</th>'+
    '</thead>');
}

function insertConditionLine(){
  var ln = condln;
  var id = condId;
  var prefix = condprefix;

  if(!$("#"+id+"_head").length){
      insertConditionLinesHeader();
  }
  $("#"+id+"_head").show();

  tablebody = document.createElement("tbody");
  tablebody.id = prefix+"body"+ln;
  $('#'+id).append(tablebody);

  //Create row
  var x = tablebody.insertRow(-1);
  x.id = prefix+ln;
  x.insertCell(-1).id=prefix+'Cell'+'delete'+ln;   // Delete button
  x.insertCell(-1).id=prefix+'Cell'+'field'+ln;    // Field
  x.insertCell(-1).id=prefix+'Cell'+'operator'+ln; // Operator
  x.insertCell(-1).id=prefix+'Cell'+'value'+ln;    // Value

  // Initial fills

  // Delete button
  $("#"+prefix+'Cell'+'delete'+ln).html(getDeleteButton(prefix, ln, "markConditionLineDeleted"));

  // Field
  $("#"+prefix+'Cell'+'field'+ln).html(
  "<select name='"+prefix+"field["+ln+"]' id='"+prefix+"field"+ln+"'>"+
    view_module_action_map.actionTypes.field_modification.elements.options+
  "</select>"+
  "<span id='"+prefix+"field"+"_label"+ln+"' ></span>");
  
  $("#"+prefix+'field'+ln).on("change", function(){onConditionFieldChanged(ln);});
  $("#"+prefix+'field'+ln).val(null);
  $("#"+prefix+'field'+ln).change();

  $('.edit-view-field #'+prefix+'Lines').find('tbody').last().find('select').change(function () {
    $(this).find('td').last().removeAttr("style");
    $(this).find('td').height($(this).find('td').last().height() + 8);
  });

  condln++;
  condln_count++;

  return ln;
}

function loadConditionLine(condition) {
  var prefix = condprefix;
  var ln = 0;

  ln = insertConditionLine();
  for(var a in condition) {
    $("#"+prefix+a+ln).val(condition[a]);
    $("#"+prefix+a+ln).change();
  }

  if (condition['value'] instanceof Array) {
      condition['value'] = JSON.stringify(condition['value'])
  }
}

function markConditionLineDeleted(ln){
  // collapse line; update deleted value
  $("#"+condprefix+'body'+ln).hide();
  $("#"+condprefix+'deleted'+ln).val('1');
  $("#"+condprefix+'delete'+ln).prop("onclick", null).off("click");

  condln_count--;
  if(condln_count <= 0){
    $("#"+condId+"_head").hide();
  }
}

function onConditionFieldChanged(ln){
  var field = $("#"+condprefix+'field'+ln).val();
  if(field==""||field==null){
    // Reset next selectors
    $("#"+condprefix+'Cell'+'operator'+ln).html("");
    $("#"+condprefix+'Cell'+'value'+ln).html("");
  } else {
    // Create next selector
    // Operator selector
    $("#"+condprefix+'Cell'+'operator'+ln).html(
      "<select type='text' name='"+condprefix+"operator["+ln+"]' id='"+condprefix+"operator"+ln+"'>"+
        view_field_map[field].condition_operators.options+
      "</select>");

    $("#"+condprefix+'operator'+ln).on("change", function(){onConditionOperatorChanged(ln);});
    $("#"+condprefix+'operator'+ln).val(null);
    $("#"+condprefix+'operator'+ln).change();
  }
}
function onConditionOperatorChanged(ln) {
  var field = $("#"+condprefix+'field'+ln).val();
  var operator = $("#"+condprefix+'operator'+ln).val();
  if(field==""||field==null) {
    $("#"+prefix+'field'+ln).change();
  } else if(operator==""||operator==null){
    // Reset next selectors
    $("#"+condprefix+'Cell'+'value'+ln).html("");
  } else {
    // Create next selector
    if(operator=='is_null') {
        $("#"+condprefix+'Cell'+'value'+ln).html("<p> - </p>");
    } else {
      // Value editor
      $("#"+condprefix+'Cell'+'value'+ln).html(decodeURIComponent(escape(atob(view_field_map[field].editor_base64))));
      $("#"+condprefix+'Cell'+'value'+ln).children().attr("name", condprefix+"value["+ln+"]");
      $("#"+condprefix+'Cell'+'value'+ln).children().attr("id", condprefix+"value"+ln);
      $("#"+condprefix+'Cell'+'value'+ln).children().attr('style', 'width: 90% !important');
    }
  }
}
