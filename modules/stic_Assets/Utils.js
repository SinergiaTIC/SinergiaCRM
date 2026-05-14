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
/* HEADER */
// Set module name
var module = 'stic_Assets';

/* INCLUDES */

/* VALIDATION DEPENDENCIES */


/* VALIDATION CALLBACKS */
validateFunctions.other = function() {
    var isRequired = ["edit", "quickcreate","popup"].indexOf(viewType()) >= 0;
    addToValidate(
        getFormName(),
        "other",
        "text",
        isRequired,
        SUGAR.language.get(module, "LBL_NO_OTHER_ERROR"),
        
    );
};

/* VIEWS CUSTOM CODE */
switch (viewType()) {
	case 'edit':
	case 'quickcreate':
	case "popup":
		setAutofill(["name"]);

		// Definition of the behavior of fields that are conditionally enabled or disabled
        let type = {
            other: {
                enabled: ["other"],
                disabled: []
            },
			 default: {
				enabled: [],
				disabled: ["other"]
			}
           
        };

        setCustomStatus(type, $("#type", "form").val());
        $("form").on("change", "#type", function() {
            clear_all_errors();
            setCustomStatus(type, $("#type", "form").val());
        });
		break;
	case 'detail':
		break;

	case 'list':
		break;

	default:
		break;
}

/* AUX FUNCTIONS */


var $type = $('select#type');
showHideOtherRequiredMark($type);
$type.on('change', function() {
  showHideOtherRequiredMark($type);
});

function showHideOtherRequiredMark($type) {
	if (['other'].indexOf($type.val()) != -1) {
		addRequiredMark('other');
	} else {
		removeRequiredMark('other');
	}
}
