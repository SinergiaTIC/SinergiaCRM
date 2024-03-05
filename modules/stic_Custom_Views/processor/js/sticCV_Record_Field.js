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

var sticCV_Record_Field = class sticCV_Record_Field extends sticCV_Record_Container {
    constructor (customView, fieldName) {
        super(customView, fieldName);

        var $fieldElement = this.customView.$elementView.find('*[data-field="'+this.name+'"]');

        this.container = new sticCV_Element_Div(this.customView, $fieldElement);
        this.header = new sticCV_Record_Field_Header(this.customView, $fieldElement);
        this.content = new sticCV_Record_Field_Content(this.customView, $fieldElement, fieldName);

    }
    readonly(readonly=true) { return this.applyAction({action: "readonly", value: readonly, element_section: "container"}); }
    required(required=true) { return sticCVUtils.required(this, required); }
    inline(inline=true) { return this.applyAction({action: "inline", value: inline, element_section: "container"}); }

    fixed_value(fixed_value) { return this.applyAction({action: "fixed_value", value: fixed_value, element_section: "container"}); }
    value(newValue) { return this.fixed_value(newValue); }

    applyAction(action) {
        switch(action.action){
            case "visible":
                if((action.value!==true || action.value!=="1" || action.value!==1) && 
                   (action.element_section=="field" || action.element_section=="container") &&
                   (this.customView.view=="editview" || this.customView.view=="quickcreate")){
                    // Unrequire hidden fields
                    this.required(false);
                }
                break;
            case "readonly": 
            case "inline": 
            case "fixed_value": 
                return this.content?.applyAction(action);
            case "required":
                return this.required(action.value);
        }
        return super.applyAction(action);
    }
    
    checkCondition(condition) {
        return this.content.checkCondition(condition);
    }

    onChange(callback) {
        this.content.onChange(callback);
    }
    change() {
        this.content.change();
    }
}