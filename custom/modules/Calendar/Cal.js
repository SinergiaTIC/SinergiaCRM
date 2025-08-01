/**
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
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
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo, "Supercharged by SuiteCRM" logo and “Nonprofitized by SinergiaCRM” logo. 
 * If the display of the logos is not reasonably feasible for technical reasons, 
 * the Appropriate Legal Notices must display the words "Powered by SugarCRM", 
 * "Supercharged by SuiteCRM" and “Nonprofitized by SinergiaCRM”. 
 */
var CAL = {};
CAL.slot_height = 14;
CAL.dropped = 0;
CAL.records_openable = true;
CAL.moved_from_cell = "";
CAL.deleted_id = "";
CAL.deleted_module = "";
CAL.tmp_header = "";
CAL.disable_creating = false;
CAL.record_editable = false;
CAL.shared_users = {};
CAL.shared_users_count = 0;
CAL.script_evaled = false;
CAL.editDialog = false;
CAL.settingsDialog = false;
CAL.sharedDialog = false;
CAL.basic = {};
CAL.basic.items = {};
CAL.update_dd = new YAHOO.util.CustomEvent("update_dd");
CAL.dd_registry = new Object();
CAL.resize_registry = new Object();
CAL.print = false;
CAL.dom = YAHOO.util.Dom;
CAL.get = YAHOO.util.Dom.get;
CAL.query = YAHOO.util.Selector.query;
CAL.destroy_ui = function (id) {
    if (CAL.items_resizable && typeof CAL.resize_registry[id] != "undefined") {
        CAL.resize_registry[id].destroy();
        delete CAL.resize_registry[id];
    }
    if (CAL.items_draggable && typeof CAL.dd_registry[id] != "undefined") CAL.dd_registry[id].unreg();
    delete CAL.dd_registry[id];
};
CAL.basic.remove = function (item) {
    if (typeof CAL.basic.items[item.user_id] == "undefined") CAL.basic.items[item.user_id] = new Object();
    delete CAL.basic.items[item.user_id][item.record];
};
CAL.basic.add = function (item) {
    if (typeof CAL.basic.items[item.user_id] == "undefined") CAL.basic.items[item.user_id] = new Object();
    CAL.basic.items[item.user_id][item.record] = item;
};
CAL.init_edit_dialog = function (params) {
    CAL.editDialog = false;
    var rd = CAL.get("cal-edit");
};
CAL.open_edit_dialog = function (params) {
    $(".modal-cal-edit").modal("show");
};
CAL.close_edit_dialog = function () {
    CAL.reset_edit_dialog();
};
CAL.remove_edit_dialog = function () {
    var rd_c = CAL.get("cal-edit_c");
    if (rd_c) {
        rd_c.parentNode.removeChild(rd_c);
    }
};
CAL.reset_edit_dialog = function () {
    var e;
    document.forms["CalendarEditView"].elements["current_module"].value = "Meetings";
    CAL.get("radio_call").removeAttribute("disabled");
    CAL.get("radio_meeting").removeAttribute("disabled");
    CAL.get("radio_call").checked = false;
    CAL.get("radio_meeting").checked = true;
    CAL.get("send_invites").value = "";
    if ((e = CAL.get("record"))) e.value = "";
    if ((e = CAL.get("list_div_win"))) e.style.display = "none";
    if (typeof SugarWidgetSchedulerSearch.hideCreateForm != "undefined") SugarWidgetSchedulerSearch.hideCreateForm();
    $("#scheduler .schedulerInvitees").css("display", "");
    $("#create-invitees-title").css("display", "");
    $("#create-invitees-buttons").css("display", "");
    if (CAL.enable_repeat) {
        CAL.reset_repeat_form();
    }
    CAL.GR_update_focus("Meetings", "");
    CAL.select_tab("cal-tab-1");
    QSFieldsArray = new Array();
    QSProcessedFieldsArray = new Array();
};
CAL.reset_repeat_form = function () {
    document.forms["CalendarRepeatForm"].reset();
    var fields = ["type", "interval", "count", "until", "dow"];
    CAL.each(fields, function (i, field) {
        CAL.get("repeat_" + field).value = "";
    });
    toggle_repeat_type();
    CAL.get("repeat_parent_id").value = "";
    CAL.get("edit_all_recurrences").value = "";
    CAL.get("edit_all_recurrences_block").style.display = "none";
    CAL.get("cal-repeat-block").style.display = "none";
};
CAL.select_tab = function (tid) {};
CAL.fill_repeat_data = function () {
    if (CAL.enable_repeat && (CAL.get("current_module").value == "Meetings" || CAL.get("current_module").value == "Calls")) {
        if ((repeat_type = document.forms["CalendarRepeatForm"].repeat_type.value)) {
            document.forms["CalendarEditView"].repeat_type.value = repeat_type;
            document.forms["CalendarEditView"].repeat_interval.value = document.forms["CalendarRepeatForm"].repeat_interval.value;
            if (document.getElementById("repeat_count_radio").checked) {
                document.forms["CalendarEditView"].repeat_count.value = document.forms["CalendarRepeatForm"].repeat_count.value;
                document.forms["CalendarEditView"].repeat_until.value = "";
            } else {
                document.forms["CalendarEditView"].repeat_until.value = document.forms["CalendarRepeatForm"].repeat_until.value;
                document.forms["CalendarEditView"].repeat_count.value = "";
            }
            if (repeat_type == "Weekly") {
                var repeat_dow = "";
                for (var i = 0; i < 7; i++) if (CAL.get("repeat_dow_" + i).checked) repeat_dow += i.toString();
                CAL.get("repeat_dow").value = repeat_dow;
            }
        }
    }
};
CAL.fill_repeat_tab = function (data) {
    if (!CAL.enable_repeat) return;
    if (typeof data.repeat_parent_id != "undefined") {
        CAL.get("cal-repeat-block").style.display = "none";
        CAL.get("edit_all_recurrences_block").style.display = "";
        CAL.get("edit_all_recurrences").value = "";
        CAL.get("repeat_parent_id").value = data.repeat_parent_id;
        return;
    }
    CAL.get("cal-repeat-block").style.display = "";
    var repeat_type = "";
    var set_default_repeat_until = true;
    if (typeof data.repeat_type != "undefined") {
        repeat_type = data.repeat_type;
        document.forms["CalendarRepeatForm"].repeat_type.value = data.repeat_type;
        document.forms["CalendarRepeatForm"].repeat_interval.value = data.repeat_interval;
        if (data.repeat_count != "" && data.repeat_count != 0) {
            document.forms["CalendarRepeatForm"].repeat_count.value = data.repeat_count;
            CAL.get("repeat_count_radio").checked = true;
            CAL.get("repeat_until_radio").checked = false;
        } else {
            document.forms["CalendarRepeatForm"].repeat_until.value = data.repeat_until;
            CAL.get("repeat_until_radio").checked = true;
            CAL.get("repeat_count_radio").checked = false;
            set_default_repeat_until = false;
        }
        if (data.repeat_type == "Weekly") {
            var arr = data.repeat_dow.split("");
            CAL.each(arr, function (i, d) {
                CAL.get("repeat_dow_" + d).checked = true;
            });
        }
        CAL.get("cal-repeat-block").style.display = "";
        CAL.get("edit_all_recurrences_block").style.display = "none";
        toggle_repeat_type();
    }
    CAL.get("edit_all_recurrences").value = "true";
    if (typeof data.current_dow != "undefined" && repeat_type != "Weekly") CAL.get("repeat_dow_" + data.current_dow).checked = true;
    if (typeof data.default_repeat_until != "undefined" && set_default_repeat_until)
        CAL.get("repeat_until_input").value = data.default_repeat_until;
};
CAL.repeat_tab_handle = function (module_name) {
    clear_all_errors();
    toggle_repeat_type();
};
CAL.GR_update_user = function (user_id) {
    var callback = {
        success: function (o) {
            SUGAR.util.globalEval("res = (" + o.responseText + ")");
            GLOBAL_REGISTRY.focus.users_arr_hash = undefined;
        },
    };
    var data = { users: user_id };
    var url = "index.php?module=Calendar&action=GetGRUsers&sugar_body_only=true";
    YAHOO.util.Connect.asyncRequest("POST", url, callback, CAL.toURI(data));
};
CAL.GR_update_focus = function (module, record) {
    if (record == "") {
        GLOBAL_REGISTRY["focus"] = { module: module, users_arr: [], fields: { id: "-1" } };
        SugarWidgetScheduler.update_time();
    } else {
        var callback = {
            success: function (o) {
                SUGAR.util.globalEval("retValue = " + o.responseText);
                res = retValue;
                SugarWidgetScheduler.update_time();
                if (CAL.record_editable) {
                    CAL.enable_buttons();
                }
            },
        };
        var url = "index.php?module=Calendar&action=GetGR&sugar_body_only=true&type=" + module + "&record=" + record;
        YAHOO.util.Connect.asyncRequest("POST", url, callback, false);
    }
};
CAL.toggle_settings = function () {
    $(".modal-calendar-settings").modal("toggle");
};
CAL.fill_invitees = function () {
    CAL.get("user_invitees").value = "";
    CAL.get("contact_invitees").value = "";
    CAL.get("lead_invitees").value = "";
    CAL.each(GLOBAL_REGISTRY["focus"].users_arr, function (i, v) {
        var field_name = "";
        if (v.module == "User") field_name = "user_invitees";
        if (v.module == "Contact") field_name = "contact_invitees";
        if (v.module == "Lead") field_name = "lead_invitees";
        var str = CAL.get(field_name).value;
        CAL.get(field_name).value = str + v.fields.id + ",";
    });
};
CAL.repeat_type_selected = function () {
    var rt;
    if ((rt = CAL.get("repeat_type"))) {
        if (rt.value == "Weekly") {
            var nodes = CAL.query(".weeks_checks_div");
            CAL.each(nodes, function (i, v) {
                nodes[i].style.display = "block";
            });
        } else {
            var nodes = CAL.query(".weeks_checks_div");
            CAL.each(nodes, function (i, v) {
                nodes[i].style.display = "none";
            });
        }
        if (rt.value == "") {
            CAL.get("repeat_interval").setAttribute("disabled", "disabled");
            CAL.get("repeat_end_date").setAttribute("disabled", "disabled");
        } else {
            CAL.get("repeat_interval").removeAttribute("disabled");
            CAL.get("repeat_end_date").removeAttribute("disabled");
        }
    }
};
CAL.load_form = function (module_name, record, edit_all_recurrences, cal_event) {
    CAL.disable_creating = true;
    CAL.reset_edit_dialog();
    CAL.disable_buttons();    
    var e;
    var to_open = true;
    if (module_name != "Meetings" && module_name != "Calls") {
        to_open = false;
    }
    if (module_name == "Tasks") {
        var url = "index.php?to_pdf=1&module=Home&action=AdditionalDetailsRetrieve&bean=" + cal_event.module + "&id=" + cal_event.record;
        var body = SUGAR.language.translate("app_strings", "LBL_LOADING_PAGE");
        $.ajax(url)
            .done(function (data) {
                SUGAR.util.globalEval(data);
                $(".modal-cal-tasks-edit .modal-body .container-fluid").html(result.body);
            })
            .fail(function () {
                $(".modal-cal-tasks-edit .modal-body .container-fluid").html(
                    SUGAR.language.translate("app_strings", "LBL_EMAIL_ERROR_GENERAL_TITLE")
                );
            })
            .always(function () {});
        $(".modal-cal-tasks-edit .modal-body .container-fluid").html(body);
        $(".modal-cal-tasks-edit").modal("show");
        $("#btn-view-task")
            .unbind()
            .click(function () {
                window.location.assign("index.php?module=" + cal_event.module + "&action=DetailView&record=" + cal_event.record);
            });
        $("#btn-tasks-full-form")
            .unbind()
            .click(function () {
                window.location.assign("index.php?module=" + cal_event.module + "&action=EditView&record=" + cal_event.record);
            });
    } else if (module_name == "FP_events") {
        var url = "index.php?to_pdf=1&module=Home&action=AdditionalDetailsRetrieve&bean=" + cal_event.module + "&id=" + cal_event.record;
        var body = SUGAR.language.translate("app_strings", "LBL_LOADING_PAGE");
        $.ajax(url)
            .done(function (data) {
                SUGAR.util.globalEval(data);
                $(".modal-cal-events-edit .modal-body .container-fluid").html(result.body);
            })
            .fail(function () {
                $(".modal-cal-events-edit .modal-body .container-fluid").html(
                    SUGAR.language.translate("app_strings", "LBL_EMAIL_ERROR_GENERAL_TITLE")
                );
            })
            .always(function () {});
        $(".modal-cal-events-edit .modal-body .container-fluid").html(body);
        $(".modal-cal-events-edit").modal("show");
        $("#btn-view-events")
            .unbind()
            .click(function () {
                window.location.assign("index.php?module=" + cal_event.module + "&action=DetailView&record=" + cal_event.record);
            });
        $("#btn-events-full-form")
            .unbind()
            .click(function () {
                window.location.assign("index.php?module=" + cal_event.module + "&action=EditView&record=" + cal_event.record);
            });
    }
    if (to_open && CAL.records_openable) {
        CAL.get("form_content").style.display = "none";
        CAL.disable_buttons();
        CAL.get("title-cal-edit").innerHTML = CAL.lbl_loading;
        CAL.repeat_tab_handle(module_name);
        ajaxStatus.showStatus(SUGAR.language.get("app_strings", "LBL_LOADING"));
        params = {};
        if (edit_all_recurrences) {
            params = { stay_on_tab: true };
        }
        CAL.open_edit_dialog(params);
        CAL.get("record").value = "";
        if (!edit_all_recurrences) {
            edit_all_recurrences = "";
        }
        var callback = {
            success: function (o) {
                try {
                    SUGAR.util.globalEval("retValue = (" + o.responseText + ")");
                    res = retValue;
                } catch (err) {
                    alert(CAL.lbl_error_loading);
                    CAL.editDialog.cancel();
                    ajaxStatus.hideStatus();
                    return;
                }
                if (res.access == "yes") {
                    var fc = document.getElementById("form_content");
                    CAL.script_evaled = false;
                    fc.innerHTML = '<script type="text/javascript">CAL.script_evaled = true;</script>' + res.html;
                    if (!CAL.script_evaled) {
                        SUGAR.util.evalScript(res.html);
                    }
                    CAL.get("record").value = res.record;
                    CAL.get("current_module").value = res.module_name;
                    var mod_name = res.module_name;
                    if (mod_name == "Meetings") CAL.get("radio_meeting").checked = true;
                    if (mod_name == "Calls") CAL.get("radio_call").checked = true;
                    if (res.edit == 1) {
                        CAL.record_editable = true;
                    } else {
                        CAL.record_editable = false;
                    }
                    CAL.get("radio_call").setAttribute("disabled", "disabled");
                    CAL.get("radio_meeting").setAttribute("disabled", "disabled");
                    SUGAR.util.globalEval(res.gr);
                    SugarWidgetScheduler.update_time();
                    if (CAL.record_editable) {
                        CAL.enable_buttons();
                    }
                    CAL.get("form_content").style.display = "";
                    if (typeof res.repeat != "undefined") {
                        CAL.fill_repeat_tab(res.repeat);
                    }
                    CAL.get("title-cal-edit").innerHTML = CAL.lbl_edit;
                    ajaxStatus.hideStatus();
                    CAL.get("btn-save").focus();
                    setTimeout(function () {
                        if (!res.edit) {
                            $("#scheduler .schedulerInvitees").css("display", "none");
                            $("#create-invitees-buttons").css("display", "none");
                            $("#create-invitees-title").css("display", "none");
                        }
                        enableQS(false);
                        disableOnUnloadEditView();
                    }, 500);
                } else alert(CAL.lbl_error_loading);
            },
            failure: function () {
                alert(CAL.lbl_error_loading);
            },
        };
        var url = "index.php?module=Calendar&action=QuickEdit&sugar_body_only=true";
        var data = { current_module: module_name, record: record, edit_all_recurrences: edit_all_recurrences };
        YAHOO.util.Connect.asyncRequest("POST", url, callback, CAL.toURI(data));
    }
};
CAL.edit_all_recurrences = function () {
    var record = CAL.get("record").value;
    if (CAL.get("repeat_parent_id").value != "") {
        record = CAL.get("repeat_parent_id").value;
        CAL.get("repeat_parent_id").value = "";
    }
    var module = CAL.get("current_module").value;
    if (record != "") {
        CAL.load_form(module, record, true);
    }
};
CAL.remove_shared = function (record_id, edit_all_recurrences) {
    if (typeof edit_all_recurrences == "undefined") edit_all_recurrences = false;
    var e;
    var arr = new Array();
    if (CAL.enable_repeat && edit_all_recurrences) {
        var nodes = CAL.query("div.act_item[repeat_parent_id='" + record_id + "']");
        CAL.each(nodes, function (i, v) {
            var record = nodes[i].getAttribute("record");
            if (!CAL.contains(arr, record)) arr.push(record);
            nodes[i].parentNode.removeChild(nodes[i]);
            CAL.destroy_ui(nodes[i].id);
        });
    }
    CAL.each(CAL.shared_users, function (user_id, v) {
        if ((e = CAL.get(record_id + "____" + v))) {
            CAL.destroy_ui(e.id);
            e.parentNode.removeChild(e);
        }
        CAL.basic.remove({ record: record_id, user_id: user_id });
        CAL.each(arr, function (i, id) {
            CAL.basic.remove({ record: id, user_id: user_id });
        });
    });
};
CAL.change_activity_type = function (mod_name) {
    if (typeof CAL.current_params.module_name != "undefined") if (CAL.current_params.module_name == mod_name) return;
    var e, user_name, user_id, date_start;
    CAL.get("title-cal-edit").innerHTML = CAL.lbl_loading;
    document.forms["CalendarEditView"].elements["current_module"].value = mod_name;
    CAL.current_params.module_name = mod_name;
    QSFieldsArray = new Array();
    QSProcessedFieldsArray = new Array();
    CAL.load_create_form(CAL.current_params);
};
CAL.load_create_form = function (params) {
    CAL.reset_edit_dialog();
    CAL.disable_buttons();
    ajaxStatus.showStatus(SUGAR.language.get("app_strings", "LBL_LOADING"));
    CAL.repeat_tab_handle(CAL.current_params.module_name);
    var callback = {
        success: function (o) {
            try {
                SUGAR.util.globalEval("retValue = (" + o.responseText + ")");
                res = retValue;
            } catch (err) {
                alert(CAL.lbl_error_loading);
                $(".modal-cal-edit").modal("hide");
                ajaxStatus.hideStatus();
                return;
            }
            if (res.access == "yes") {
                var fc = document.getElementById("form_content");
                CAL.script_evaled = false;
                fc.innerHTML = '<script type="text/javascript">CAL.script_evaled = true;</script>' + res.html;
                if (!CAL.script_evaled) {
                    SUGAR.util.evalScript(res.html);
                }
                CAL.get("record").value = "";
                CAL.get("current_module").value = res.module_name;
                var mod_name = res.module_name;
                if (res.edit == 1) {
                    CAL.record_editable = true;
                } else {
                    CAL.record_editable = false;
                }
                CAL.get("title-cal-edit").innerHTML = CAL.lbl_create_new;
                if (typeof res.repeat != "undefined") {
                    CAL.fill_repeat_tab(res.repeat);
                }
                CAL.enable_buttons();
                setTimeout(function () {
                    SugarWidgetScheduler.update_time();
                    enableQS(false);
                    disableOnUnloadEditView();
                    if (typeof SUGAR.loadDynamicEnum === 'undefined') {

                        $.getScript('include/SugarFields/Fields/Dynamicenum/SugarFieldDynamicenum.js')
                            .done(function() {
                                CAL.initializeDynamicEnumFields();
                            })
                            .fail(function() {
                                console.error('No se pudo cargar SugarFieldDynamicenum.js');
                            });
                    } else {
                        CAL.initializeDynamicEnumFields();
                    }
                }, 500);
                ajaxStatus.hideStatus();
            } else {
                alert(CAL.lbl_error_loading);
                ajaxStatus.hideStatus();
            }
        },
        failure: function () {
            alert(CAL.lbl_error_loading);
            ajaxStatus.hideStatus();
        },
    };
    var url = "index.php?module=Calendar&action=QuickEdit&sugar_body_only=true";
    var data = {
        current_module: params.module_name,
        assigned_user_id: params.user_id,
        assigned_user_name: params.user_name,
        date_start: params.date_start,
    };
    if ("date_end" in params && params.date_end != "") {
        data["date_end"] = params.date_end;
    }
    YAHOO.util.Connect.asyncRequest("POST", url, callback, CAL.toURI(data));
};

