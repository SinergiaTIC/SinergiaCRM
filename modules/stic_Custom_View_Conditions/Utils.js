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


var condln = 0;
var condln_count = 0;
// var view_module_fields_option_list =  new Array();
// var view_module = '';
// var flow_fields =  new Array();
// var flow_module = '';
var condprefix = 'stic_custom_view_conditions_';


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

    var select_field2 = document.getElementById(condprefix + 'module_path' + ln);
    document.getElementById(condprefix + 'module_path_label' + ln).innerHTML = select_field2.options[select_field2.selectedIndex].text;

    if (condition['value'] instanceof Array) {
        condition['value'] = JSON.stringify(condition['value'])
    }

    showModuleField(ln, condition['operator'], condition['value_type'], condition['value'])

    //getView(ln,action['id']);

}

function showConditionCurrentModuleFields(ln, value){
    if (typeof value === 'undefined') { value = ''; }

    view_module = document.getElementById('view_module').value;
    var rel_field = document.getElementById(condprefix + 'module_path' + ln).value;

    if(view_module != '' && rel_field != '') {
        var callback = {
            success: function(result) {
                var fields = JSON.parse(result.responseText);

                document.getElementById(condprefix + 'field' + ln).innerHTML = '';

                var selector = document.getElementById(condprefix + 'field' + ln);
                for (var i in fields) {
                    selector.options[selector.options.length] = new Option(fields[i], i);
                }

                if(fields[value] != null ){
                    document.getElementById(condprefix + 'field' + ln).value = value;
                }
                if(value == '') showModuleField(ln);
            }
        }
        YAHOO.util.Connect.asyncRequest ("GET", "index.php?module=AOW_WorkFlow&action=getModuleFields&aow_module="+view_module+"&view=JSON&rel_field="+rel_field+"&aow_value="+value,callback);
    }

}

function showModuleField(ln, operator_value, type_value, field_value){
    if (typeof operator_value === 'undefined') { operator_value = ''; }
    if (typeof type_value === 'undefined') { type_value = ''; }
    var is_value_set = true;
    if (typeof field_value === 'undefined') {
        field_value = '';
        is_value_set = false;
    }

    var rel_field = document.getElementById(condprefix + 'module_path' + ln).value;
    var aow_field = document.getElementById(condprefix + 'field' + ln).value;
    if(aow_field != ''){

        var callback = {
            success: function(result) {
                document.getElementById(condprefix + 'operatorInput' + ln).innerHTML = result.responseText;
                SUGAR.util.evalScript(result.responseText);
                document.getElementById(condprefix + 'operatorInput' + ln).onchange = function(){changeOperator(ln);};

            },
            failure: function(result) {
                document.getElementById(condprefix + 'operatorInput' + ln).innerHTML = '';
            }
        }
        var callback2 = {
            success: function(result) {
                document.getElementById(condprefix + 'fieldTypeInput' + ln).innerHTML = result.responseText;
                SUGAR.util.evalScript(result.responseText);
                document.getElementById(condprefix + 'fieldTypeInput' + ln).onchange = function(){showModuleFieldType(ln);};
            },
            failure: function(result) {
                document.getElementById(condprefix + 'fieldTypeInput' + ln).innerHTML = '';
            }
        }
        var callback3 = {
            success: function(result) {
                document.getElementById(condprefix + 'fieldInput' + ln).innerHTML = result.responseText;
                SUGAR.util.evalScript(result.responseText);
                enableQS(true);
            },
            failure: function(result) {
                document.getElementById(condprefix + 'fieldInput' + ln).innerHTML = '';
            }
        }

        var aow_operator_name = condprefix + "operator[" + ln + "]";
        var aow_field_type_name = condprefix + "value_type[" + ln + "]";
        var aow_field_name = condprefix + "value[" + ln + "]";

        YAHOO.util.Connect.asyncRequest ("GET", "index.php?module=AOW_WorkFlow&action=getModuleOperatorField&view="+action_sugar_grp1+"&aow_module="+view_module+"&aow_fieldname="+aow_field+"&aow_newfieldname="+aow_operator_name+"&aow_value="+operator_value+"&rel_field="+rel_field,callback);
        YAHOO.util.Connect.asyncRequest ("GET", "index.php?module=AOW_WorkFlow&action=getFieldTypeOptions&view="+action_sugar_grp1+"&aow_module="+view_module+"&aow_fieldname="+aow_field+"&aow_newfieldname="+aow_field_type_name+"&aow_value="+type_value+"&rel_field="+rel_field,callback2);
        YAHOO.util.Connect.asyncRequest ("GET", "index.php?module=AOW_WorkFlow&action=getModuleFieldType&view="+action_sugar_grp1+"&aow_module="+view_module+"&aow_fieldname="+aow_field+"&aow_newfieldname="+aow_field_name+"&aow_value="+field_value+"&is_value_set="+is_value_set+"&aow_type="+type_value+"&rel_field="+rel_field,callback3);
    } else {
        document.getElementById(condprefix + 'operatorInput' + ln).innerHTML = ''
        document.getElementById(condprefix + 'fieldTypeInput' + ln).innerHTML = '';
        document.getElementById(condprefix + 'fieldInput' + ln).innerHTML = '';
    }

    if(operator_value == 'is_null'){
        hideElem(condprefix + 'fieldTypeInput' + ln);
        hideElem(condprefix + 'fieldInput' + ln);
    } else {
        showElem(condprefix + 'fieldTypeInput' + ln);
        showElem(condprefix + 'fieldInput' + ln);
    }
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

    var rel_field = document.getElementById(condprefix + 'module_path' + ln).value;
    var aow_field = document.getElementById(condprefix + 'field' + ln).value;
    var type_value = document.getElementById(condprefix + "value_type[" + ln + "]").value;
    var aow_field_name = condprefix + "value[" + ln + "]";

    YAHOO.util.Connect.asyncRequest ("GET", "index.php?module=AOW_WorkFlow&action=getModuleFieldType&view="+action_sugar_grp1+"&aow_module="+view_module+"&aow_fieldname="+aow_field+"&aow_newfieldname="+aow_field_name+"&aow_value="+value+"&aow_type="+type_value+"&rel_field="+rel_field,callback);
}


