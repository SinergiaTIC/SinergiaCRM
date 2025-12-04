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
var module = "stic_Transactions";

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    $(document).ready(function() {
      setAutofill(["document_name"]);

      // Definition of the behavior of fields that are conditionally enabled or disabled
      typeStatus = {
        income: {
          enabled: ["category"],
          disabled: ["subcategory"]
        },
        expense: {
          enabled: ["category"],
          disabled: ["subcategory"]
        },
        default: {
          enabled: [],
          disabled: ["category", "subcategory"]
        }
      };

      categoryStatus = {
        default: {
          enabled: [],
          disabled: ["subcategory"]
        }
      };

      // Initialize field status on page load
      var initialType = $("#type", "form").val();
      setCustomStatus(typeStatus, initialType);
      
      // If type is selected, check if category is also selected
      if (initialType) {
        var initialCategory = $("#category", "form").val();
        if (initialCategory) {
          setEnabledStatus("subcategory");
        } else {
          setDisabledStatus("subcategory");
        }
      }
      
      // Handle changes to type field
      $("form").on("change", "#type", function() {
        var selectedType = $(this).val();
        clear_all_errors();
        setCustomStatus(typeStatus, selectedType);
        
        // Reset category and subcategory when type changes
        if (selectedType) {
          $("#category").change();
        } else {
          // If type is cleared, disable both category and subcategory
          setDisabledStatus("category");
          setDisabledStatus("subcategory");
          $("#category").val("");
          $("#subcategory").val("");
        }
      });
      
      // Handle changes to category field
      $("form").on("change", "#category", function() {
        var selectedCategory = $(this).val();
        
        if (selectedCategory) {
          // Enable subcategory if category is selected
          setEnabledStatus("subcategory");
        } else {
          // Disable subcategory if category is cleared
          setDisabledStatus("subcategory");
          $("#subcategory").val("");
        }
      });
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
// Check destination_account IBAN only when it has a value
function checkDestinationAccountIBAN() {
    var iban = getFieldValue("destination_account");
    
    // If IBAN has value, validate it
    if (iban && iban !== "") {
        var res = checkIBAN(iban);
        return res;
    }
    
    // If IBAN is empty, it's valid (not required)
    return "true";
}

// Register destination_account IBAN validation
$(document).ready(function() {    
    // Capture blur event on all destination_account inputs
    $(document).on("blur", "input[data-fieldname='destination_account'], input[name*='destination_account']", function(e) {
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
            var $container = $field.closest(".field, .editable-cell, .inline-edit, [data-fieldname='destination_account']");
            $errorContainer = $container.find(".inline-destination-account-error");
            if (!$errorContainer.length) {
                $errorContainer = $("<div class='inline-destination-account-error' style='position: absolute; color: red; font-size: 0.85em; margin-top: 2px; white-space: nowrap; z-index: 1000;'></div>");
                $container.append($errorContainer);
                if ($container.css("position") === "static") {
                    $container.css("position", "relative");
                }
            }
        }
        
        // Validate using checkDestinationAccountIBAN
        var isValid = JSON.parse(checkDestinationAccountIBAN());
        
        if (!isValid) {
            if (isInlineEdit && $errorContainer) {
                $field.addClass("error");
                $errorContainer.text(SUGAR.language.get(module, "LBL_INVALID_IBAN_ERROR")).show();
            }
        } 
        // Valid or empty
        else {
            $field.removeClass("error");
            if (isInlineEdit && $errorContainer) {
                $errorContainer.hide().text("");
            }
        }
    });
    
    // Clean destination_account on paste
    $(document).on("paste", "input[data-fieldname='destination_account'], input[name*='destination_account']", function(e) {
        var $field = $(this);
        setTimeout(function() {
            var ibanValue = $field.val();
            var cleanedValue = ibanValue.replace(/[^0-9a-zA-Z]/g, "").toUpperCase();
            $field.val(cleanedValue);
        }, 10);
    });
    
    // Register callback for normal edit form (not required, only validation when filled)
    if (typeof addToValidateCallback !== "undefined" && typeof getFormName !== "undefined") {
        try {
            var formName = getFormName();
            if (formName) {
                addToValidateCallback(
                    formName,
                    "destination_account",
                    "text",
                    false, // Not required
                    SUGAR.language.get(module, "LBL_INVALID_IBAN_ERROR"),
                    function() {
                        return JSON.parse(checkDestinationAccountIBAN());
                    }
                );
            }
        } catch (e) {
            console.log("destination_account IBAN validation callback not registered: " + e.message);
        }
    }
});