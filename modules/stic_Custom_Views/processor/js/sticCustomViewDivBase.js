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
    constructor (item, $element){
        this.item = item;
        this.$element = $element;
    }

    addUndoFunction(func, reverse=false) { return this.item.addUndoFunction(func, reverse); }

    _show($elem, show=true) {
        var visible = $elem.is(":visible");
        if(show===true||show==="1"||show===1) {
            if(!visible) {
                $elem.show();
                this.addUndoFunction(function() { $elem.hide(); });
            }
        } else {
            if(visible) {
                $elem.hide();
                this.addUndoFunction(function() { $elem.show(); });
            }
        }
        return this;
    }
    show(show=true) {
        return this._show(this.$element, show);
    }
    hide() { return this.show(false); }

    _color($elem, color="") {
        $elem.css("color", color);
        this.addUndoFunction(function() { $elem.css('color', ''); });
        return this;
    }
    color(color="") { 
        return this._color(this.$element, color);
    }

    _background($elem, color="") { 
        $elem.css("background-color", color); 
        this.addUndoFunction(function() { $elem.css("background-color", ''); });
        return this; 
    }
    background(color="") { 
        return this._background(this.$element, color);
    }

    _bold($elem, bold=true) {
        if(bold===true||bold==="1"||bold===1) {
            $elem.css('font-weight', 'bold');
            this.addUndoFunction(function() { $elem.css('font-weight', ''); });
        } else {
            $elem.css('font-weight', 'normal');
            this.addUndoFunction(function() { $elem.css('font-weight', ''); });
        }
        return this;
    }
    bold(bold=true) {
        return this._bold(this.$element, bold);
    }

    _italic($elem, italic=true) {
        if(italic===true||italic==="1"||italic===1) {
            $elem.css('font-style', 'italic');
            this.addUndoFunction(function() { $elem.css('font-style', ''); });
        } else {
            $elem.css('font-style', 'normal');
            this.addUndoFunction(function() { $elem.css('font-style', ''); });
        }
        return this;
    }
    italic(italic=true) {
        return this._italic(this.$element, italic);
    }

    _underline($elem, underline=true) {
        if(underline===true||underline==="1"||underline===1) {
            $elem.css('text-decoration', 'underline');
            this.addUndoFunction(function() { $elem.css('text-decoration', ''); });
        } else {
            $elem.css('text-decoration', 'none');
            this.addUndoFunction(function() { $elem.css('text-decoration', ''); });
        }
        return this;
    }
    underline(underline=true) {
        return this._underline(this.$element, underline);
    }

    _style($elem, style) {
        var oldStyle = $elem.attr('style');

        $elem.css(style);
        this.addUndoFunction(function() { $elem.attr('style', oldStyle); });
        return this;
    }
    style(style) {
        return this._style(this.$element, style);
    }

    _mark($elem, mark=true){
        if(mark===true||mark==="1"||mark===1) {
            $elem.css({"border-color": "orangered", "border-style": "dashed"});
            this.addUndoFunction(function() { $elem.css({"border-color": "", "border-style": ""}); });
        } else {
            $elem.css({"border-color": "", "border-style": ""});
            this.addUndoFunction(function() { $elem.css({"border-color": "", "border-style": ""}); });
        }
        return this;
    }
    mark(mark=true){
        return this._mark(this.$element, mark);
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