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
addToValidateCallback(getFormName(), "type", "type", false, SUGAR.language.get(module, "LBL_INCOMPATIBLE_TYPE_WITH_EXISTING_RECORDS"), function () {
  return checkIfExistsOtherTypesIncompatibleRecords("application_date", "type", "assigned_user_id");
});


/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":    
    // Set autofill mark beside field label
    var allDayTypes = ["", "working", "punctual_absence"];
    var cv = sticCustomizeView.editview();
    setAutofill(["name"]);
    updateName();
    updateApplicationDate();
    updateAllDay();
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
 * Updates the name field as other fields are modified
 */
function updateName() {
  cv.field("name").readonly();
  cv.field("name").content.bold(); // Marcamos en negrita el nombre

  // Función de actualización del campo autogenerado
  function updateFieldName() {
    if (allDayTypes.includes(cv.field("type").value())) 
    {
      cv.field("name").value(
        cv.field("assigned_user_name").content.text() + ' - ' +
        cv.field("type").content.text()  + ' - ' +
        cv.field("start_date").content.text()  + ' - ' +
        cv.field("end_date").content.text().substring(11)
      );
    } else {
      cv.field("name").value(
        cv.field("assigned_user_name").content.text() + ' - ' +
        cv.field("type").content.text()  + ' - ' +
        cv.field("start_date").content.text().substring(0 , 10)
      );
    }
  }

  // Actualizamos el nombre cuando cambie algun valor asociado
  cv.field("start_date").onChange(updateFieldName);
  cv.field("end_date").onChange(updateFieldName);
  cv.field("type").onChange(updateFieldName);
  cv.field("assigned_user_name").onChange(updateFieldName);

  // Forzamos el valor inicial del campo "name"
  updateFieldName();
}


/**
 * Updates the name field as other fields are modified
 */
function updateApplicationDate() {
  cv.field("application_date").readonly(); // No permitimos modificar el campo
  cv.field("application_date").content.bold(); // Marcamos en negrita el nombre
  
  // Función de actualización del campo autogenerado
  function updateFieldApplicationDate() {
    cv.field("application_date").value(
      cv.field("start_date").content.text().substring(0, 10)
    );
  }
  
  // Actualizamos el nombre cuando cambie algun valor asociado
  cv.field("start_date").onChange(updateFieldApplicationDate);
  
  // Forzamos el valor inicial del campo "name"
  updateFieldApplicationDate();

}


/**
 * Used as a callback for mass update dates button
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
 * @returns {Boolean} true if the difference between the end date and the start date is less than 24 hours, and false if not. 
 */
