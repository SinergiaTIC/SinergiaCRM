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

// Set module name
var module = "stic_Organizational_Environment";

/* INCLUDES */
loadScript("include/javascript/moment.min.js");

/* VALIDATION DEPENDENCIES */
var validationDependencies = {
  start_date: "end_date",
  end_date: "start_date",
};

/* VALIDATION CALLBACKS */

// Required base organization field
addToValidateCallback(
  getFormName(),
  "stic_organizational_environment_accounts_1_name",
  "related",
  false,
  SUGAR.language.get(module, "LBL_BASE_ACCOUNT_REQUIRED"),
  () =>
    Boolean(
      getFieldValue("stic_organizational_environment_accounts_1accounts_ida"),
    ),
);

// Must relate to either an account or a contact - Accounts
addToValidateCallback(
  getFormName(),
  "stic_organizational_environment_accounts_name",
  "related",
  false,
  SUGAR.language.get(module, "LBL_MUST_RELATE_TO_AN_ACCOUNT_OR_A_CONTACT"),
  () =>
    Boolean(getFieldValue(environmentFields.environmentAccount.name)) ||
    Boolean(getFieldValue(environmentFields.environmentContact.name)),
);

// Must relate to either an account or a contact - Contacts
addToValidateCallback(
  getFormName(),
  "stic_organizational_environment_contacts_name",
  "related",
  false,
  SUGAR.language.get(module, "LBL_MUST_RELATE_TO_AN_ACCOUNT_OR_A_CONTACT"),
  () =>
    Boolean(getFieldValue(environmentFields.environmentAccount.name)) ||
    Boolean(getFieldValue(environmentFields.environmentContact.name)),
);

// Only one environment record allowed at a time
addToValidateCallback(
  getFormName(),
  "stic_organizational_environment_accounts_name",
  "related",
  false,
  SUGAR.language.get(module, "LBL_ONLY_ONE_ENVIRONMENT_RECORD"),
  () =>
    !(
      hasRelateValue(environmentFields.environmentAccount) &&
      hasRelateValue(environmentFields.environmentContact)
    ),
);

// Validate selected relationship type valid
addToValidateCallback(
  getFormName(),
  "relationship_type",
  "enum",
  false,
  SUGAR.language.get(module, "LBL_RELATIONSHIP_PREFIX_ERROR"),
  () => {
    if (
      !getFieldElement("stic_organizational_environment_accounts_name") ||
      !getFieldElement("stic_organizational_environment_contacts_name")
    ) {
      return true;
    }
    const key = getFieldValue("relationship_type");
    return !key || key.startsWith(`${getActiveEnvironmentType()}_`);
  },
);

for (const date of ["start_date", "end_date"]) {
  addToValidateCallback(
    getFormName(),
    date,
    "date",
    false,
    SUGAR.language.get(module, `LBL_${date.toUpperCase()}_ERROR`),
    () => checkStartAndEndDatesCoherence("start_date", "end_date"),
  );
}

// Fields data
const environmentFields = {
  environmentAccount: {
    id: "stic_organizational_environment_accountsaccounts_ida",
    name: "stic_organizational_environment_accounts_name",
    type: "accounts",
  },
  environmentContact: {
    id: "stic_organizational_environment_contactscontacts_ida",
    name: "stic_organizational_environment_contacts_name",
    type: "contacts",
  },
};

let relationshipTypeOptions = null;

// Find a field element
const getFieldElement = (fieldId) =>
  document.forms[getFormName()]?.querySelector(`#${fieldId}`) ?? null;

// A relate is selected when either its hidden ID or visible name has a value.
const hasRelateValue = ({ id, name }) =>
  Boolean(getFieldValue(id) || getFieldValue(name));

// Identify which relationship-type prefix belongs to the selected relate.
const getActiveEnvironmentType = () => {
  for (const field of [
    environmentFields.environmentAccount,
    environmentFields.environmentContact,
  ]) {
    if (hasRelateValue(field)) return field.type;
  }
  return "";
};

// Clear both the visible name and hidden foreign key for a relate field.
const clearRelateField = ({ id, name }) => {
  for (const fieldId of [id, name]) {
    const element = getFieldElement(fieldId);
    if (element) element.value = "";
  }
};

// Show options for the active relate; with no relate selected, show every option.
const filterRelationshipTypeOptions = (type, clearSelection = false) => {
  const select = getFieldElement("relationship_type");
  if (!select) return;

  relationshipTypeOptions ??= [...select.options].map((o) => o.cloneNode(true));

  const selected = clearSelection ? "" : select.value;

  select.replaceChildren(
    ...relationshipTypeOptions
      .filter(
        (option) =>
          !option.value || !type || option.value.startsWith(`${type}_`),
      )
      .map((option) => option.cloneNode(true)),
  );
  select.value = selected;
};

// Prefer the relate that triggered this sync; infer from values during initialization.
const syncEnvironmentRelates = (changedField, clearSelection = false) => {
  const type =
    changedField && hasRelateValue(changedField)
      ? (changedField.type ?? "")
      : getActiveEnvironmentType();

  if (type === "accounts")
    clearRelateField(environmentFields.environmentContact);
  else if (type === "contacts")
    clearRelateField(environmentFields.environmentAccount);

  filterRelationshipTypeOptions(type, clearSelection);
};

// Handle changes from either relate input, including manual deletion of its name.
const onEnvironmentRelateChange = (field, isNameInput = false) => {
  // Only the name input owns this rule: the popup writes the hidden ID first,
  // so clearing it here would drop the ID just returned by the popup.
  if (isNameInput && !getFieldValue(field.name)) {
    const idInput = getFieldElement(field.id);
    if (idInput) idInput.value = "";
  }

  // Sync the environment relates to ensure the correct relationship type options are displayed.
  syncEnvironmentRelates(field, true);
};

// Register change handlers on each visible relate name and hidden foreign key.
function initOEEditView() {
  for (const field of [
    environmentFields.environmentAccount,
    environmentFields.environmentContact,
  ]) {
    for (const fieldId of [field.id, field.name]) {
      const element = getFieldElement(fieldId);
      if (!element) continue;

      const handleChange = () =>
        onEnvironmentRelateChange(field, fieldId === field.name);
      YAHOO.util.Event.addListener(element, "change", handleChange);
    }
  }

  syncEnvironmentRelates();
}

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    setAutofill(["name"]);
    addRequiredMark("stic_organizational_environment_accounts_1_name");
    initOEEditView();
    break;
  case "detail":
    break;
  case "list":
    break;
  default:
    break;
}
