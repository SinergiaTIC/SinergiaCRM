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

/*
 * This script handles client-side validations and custom behaviors for the stic_Signatures module.
 * It includes date coherence checks and field manipulation based on the current view type.
 */

/* HEADER */
// Set module name
var module = "stic_Signatures";

/* INCLUDES */
// Load moment.js to use in validations
loadScript("include/javascript/moment.min.js");

/* VALIDATION DEPENDENCIES */
var validationDependencies = {
  activation_date: "activation_date",
  expiration_date: "expiration_date",
};

/* VALIDATION CALLBACKS */
/**
 * Adds a validation callback for the 'activation_date' field to ensure date coherence.
 * It checks that the activation date is not after the expiration date.
 */
addToValidateCallback(getFormName(), "activation_date", "date", false, SUGAR.language.get(module, "LBL_ACTIVATION_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("activation_date", "expiration_date", true);
});

/**
 * Adds a validation callback for the 'expiration_date' field to ensure date coherence.
 * It checks that the expiration date is not before the activation date.
 */
addToValidateCallback(getFormName(), "expiration_date", "date", false, SUGAR.language.get(module, "LBL_EXPIRATION_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("activation_date", "expiration_date", true);
});

/* VIEWS CUSTOM CODE */

/* AUX. FUNCTIONS */

// Apply custom logic based on the current view type (edit, quickcreate, detail, list, etc.)
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    // Enable the 'main_module' field for editing
    setDisabledStatus('main_module', false);
    setAutofill(["name"]);
    
    // Initially hide step 2 and step 3 panels
    if (typeof STIC.record.name == 'undefined') {
      $('[data-id=LBL_STEP2_PANEL]').parent('.panel').hide()
      $('[data-id=LBL_STEP3_PANEL]').parent('.panel').hide()
    }
    if (STIC.record.signer_path == '') {
      //$('[data-id=LBL_STEP2_PANEL]').parent('.panel').hide()
      $('[data-id=LBL_STEP3_PANEL]').parent('.panel').hide()
    }



    break;
  case "detail":
    // No specific custom logic for detail view in this section.
    break;
  case "list":
    // No specific custom logic for list view in this section.
    break;
  default:
    // No specific custom logic for other view types.
    break;
}