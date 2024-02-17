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
var sticCustomViewDivBase = class sticCustomViewDivBase {
    constructor (item, element){
        this.item = item;
        this.element = element;
    }
    show(show=true) {
        var self = this;
        var visible = this.element.is(":visible");
        if(show===true||show==="1"||show===1) {
            if(!visible) {
                this.element.show();
                this.item.customView.addUndoFunction(function() { self.element.hide(); });
            }
        } else {
            if(visible) {
                this.element.hide();
                this.item.customView.addUndoFunction(function() { self.element.show(); });
            }
        }
        return this;
    }
    hide() { return this.show(false); }

    color(color="") { 
        var self = this;
        this.element.css("color", color);
        this.item.customView.addUndoFunction(function() { self.element.css('color', ''); });
        return this; 
    }
    background(color="") { 
        var self = this;
        this.element.css("background-color", color); 
        this.item.customView.addUndoFunction(function() { self.element.css("background-color", ''); });
        return this; 
    }

    bold(bold=true) {
        var self = this;
        if (bold===true||bold==="1"||bold===1) {
            this.element.css('font-weight', 'bold');
            this.item.customView.addUndoFunction(function() { self.element.css('font-weight', ''); });
        } else {
            this.element.css('font-weight', 'normal');
            this.item.customView.addUndoFunction(function() { self.element.css('font-weight', ''); });
        }
        return this;
    }
    italic(italic=true) {
        var self = this;
        if (italic===true||italic==="1"||italic===1) {
            this.element.css('font-style', 'italic');
            this.item.customView.addUndoFunction(function() { self.element.css('font-style', ''); });
        } else {
            this.element.css('font-style', 'normal');
            this.item.customView.addUndoFunction(function() { self.element.css('font-style', ''); });
        }
        return this;
    }
    underline(underline=true) {
        var self = this;
        if (underline===true||underline==="1"||underline===1) {
            this.element.css('text-decoration', 'underline');
            this.item.customView.addUndoFunction(function() { self.element.css('text-decoration', ''); });
        } else {
            this.element.css('text-decoration', 'none');
            this.item.customView.addUndoFunction(function() { self.element.css('text-decoration', ''); });
        }
        return this;
    }

    style(style) {
        var self = this;
        var oldStyle = this.element.attr('style');

        this.element.css(style);
        this.item.customView.addUndoFunction(function() {
            self.element.attr('style', oldStyle);
        });
        return this;
    }

    mark(mark=true){
        var self = this;
        if(mark) {
            this.element.css({"border-color": "orangered", "border-style": "dashed"});
            this.item.customView.addUndoFunction(function() { self.element.css({"border-color": "", "border-style": ""}); });
        } else {
            this.element.css({"border-color": "", "border-style": ""});
            this.item.customView.addUndoFunction(function() { self.element.css({"border-color": "", "border-style": ""}); });
        }
        return this;
    }

    applyAction(action) {
        return this.applyActionWithValue(action.action, action.value);
    }

    applyActionWithValue(actionName, value) { 
        switch(actionName){
            case "visible": return this.show(value);
            case "color": return this.color(value);
            case "background": return this.background(value);
            case "bold": return this.bold(value);
            case "italic": return this.italic(value);
            case "underline": return this.underline(value);
            case "css_style": return this.style(JSON.parse(value));
        }
        return false;
    } 
}