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
var module = "stic_Work_Calendar";

/* VALIDATION DEPENDENCIES */
var validationDependencies = {
  end_date: "start_date",
};

/* VALIDATION CALLBACKS */
addToValidateCallback(getFormName(), "end_date", "date", false, SUGAR.language.get(module, "LBL_END_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("start_date", "end_date", true);
});
addToValidateCallback(getFormName(), "end_date", "date", false, SUGAR.language.get(module, "LBL_END_DATE_EXCCEDS_24_HOURS"), function () {
  return checkStartAndEndDatesExcceds24Hours("start_date", "end_date");
});


/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":    
    // Set autofill mark beside field label
    setAutofill(["name"]);
    // Disable editing of the name field    
    document.getElementById('name').disabled = true;
    break;
    
  case "detail":
    break;

  case "list":
    button = {
      id: "bt_mass_update_dates_listview",
      title: SUGAR.language.get("stic_Work_Calendar", "LBL_MASS_UPDATE_DATES_BUTTON_TITTLE"),
      text: SUGAR.language.get("stic_Work_Calendar", "LBL_MASS_UPDATE_DATES_BUTTON_TITTLE"),
      onclick: "onClickMassUpdateDatesButton()",
    };
    createListViewButton(button);
    break;

  default:
    break;
}

/**
 * Used as a callback for the Incorpora Synchronization button
 */
function onClickMassUpdateDatesButton() {
  sugarListView.get_checks();
  if(sugarListView.get_checks_count() < 1) {
      alert(SUGAR.language.get('app_strings', 'LBL_LISTVIEW_NO_SELECTED'));
      return false;
  }
  document.MassUpdate.action.value='showMassUpdateDatesForm';
  document.MassUpdate.module.value='stic_Work_Calendar';
  document.MassUpdate.submit();
}


/**
 * Check that the difference between the end date and the start date is less than 24 hours.
 * It is necessary to load at the beginning of the page moment.js by "loadScript("include/javascript/moment.min.js");"
 * It is assumed that if start_date and end_date include hours and minutes, they will be in H:i (php) or HH:MM (momentjs) format
 *
 * @param {String} startDate name of the field whose date must be previous
 * @param {String} endDate name of the field whose date must be prior
 * @returns {Boolean} True if the difference between the end date and the start date is less than 24 hours, and False if not. 
 */
function checkStartAndEndDatesExcceds24Hours(startDate, endDate) 
{
  debugger;
  var userDateFormat = STIC.userDateFormat.toUpperCase();  
  var startDate = moment(getFieldValue(startDate), userDateFormat + "HH:mm");
  var endDate = moment(getFieldValue(endDate), userDateFormat + "HH:mm");

  // Calcular la diferencia entre las dos fechas en horas y verificar que sea menor a 24 horas
  const diferenciaHoras = endDate.diff(startDate, 'hours');
  // Verificar si la diferencia es mayor a 24 horas
  if (diferenciaHoras >= 24) {
      return false;
  } 
}