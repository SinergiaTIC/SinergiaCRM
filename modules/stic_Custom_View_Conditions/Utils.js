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

//IEPA!!!
// A eliminar el Utils.js de Conditions (s'usa el de Customizations)

// var condln = 0;
// var condln_count = 0;
// var condprefix = 'stic_custom_view_conditions_';


// /**
//  * Insert Header for condition table
//  */
// function insertConditionHeader(){
//     tablehead = document.createElement("thead");
//     tablehead.id = "sticCustomView_ConditionLines_head";
//     document.getElementById('sticCustomView_ConditionLines').appendChild(tablehead);

//     var x=tablehead.insertRow(-1);
//     x.insertCell(-1); // Remove button
//     x.insertCell(-1).innerHTML=SUGAR.language.get('stic_Custom_View_Conditions', 'LBL_FIELD');
//     x.insertCell(-1).innerHTML=SUGAR.language.get('stic_Custom_View_Conditions', 'LBL_OPERATOR');
//     x.insertCell(-1).innerHTML=SUGAR.language.get('stic_Custom_View_Conditions', 'LBL_VALUE');
// }

function insertConditionLine(){
    if(!$("#sticCustomView_ConditionLines_head").length){
        insertConditionLinesHeader();
    }
    $("#sticCustomView_ConditionLines_head").show();

    tablebody = document.createElement("tbody");
    tablebody.id = condprefix + "body" + condln;
    document.getElementById('sticCustomView_ConditionLines').appendChild(tablebody);

    var x = tablebody.insertRow(-1);
    x.id = 'condition_line' + condln;

    // Remove button
    x.insertCell(-1).innerHTML = getDeleteButton(condprefix, condln, "markConditionLineDeleted");
        // "<button type='button' class='button' id='"+condprefix+"delete_line"+condln+"' onclick='markConditionLineDeleted("+condln+")'>"+
        //     "<span class='suitepicon suitepicon-action-minus'></span>"+
        // "</button><br>"+
        // "<input type='hidden' name='"+condprefix+"deleted["+condln+"]' id='"+condprefix+"deleted"+condln+"' value='0'>"+
        // "<input type='hidden' name='"+condprefix+"id["+condln+"]' id='"+condprefix+"id"+condln+"' value=''>";

    // Field
    x.insertCell(-1).innerHTML = 
        "<select name='"+condprefix+"field["+condln+"]' id='"+condprefix+"field"+condln+"' value='' onchange='showModuleField("+condln+");'>"+ 
            view_module_fields_option_list+
        "</select>"+
        "<span id='"+condprefix+"field_label"+condln+"' ></span>";

    // Operator
    x.insertCell(-1).id=condprefix+'operatorInput'+condln;

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

function showModuleField(ln, operator_value, type_value, field_value){
    if (typeof operator_value === 'undefined') { operator_value = ''; }
    if (typeof type_value === 'undefined') { type_value = ''; }
    var is_value_set = true;
    if (typeof field_value === 'undefined') {
        field_value = '';
        is_value_set = false;
    }

    var view_field = $("#"+condprefix+'field'+ln).val();
    if(view_field != ''){
        console.log("view_field:" + view_field);
        $("#"+condprefix+'operatorInput'+ln).html(
            "<select type='text' name='"+condprefix+"operator["+ln+"]' id='"+condprefix+"operator"+ln+"'>"+
                view_module_fields_operators_option_map[view_field]+
            "</select>");
        $("#"+condprefix+'operatorInput'+ln).change(function(){changeOperator(ln);});
        // var callback = {
        //     success: function(result) {
        //         SUGAR.util.evalScript(result.responseText);
        //         $("#"+condprefix+'operatorInput'+ln).html(result.responseText);
        //         $("#"+condprefix+'operatorInput'+ln).change(function(){changeOperator(ln);});
        //     },
        //     failure: function(result) {
        //         $("#"+condprefix+'operatorInput'+ln).html("");
        //     }
        // }
        var callback2 = {
            success: function(result) {
                SUGAR.util.evalScript(result.responseText);
                $("#"+condprefix+'fieldInput'+ln).html(result.responseText);
                enableQS(true);
            },
            failure: function(result) {
                $("#"+condprefix+'fieldInput'+ln).html("");
            }
        }

        // var aow_operator_name = condprefix+"operator["+ln+"]";
        var aow_field_name = condprefix+"value["+ln+"]";

        // YAHOO.util.Connect.asyncRequest ("GET", "index.php?module=AOW_WorkFlow&action=getModuleOperatorField&view="+action_sugar_grp1+"&aow_module="+view_module+"&aow_fieldname="+aow_field+"&aow_newfieldname="+aow_operator_name+"&aow_value="+operator_value+"&rel_field="+view_module,callback);
        YAHOO.util.Connect.asyncRequest ("GET", "index.php?module=AOW_WorkFlow&action=getModuleFieldType&view="+action_sugar_grp1+"&aow_module="+view_module+"&aow_fieldname="+aow_field+"&aow_newfieldname="+aow_field_name+"&aow_value="+field_value+"&is_value_set="+is_value_set+"&aow_type="+type_value+"&rel_field="+view_module,callback2);
    } else {
        $("#"+condprefix+'operatorInput'+ln).html("");
        $("#"+condprefix+'fieldInput'+ln).html("");
    }

    if(operator_value == 'is_null'){
        $("#"+condprefix+'fieldInput'+ln).hide();
    } else {
        $("#"+condprefix+'fieldInput'+ln).show();
    }
}


function loadConditionLine(condition){
    var ln = 0;

    ln = insertConditionLine();

    for(var a in condition){
        if(document.getElementById(condprefix + a + ln) != null){
            document.getElementById(condprefix + a + ln).value = condition[a];
        }
    }

    var select_field = document.getElementById(condprefix + 'field' + ln);
    document.getElementById(condprefix + 'field_label' + ln).innerHTML = select_field.options[select_field.selectedIndex].text;

    if (condition['value'] instanceof Array) {
        condition['value'] = JSON.stringify(condition['value'])
    }

    showModuleField(ln, condition['operator'], condition['value_type'], condition['value'])

    //getView(ln,action['id']);

}


function showModuleFieldType(ln, value){
    if (typeof value === 'undefined') { value = ''; }

    var callback = {
        success: function(result) {
            document.getElementById(condprefix + 'fieldInput' + ln).innerHTML = result.responseText;
            SUGAR.util.evalScript(result.responseText);
            enableQS(false);
        },
        failure: function(result) {
            document.getElementById(condprefix + 'fieldInput' + ln).innerHTML = '';
        }
    }

    var aow_field = document.getElementById(condprefix + 'field' + ln).value;
    var type_value = document.getElementById(condprefix + "value_type[" + ln + "]").value;
    var aow_field_name = condprefix + "value[" + ln + "]";

    YAHOO.util.Connect.asyncRequest ("GET", "index.php?module=AOW_WorkFlow&action=getModuleFieldType&view="+action_sugar_grp1+"&aow_module="+view_module+"&aow_fieldname="+aow_field+"&aow_newfieldname="+aow_field_name+"&aow_value="+value+"&aow_type="+type_value+"&rel_field="+view_module,callback);
}

function changeOperator(ln){
    var aow_operator = document.getElementById(condprefix + "operator[" + ln + "]").value;

    if(aow_operator == 'is_null'){
        showModuleField(ln,aow_operator);
    } else {
        showElem(condprefix + 'fieldTypeInput' + ln);
        showElem(condprefix + 'fieldInput' + ln);
    }
}

function markConditionLineDeleted(ln){
    // collapse line; update deleted value
    document.getElementById(condprefix + 'body' + ln).style.display = 'none';
    document.getElementById(condprefix + 'deleted' + ln).value = '1';
    document.getElementById(condprefix + 'delete_line' + ln).onclick = '';

    condln_count--;
    if(condln_count == 0){
        document.getElementById('conditionLines_head').style.display = "none";
    }
}

function clearConditionLines(){
    if(document.getElementById('sticCustomView_ConditionLines') != null){
        var cond_rows = document.getElementById('sticCustomView_ConditionLines').getElementsByTagName('tr');
        var cond_row_length = cond_rows.length;
        var i;
        for (i=0; i < cond_row_length; i++) {
            if(document.getElementById(condprefix + 'delete_line'+i) != null){
                document.getElementById(condprefix + 'delete_line'+i).click();
            }
        }
    }
}


function hideElem(id){
    if(document.getElementById(id)){
        document.getElementById(id).style.display = "none";
        document.getElementById(id).value = "";
    }
}

function showElem(id){
    if(document.getElementById(id)){
        document.getElementById(id).style.display = "";
    }
}

function date_field_change(field){
    if(document.getElementById(field + '[1]').value == 'now'){
        hideElem(field + '[2]');
        hideElem(field + '[3]');
    } else {
        showElem(field + '[2]');
        showElem(field + '[3]');
    }
}
