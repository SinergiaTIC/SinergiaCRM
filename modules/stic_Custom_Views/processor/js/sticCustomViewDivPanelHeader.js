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
        this.anchor = this.element.find("a");
        this.divText = this.anchor.children(":first");
    }
    text(newText){
        return this.divText.text(newText);
    }
    color(color="") {
        this.anchor.css("color", color);
        return this;
    }
    background(color="") {
        if (this.anchor.length>0) {
            this.anchor[0].style.setProperty("background-color", color, "important");
        }
        return this;
    }
    bold(bold=true) {
        if (bold===true||bold==="1"||bold===1) {
            this.divText.css('font-weight', 'bold');
        } else {
            this.divText.css('font-weight', 'normal');
        }
        return this;
    }
    italic(italic=true) {
        if (italic===true||italic==="1"||italic===1) {
            this.divText.css('font-style', 'italic');
        } else {
            this.divText.css('font-style', 'normal');
        }
        return this;
    }
    underline(underline=true) {
        if (underline===true||underline==="1"||underline===1) {
            this.divText.css('text-decoration', 'underline');
        } else {
            this.divText.css('text-decoration', 'none');
        }
        return this;
    }

}


