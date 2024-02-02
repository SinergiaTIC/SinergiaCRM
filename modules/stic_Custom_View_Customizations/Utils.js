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
    "<button type='button' class='button' id='"+prefix+"delete_line"+ln+"' onclick='"+functionName+"("+ln+")'>"+
      "<span class='suitepicon suitepicon-action-minus'></span>"+
    "</button><br>"+
    "<input type='hidden' name='"+prefix+"deleted["+ln+"]' id='"+prefix+"deleted"+ln+"' value='0'>"+
    "<input type='hidden' name='"+prefix+"id["+ln+"]' id='"+prefix+"id"+ln+"' value=''>";
  return html;
}

function insertActionLine(){
  if(!$("#"+actId+"_head").length){
      insertActionLinesHeader();
  }
  $("#"+actId+"_head").show();

  tablebody = document.createElement("tbody");
  tablebody.id = actprefix + "body" + actln;
  $('#'+actId).appendChild(tablebody);

  var x = tablebody.insertRow(-1);
  x.id = actprefix+actln;

  // Remove button
  x.insertCell(-1).innerHTML = getDeleteButton(actprefix, actln, "markActionLineDeleted");

  // Field
  x.insertCell(-1).innerHTML = 
      "<select name='"+actprefix+"field["+actln+"]' id='"+actprefix+"field"+actln+"' value='' onchange='showModuleField("+actln+");'>"+ 
          view_module_fields_option_list+
      "</select>"+
      "<span id='"+actprefix+"field_label"+actln+"' ></span>";

  // Operator
  x.insertCell(-1).id=actprefix+'operatorInput'+actln;

  // Value
  x.insertCell(-1).id=condprefix+'fieldInput'+condln;

  condln++;
  condln_count++;

  $('.edit-view-field #sticCustomView_ConditionLines').find('tbody').last().find('select').change(function () {
      $(this).find('td').last().removeAttr("style");
      $(this).find('td').height($(this).find('td').last().height() + 8);
  });

  return condln -1;
}