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
// Check IBAN only when it has a value
function checkProductIBAN() {
    var iban = getFieldValue("iban");
    var type = getFieldValue("type", "stic_financial_products_types_list") || getFieldValue("type");
    var isRequired = type == "current_account" || type == "savings_account";
    
    // If IBAN is empty and required, return false
    if (isRequired && (!iban || iban === "")) {
        return "false";
    }
    
    // If IBAN has value, validate it
    if (iban && iban !== "") {
        var res = checkIBAN(iban);
        return res;
    }
    
    // If IBAN is empty and not required, it's valid
    return "true";
}

// Register IBAN validation
$(document).ready(function() {    
    // Capture blur event on all IBAN inputs
    $(document).on("blur", "input[data-fieldname='iban'], input[name*='iban']", function(e) {
        var $field = $(this);
        var ibanValue = $field.val();
        
        // Remove spaces and strange characters
        var cleanedValue = ibanValue.replace(/[^0-9a-zA-Z]/g, "").toUpperCase();
        $field.val(cleanedValue);
        
        // Check if this is inline editing
        var isInlineEdit = $field.closest(".editable-cell, .inline-edit").length > 0;
        
        // Get or create error message container for inline editing
        var $errorContainer = null;
        if (isInlineEdit) {
            var $container = $field.closest(".field, .editable-cell, .inline-edit, [data-fieldname='iban']");
            $errorContainer = $container.find(".inline-iban-error");
            if (!$errorContainer.length) {
                $errorContainer = $("<div class='inline-iban-error' style='position: absolute; color: red; font-size: 0.85em; margin-top: 2px; white-space: nowrap; z-index: 1000;'></div>");
                $container.append($errorContainer);
                if ($container.css("position") === "static") {
                    $container.css("position", "relative");
                }
            }
        }
        
        // Validate using checkProductIBAN
        var isValid = JSON.parse(checkProductIBAN());
        
        if (!isValid) {
            if (isInlineEdit && $errorContainer) {
                $field.addClass("error");
                // Determine which message to show based on field content
                var errorMsg = (!ibanValue || ibanValue.trim() === "")
                    ? SUGAR.language.get(module, "LBL_IBAN_REQUIRED")
                    : SUGAR.language.get(module, "LBL_INVALID_IBAN_ERROR");
                $errorContainer.text(errorMsg).show();
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
    
    // Handle type field changes to revalidate IBAN and toggle required mark
    $("form").on("change", "#type", function() {
        try {
            var selectedType = getFieldValue("type", "stic_financial_products_types_list") || getFieldValue("type");
            var needRequired = selectedType == "current_account" || selectedType == "savings_account";
            // Re-register validation with correct required flag
            registerIbanValidation(needRequired);
        } catch (e) {
            // ignore
        }
        // Revalidate IBAN field when type changes
        var $ibanField = $("#iban", "form");
        if ($ibanField.length) {
            $ibanField.trigger("blur");
        }
    });
    
    // Register callback for normal edit form
    // The IBAN field is required only when type == 'current_account' or 'savings_account'.
    function registerIbanValidation(isRequired) {
        if (typeof addToValidateCallback === "undefined" || typeof getFormName === "undefined") return;
        try {
            var formName = getFormName();
            if (!formName) return;

            // Always remove previous validation for iban to avoid duplicates
            try {
                removeFromValidate(formName, "iban");
            } catch (er) {}

            // Determine which message to use based on field state
            var errorMessage = isRequired 
                ? SUGAR.language.get(module, "LBL_IBAN_REQUIRED")
                : SUGAR.language.get(module, "LBL_INVALID_IBAN_ERROR");

            // Register validation with callback
            addToValidateCallback(
                formName,
                "iban",
                "text",
                !!isRequired,
                errorMessage,
                function() {
                    var iban = getFieldValue("iban");
                    var result = JSON.parse(checkProductIBAN());
                    
                    // Update error message dynamically based on whether field is empty or invalid
                    if (!result) {
                        var formName = getFormName();
                        if (formName && validate[formName]) {
                            for (var i = 0; i < validate[formName].length; i++) {
                                if (validate[formName][i][nameIndex] == "iban") {
                                    validate[formName][i][msgIndex] = (!iban || iban === "")
                                        ? SUGAR.language.get(module, "LBL_IBAN_REQUIRED")
                                        : SUGAR.language.get(module, "LBL_INVALID_IBAN_ERROR");
                                    break;
                                }
                            }
                        }
                    }
                    
                    return result;
                }
            );

            // Add or remove the required mark
            if (isRequired) {
                addRequiredMark("iban");
            } else {
                removeRequiredMark("iban");
            }
        } catch (e) {
            console.log("IBAN validation callback not registered: " + e.message);
        }
    }

    // Decide initial required status based on current "type" value
    try {
        var currentType = getFieldValue("type", "stic_financial_products_types_list") || getFieldValue("type");
        var needRequired = currentType == "current_account" || currentType == "savings_account";
        registerIbanValidation(needRequired);
    } catch (e) {
        // fallback: register not required
        registerIbanValidation(false);
    }
});
