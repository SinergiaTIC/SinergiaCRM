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

var activeFilters = [];

function populateSubpanelsFromType() {
    var moduleSelect = document.getElementById('type') || document.getElementById('module_name');
    var moduleName = moduleSelect ? moduleSelect.value : '';
    var select = document.getElementById('subpanel_name');
    
    if (!select) {
        return;
    }
    
    select.innerHTML = '<option value="">-- ' + SUGAR.language.get('AOS_PDF_Templates', 'LBL_SELECT') + ' --</option>';
    
    if (moduleName && typeof subpanelModuleOptions !== 'undefined' && subpanelModuleOptions[moduleName]) {
        var subpanels = subpanelModuleOptions[moduleName].subpanels;
        for (var key in subpanels) {
            var option = document.createElement('option');
            option.value = key;
            option.text = subpanels[key].name || key;
            select.appendChild(option);
        }
    }
    
    populateSubpanelFields();
}

function populateSubpanelFields() {
    var moduleSelect = document.getElementById('type') || document.getElementById('module_name');
    var moduleName = moduleSelect ? moduleSelect.value : '';
    var subpanelSelect = document.getElementById('subpanel_name');
    var fieldSelect = document.getElementById('subpanel_field_name');
    var orderSelect = document.getElementById('subpanel_order_field');
    var filterField = document.getElementById('subpanel_filter_field');
    
    if (!fieldSelect) return;
    
    var subpanelKey = subpanelSelect ? subpanelSelect.value : '';
    var emptyOption = '<option value="">-- ' + SUGAR.language.get('AOS_PDF_Templates', 'LBL_SELECT') + ' --</option>';
    fieldSelect.innerHTML = emptyOption;
    if (orderSelect) orderSelect.innerHTML = emptyOption;
    if (filterField) filterField.innerHTML = emptyOption;
    
    if (moduleName && subpanelKey && typeof subpanelModuleOptions !== 'undefined' && subpanelModuleOptions[moduleName]) {
        var subpanels = subpanelModuleOptions[moduleName].subpanels;
        if (subpanels[subpanelKey] && subpanels[subpanelKey].fields) {
            var fields = subpanels[subpanelKey].fields;
            var tableName = subpanels[subpanelKey].table_name || subpanelKey;
            for (var key in fields) {
                var val = '$' + tableName + '_' + key;
                var opt = document.createElement('option');
                opt.value = val;
                opt.text = fields[key];
                fieldSelect.appendChild(opt);
                if (orderSelect) orderSelect.appendChild(opt.cloneNode(true));
                if (filterField) filterField.appendChild(opt.cloneNode(true));
            }
        }
    }
}

function insertSubpanelLoop() {
    var moduleSelect = document.getElementById('type') || document.getElementById('module_name');
    var moduleName = moduleSelect ? moduleSelect.value : '';
    var subpanelSelect = document.getElementById('subpanel_name');
    var fieldSelect = document.getElementById('subpanel_field_name');
    
    var subpanelKey = subpanelSelect ? subpanelSelect.value : '';
    var fieldValue = fieldSelect ? fieldSelect.value : '';
    
    if (!moduleName || !subpanelKey) {
        alert(SUGAR.language.get('AOS_PDF_Templates', 'LBL_SUBPANEL_SELECT_MODULE_WARN'));
        return;
    }
    
    // Build options string
    var options = buildOptionsString();
    
    var startTag = '<!--$subpanel:' + subpanelKey;
    if (options) {
        startTag += ':' + options;
    }
    startTag += '-->';
    var endTag = '<!--/$subpanel:' + subpanelKey + '-->';
    
    var templateContent = '';
    if (fieldValue) {
        templateContent = startTag + '<tr><td>' + fieldValue + '</td></tr>' + endTag;
    } else {
        templateContent = startTag + endTag;
    }
    
    insertAtCursor(templateContent);
}

