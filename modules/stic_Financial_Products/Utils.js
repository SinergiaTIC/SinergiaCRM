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
var module = "stic_Financial_Products";

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    $(document).ready(function() {
      setAutofill(["name"]);
    });
    break;

  case "detail":
    break;

  case "list":
    break;

  default:
    break;
}

// Attach event to remove spaces and strange characters in the IBAN
$("body").on("blur paste change", "input#iban", function() {
    $(this).val($(this).val().replace(/[^0-9a-zA-Z]/g, "").toUpperCase());
});

/* VALIDATION CALLBACKS */
// Register IBAN validation
$(document).ready(function() {
    addToValidateCallback(
        getFormName(),
        "iban",
        "text",
        false, // not required
        SUGAR.language.get(module, "LBL_INVALID_IBAN_ERROR"),
        function() {
            // Get the IBAN value from the form field
            var iban = getFieldValue("iban");

            // If empty, it's valid (not required)
            if (!iban || iban === "") {
                return true;
            }

            // If it has content, validate
            var res;
            
            // Make a synchronous AJAX request to validate the IBAN
            $.ajax({
                // Call the checkIBAN action in the controller
                url: "index.php?module=stic_Financial_Products&action=checkIBAN&iban=" + encodeURIComponent(iban),
                type: "POST",
                async: false
            })
                // If the AJAX request succeeds, store the response
                .done(function (data) {
                    res = data;
                })
                // If the AJAX request fails, set res to false
                .fail(function (data) {
                    res = false;
                });

            // Parse the JSON response and return the boolean result
            try {
                // Convert the JSON string response ("true" or "false") to a JavaScript boolean
                return JSON.parse(res);
            } catch (e) {
                // If JSON parsing fails, return false (invalid IBAN)
                return false;
            }
        }
    );
});
