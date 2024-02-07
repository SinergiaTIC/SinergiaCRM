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

var sticCustomViewPanel = class sticCustomViewPanel extends sticCustomViewItemBase {
    constructor (customView, panelName) {
        super(customView, panelName);

        this.panelName = panelName;

        this.panel = new sticCustomViewDivBase(this, this.elementView.find('.panel-body[data-id="'+this.panelName+'"]').parent());
        this.header = new sticCustomViewDivHeader(this, this.panel.element.children('.panel-heading'));
        this.content = new sticCustomViewDivBase(this, this.panel.element.find('.tab-content'));
    };

    show(show=true) { this.panel.show(show); return this; }
    hide() { return this.show(false); }

    applyAction(action) {
        switch(action.element_section){
            case "panel_header": return this.header.applyAction(action);
            case "panel": {
                switch(action.action){
                    case "visible": return this.show(action.value);
                }
            }
        }
        return false;
    }
}