function buildOptionsString() {
    var parts = [];
    
    // Order
    var orderField = document.getElementById('subpanel_order_field');
    var orderDir = document.getElementById('subpanel_order_dir');
    if (orderField && orderField.value) {
        parts.push('order=' + orderField.value.replace(/^\$/, '').replace(/^[a-z0-9_]+_/, ''));
        if (orderDir && orderDir.value) {
            parts.push('dir=' + orderDir.value);
        }
    }
    
    // Limit
    var limitInput = document.getElementById('subpanel_limit');
    if (limitInput && limitInput.value && parseInt(limitInput.value) > 0) {
        parts.push('limit=' + parseInt(limitInput.value));
    }
    
    // Filters
    for (var i = 0; i < activeFilters.length; i++) {
        var f = activeFilters[i];
        parts.push('filter=' + f.field + ':' + f.op + ':' + f.value);
    }
    
    return parts.join(';');
}

function addSubpanelFilter() {
    var filterField = document.getElementById('subpanel_filter_field');
    var filterOp = document.getElementById('subpanel_filter_op');
    var filterValue = document.getElementById('subpanel_filter_value');
    
    if (!filterField || !filterField.value) {
        alert(SUGAR.language.get('AOS_PDF_Templates', 'LBL_SUBPANEL_SELECT_FIELD_WARN'));
        return;
    }
    if (!filterValue || !filterValue.value) {
        alert(SUGAR.language.get('AOS_PDF_Templates', 'LBL_SUBPANEL_ENTER_VALUE_WARN'));
        return;
    }
    
    var fieldName = filterField.value.replace(/^\$/, '').replace(/^[a-z0-9_]+_/, '');
    var op = filterOp ? filterOp.value : 'eq';
    var value = filterValue.value;
    
    activeFilters.push({ field: fieldName, op: op, value: value });
    renderActiveFilters();
    filterValue.value = '';
}

function removeSubpanelFilter(index) {
    activeFilters.splice(index, 1);
    renderActiveFilters();
}

function renderActiveFilters() {
    var container = document.getElementById('subpanel_active_filters');
    if (!container) return;
    
    var opLabels = { eq: '=', neq: '!=', gt: '>', gte: '>=', lt: '<', lte: '<=', like: '~', in: 'IN' };
    var html = '';
    for (var i = 0; i < activeFilters.length; i++) {
        var f = activeFilters[i];
        html += '<span class="filter-tag" style="display:inline-block;background:#e8f0fe;border:1px solid #aecbfa;border-radius:4px;padding:2px 6px;margin:2px;font-size:12px;">';
        html += f.field + ' ' + (opLabels[f.op] || f.op) + ' ' + f.value;
        html += ' <a href="javascript:void(0)" onclick="removeSubpanelFilter(' + i + ')" style="color:#c00;text-decoration:none;font-weight:bold;">x</a>';
        html += '</span>';
    }
    container.innerHTML = html;
}

function insertSubpanelField() {
    var fieldSelect = document.getElementById('subpanel_field_name');
    var fieldValue = fieldSelect ? fieldSelect.value : '';
    if (!fieldValue) return;
    insertAtCursor(fieldValue);
}

function insertAggregateField() {
    var aggFunc = document.getElementById('subpanel_agg_func');
    var fieldSelect = document.getElementById('subpanel_field_name');
    
    if (!aggFunc || !aggFunc.value) return;
    if (!fieldSelect || !fieldSelect.value) return;
    
    var func = aggFunc.value;
    var fieldVal = fieldSelect.value;
    // Parse $tablename_fieldname into table and field
    var match = fieldVal.match(/^\$([a-z0-9_]+)_(.+)$/i);
    if (!match) return;
    
    var table = match[1];
    var field = match[2];
    var placeholder = '$' + func + ':' + table + ':' + field;
    insertAtCursor(placeholder);
}

function insertAtCursor(content) {
    var inst = tinyMCE.getInstanceById("description");
    if (inst) {
        inst.getWin().focus();
        inst.execCommand('mceInsertContent', false, content);
    } else {
        var textarea = document.getElementById('description');
        if (textarea) {
            var start = textarea.selectionStart || 0;
            var end = textarea.selectionEnd || 0;
            textarea.value = textarea.value.substring(0, start) + content + textarea.value.substring(end);
        }
    }
}

if (typeof jQuery !== 'undefined') {
    jQuery(document).ready(function() {
        var typeSelect = document.getElementById('type');
        if (typeSelect) {
            typeSelect.addEventListener('change', function() {
                populateSubpanelsFromType();
            });
        }
        setTimeout(populateSubpanelsFromType, 500);
    });
}
