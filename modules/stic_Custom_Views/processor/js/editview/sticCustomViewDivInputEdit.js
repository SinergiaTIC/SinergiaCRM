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
var sticCustomViewDivInputEdit = class sticCustomViewDivInputEdit extends sticCustomViewDivInputBase {
    constructor (item, $element){
        super(item, $element);

        if(this.type=="bool") {
            this.$editor = this.$element.find("[type='checkbox']:input");
        } else {
            this.$editor = this.$element.find(":input");
        }
        this.$items = this.$element.find(".items");
        this.$labelValue = this.$element.find(".stic-ReadonlyInput");
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
        if(this.type=="radioenum") {
            return this.$editor.parent().find("[type='radio']:checked").val();
        } else if(this.type=="bool") {
            return this.$editor.prop("checked");
        } else {
            return this.$editor.val();
        }
    }

    text(newText){
        var text = this._text(this.$editor, newText);
        if(this.type=="enum" || this.type=="currency_id" || this.type=="dynamicenum" || 
           this.type=="multienum"){
            text = this._text(this.$editor.find("option:selected"), newText);
        } else if(this.type=="radioenum") {
            text = this._text(this.$editor.parent().find("[type='radio']:checked").parent(), newText);
        } else if(this.type=="bool") {
            if(this.getValue()) {
                text="☒";
            } else {
                text="☐";
            }
        }
        return text;
    }

    color(color="") {
        this._color(this.$editor, color);
        this._color(this.$items, color);
        this._color(this.$labelValue, color);
        super.color(color);
        return this;
    }
    background(color="") {
        this._background(this.$editor, color);
        this._background(this.$items, color);
        this._background(this.$labelValue, color);
        if (this.type=="radioenum") {
            super.background(color);
        }
        return this;
    }

    readonly(readonly=true) {
        if(readonly===true||readonly==="1"||readonly===1) {
            if(this.$labelValue.length==0 || this.$labelValue.is(":hidden")) {
                this._show(this.$editor, false);
                this._show(this.$items, false);

                if(this.type=="radioenum") {
                    this._show(this.$editor.parent(), false);
                }
                if(this.type!="image" && this.type!="html") {
                    if (this.$labelValue.length==0) {
                        this.$element.prepend('<p class="stic-ReadonlyInput"></p>');
                        this.$labelValue = this.$element.find(".stic-ReadonlyInput");
                        // Update label when value is changed
                        var self = this;
                        this.$editor.on("change paste keyup", function() {
                            self.$labelValue.text(self.text());
                        });
                    }
                    this.show(this.$labelValue, true);
                    this.$editor.change();
                }
            }
        }
        else {
            if(this.$editor.is(":hidden")||this.$items.is(":hidden")) {
                this._show(this.$editor, true);
                this._show(this.$items, true);
 
                if(this.type=="radioenum"){
                    this._show(this.$editor.parent(), true);
                } 
                this._show(this.$labelValue, false);
            }
        }
        return this;
    }

    onChange(callback) {
        this.$editor.on("change paste keyup", function() { callback();});
    }
    change() {
        this.$editor.change();
    }
}