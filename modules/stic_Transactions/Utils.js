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