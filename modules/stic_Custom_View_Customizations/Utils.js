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
var module = "stic_Custom_View_Customizations";

/* INCLUDES */

/* VALIDATION DEPENDENCIES */

/* VALIDATION CALLBACKS */

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "quickcreate":
  case "edit":
  case "popup":
    $(document).ready(function () { 
      // Hide Condition lines
      if($('input[name="init"]').val()=="1") {
        sticCustomView.editview.panel("LBL_CONDITION_LINES").hide();
        sticCustomView.editview.field("customization_order").readonly();
      }
    });
    break;

  case "detail":
    break;

  case "list":
    break;

  default:
    break;
}

$(document).ready(function () {
});

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
 * Table Headers
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
  $("#"+prefix+'type'+ln).change();

  $('.edit-view-field #'+prefix+'Lines').find('tbody').last().find('select').change(function () {
    $(this).find('td').last().removeAttr("style");
    $(this).find('td').height($(this).find('td').last().height() + 8);
  });

  actln++;
  actln_count++;

  return ln;
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
  if(type==""){
    $("#"+actprefix+'Cell'+'element'+ln).html("");
    $("#"+actprefix+'Cell'+'action'+ln).html("");
    $("#"+actprefix+'Cell'+'value'+ln).html("");
    $("#"+actprefix+'Cell'+'section'+ln).html("");
  } else {
    // Element selector
    $("#"+actprefix+'Cell'+'element'+ln).html(
      "<select type='text' name='"+actprefix+"element["+ln+"]' id='"+actprefix+"element"+ln+"'>"+
        view_module_action_map.actionTypes[type].elements.options+
      "</select>");

    // Action selector
    $("#"+actprefix+'Cell'+'action'+ln).html(
        "<select type='text' name='"+actprefix+"action["+ln+"]' id='"+actprefix+"action"+ln+"'>"+
          view_module_action_map.actionTypes[type].actions.options+
        "</select>");

    $("#"+actprefix+'action'+ln).on("change", function(){onActionChanged(ln);});
    $("#"+actprefix+'element'+ln).on("change", function(){onActionChanged(ln);});
    $("#"+actprefix+'action'+ln).change();
  }
}
function onActionChanged(ln) {
  var type = $("#"+actprefix+'type'+ln).val();
  var action = $("#"+actprefix+'action'+ln).val();
  var element = $("#"+actprefix+'element'+ln).val();
  if(type==''||action==''||element==''){
    $("#"+prefix+'type'+ln).change();
  } else {
    // Value editor
    if(type=='field_modification' && action=='fixed_value'){
      $("#"+actprefix+'Cell'+'value'+ln).html(decodeURIComponent(escape(atob(view_field_editor_map[element].editor_base64))));
    } else {
      $("#"+actprefix+'Cell'+'value'+ln).html(decodeURIComponent(escape(atob(view_action_editor_map[action].editor_base64))));
    }
    $("#"+actprefix+'Cell'+'value'+ln).children().attr("name", actprefix+"value["+ln+"]");
    $("#"+actprefix+'Cell'+'value'+ln).children().attr("id", actprefix+"value"+ln);

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