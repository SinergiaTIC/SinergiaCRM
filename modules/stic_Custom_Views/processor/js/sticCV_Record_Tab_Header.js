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
var sticCV_Record_Tab_Header = class sticCV_Record_Tab_Header extends sticCV_Element_Label {
    constructor (tab) {
        super(tab.customView, tab.customView.$elementView.find('ul.nav.nav-tabs li[role="presentation"] > a[data-toggle="tab"][data-label="'+tab.name+'"]'));
        this.$label = this.$element;
        this.$xsLabel = tab.customView.$elementView.find('ul.nav.nav-tabs > li[role="presentation"] > ul li > a[data-toggle="tab"][data-label="'+tab.name+'"]');
        this.$xsMenuLabel = tab.customView.$elementView.find('ul.nav.nav-tabs > li[role="presentation"] > a[data-toggle="dropdown"][data-label="'+tab.name+'"]');
    }
    show(show=true) {
        sticCVUtils.show(this.$label, this.customView, show);
        sticCVUtils.show(this.$xsLabel, this.customView, show);
        // sticCVUtils.show(this.$xsMenuLabel, this.customView, show);
        return this;
    }
    color(color="") { 
        sticCVUtils.color(this.$label, this.customView, color);
        sticCVUtils.color(this.$xsLabel, this.customView, color);
        sticCVUtils.color(this.$xsMenuLabel, this.customView, color);
        return this;
    }
    background(color="") { 
        sticCVUtils.background(this.$label, this.customView, color);
        sticCVUtils.background(this.$xsLabel, this.customView, color);
        sticCVUtils.background(this.$xsMenuLabel, this.customView, color);
        return this;
    }
    bold(bold=true) {
        sticCVUtils.bold(this.$label, this.customView, bold);
        sticCVUtils.bold(this.$xsLabel, this.customView, bold);
        sticCVUtils.bold(this.$xsMenuLabel, this.customView, bold);
        return this;
    }
    italic(italic=true) {
        sticCVUtils.italic(this.$label, this.customView, italic);
        sticCVUtils.italic(this.$xsLabel, this.customView, italic);
        sticCVUtils.italic(this.$xsMenuLabel, this.customView, italic);
        return this;
    }
    underline(underline=true) {
        sticCVUtils.underline(this.$label, this.customView, underline);
        sticCVUtils.underline(this.$xsLabel, this.customView, underline);
        sticCVUtils.underline(this.$xsMenuLabel, this.customView, underline);
        return this;
    }
    style(style) {
        sticCVUtils.style(this.$label, this.customView, style);
        sticCVUtils.style(this.$xsLabel, this.customView, style);
        sticCVUtils.style(this.$xsMenuLabel, this.customView, style);
        return this;
    }
    frame(frame=true){
        sticCVUtils.frame(this.$label, this.customView, frame);
        sticCVUtils.frame(this.$xsLabel, this.customView, frame);
        sticCVUtils.frame(this.$xsMenuLabel, this.customView, frame);
        return this;
    }
    text(newText) {
        var oldText = sticCVUtils.text(this.$label, this.customView, newText).split(", ")[0];
        sticCVUtils.text(this.$xsLabel, this.customView, newText);
        sticCVUtils.text(this.$xsMenuLabel, this.customView, newText);
        if(newText===undefined) {
            return oldText;
        } else {
            return newText;
        }
    }
}