/**
 * Insert Header
 */

function insertConditionHeader(){
    tablehead = document.createElement("thead");
    tablehead.id = "conditionLines_head";
    document.getElementById('stic_custom_view_conditionLines').appendChild(tablehead);

    var x=tablehead.insertRow(-1);
    x.id='conditionLines_head';

    var a=x.insertCell(0);
    //a.style.color="rgb(68,68,68)";

    var b=x.insertCell(1);
    b.innerHTML=SUGAR.language.get('stic_Custom_View_Conditions', 'LBL_MODULE_PATH');

    var c=x.insertCell(2);
    c.innerHTML=SUGAR.language.get('stic_Custom_View_Conditions', 'LBL_FIELD');

    var d=x.insertCell(3);
    d.innerHTML=SUGAR.language.get('stic_Custom_View_Conditions', 'LBL_OPERATOR');

    var e=x.insertCell(4);
    e.innerHTML=SUGAR.language.get('stic_Custom_View_Conditions', 'LBL_VALUE_TYPE');

    var f=x.insertCell(5);
    f.innerHTML=SUGAR.language.get('stic_Custom_View_Conditions', 'LBL_VALUE');
}

function insertConditionLine(){
    if (document.getElementById('conditionLines_head') == null) {
        insertConditionHeader();
    } else {
        document.getElementById('conditionLines_head').style.display = '';
    }

    tablebody = document.createElement("tbody");
    tablebody.id = condprefix + "body" + condln;
    document.getElementById('stic_custom_view_conditionLines').appendChild(tablebody);

    var x = tablebody.insertRow(-1);
    x.id = 'condition_line' + condln;

    var a = x.insertCell(0);
    console.log("action_sugar_grp1:" + action_sugar_grp1);
    if(action_sugar_grp1 == 'EditView'){
        a.innerHTML = "<button type='button' id='" + condprefix + "delete_line" + condln + "' class='button' value='' tabindex='116' onclick='markConditionLineDeleted(" + condln + ")'><span class='suitepicon suitepicon-action-minus'></span></button><br>";
        a.innerHTML += "<input type='hidden' name='" + condprefix + "deleted[" + condln + "]' id='" + condprefix + "deleted" + condln + "' value='0'><input type='hidden' name='" + condprefix + "id[" + condln + "]' id='" + condprefix + "id" + condln + "' value=''>";
    } else{
        a.innerHTML = '';
    }

    // var b = x.insertCell(1);
    // var viewStyle = 'display:none';
    // if(action_sugar_grp1 == 'EditView'){viewStyle = '';}
    // b.innerHTML = "<select style='"+viewStyle+"' name='" + condprefix + "module_path["+ condln +"][0]' id='" + condprefix + "module_path" + condln + "' value='' title='' tabindex='116' onchange='showConditionCurrentModuleFields(" + condln + ");'>" + flow_rel_modules + "</select>";
    // if(action_sugar_grp1 == 'EditView'){viewStyle = 'display:none';}else{viewStyle = '';}
    // b.innerHTML += "<span style='"+viewStyle+"' id='" + condprefix + "module_path_label" + condln + "' ></span>";

    var c = x.insertCell(1);
    viewStyle = 'display:none';
    if(action_sugar_grp1 == 'EditView'){viewStyle = '';}
    c.innerHTML = "<select style='"+viewStyle+"' name='" + condprefix + "field["+ condln +"]' id='" + condprefix + "field" + condln + "' value='' title='' tabindex='116' onchange='showModuleField(" + condln + ");'>" + view_module_fields_option_list + "</select>";
    if(action_sugar_grp1 == 'EditView'){viewStyle = 'display:none';}else{viewStyle = '';}
    c.innerHTML += "<span style='"+viewStyle+"' id='" + condprefix + "field_label" + condln + "' ></span>";


    var d = x.insertCell(2);
    d.id=condprefix + 'operatorInput' + condln;

    var e = x.insertCell(3);
    e.id=condprefix + 'fieldTypeInput' + condln;

    var f = x.insertCell(4);
    f.id=condprefix + 'fieldInput' + condln;

    condln++;
    condln_count++;

    $('.edit-view-field #stic_custom_view_conditionLines').find('tbody').last().find('select').change(function () {
        $(this).find('td').last().removeAttr("style");
        $(this).find('td').height($(this).find('td').last().height() + 8);
    });

    return condln -1;
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
    if(document.getElementById('stic_custom_view_conditionLines') != null){
        var cond_rows = document.getElementById('stic_custom_view_conditionLines').getElementsByTagName('tr');
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
