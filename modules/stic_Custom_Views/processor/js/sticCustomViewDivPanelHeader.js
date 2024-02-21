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
    constructor (item, $panelElement){
        super(item, $panelElement.children('.panel-heading'));

        this.$label = this.$element.find('[data-label="'+this.item.panelName+'"]');
    }
    text(newText) {
        return this._text(this.$label, newText);
    }
    color(color="") {
        return this._color(this.$label.parent(), color);
    }
    background(color="") {
        return this._background(this.$label.parent(), color);
    }
    bold(bold=true) {
        return this._bold(this.$label, bold);
    }
    italic(italic=true) {
        return this._italic(this.$label, italic);
    }
    underline(underline=true) {
        return this._underline(this.$label, underline);
    }
}


