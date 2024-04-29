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

/* VALIDATION DEPENDENCIES */
var validationDependencies = {};

/* VALIDATION CALLBACKS */

/* VIEWS CUSTOM CODE */

switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":    
    // Set autofill mark beside field label
    setAutofill(["name"]);
    var cv = sticCustomizeView.editview();
    updateName();
    updateApplicationDate();
    break;

  case "detail":
    break;
    
  case "list":
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
    cv.field("name").value(
      cv.field("assigned_user_name").content.text() + ' - ' +
      cv.field("start_date").content.text()  + ' - ' +
      cv.field("end_date").content.text().substring(11)
    );
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