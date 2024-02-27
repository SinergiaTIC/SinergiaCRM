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

function translateCondition(label) {
    return translate(label,'stic_Custom_View_Conditions');
}
  
var condln = 0;
var condln_count = 0;
var condprefix = 'sticCustomView_Condition';
var condId = condprefix+'Lines';
var condValArray = [];

function getConditionDeleteButton(ln, functionName) {
    var html = 
      "<button type='button' class='button' style='padding-top:8px;' title='"+translateCustomization('LBL_DELETE_CONDITION')+"' "+
              "id='"+condprefix+"delete"+ln+"' onclick='"+functionName+"("+ln+")'>"+
        "<span class='suitepicon suitepicon-action-minus'></span>"+
      "</button><br>"+
      "<input type='hidden' name='"+condprefix+"deleted["+ln+"]' id='"+condprefix+"deleted"+ln+"' value='0'>"+
      "<input type='hidden' name='"+condprefix+"id["+ln+"]' id='"+condprefix+"id"+ln+"' value=''>";
    return html;
  }

/**
 * Condition Table Header
 */
function insertConditionLinesHeader(){
    $('#'+condId).append(
      '<thead id="'+condId+'_head"><tr>' + 
        '<th style="width:70px;"></th>' + // Remove button
        '<th style="width:25%;">'+translateCondition('LBL_FIELD')+'</th>'+
        '<th style="width:25%;">'+translateCondition('LBL_OPERATOR')+'</th>'+
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
    $("#"+prefix+'Cell'+'delete'+ln).html(getConditionDeleteButton(ln, "markConditionLineDeleted"));
  
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
  
  function loadConditionLine(conditionString) {
    var prefix = condprefix;
    var ln = 0;

    var condition = JSON.parse(conditionString);
  
    ln = insertConditionLine();
    if (condition['value'] instanceof Array) {
      condition['value'] = JSON.stringify(condition['value']);
    }
    condValArray.push({line:ln, value:(condition['value']??"").split("|")[0]});
    for(var a in condition) {
      $("#"+prefix+a+ln).val(condition[a]);
      $("#"+prefix+a+ln).change();
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
        "</select>"+
        "<input type='hidden' name='"+condprefix+"value_type["+ln+"]' id='"+condprefix+"value_type"+ln+"' value='"+view_field_map[field].type+"'>"
        );
  
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
      if(operator=='is_null'||operator=='is_not_null') {
          $("#"+condprefix+'Cell'+'value'+ln).html("<p> - </p>");
      } else {
        // Value editor
        if($("#"+condprefix+'Cell'+'value'+ln).html()=="" || 
           $("#"+condprefix+'Cell'+'value'+ln).html()=="<p> - </p>") {
            var condValue = undefined;
            for(let i=0; i<condValArray.length; i++) {
              if(condValArray[i].line==ln) {
                condValue = condValArray[i].value;
                condValArray.splice(i,1);
                break;
              }
            }
            getModuleFieldEditor(ln, condprefix, condValue);
        }
      }
    }
  }