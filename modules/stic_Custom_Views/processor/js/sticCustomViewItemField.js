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

/**
 * This file contains logic and functions needed to manage custom views behaviour
 *
 */

var sticCustomViewItemField = class sticCustomViewItemField extends sticCustomViewItemBase {
    constructor (customView, fieldName) {
        super(customView, fieldName);

        this.fieldName = fieldName;

        var $rowElement = this.$elementView.find('*[data-field="'+this.fieldName+'"]');
        switch(this.view) {
            case "detailview":  this.row = new sticCustomViewDivDetailRow(this, $rowElement);; break;
            case "editview":    this.row = new sticCustomViewDivEditRow(this, $rowElement); break;
            case "quickcreate": this.row = new sticCustomViewDivEditRow(this, $rowElement); break;
        }
        
        this.label = new sticCustomViewDivLabel(this, this.row.$element.children('.label'));
        
        var $inputElement = this.row.$element.children('[field="'+this.fieldName+'"]');
        switch(this.view) {
            case "detailview":  this.input = new sticCustomViewDivDetailInput(this, $inputElement); break;
            case "editview":    this.input = new sticCustomViewDivEditInput(this, $inputElement); break;
            case "quickcreate": this.input = new sticCustomViewDivEditInput(this, $inputElement); break;
        }
    }
    show(show=true) { this.row.show(show); return this; }
    hide() { return this.show(false); }

    style(style) { this.row.style(style); return this; }

    readonly(readonly=true) { this.input.readonly(readonly); return this; }

    required(required=true, type="text") {
        var oldRequired = getRequiredStatus(this.fieldName);
        var newRequired = required===true||required==="1"||required===1;

        if(newRequired) {
            setRequiredStatus(this.fieldName, type, SUGAR.language.get('app_strings', 'ERR_MISSING_REQUIRED_FIELDS'));
        } else {
            setUnrequiredStatus(this.fieldName);
        }
        if(oldRequired!=newRequired) {
            var self = this;
            this.addUndoFunction(function() { self.required(oldRequired); });
        }
        return this;
    }

    inline(inline=true) {
        //IEPA!!
        console.log("Inline not available. Requested:" + inline);
        return false;
    }

    value(newValue) { return this.input.value(newValue); }
    fixed_value(fixed_value) { return this.value(fixed_value); }

    applyAction(action) {
        switch(action.element_section){
            case "field_label": return this.label.applyAction(action);
            case "field_input": return this.input.applyAction(action);
            case "field": {
                switch(action.action){
                    case "readonly": return this.readonly(action.value);
                    case "required": return this.required(action.value, action.value_type);
                    case "inline": return this.inline(action.value);
                    case "fixed_value": return this.fixed_value(action.value);
                    default: return this.row.applyAction(action);
                }
            }
        }
        return false;
    }
    
    checkCondition(condition) {
        switch(condition.operator) {
            case 'Equal_To':
                return this.value()==condition.value;
            case 'Not_Equal_To':
                return this.value()!==condition.value;
            case 'Greater_Than':
                return this.value()>condition.value;
            case 'Less_Than':
                return this.value()<condition.value;
            case 'Greater_Than_or_Equal_To':
                return this.value()>=condition.value;
            case 'Less_Than_or_Equal_To':
                return this.value()<=condition.value;
            case 'Contains':
                return (this.value()??"").includes(condition.value);
            case 'Not_Contains':
                return !(this.value()??"").includes(condition.value);
            case 'Starts_With':
                return (this.value()??"").startsWith(condition.value);
            case 'Ends_With':
                return (this.value()??"").endsWith(condition.value);
            case 'is_null':
                return (this.value()??"")=="";
            case 'is_not_null':
                return (this.value()??"")!="";
        }
        return false;
    }

    onChange(callback) {
        this.input.onChange(callback);
    }
    change() {
        this.input.change();
    }
}