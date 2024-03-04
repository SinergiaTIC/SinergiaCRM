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
var sticCV_Element_FieldContainer = class sticCV_Element_FieldContainer extends sticCV_Element_Div {
    constructor (customView, $element){
        super(customView, $element);
    }

    getFields() {
        var fields = [];
        var customView=this.customView;
        this.$element.find("[field]").each(function(){
            fields.push(new sticCV_Record_Field(customView, $(this).attr("field")));
        });
        return fields;
    }

    show(show=true) {
        show=(show===true||show==="1"||show===1);
        if(!show) {
            if(this.customView.view=="editview" || this.customView.view=="quickcreate") {
                for(var field of this.getFields()) {
                    // Unrequire hidden fields
                    sticCVUtils.required(field, false);
                };
            }
        }
        sticCVUtils.show(this.$element, this.customView, show);
        return this;
    }
}


