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
var module = "Project";

/* INCLUDES */
// Load moment.js to use in validations
loadScript("include/javascript/moment.min.js");

/* VALIDATION DEPENDENCIES */
var validationDependencies = {
  estimated_start_date: "estimated_end_date",
  estimated_end_date: "estimated_start_date",
};

/* DIRECT VALIDATION CALLBACKS */
addToValidateCallback(getFormName(), "estimated_start_date", "date", false, SUGAR.language.get(module, "LBL_ESTIMATED_START_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("estimated_start_date", "estimated_end_date");
});

addToValidateCallback(getFormName(), "estimated_end_date", "date", false, SUGAR.language.get(module, "LBL_ESTIMATED_END_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("estimated_start_date", "estimated_end_date");
});

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
    break;

  case "detail":
    break;

  case "list":
    break;

  default:
    break;
}

/* AUX FUNCTIONS */

/* FIX MODULE AND RECORD - Handle duplicate and edit scenarios */
(function() {
  var hasClickedCancel = false;

  function fixModule() {
    if (!document.EditView) { return; }
    
    // Always force the correct module
    if (document.EditView.module) { 
      document.EditView.module.value = "Project"; 
    }
    
    // Check if we are in duplicate mode
    var isDuplicate = document.EditView.duplicateId && document.EditView.duplicateId.value !== '';
    
    // Track Cancel button click to prevent duplicate creation
    if (document.EditView.record) {
      if (!isDuplicate || hasClickedCancel) {
        // Edit mode or Cancel was clicked, use return_id or URL to get record ID
        if (!document.EditView.record.value) {
          if (document.EditView.return_id && document.EditView.return_id.value) {
            document.EditView.record.value = document.EditView.return_id.value;
          } else {
            var urlParams = new URLSearchParams(window.location.search);
            var recordFromUrl = urlParams.get('record');
            if (recordFromUrl) {
              document.EditView.record.value = recordFromUrl;
            }
          }
        }
      } else {
        // In duplicate mode and not cancelled, clear record to create new
        document.EditView.record.value = '';
      }
    }
  }

  // Run on page load
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
      fixModule();
      setupEventHandlers();
    });
  } else {
    fixModule();
    setupEventHandlers();
  }

  function setupEventHandlers() {
    if (!document.EditView) { return; }

    // Also run on page show
    window.addEventListener("pageshow", fixModule);

    // Track Cancel button click to prevent duplicate creation
    var cancelButtons = document.querySelectorAll('input[value*="Cancel"], button[id*="CANCEL"]');
    cancelButtons.forEach(function(btn) {
      btn.addEventListener('click', function() {
        hasClickedCancel = true;
      });
    });

    // Handle form submit
    document.EditView.addEventListener('submit', function(e) {
      fixModule();
    });

    // Override formSubmitCheck if exists
    if (typeof window.formSubmitCheck === "function") {
      var originalFormSubmitCheck = window.formSubmitCheck;
      window.formSubmitCheck = function() {
        fixModule();
        return originalFormSubmitCheck.apply(this, arguments);
      };
    }
  }
})();