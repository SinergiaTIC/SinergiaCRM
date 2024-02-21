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

var sticCustomViewItemFieldEdit = class sticCustomViewItemFieldEdit extends sticCustomViewItemFieldBase {
    constructor (customView, fieldName) {
        super(customView, fieldName);
    }

    readonly(readonly=true) { this.input.readonly(readonly); return this; }

    required(required=true, type="text") {
        var oldRequired = _getRequiredStatus();
        var newRequired = required===true||required==="1"||required===1;

        if(newRequired) {
            addToValidate(this.formName, this.fieldName, type, true, SUGAR.language.get('app_strings', 'ERR_MISSING_REQUIRED_FIELDS'));
            this.label.$element.addClass("conditional-required");
        } else {
            removeFromValidate(this.formName, this.fieldName);
            this.label.$element.removeClass("conditional-required");
        }
        if(oldRequired!=newRequired) {
            var self = this;
            this.addUndoFunction(function() { self.required(oldRequired); });
        }
        return this;
    }
    _getRequiredStatus() {
        var validateFields = validate[this.formName];
        for (i = 0; i < validateFields.length; i++) {
            // Array(name, type, required, msg);
            if (validateFields[i][0] == this.fieldName) {
                return validateFields[i][2];
            }
        }
        return false;
    }

}