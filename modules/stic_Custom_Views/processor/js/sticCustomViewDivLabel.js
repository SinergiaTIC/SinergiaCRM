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
var sticCustomViewDivLabel = class sticCustomViewDivLabel extends sticCustomViewDivBase {
    constructor (item, element){
        super(item, element);
    }
    text(newText){
        var self = this;

        var oldText = this.element.text();
        if(newText===undefined || newText!=oldText) {
            return oldText;
        }
        var text = this.element.text(newText);
        this.item.customView.addUndoFunction(function() { self.element.text(oldText); });

        return text;
    }
    value(newValue) {
        return null;
    }

    applyActionWithValue(actionName, value) { 
        var result = super.applyActionWithValue(actionName, value);
        if(result!== false) {
            return result;
        }
        switch(actionName){
            case "fixed_value": return this.value(value);
            case "fixed_text": return this.text(value);
        }
        return false;
    } 

    onChange(callback) {
        this.element.on("change paste keyup", function() { callback();});
    }
    change() {
        this.element.change();
    }
}