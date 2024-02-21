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
var sticCustomViewDivTabHeaderBase = class sticCustomViewDivTabHeaderBase extends sticCustomViewDivLabelBase {
    constructor (item) {
        super(item, item.$elementView.find('ul.nav.nav-tabs li[role="presentation"] > a[data-toggle="tab"][data-label="'+item.tabName+'"]'));
        this.$label = this.$element;
        this.$xsLabel = item.$elementView.find('ul.nav.nav-tabs > li[role="presentation"] > ul li > a[data-toggle="tab"][data-label="'+item.tabName+'"]');
        this.$xsMenuLabel = item.$elementView.find('ul.nav.nav-tabs > li[role="presentation"] > a[data-toggle="dropdown"][data-label="'+item.tabName+'"]');
    }
    show(show=true) {
        this._show(this.$label, show);
        this._show(this.$xsLabel, show);
        this._show(this.$xsMenuLabel, show);
        return this;
    }
    color(color="") { 
        this._color(this.$label, color);
        this._color(this.$xsLabel, color);
        this._color(this.$xsMenuLabel, color);
        return this;
    }
    background(color="") { 
        this._background(this.$label, color);
        this._background(this.$xsLabel, color);
        this._background(this.$xsMenuLabel, color);
        return this;
    }
    bold(bold=true) {
        this._bold(this.$label, bold);
        this._bold(this.$xsLabel, bold);
        this._bold(this.$xsMenuLabel, bold);
        return this;
    }
    italic(italic=true) {
        this._italic(this.$label, italic);
        this._italic(this.$xsLabel, italic);
        this._italic(this.$xsMenuLabel, italic);
        return this;
    }
    underline(underline=true) {
        this._underline(this.$label, underline);
        this._underline(this.$xsLabel, underline);
        this._underline(this.$xsMenuLabel, underline);
        return this;
    }
    style(style) {
        this._style(this.$label, style);
        this._style(this.$xsLabel, style);
        this._style(this.$xsMenuLabel, style);
        return this;
    }
    mark(mark=true){
        this._mark(this.$label, mark);
        this._mark(this.$xsLabel, mark);
        this._mark(this.$xsMenuLabel, mark);
        return this;
    }
    text(newText) {
        this._text(this.$label, newText);
        this._text(this.$xsLabel, newText);
        this._text(this.$xsMenuLabel, newText);
        return newText;
    }
}


