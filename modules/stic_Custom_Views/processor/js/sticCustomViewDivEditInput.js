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
            this.editor.val(newValue);
            this.change();
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
        return super.color(color);
    }
    background(color="") {
        this.editor.css("background-color", color);
        this.items.css("background-color", color);
        this.labelValue.css("background-color", color);
        if (this.type=="radioenum") {
            super.background(color);
        }
        return this;
    }
    readonly(readonly=true) {
        if(readonly===true||readonly==="1"||readonly===1) {
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
        }
        else {
            this.labelValue.hide();
            this.editor.show();
            this.items.show();
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