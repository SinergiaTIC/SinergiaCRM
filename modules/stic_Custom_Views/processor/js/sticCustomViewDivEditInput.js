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
var sticCustomViewDivEditInput = class sticCustomViewDivEditInput extends sticCustomViewDivLabel {
    constructor (item, element){
        super(item, element);
        this.editor = this.element.find(":input");
        this.option
        this.items = this.element.find(".items");
        this.labelValue = this.element.find(".stic-ReadonlyInput");
        this.type = this.element.attr("type"); 
    }
    value(newValue) {
        if(newValue!==undefined) {
            var oldValue = this.editor.val();
            if(newValue!=oldValue) {
                // Set new value
                this.editor.val(newValue);

                // Unset value is modified by user
                var attr = this.editor.attr("data-changedByUser");
                if(typeof(attr==="undefined") || attr===false) {
                    this.editor.removeAttr("data-changedByUser");
                }
                this.change();

                // Set last setted value by this Api to be undoed
                this.editor.attr("data-lastChangeByApi", newValue);

                var self = this;
                this.item.customView.addUndoFunction(function() { 
                    var editor = self.editor;
                    var currentValue = editor.val();

                    // Check if the last value change with Api is processed
                    var attrApi = editor.attr("data-lastChangeByApi");
                    if(typeof(attrApi!=="undefined") && attrApi!==false) {
                        // The last value change with Api, is the current value?
                        if(attrApi!=currentValue) {
                            // Set data is changed by User
                            this.editor.attr("data-changedByUser", currentValue);
                        } else {
                            // Data is not changed by User
                            var attrUser = editor.attr("data-changedByUser");
                            if(typeof(attrUser==="undefined") || attrUser===false) {
                                editor.removeAttr("data-changedByUser");
                            }
                        }
                        // The last value change with Api is processed
                        editor.removeAttr("data-lastChangeByApi");
                    }
                    // Undo only if last change is not made by user
                    var attrUser = editor.attr("data-changedByUser");
                    if(typeof(attrUser==="undefined") || attrUser===false) {
                        editor.val(oldValue);
                    }
                }, true);
            }
        }
        return this.editor.val();
    }
    text(newText){
        var text = this.editor.val();
        if(this.type=="enum" || this.type=="multienum"){
            text = this.editor.find("option:selected").text();
        }
        return text;
    }
    color(color="") {
        this.editor.css("color", color);
        this.items.css("color", color);
        this.labelValue.css("color", color);

        var self = this;
        this.item.customView.addUndoFunction(function() { self.editor.css('color', ''); });
        this.item.customView.addUndoFunction(function() { self.items.css('color', ''); });
        this.item.customView.addUndoFunction(function() { self.labelValue.css('color', ''); });

        return super.color(color);
    }
    background(color="") {
        this.editor.css("background-color", color);
        this.items.css("background-color", color);
        this.labelValue.css("background-color", color);

        var self = this;
        this.item.customView.addUndoFunction(function() { self.editor.css('background-color', ''); });
        this.item.customView.addUndoFunction(function() { self.items.css('background-color', ''); });
        this.item.customView.addUndoFunction(function() { self.labelValue.css('background-color', ''); });

        if (this.type=="radioenum") {
            super.background(color);
        }
        return this;
    }
    readonly(readonly=true) {
        if(readonly===true||readonly==="1"||readonly===1) {
            if(this.labelValue.length==0 || this.labelValue.is(":hidden")) {
                this.editor.hide();
                this.items.hide();
                if (this.labelValue.length==0) {
                    this.element.append('<p class="stic-ReadonlyInput"></p>');
                    this.labelValue = this.element.find(".stic-ReadonlyInput");
                    // Update label when value is changed
                    var self = this;
                    this.editor.on("change paste keyup", function() {
                        self.labelValue.text(self.text());
                    });
                }
                this.labelValue.show();
                this.editor.change();

                var self = this;
                this.item.customView.addUndoFunction(function() { self.editor.show(); });
                this.item.customView.addUndoFunction(function() { self.items.show(); });
                this.item.customView.addUndoFunction(function() { self.labelValue.hide(); });
            }
        }
        else {
            if(this.editor.is(":hidden")||this.items.is(":hidden")) {
                this.labelValue.hide();
                this.editor.show();
                this.items.show();

                var self = this;
                this.item.customView.addUndoFunction(function() { self.labelValue.show(); });
                this.item.customView.addUndoFunction(function() { self.editor.hide(); });
                this.item.customView.addUndoFunction(function() { self.items.hide(); });
            }
        }
        return this;
    }

    onChange(callback) {
        this.editor.on("change paste keyup", function() { callback();});
    }
    change() {
        this.editor.change();
    }
}