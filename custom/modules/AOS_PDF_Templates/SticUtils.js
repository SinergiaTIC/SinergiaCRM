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
    
    if (!fieldSelect) {
        return;
    }
    
    var subpanelKey = subpanelSelect ? subpanelSelect.value : '';
    fieldSelect.innerHTML = '<option value="">-- ' + SUGAR.language.get('AOS_PDF_Templates', 'LBL_SELECT') + ' --</option>';
    
    if (moduleName && subpanelKey && typeof subpanelModuleOptions !== 'undefined' && subpanelModuleOptions[moduleName]) {
        var subpanels = subpanelModuleOptions[moduleName].subpanels;
        if (subpanels[subpanelKey] && subpanels[subpanelKey].fields) {
            var fields = subpanels[subpanelKey].fields;
            var tableName = subpanels[subpanelKey].table_name || subpanelKey;
            for (var key in fields) {
                var option = document.createElement('option');
                option.value = '$' + tableName + '_' + key;
                option.text = fields[key];
                fieldSelect.appendChild(option);
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
        alert('Please select a module and subpanel first.');
        return;
    }
    
    var templateContent = '';
    if (fieldValue) {
        templateContent = '<!--$subpanel:' + subpanelKey + '--><tr><td>' + fieldValue + '</td></tr><!--/$subpanel:' + subpanelKey + '-->';
    } else {
        templateContent = '<!--$subpanel:' + subpanelKey + '--><!--/$subpanel:' + subpanelKey + '-->';
    }
    
    var inst = tinyMCE.getInstanceById("description");
    if (inst) {
        inst.getWin().focus();
        inst.execCommand('mceInsertContent', false, templateContent);
    } else {
        var textarea = document.getElementById('description');
        if (textarea) {
            textarea.value += templateContent;
        }
    }
}

function insertSubpanelField() {
    var fieldSelect = document.getElementById('subpanel_field_name');
    var fieldValue = fieldSelect ? fieldSelect.value : '';
    
    if (!fieldValue) {
        return;
    }
    
    var inst = tinyMCE.getInstanceById("description");
    if (inst) {
        inst.getWin().focus();
        inst.execCommand('mceInsertContent', false, fieldValue);
    } else {
        var textarea = document.getElementById('description');
        if (textarea) {
            textarea.value += fieldValue;
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
