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
var sticCV_Element_Div = class sticCV_Element_Div {
    constructor (customView, $element){
        this.customView = customView;
        this.$element = $element;
    }

    show(show=true) {
        sticCVUtils.show(this.$element, this.customView, show);
        return this;
    }
    hide() { return this.show(false); }

    color(color="") { 
        sticCVUtils.color(this.$element, this.customView, color);
        return this;
    }

    background(color="") { 
        sticCVUtils.background(this.$element, this.customView, color);
        return this;
    }

    bold(bold=true) {
        sticCVUtils.bold(this.$element, this.customView, bold);
        return this;
    }

    italic(italic=true) {
        sticCVUtils.italic(this.$element, this.customView, italic);
        return this;
    }

    underline(underline=true) {
        sticCVUtils.underline(this.$element, this.customView, underline);
        return this;
    }

    style(style) {
        sticCVUtils.style(this.$element, this.customView, style);
        return this;
    }

    frame(frame=true){
        sticCVUtils.frame(this.$element, this.customView, frame);
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