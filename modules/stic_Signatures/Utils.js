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
addToValidateCallback(getFormName(), "activation_date", "date", false, SUGAR.language.get(module, "LBL_ACTIVATION_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("activation_date", "expiration_date", true);
});

addToValidateCallback(getFormName(), "expiration_date", "date", false, SUGAR.language.get(module, "LBL_EXPIRATION_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("activation_date", "expiration_date", true);
});

/* VIEWS CUSTOM CODE */

/* AUX. FUNCTIONS */

switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
  setDisabledStatus('main_module',false)
  // setDisabledStatus('signer_path',false)

  
  

  
    
    break;
  case "detail":
    
    break;
  case "list":
    
    break;
  default:
    break;
}