CAL.initializeDynamicEnumFields = function() {
    
    function getParentEnumFromVardef(fieldId, callback) {

        YAHOO.util.Connect.asyncRequest(
            'GET',
            'index.php?module=Calendar&action=getFieldVardef&field=' + fieldId + '&to_pdf=1', 
            {
                success: function(o) {
                    try {
                        var jsonStr = o.responseText;
                        if (jsonStr.indexOf('<!DOCTYPE') !== -1) {
                            jsonStr = jsonStr.substring(0, jsonStr.indexOf('<!DOCTYPE'));
                        }
                                                
                        var response = YAHOO.lang.JSON.parse(jsonStr);

                        if (response.vardef && response.vardef.parentenum) {
                            callback(response.vardef.parentenum);
                        } else {
                            callback(null);
                        }
                    } catch (e) {
                        console.error("Error parsing vardef response:", e);
                        console.error("Error details:", e.message);
                        callback(null);
                    }
                },
                failure: function(o) {
                    console.error("Failed to get vardef for field:", fieldId);
                    callback(null);
                }
            }
        );
    }

    var dynamicFields = document.querySelectorAll('div[type="dynamicenum"] select');
    
    dynamicFields.forEach(function(dynamicField) {
        var dynamicFieldId = dynamicField.id;
        
        getParentEnumFromVardef(dynamicFieldId, function(parentFieldId) {
            if (parentFieldId) {
                
                if (typeof de_entries === 'undefined') {
                    window.de_entries = new Array();
                }
                
                var initDynamicEnum = function() {
                    loadDynamicEnum(parentFieldId, dynamicFieldId);
                };
                
                var parentField = document.getElementById(parentFieldId);
                if (parentField) {
                    parentField.removeEventListener('change', initDynamicEnum); 
                    parentField.addEventListener('change', initDynamicEnum);
                }
                
                initDynamicEnum();
                
                if (SUGAR.ajaxUI && SUGAR.ajaxUI.hist_loaded) {
                    initDynamicEnum();
                }
            }
        });
    });
};

