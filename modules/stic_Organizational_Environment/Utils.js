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
var module = "stic_Organizational_Environment";

/* INCLUDES */
// loadScript("include/javascript/moment.min.js");

/* VALIDATION DEPENDENCIES */
var validationDependencies = {};

// Fields data
const stic_oe_fields = {
  environment_account: {
    id: "stic_organizational_environment_accountsaccounts_ida",
    name: "stic_organizational_environment_accounts_name",
    type: "accounts",
  },
  environment_contact: {
    id: "stic_organizational_environment_contactscontacts_ida",
    name: "stic_organizational_environment_contacts_name",
    type: "contacts",
  },
  base_organization: {
    id: "stic_organizational_environment_accounts_1accounts_ida",
    name: "stic_organizational_environment_accounts_1_name",
  },
};

let stic_oe_relationshipTypeOptions = null;

// Return the active form, including when this view is a subpanel quick create.
function stic_oe_getForm() {
  const formName = getFormName();
  return document.forms[formName] || document.getElementById(formName);
}

// Find a field in the active form so duplicate IDs in other forms are ignored.
function stic_oe_getFieldInForm(fieldId) {
  const form = stic_oe_getForm();
  return form
    ? form.querySelector(`#${fieldId}`)
    : document.getElementById(fieldId);
}

// Read a field's current value, returning an empty string when it is absent.
const stic_oe_getFieldValue = (fieldId) =>
  stic_oe_getFieldInForm(fieldId)?.value ?? "";

// A relate is selected when either its hidden ID or visible name has a value.
const stic_oe_hasRelateValue = ({ id, name }) =>
  Boolean(stic_oe_getFieldValue(id) || stic_oe_getFieldValue(name));

// Identify which relationship-type prefix belongs to the selected relate.
const stic_oe_getActiveEnvironmentType = () => {
  for (const field of [
    stic_oe_fields.environment_account,
    stic_oe_fields.environment_contact,
  ]) {
    if (stic_oe_hasRelateValue(field)) return field.type;
  }
  return "";
};

// Clear both the visible name and hidden foreign key for a relate field.
const stic_oe_clearRelateField = ({ id, name }) => {
  for (const fieldId of [id, name]) {
    const element = stic_oe_getFieldInForm(fieldId);
    if (element) element.value = "";
  }
};

// Show options for the active relate; with no relate selected, show every option.
const stic_oe_filterRelationshipTypeOptions = (
  type,
  clearSelection = false,
) => {
  const select = stic_oe_getFieldInForm("relationship_type");
  if (!select) return;

  stic_oe_relationshipTypeOptions ??= [...select.options].map((option) =>
    option.cloneNode(true),
  );

  const selected = clearSelection ? "" : select.value;
  select.length = 0;
  for (const option of stic_oe_relationshipTypeOptions) {
    if (option.value === "" || !type || option.value.startsWith(`${type}_`)) {
      select.add(option.cloneNode(true));
    }
  }
  select.value = selected;
};

// Prefer the relate that triggered this sync; infer from values during initialization.
const stic_oe_syncEnvironmentRelates = (
  changedField,
  clearSelection = false,
) => {
  const type =
    changedField && stic_oe_hasRelateValue(changedField)
      ? (changedField.type ?? "")
      : stic_oe_getActiveEnvironmentType();

  if (type === "accounts")
    stic_oe_clearRelateField(stic_oe_fields.environment_contact);
  else if (type === "contacts")
    stic_oe_clearRelateField(stic_oe_fields.environment_account);

  stic_oe_filterRelationshipTypeOptions(type, clearSelection);
};

// Handle changes from either relate input, including manual deletion of its name.
const stic_oe_onEnvironmentRelateChange = (field, isNameInput = false) => {
  // Only the name input owns this rule: the popup writes the hidden ID first,
  // so clearing it here would drop the ID just returned by the popup.
  if (isNameInput && !stic_oe_getFieldValue(field.name)) {
    const idInput = stic_oe_getFieldInForm(field.id);
    if (idInput) idInput.value = "";
  }

  // Sync the environment relates to ensure the correct relationship type options are displayed.
  stic_oe_syncEnvironmentRelates(field, true);
};

// Register change handlers on each visible relate name and hidden foreign key.
function stic_oe_initEditView() {
  const yahooEvent = globalThis.YAHOO?.util?.Event;

  for (const field of [
    stic_oe_fields.environment_account,
    stic_oe_fields.environment_contact,
  ]) {
    for (const fieldId of [field.id, field.name]) {
      const element = stic_oe_getFieldInForm(fieldId);
      if (!element) continue;

      const handleChange = () =>
        stic_oe_onEnvironmentRelateChange(field, fieldId === field.name);
      if (yahooEvent) yahooEvent.addListener(element, "change", handleChange);
      else element.addEventListener("change", handleChange);
    }
  }

  stic_oe_syncEnvironmentRelates();
}

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    console.log("[OE] viewType =", viewType());
    stic_oe_initEditView();
    break;
  case "detail":
    console.log("[OE] viewType =", viewType());
    break;
  case "list":
    console.log("[OE] viewType =", viewType());
    break;
  default:
    break;
}
