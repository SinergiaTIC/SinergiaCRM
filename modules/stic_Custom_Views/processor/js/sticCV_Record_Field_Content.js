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
var sticCV_Record_Field_Content = class sticCV_Record_Field_Content extends sticCV_Element_Label {
    constructor (customView, $fieldElement, fieldName) {
        super(customView, $fieldElement.children('[field="'+fieldName+'"]'));

        this.type = this.$element.attr("type");
        
        if(this.type=="bool") {
            this.$editor = this.$element.find("[type='checkbox']:input");
        } else {
            this.$editor = this.$element.find(":input");
        }
        this.$items = this.$element.find(".items");
        this.$readonlyLabel = this.$element.find(".stic-ReadonlyInput");
    }

    color(color="") {
        sticCVUtils.color(this.$editor, this.customView, color);
        sticCVUtils.color(this.$items, this.customView, color);
        sticCVUtils.color(this.$labelValue, this.customView, color);
        return super.color(color);
    }
    background(color="") {
        sticCVUtils.background(this.$editor, this.customView, color);
        sticCVUtils.background(this.$items, this.customView, color);
        sticCVUtils.background(this.$labelValue, this.customView, color);
        if (this.customView.view == "detailview" || this.type=="radioenum") {
            super.background(color);
        }
        return this;
    }

    readonly(readonly=true) {
        return sticCVUtils.readonly(this, readonly);
    }
    inline_edit(inline_edit=true) {
       return sticCVUtils.inline_edit(this, inline_edit);
    }
    value(newValue) {
        return sticCVUtils.value(this, newValue);
    }
    value(newValue) {
        if(newValue!==undefined) {
            this._setValue(newValue);
        }
        return this._getValue();
    }
    _setValue(newValue) {
        var oldValue = this.value();
        if(newValue!=oldValue) {
            // Set new value
            if(this.type=="radioenum") {
                this.$editor.parent().find("[type='radio'][value='"+newValue+"']").prop('checked', true);
            } else if(this.type=="bool") {
                this.$editor.prop("checked", newValue)
            } else {
                this.$editor.val(newValue);
            }

            // Unset value modified by user
            var attr = this.$editor.attr("data-changedByUser");
            if(typeof(attr==="undefined") || attr===false) {
                this.$editor.removeAttr("data-changedByUser");
            }
            this.change();

            // Set last setted value by this Api to be undoed
            this.$editor.attr("data-lastChangeByApi", newValue);

            // Add undo function
            var self = this;
            this.addUndoFunction(function() { 
                var $editor = self.$editor;
                var currentValue = self.value();

                // Check if the last value change with Api is processed
                var attrApi = $editor.attr("data-lastChangeByApi");
                if(typeof(attrApi!=="undefined") && attrApi!==false) {
                    // The last value change with Api, is the current value?
                    if(attrApi!=currentValue) {
                        // Set data is changed by User
                        self.$editor.attr("data-changedByUser", currentValue);
                    } else {
                        // Data is not changed by User
                        var attrUser = $editor.attr("data-changedByUser");
                        if(typeof(attrUser==="undefined") || attrUser===false) {
                            $editor.removeAttr("data-changedByUser");
                        }
                    }
                    // The last value change with Api is processed
                    $editor.removeAttr("data-lastChangeByApi");
                }
                // Undo only if last change is not made by user
                var attrUser = $editor.attr("data-changedByUser");
                if(typeof(attrUser==="undefined") || attrUser===false) {
                    self.value(oldValue);
                }
            }, true);
        }
    }
    _getValue() {
        return sticCVUtils.getValue(this);
    }

    text(newText){
        if(this.customView.view == "editview" || this.customView.view == "quickcreate"){
            if(this.type=="enum" || this.type=="currency_id" || this.type=="dynamicenum" || this.type=="multienum") {
                return sticCVUtils.text(this.$editor.find("option:selected"), this.customView, newText);
            } else if(this.type=="radioenum") {
                return sticCVUtils.text(this.$editor.parent().find("[type='radio']:checked").parent(), this.customView, newText);
            } else if(this.type=="bool") {
                if(this.value()) {
                    return "☒";
                } else {
                    return "☐";
                }
            }
        }
        return super.text(newText);
    }

    showEditor(show=true){
        sticCVUtils.show(this.$editor, this.customView, show);
        sticCVUtils.show(this.$items, this.customView, show);
        if(this.type=="radioenum"){
            sticCVUtils.show(this.$editor.parent(), this.customView, show);
        } 
    }
    showReadOnlyLabel(show=true){
        if(this.type!="image" && this.type!="html") {
            if(show) {
                if (this.$readonlyLabel.length==0) {
                    this.$element.prepend('<p class="stic-ReadonlyInput"></p>');
                    this.$readonlyLabel = this.$element.find(".stic-ReadonlyInput");
                    // Update label when value is changed
                    var self = this;
                    this.onChange(function() {
                        self.$readonlyLabel.text(self.text());
                    });
                }
            }
            sticCVUtils.show(this.$labelValue, this.customView, show);
            this.change();
        }
    }
    is_readonly(){
        return this.$readonlyLabel.length!=0 && this.$readonlyLabel.is(":visible");
    }


    applyActionWithValue(actionName, value) { 
        var result = super.applyActionWithValue(actionName, value);
        if(result!== false) {
            return result;
        }
        switch(actionName){
            case "fixed_value": return this.value(value);
            case "readonly": return this.readonly(value);
            case "inline": return this.inline_edit(value); 
            case "required": return this.required(value);
        }
        return false;
    }

    onChange(callback) {
        return sticCVUtils.onChange(this.$editor, callback) || super.onChange(callback);
    }
    change() {
        return sticCVUtils.change(this.$editor) || super.change();
    }
}


