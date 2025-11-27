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

/* VALIDATION CALLBACKS */
// Function to validate IBAN format when is not empty
function validateIBANFormat(ibanValue) {
    // Make a synchronous AJAX request to validate the IBAN
    var res;
    $.ajax({
        // Call the checkIBAN action in the controller
        url: "index.php?module=stic_Financial_Products&action=checkIBAN&iban=" + encodeURIComponent(ibanValue),
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

// Check if IBAN is required based on type
function isIBANRequired() {
    var productType = $("#type", "form").val();
    return productType === "current_account" || productType === "savings_account";
}

// Register IBAN validation
$(document).ready(function() {    
    // Function to update IBAN label based on requirement
    function updateIBANLabel() {
        var ibanRequired = isIBANRequired();
        
        if (ibanRequired) {
            // Mark field as required
            setRequiredStatus("iban", "varchar", SUGAR.language.get(module, "LBL_IBAN"));
        } else {
            //Remove required status
            setUnrequiredStatus("iban");
        }
    }
    
    // Initialize label on page load
    updateIBANLabel();
    
    // Capture blur event on all IBAN inputs
    $(document).on("blur", "input[data-fieldname='iban'], input[name*='iban']", function(e) {
        var $field = $(this);
        var ibanValue = $field.val();
        
        // Remove spaces and strange characters
        var cleanedValue = ibanValue.replace(/[^0-9a-zA-Z]/g, "").toUpperCase();
        $field.val(cleanedValue);
        
        // Check if this is inline editing (list or detail view)
        var isInlineEdit = $field.closest(".editable-cell, .inline-edit").length > 0;
        
        // Get or create error message container only for inline editing
        var $errorContainer = null;
        if (isInlineEdit) {
            var $container = $field.closest(".field, .editable-cell, .inline-edit, [data-fieldname='iban']");
            $errorContainer = $container.find(".inline-iban-error");
            if (!$errorContainer.length) {
                $errorContainer = $("<div class='inline-iban-error' style='position: absolute; color: red; font-size: 0.85em; margin-top: 2px; white-space: nowrap; z-index: 1000;'></div>");
                $container.append($errorContainer);
                // Make container position relative so absolute positioning works
                if ($container.css("position") === "static") {
                    $container.css("position", "relative");
                }
            }
        }
        
        // Check if IBAN is required for this product type
        var ibanRequired = isIBANRequired();
        
        // If is required but empty
        if (ibanRequired && (!cleanedValue || cleanedValue === "")) {
            if (isInlineEdit && $errorContainer) {
                $field.addClass("error");
                $errorContainer.text(SUGAR.language.get(module, "LBL_IBAN_REQUIRED")).show();
            }
        } 

        // If has value but invalid format
        else if (cleanedValue && cleanedValue !== "" && !validateIBANFormat(cleanedValue)) {
            if (isInlineEdit && $errorContainer) {
                $field.addClass("error");
                $errorContainer.text(SUGAR.language.get(module, "LBL_INVALID_IBAN_ERROR")).show();
            }
        } 
        // Valid or not required and empty
        else {
            $field.removeClass("error");
            if (isInlineEdit && $errorContainer) {
                $errorContainer.hide().text("");
            }
        }
    });
    
    // Clean IBAN on paste
    $(document).on("paste", "input[data-fieldname='iban'], input[name*='iban']", function(e) {
        var $field = $(this);
        setTimeout(function() {
            var ibanValue = $field.val();
            var cleanedValue = ibanValue.replace(/[^0-9a-zA-Z]/g, "").toUpperCase();
            $field.val(cleanedValue);
        }, 10);
    });
    
    // Handle type field changes to update IBAN requirement
    $("form").on("change", "#type", function() {
        updateIBANLabel();
        
        // Revalidate IBAN field when type changes
        var $ibanField = $("#iban", "form");
        if ($ibanField.length) {
            $ibanField.trigger("blur");
        }
    });
    
    // Also register callback for normal edit form if available
    if (typeof addToValidateCallback !== "undefined" && typeof getFormName !== "undefined") {
        try {
            var formName = getFormName();
            if (formName) {
                addToValidateCallback(
                    formName,
                    "iban",
                    "text",
                    false, // not required by default
                    SUGAR.language.get(module, "LBL_INVALID_IBAN_ERROR"),
                    function() {
                        var iban = getFieldValue("iban");
                        var ibanRequired = isIBANRequired();
                        
                        // If it's required but empty, mark with special flag to show required message
                        if (ibanRequired && (!iban || iban === "")) {
                            // Change the error message for required field
                            var validateFields = validate[getFormName()];
                            for (i = 0; i < validateFields.length; i++) {
                                if (validateFields[i][0] == "iban") {
                                    validateFields[i][3] = SUGAR.language.get(module, "LBL_IBAN_REQUIRED");
                                    break;
                                }
                            }
                            return false;
                        }
                        
                        // If it's not empty, validate format
                        if (iban && iban !== "") {
                            // Change the error message to invalid format
                            var validateFields = validate[getFormName()];
                            for (i = 0; i < validateFields.length; i++) {
                                if (validateFields[i][0] == "iban") {
                                    validateFields[i][3] = SUGAR.language.get(module, "LBL_INVALID_IBAN_ERROR");
                                    break;
                                }
                            }
                            return validateIBANFormat(iban);
                        }
                        
                        // If it's not required and empty
                        return true;
                    }
                );
            }
        } catch (e) {
            console.log("IBAN validation callback not registered: " + e.message);
        }
    }
});