if (typeof YAHOO !== 'undefined') {
    YAHOO.util.Event.onDOMReady(function() {
        CAL.initializeDynamicEnumFields();
    });
}

if (typeof CAL !== 'undefined' && CAL.load) {
    var originalLoadFunction = CAL.load;
    CAL.load = function() {
        originalLoadFunction.apply(this, arguments);
        CAL.initializeDynamicEnumFields();
    };
}

CAL.full_form = function () {
    var e = document.createElement("input");
    e.setAttribute("type", "hidden");
    e.setAttribute("name", "module");
    e.value = CAL.get("current_module").value;
    CAL.get("form_content").parentNode.appendChild(e);
    var e = document.createElement("input");
    e.setAttribute("type", "hidden");
    e.setAttribute("name", "action");
    e.value = "EditView";
    CAL.get("form_content").parentNode.appendChild(e);
    document.forms["CalendarEditView"].action = "index.php";
    document.forms["CalendarEditView"].full_form = "true";
    document.forms["CalendarEditView"].submit();
};
CAL.disable_buttons = function () {
    CAL.get("btn-save").setAttribute("disabled", "disabled");
    CAL.get("btn-send-invites").setAttribute("disabled", "disabled");
    CAL.get("btn-delete").setAttribute("disabled", "disabled");
    CAL.get("btn-full-form").setAttribute("disabled", "disabled");
    if (CAL.enable_repeat) {
        CAL.get("btn-edit-all-recurrences").setAttribute("disabled", "disabled");
        CAL.get("btn-remove-all-recurrences").setAttribute("disabled", "disabled");
    }
};
CAL.enable_buttons = function () {
    CAL.get("btn-save").removeAttribute("disabled");
    CAL.get("btn-send-invites").removeAttribute("disabled");
    if (CAL.get("record").value != "") CAL.get("btn-delete").removeAttribute("disabled");
    CAL.get("btn-full-form").removeAttribute("disabled");
    if (CAL.enable_repeat) {
        CAL.get("btn-edit-all-recurrences").removeAttribute("disabled");
        CAL.get("btn-remove-all-recurrences").removeAttribute("disabled");
    }
};
CAL.dialog_create = function (date, end_date, user_id) {
    var e, user_id, user_name;
    CAL.get("title-cal-edit").innerHTML = CAL.lbl_loading;
    CAL.open_edit_dialog();
    CAL.disable_buttons();
    var module_name = CAL.get("current_module").value;
    
    // STIC-Custom 20210927 AAM - Adding Shared Day option to view
    // STIC#422
    if (CAL.view == "sharedDay" || CAL.view == "sharedWeek" || CAL.view == "sharedMonth") {
    // END STIC
        user_name = "";
        CAL.GR_update_user(user_id);
        $.ajax({ url: "index.php?module=Calendar&action=getUser&record=" + user_id }).done(function (data) {
            data = jQuery.parseJSON(data);
            user_name = data.user_name;
            callback(user_name, user_id, module_name, date, end_date);
        });
    } else {
        user_id = CAL.current_user_id;
        user_name = CAL.current_user_name;
        CAL.GR_update_user(CAL.current_user_id);
        callback(user_name, user_id, module_name, date, end_date);
    }
    function callback(user_name, user_id, module_name, date, end_date) {
        var params = { module_name: module_name, user_id: user_id, user_name: user_name, date_start: date, date_end: "" };
        if (end_date != "") {
            params.date_end = end_date;
        }
        CAL.current_params = params;
        CAL.load_create_form(CAL.current_params);
    }
};
CAL.dialog_save = function () {
    if (!check_form("CalendarEditView")) {
        return;
    }
    CAL.disable_buttons();
    ajaxStatus.showStatus(SUGAR.language.get("app_strings", "LBL_SAVING"));
    if (CAL.get("send_invites").value == "1") {
        CAL.get("title-cal-edit").innerHTML = CAL.lbl_sending;
    } else {
        CAL.get("title-cal-edit").innerHTML = CAL.lbl_saving;
    }
    CAL.fill_invitees();
    CAL.fill_repeat_data();
    var callback = {
        success: function (o) {
            try {
                SUGAR.util.globalEval("retValue = (" + o.responseText + ")");
                res = retValue;
            } catch (err) {
                alert(CAL.lbl_error_saving);
                $(".modal-cal-edit").modal("hide");
                ajaxStatus.hideStatus();
                return;
            }
            if (res.access == "yes") {
                if (typeof res.limit_error != "undefined") {
                    var alert_msg = CAL.lbl_repeat_limit_error;
                    alert(alert_msg.replace("$limit", res.limit));
                    CAL.get("title-cal-edit").innerHTML = CAL.lbl_edit;
                    ajaxStatus.hideStatus();
                    CAL.enable_buttons();
                    return;
                }
                $(".modal-cal-edit").modal("hide");
                CAL.update_vcal();
                var newEvent = new Object();
                var thisCal = $('div[id^="calendar"].fc');
                if (thisCal.length > 1) {
                    var user_id = res.user_id;
                    if (user_id === "") {
                        user_id = res.users[0];
                    }
                    thisCal = $("#calendar" + user_id);
                }
                thisCal.fullCalendar("removeEvents", res["record"]);
                newEvent.module = res["module_name"];
                newEvent.title = res["name"];
                newEvent.record = res["record"];
                newEvent.id = res["record"];
                if (undefined !== global_colorList[res.module_name]) {
                    newEvent.backgroundColor = "#" + global_colorList[res.module_name].body;
                    newEvent.borderColor = "#" + global_colorList[res.module_name].border;
                    newEvent.textColor = "#" + global_colorList[res.module_name].text;
                }
                newEvent.start = new Date(
                    moment.utc(moment.unix(res["ts_start"])).format("MM/DD/YYYY") +
                        " " +
                        moment(res["time_start"], "hh:mma").format("HH:mm")
                );
                newEvent.end = moment(
                    new Date(
                        moment.utc(moment.unix(res["ts_start"])).format("MM/DD/YYYY") +
                            " " +
                            moment(res["time_start"], "hh:mma").format("HH:mm")
                    )
                )
                    .add(res["duration_hours"], "hours")
                    .add(res["duration_minutes"], "minutes");
                if (res["duration_hours"] % 24 === 0 && res["time_start"] == "12:00am") {
                    newEvent.allDay = "true";
                }
                thisCal.fullCalendar("renderEvent", newEvent);
                if (res["repeat"]) {
                    $.each(res["repeat"], function (key, value) {
                        var newEvent = new Object();
                        newEvent.module = res["module_name"];
                        newEvent.title = res["name"];
                        newEvent.record = value["id"];
                        newEvent.id = value["id"];
                        newEvent.start = new Date(
                            moment.utc(moment.unix(value["ts_start"])).format("MM/DD/YYYY") +
                                " " +
                                moment(res["time_start"], "hh:mma").format("HH:mm")
                        );
                        newEvent.end = moment(
                            new Date(
                                moment.utc(moment.unix(value["ts_start"])).format("MM/DD/YYYY") +
                                    " " +
                                    moment(res["time_start"], "hh:mma").format("HH:mm")
                            )
                        )
                            .add(res["duration_hours"], "hours")
                            .add(res["duration_minutes"], "minutes");
                        if (res["duration_hours"] % 24 === 0 && res["time_start"] == "12:00am") {
                            newEvent.allDay = "true";
                        }
                        thisCal.fullCalendar("renderEvent", newEvent);
                    });
                }
                ajaxStatus.hideStatus();
            } else {
                alert(CAL.lbl_error_saving);
                ajaxStatus.hideStatus();
            }
        },
        failure: function () {
            alert(CAL.lbl_error_saving);
            ajaxStatus.hideStatus();
        },
    };
    var url = "index.php?module=Calendar&action=SaveActivity&sugar_body_only=true";
    YAHOO.util.Connect.setForm(CAL.get("CalendarEditView"));
    YAHOO.util.Connect.asyncRequest("POST", url, callback, false);
};
CAL.remove_all_recurrences = function () {
    if (confirm(CAL.lbl_confirm_remove_all_recurring)) {
        if (CAL.get("repeat_parent_id").value != "") {
            CAL.get("record").value = CAL.get("repeat_parent_id").value;
        }
        CAL.get("edit_all_recurrences").value = true;
        CAL.dialog_remove();
    }
};
CAL.dialog_remove = function () {
    CAL.deleted_id = CAL.get("record").value;
    CAL.deleted_module = CAL.get("current_module").value;
    var remove_all_recurrences = CAL.get("edit_all_recurrences").value;
    var isRecurrence = false;
    if (CAL.enable_repeat) {
        if (CAL.get("repeat_parent_id").value != "") {
            var isRecurrence = true;
        } else {
            if (document.CalendarRepeatForm.repeat_type.value != "") {
                var isRecurrence = true;
            }
        }
    }
    var callback = {
        success: function (o) {
            $("#calendar" + global_current_user_id).fullCalendar("removeEvents", CAL.deleted_id);
        },
        failure: function () {
            alert(CAL.lbl_error_saving);
        },
    };
    var data = { current_module: CAL.deleted_module, record: CAL.deleted_id, remove_all_recurrences: remove_all_recurrences };
    var url = "index.php?module=Calendar&action=Remove&sugar_body_only=true";
    YAHOO.util.Connect.asyncRequest("POST", url, callback, CAL.toURI(data));
    $(".modal-cal-edit").modal("hide");
};
CAL.refresh = function () {
    var callback = {
        success: function (o) {
            try {
                SUGAR.util.globalEval("retValue = (" + o.responseText + ")");
                var activities = retValue;
            } catch (err) {
                alert(CAL.lbl_error_saving);
                ajaxStatus.hideStatus();
                return;
            }
            CAL.update_dd.fire();
        },
    };
    var data = { view: CAL.view, year: CAL.year, month: CAL.month, day: CAL.day };
    var url = "index.php?module=Calendar&action=getActivities&sugar_body_only=true";
    YAHOO.util.Connect.asyncRequest("POST", url, callback, CAL.toURI(data));
    CAL.clear();
};
CAL.clear = function () {
    CAL.basic.items = {};
    var nodes = CAL.query("#cal-grid div.act_item");
    CAL.each(nodes, function (i, v) {
        nodes[i].parentNode.removeChild(nodes[i]);
    });
};
CAL.show_additional_details = function (id) {
    var obj = CAL.get(id);
    var record = obj.getAttribute("record");
    var module_name = obj.getAttribute("module_name");
    SUGAR.util.getAdditionalDetails(module_name, record, "div_" + id, true);
    return;
};
CAL.clear_additional_details = function (id) {
    if (typeof SUGAR.util.additionalDetailsCache[id] != "undefined") SUGAR.util.additionalDetailsCache[id] = undefined;
    if (typeof SUGAR.util.additionalDetailsCalls[id] != "undefined") SUGAR.util.additionalDetailsCalls[id] = undefined;
};
CAL.toggle_shared_edit = function () {
    $(".modal-calendar-user-list").modal("toggle");
};
CAL.goto_date_call = function () {
    var date_string = CAL.get("goto_date").value;
    var date_arr = [];
    date_arr = date_string.split("/");
    window.location.href =
        "index.php?module=Calendar&view=" + CAL.view + "&day=" + date_arr[1] + "&month=" + date_arr[0] + "&year=" + date_arr[2];
};
CAL.check_forms = function () {
    if (CAL.enable_repeat && CAL.get("edit_all_recurrences").value != "") {
        lastSubmitTime = lastSubmitTime - 2001;
    }
    return true;
};
CAL.toURI = function (a) {
    t = [];
    for (x in a) {
        t.push(x + "=" + encodeURIComponent(a[x]));
    }
    return t.join("&");
};
CAL.each = function (object, callback) {
    if (typeof object == "undefined") return;
    var name,
        i = 0,
        length = object.length,
        isObj = length === undefined || typeof object === "function";
    if (isObj) {
        for (name in object) {
            if (callback.call(object[name], name, object[name]) === false) {
                break;
            }
        }
    } else {
        for (; i < length; ) {
            if (callback.call(object[i], i, object[i++]) === false) {
                break;
            }
        }
    }
    return object;
};
CAL.contains = function (a, obj) {
    var i = a.length;
    while (i--) if (a[i] === obj) return true;
    return false;
};
CAL.update_vcal = function () {
    var v = CAL.current_user_id;
    var callback = {
        success: function (result) {
            if (typeof GLOBAL_REGISTRY.freebusy == "undefined") {
                GLOBAL_REGISTRY.freebusy = new Object();
            }
            if (typeof GLOBAL_REGISTRY.freebusy_adjusted == "undefined") {
                GLOBAL_REGISTRY.freebusy_adjusted = new Object();
            }
            GLOBAL_REGISTRY.freebusy[v] = SugarVCalClient.parseResults(result.responseText, false);
            GLOBAL_REGISTRY.freebusy_adjusted[v] = SugarVCalClient.parseResults(result.responseText, true);
            SugarWidgetScheduler.update_time();
        },
    };
    var url = "vcal_server.php?type=vfb&source=outlook&user_id=" + v;
    YAHOO.util.Connect.asyncRequest("GET", url, callback, false);
};
CAL.remove_edit_dialog();
var cal_loaded = true;