function checkStartAndEndDatesExcceds24Hours(startDate, endDate) 
{
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


/**
 * Synchronous verification of whether there are Work Calendar records of incompatible type that match the assigned user and time range.
 * @returns {Boolean} false if there are records of incompatible types for the same assigned user and time range, true if there are not.
 */
function checkIfExistsOtherTypesIncompatibleRecords(applicationDate, type, assignedUserId) 
{
  //get Id of the record
  const queryString = window.location.search;
  const params = new URLSearchParams(queryString);
  const id = params.get('record') || document.querySelector('input[name="record"]').value;

  var data = {
    id: id,
    applicationDate: getFieldValue(applicationDate),
    type: getFieldValue(type),
    assignedUserId: getFieldValue(assignedUserId),
  };

  const url = 'index.php?module=stic_Work_Calendar&action=existsOtherTypesIncompatibleRecords';
  var xhr = new XMLHttpRequest();
  xhr.open('POST', url, false); // set asyncto false
  xhr.setRequestHeader('Content-Type', 'application/json');
  xhr.send(JSON.stringify(data));

  if (xhr.status === 200) {
    if (xhr.responseText == 1) {
      return true;
    } else {
      return false;
    }
  } else {
    alert(SUGAR.language.get(module, "LBL_ERROR_REQUEST_INCOMPATIBLE_TYPE") + '\n\n' + SUGAR.language.get(module, "LBL_ERROR_CODE_REQUEST_INCOMPATIBLE_TYPE") + xhr.status);
    return false;    addToValidateCallback(getFormName(), "end_date", "datetime", false, SUGAR.language.get(module, "LBL_END_DATE_EXCCEDS_24_HOURS"), function () {
      return checkStartAndEndDatesExcceds24Hours("start_date", "end_date");
    });

  }
}



/**
 * 
 */
function updateAllDay() 
{
  var typeElem = document.getElementById("type");

  previousStartDateHours = "09";
  previousStartDateMinutes = "00";
  previousEndDateHours = "18";
  previousEndDateMinutes = "00";

  if (allDayTypes.includes(typeElem.value)) 
  {
    document.getElementById('end_date_date').readOnly = false;
    addToValidateCallback(getFormName(), "end_date", "datetime", false, SUGAR.language.get(module, "LBL_END_DATE_ERROR"), function () {
      return checkStartAndEndDatesCoherence("start_date", "end_date", true);
    });
    addToValidateCallback(getFormName(), "end_date", "datetime", false, SUGAR.language.get(module, "LBL_END_DATE_EXCCEDS_24_HOURS"), function () {
      return checkStartAndEndDatesExcceds24Hours("start_date", "end_date");
    });
  } 
  else 
  { 
    $("#start_date_time_section").parent().hide();
    $("#end_date_time_section").parent().hide();
    $("#end_date_trigger").hide();
    document.getElementById('end_date_date').value = document.getElementById('start_date_date').value;
    document.getElementById('end_date_date').readOnly = true;
    removeFromValidate(getFormName(), "end_date");
  }

  typeElem.addEventListener("change", function() {
    if (allDayTypes.includes(document.getElementById("type").value)) 
    {
      $("#start_date_hours").val(previousStartDateHours);
      $("#start_date_minutes").val(previousStartDateMinutes);
      $("#end_date_hours").val(previousEndDateHours);
      $("#end_date_minutes").val(previousEndDateMinutes);
      $("#start_date_hours").change();
      $("#start_date_minutes").change();
      $("#end_date_hours").change();
      $("#end_date_minutes").change();
      $("#start_date_time_section").parent().show();
      $("#end_date_time_section").parent().show();
      $("#end_date_trigger").show();
      document.getElementById('end_date_date').readOnly = false;
      addToValidateCallback(getFormName(), "end_date", "datetime", false, SUGAR.language.get(module, "LBL_END_DATE_ERROR"), function () {
        return checkStartAndEndDatesCoherence("start_date", "end_date", true);
      });
      addToValidateCallback(getFormName(), "end_date", "datetime", false, SUGAR.language.get(module, "LBL_END_DATE_EXCCEDS_24_HOURS"), function () {
        return checkStartAndEndDatesExcceds24Hours("start_date", "end_date");
      });
    } 
    else 
    {
      previousStartDateHours = $("#start_date_hours").val();
      previousStartDateMinutes = $("#start_date_minutes").val();
      previousEndDateHours = $("#end_date_hours").val();
      previousEndDateMinutes = $("#end_date_minutes").val();      
      $("#start_date_time_section").parent().hide();
      $("#end_date_time_section").parent().hide();
      $("#end_date_trigger").hide();
      $("#start_date_hours").val("00");
      $("#start_date_minutes").val("00");
      $("#end_date_hours").val("23");
      $("#end_date_minutes").val("59");
      $("#start_date_hours").change();
      $("#start_date_minutes").change();
      $("#end_date_hours").change();
      $("#end_date_minutes").change();      
      document.getElementById('end_date_date').value = document.getElementById('start_date_date').value;
      document.getElementById('end_date_date').readOnly = true;
      removeFromValidate(getFormName(), "end_date");      
    }
  });
}