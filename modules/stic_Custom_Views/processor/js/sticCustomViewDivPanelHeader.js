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
var sticCustomViewDivPanelHeader = class sticCustomViewDivPanelHeader extends sticCustomViewDivLabel {
    constructor (item, element){
        super(item, element);
        //this.anchor = this.element.find("a");
        //this.label = this.anchor.children(":first");
        this.label = this.element.find('[data-label="'+this.item.panelName+'"]');
        this.anchor = this.label.parent();
    }
    text(newText){
        var self = this;

        var oldText = this.label.text();
        if(newText===undefined || newText!=oldText) {
            return oldText;
        }
        var text = this.label.text(newText);
        this.item.customView.addUndoFunction(function() { self.label.text(oldText); });

        return text;
    }
    color(color="") {
        var self = this;

        this.anchor.css("color", color);
        this.item.customView.addUndoFunction(function() { self.anchor.css("color", ''); });
        return this;
    }
    background(color="") {
        var self = this;
        if (this.anchor.length>0) {
            this.anchor[0].style.setProperty("background-color", color, "important");
            this.item.customView.addUndoFunction(function() { self.anchor[0].style.setProperty("background-color", "", ""); });
        }
        return this;
    }
    bold(bold=true) {
        var self = this;
        if (bold===true||bold==="1"||bold===1) {
            this.label.css('font-weight', 'bold');
            this.item.customView.addUndoFunction(function() { self.label.css('font-weight', ''); });
        } else {
            this.label.css('font-weight', 'normal');
            this.item.customView.addUndoFunction(function() { self.label.css('font-weight', ''); });
        }
        return this;
    }
    italic(italic=true) {
        var self = this;
        if (italic===true||italic==="1"||italic===1) {
            this.label.css('font-style', 'italic');
            this.item.customView.addUndoFunction(function() { self.label.css('font-style', ''); });
        } else {
            this.label.css('font-style', 'normal');
            this.item.customView.addUndoFunction(function() { self.label.css('font-style', ''); });
        }
        return this;
    }
    underline(underline=true) {
        var self = this;
        if (underline===true||underline==="1"||underline===1) {
            this.label.css('text-decoration', 'underline');
            this.item.customView.addUndoFunction(function() { self.label.css('text-decoration', ''); });
        } else {
            this.label.css('text-decoration', 'none');
            this.item.customView.addUndoFunction(function() { self.label.css('text-decoration', ''); });
        }
        return this;
    }
}