$($.fullCalendar).ready(function () {
    var calendarContainer = $("#calendarContainer");
    $.each(calendar_items, function (user_id, user_calendar_activities) {
        var calendar = '<div id="calendar' + user_id + '"></div>';
        $(calendar).appendTo(calendarContainer);
        var users_activities = [];
        $.each(user_calendar_activities, function (index, element) {
            var valueToPush = {};
            valueToPush["title"] = element["name"];
            valueToPush["id"] = element["record"];
            valueToPush["record"] = element["record"];
            valueToPush["module"] = element["module_name"];
            valueToPush["related_to"] = element["related_to"];
            valueToPush["parent_id"] = element["parent_id"];
            valueToPush["parent_name"] = element["parent_name"];
            valueToPush["parent_type"] = element["parent_type"];
            valueToPush["status"] = element["status"];
            valueToPush["date_due"] = element["date_due"];
            // STIC-Custom 20240222 MHP - Add a special class to stic_Work_Calendar events
            // https://github.com/SinergiaTIC/SinergiaCRM/pull/114            
            valueToPush["rendering"] = element["rendering"];
            // END STIC-Custom                      
            valueToPush["start"] = new Date(
                moment.utc(moment.unix(element["ts_start"])).format("MM/DD/YYYY") +
                    " " +
                    moment(element["time_start"], "hh:mma").format("HH:mm")
            );
            valueToPush["end"] = moment(
                new Date(
                    moment.utc(moment.unix(element["ts_start"])).format("MM/DD/YYYY") +
                        " " +
                        moment(element["time_start"], "hh:mma").format("HH:mm")
                )
            )
                .add(element["duration_hours"], "hours")
                .add(element["duration_minutes"], "minutes");
            if (element.module_name != "Meetings" && element.module_name != "Calls") {
                valueToPush["editable"] = false;
            }
            // END STIC-Custom         
            if (undefined !== global_colorList[element.module_name]) {
                // STIC-Custom 20230811 AAM - Adding Color to Sessions and FollowUps
                // STIC#1192
                // STIC-Custom 20240222 MHP - Adding Color to working and no-working Work Calendar Records
                // https://github.com/SinergiaTIC/SinergiaCRM/pull/114
                if (element.module_name != 'stic_Work_Calendar') {
                    valueToPush["backgroundColor"] = element.color ? element.color : "#" + global_colorList[element.module_name].body;
                    valueToPush["borderColor"] = element.color ? element.color : "#" + global_colorList[element.module_name].border;
                    valueToPush["textColor"] = element.color ? getContrastYIQ(element.color.substring(1)) : "#" + global_colorList[element.module_name].text;
                } else {
                    valueToPush["backgroundColor"] = (element.event_type == 'working') ? "#" + global_colorList[element.module_name].body_working : "#" + global_colorList[element.module_name].body_noworking;
                    valueToPush["borderColor"] = (element.event_type == 'working') ? "#" + global_colorList[element.module_name].border_working : "#" + global_colorList[element.module_name].border_noworking;
                    valueToPush["textColor"] = (element.event_type == 'working') ? "#" + global_colorList[element.module_name].text_working : "#" + global_colorList[element.module_name].text_noworking;
                }
                function getContrastYIQ(hexcolor){
                    var r = parseInt(hexcolor.substr(0,2),16);
                    var g = parseInt(hexcolor.substr(2,2),16);
                    var b = parseInt(hexcolor.substr(4,2),16);
                    var yiq = ((r*299)+(g*587)+(b*114))/1000;
                    return (yiq >= 128) ? 'black' : 'white';
                }
                // END STIC-Custom
            }
            if (element["duration_hours"] % 24 === 0 && (element["time_start"] == "12:00am" || element["time_start"] == "00:00")) {
                valueToPush["allDay"] = true;
            }
            users_activities.push(valueToPush);
        });
        constructCalendar(user_id, users_activities);
    });
    $("#calendar" + global_current_user_id).prependTo(calendarContainer);
    function constructCalendar(user_id, all_events) {
        var headerFormatDayWeek = "dddd D";
        var headerFormatMonth = "dddd";
        var headerFormat = headerFormatDayWeek;
        if (global_view == "sharedMonth" || global_view == "month") {
            headerFormat = headerFormatMonth;
        }
        $("#calendar" + user_id)
            .fullCalendar({
                header: { left: "", center: "", right: "" },
                locale: global_langPrefix,
                views: views,
                minTime: global_start_time,
                maxTime: global_end_time,
                selectHelper: true,
                selectable: true,
                selectOverlap: true,
                slotMinutes: global_timeslots,
                defaultDate: global_year + "-" + global_month + "-" + global_day,
                editable: global_edit,
                disableDragging: global_items_draggable,
                eventLimit: true,
                defaultView: global_view,
                firstDay: global_start_week_day,
                height: global_basic_min_height,
                columnFormat: headerFormat,
                select: function (date, jsEvent, view) {
                    if (global_edit == true) {
                        var date_start = date.format(global_datetime_format);
                        var date_end = jsEvent.format(global_datetime_format);
                        var date_duration = jsEvent.diff(date);
                        if (date.hasTime() == false) {
                            var date_end = date.add(1, "days").format(global_datetime_format);
                        }
                        if ($(view.target).hasClass("fc-day-top") && date_duration <= 86400000) {
                            var dateStr = $(view.target).attr("data-date");
                            var dateMoment = new moment(dateStr);
                            var url =
                                "index.php?module=Calendar&action=index&view=agendaDay&year=" +
                                dateMoment.format("YYYY") +
                                "&month=" +
                                dateMoment.format("MM") +
                                "&day=" +
                                dateMoment.format("DD") +
                                "&hour=0";
                            window.location.href = url;
                            return false;
                        }
                        CAL.dialog_create(date_start, date_end, user_id);
                    }
                },
                eventClick: function (calEvent, jsEvent, view) {
                    if (global_edit == true) {
                        CAL.load_form(calEvent.module, calEvent.record, false, calEvent);
                    }
                },
                eventDrop: function (event, delta, revertFunc) {
                    event_datetime = event.start.format(global_datetime_format);
                    var data = { current_module: event.module, record: event.record, datetime: event_datetime, calendar_style: "basic" };
                    if (event.allDay == true) {
                        data.allDay = true;
                        data.enddatetime = event.start.add(1, "days").format(global_datetime_format);
                    }
                    var url = "index.php?module=Calendar&action=Reschedule&sugar_body_only=true";
                    $.ajax({ method: "POST", url: url, data: data });
                },
                navLinks: true,
                navLinkDayClick: function (weekStart, jsEvent) {
                    if (global_edit == true) {
                        if ($(jsEvent.currentTarget).hasClass("fc-day-number")) {
                            var dateStr = $(jsEvent.currentTarget).closest(".fc-day-top").attr("data-date");
                            var dateMoment = new moment(dateStr);
                            var url =
                                "index.php?module=Calendar&action=index&view=agendaDay&year=" +
                                dateMoment.format("YYYY") +
                                "&month=" +
                                dateMoment.format("MM") +
                                "&day=" +
                                dateMoment.format("DD") +
                                "&hour=0";
                            window.location.href = url;
                            return false;
                        }
                        var dayHeader = $(jsEvent.currentTarget).closest(".fc-day-header");
                        var momentObj = moment($(dayHeader).attr("data-date"));
                        var url =
                            "index.php?module=Calendar&action=index&view=agendaDay&year=" +
                            momentObj.format("YYYY") +
                            "&month=" +
                            momentObj.format("MM") +
                            "&day=" +
                            momentObj.format("DD") +
                            "&hour=0";
                        window.location.href = url;
                        return false;
                    }
                },
                eventResize: function (event, delta, revertFunc) {
                    var url = "index.php?module=Calendar&action=Resize&sugar_body_only=true";
                    var hours = Math.floor(event.end.diff(event.start, "minutes") / 60);
                    var minutes = event.end.diff(event.start, "minutes") % 60;
                    var data = { current_module: event.module, record: event.record, duration_hours: hours, duration_minutes: minutes };
                    $.ajax({ method: "POST", url: url, data: data });
                },
                events: all_events,
                eventRender: function (event, element) {
                    var url = "index.php?to_pdf=1&module=Home&action=AdditionalDetailsRetrieve&bean=" + event.module + "&id=" + event.id;
                    var title = '<div class="qtip-title-text">' + event.title + "</div>" + '<div class="qtip-title-buttons">' + "</div>";
                    var body = SUGAR.language.translate("app_strings", "LBL_LOADING_PAGE");
                    if ($("#cal_module").val() != "Home" && typeof event.id !== "undefined") {
                        element.qtip({
                            content: { title: { text: title, button: true }, text: body },
                            events: {
                                render: function (event, api) {
                                    $.ajax(url)
                                        .done(function (data) {
                                            SUGAR.util.globalEval(data);
                                            var divCaption = "#qtip-" + api.id + "-title";
                                            var divBody = "#qtip-" + api.id + "-content";
                                            if (data.caption != "") {
                                                $(divCaption).html(result.caption);
                                            }
                                            api.set("content.text", result.body);
                                        })
                                        .fail(function () {
                                            $(divBody).html(SUGAR.language.translate("app_strings", "LBL_EMAIL_ERROR_GENERAL_TITLE"));
                                        })
                                        .always(function () {});
                                },
                            },
                            position: { my: "bottom left", at: "top left" },
                            show: { solo: true },
                            hide: { event: "mouseleave", fixed: true, delay: 500 },
                            style: {
                                width: 224,
                                padding: 5,
                                color: "black",
                                textAlign: "left",
                                border: { width: 1, radius: 3 },
                                tip: "bottomLeft",
                                classes: {
                                    tooltip: "ui-widget",
                                    tip: "ui-widget",
                                    title: "ui-widget-header",
                                    content: "ui-widget-content",
                                },
                            },
                        });
                    }
                },
            })
            .ready(function () {
                $(window).resize();
            });
        if ($("#calendar_title_" + user_id).length == 0) {
            var calendar = $("#calendar" + user_id + " > .fc-view-container");
            var calendarTitle =
                "<div class='monthCalBody'><h5 class='calSharedUser' id='calendar_title_" +
                user_id +
                "'></h5></div><div id='calendar" +
                user_id +
                "'></div>";
            $(calendarTitle).prependTo(calendar);
            $.ajax({ url: "index.php?module=Calendar&action=getUser&record=" + user_id }).done(function (data) {
                data = jQuery.parseJSON(data);
                $("#calendar_title_" + user_id).html(data.full_name);
            });
        }
    }
});
