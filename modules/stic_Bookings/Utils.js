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
var module = "stic_Bookings";
var resourceLineCount = 0;
var resourceMaxCount = 0;
var selectedCenters = [];

/* INCLUDES */
// Load moment.js to use in validations
loadScript("include/javascript/moment.min.js");

/* VALIDATION DEPENDENCIES */
var validationDependencies = {
  end_date: "start_date",
  start_date: "end_date",
};

/* VALIDATION CALLBACKS */
addToValidateCallback(
  getFormName(),
  "end_date",
  "date",
  false,
  SUGAR.language.get(module, "LBL_RESOURCES_END_DATE_ERROR"),
  function () {
    return JSON.parse(
      checkStartAndEndDatesCoherence("start_date", "end_date", false)
    );
  }
);
addToValidateCallback(
  getFormName(),
  "start_date",
  "date",
  false,
  SUGAR.language.get(module, "LBL_RESOURCES_START_DATE_ERROR"),
  function () {
    return JSON.parse(
      checkStartAndEndDatesCoherence("start_date", "end_date", false)
    );
  }
);

addToValidateCallback(
  getFormName(),
  "status",
  "enum",
  true,
  SUGAR.language.get(module, "LBL_RESOURCES_STATUS_ERROR"),
  function () {
    return JSON.parse(isResourceAvailable());
  }
);
addToValidateCallback(
  getFormName(),
  "resource_name0",
  "text",
  false,
  SUGAR.language.get(module, "LBL_RESOURCES_EMPTY_RESOURCES_ERROR"),
  function () {
    return resourceLineWithData(resourceMaxCount)
      ? true
      : confirm(
          SUGAR.language.get(
            module,
            "LBL_RESOURCES_EMPTY_RESOURCES_ERROR_DIALOG"
          )
        );
  }
);

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":

    if (typeof resources !== 'undefined' && resources.length > 0) {
      if ($("#place_booking").is(":checked") && $('[name="record"]').val()) {
          loadExistingResourcesData();
      } else {
        resources.forEach((resource) => {
          insertResourceLine();
          populateResourceLine(resource, resourceMaxCount - 1);
        });
      }
    } else {
        insertResourceLine();
    }

    // Set event to add more lines in the resources area when needed
    $("#addResourceLine").click(function () {
      insertResourceLine();
    });

    previousStartDateHours = "10";
    previousStartDateMinutes = "00";
    previousEndDateHours = "10";
    previousEndDateMinutes = "30";

    // With all_day loadCenterResourcesButtoned the DateTime fields shouldn't display the time section
    // and the end_date should display one day less
    if ($("#all_day", "form").is(":checked")) {
      $("#start_date_hours").val("00");
      $("#start_date_minutes").val("00");
      $("#end_date_hours").val("00");
      $("#end_date_minutes").val("00");

      // Update planned date fields as well
      $("#planned_start_date_hours").val("00");
      $("#planned_start_date_minutes").val("00");
      $("#planned_end_date_hours").val("00");
      $("#planned_end_date_minutes").val("00");

      $("#start_date_hours").change();
      $("#start_date_minutes").change();
      $("#end_date_hours").change();
      $("#end_date_minutes").change();

      // Trigger change on planned date fields
      $("#planned_start_date_hours").change();
      $("#planned_start_date_minutes").change();
      $("#planned_end_date_hours").change();
      $("#planned_end_date_minutes").change();

      $("#start_date_time_section").parent().hide();
      $("#end_date_time_section").parent().hide();

      // Hide planned date time sections
      $("#planned_start_date_time_section").parent().hide();
      $("#planned_end_date_time_section").parent().hide();

      if ($("#end_date_date").val()) {
        var formatString = cal_date_format
          .replace(/%/g, "")
          .toLowerCase()
          .replace(/y/g, "yy")
          .replace(/m/g, "mm")
          .replace(/d/g, "dd");
        endDate = $.datepicker.parseDate(
          formatString,
          $("#end_date_date").val()
        );
        endDate.setDate(endDate.getDate() - 1);
        endDateValue = $.datepicker.formatDate(formatString, endDate);
        $("#end_date_date").val(endDateValue);
        $("#end_date_date").change();

        if ($("#planned_end_date_date").val()) {
          plannedEndDate = $.datepicker.parseDate(
            formatString,
            $("#planned_end_date_date").val()
          );
          plannedEndDate.setDate(plannedEndDate.getDate() - 1);
          plannedEndDateValue = $.datepicker.formatDate(
            formatString,
            plannedEndDate
          );
          $("#planned_end_date_date").val(plannedEndDateValue);
          $("#planned_end_date_date").change();
        }
      }
    }
    $("#all_day", "form").on("change", function () {
      if ($("#all_day", "form").is(":checked")) {
        previousStartDateHours = $("#start_date_hours").val();
        previousStartDateMinutes = $("#start_date_minutes").val();
        previousEndDateHours = $("#end_date_hours").val();
        previousEndDateMinutes = $("#end_date_minutes").val();

        previousPlannedStartHours = $("#planned_start_date_hours").val();
        previousPlannedStartMinutes = $("#planned_start_date_minutes").val();
        previousPlannedEndHours = $("#planned_end_date_hours").val();
        previousPlannedEndMinutes = $("#planned_end_date_minutes").val();

        $("#start_date_hours").val("00");
        $("#start_date_minutes").val("00");
        $("#end_date_hours").val("00");
        $("#end_date_minutes").val("00");

        $("#planned_start_date_hours").val("00");
        $("#planned_start_date_minutes").val("00");
        $("#planned_end_date_hours").val("00");
        $("#planned_end_date_minutes").val("00");

        $("#start_date_hours").change();
        $("#start_date_minutes").change();
        $("#end_date_hours").change();
        $("#end_date_minutes").change();

        $("#planned_start_date_hours").change();
        $("#planned_start_date_minutes").change();
        $("#planned_end_date_hours").change();
        $("#planned_end_date_minutes").change();

        $("#start_date_time_section").parent().hide();
        $("#end_date_time_section").parent().hide();

        $("#planned_start_date_time_section").parent().hide();
        $("#planned_end_date_time_section").parent().hide();

        if ($("#end_date_date").val()) {
          var formatString = cal_date_format
            .replace(/%/g, "")
            .toLowerCase()
            .replace(/y/g, "yy")
            .replace(/m/g, "mm")
            .replace(/d/g, "dd");
          endDate = $.datepicker.parseDate(
            formatString,
            $("#end_date_date").val()
          );
          endDate.setDate(endDate.getDate() - 1);
          endDateValue = $.datepicker.formatDate(formatString, endDate);
          $("#end_date_date").val(endDateValue);
          $("#end_date_date").change();

          if ($("#planned_end_date_date").val()) {
            plannedEndDate = $.datepicker.parseDate(
              formatString,
              $("#planned_end_date_date").val()
            );
            plannedEndDate.setDate(plannedEndDate.getDate() - 1);
            plannedEndDateValue = $.datepicker.formatDate(
              formatString,
              plannedEndDate
            );
            $("#planned_end_date_date").val(plannedEndDateValue);
            $("#planned_end_date_date").change();
          }
        }
      } else {
        $("#start_date_hours").val(previousStartDateHours);
        $("#start_date_minutes").val(previousStartDateMinutes);
        $("#end_date_hours").val(previousEndDateHours);
        $("#end_date_minutes").val(previousEndDateMinutes);

        $("#planned_start_date_hours").val(previousPlannedStartHours);
        $("#planned_start_date_minutes").val(previousPlannedStartMinutes);
        $("#planned_end_date_hours").val(previousPlannedEndHours);
        $("#planned_end_date_minutes").val(previousPlannedEndMinutes);

        $("#start_date_hours").change();
        $("#start_date_minutes").change();
        $("#end_date_hours").change();
        $("#end_date_minutes").change();

        $("#planned_start_date_hours").change();
        $("#planned_start_date_minutes").change();
        $("#planned_end_date_hours").change();
        $("#planned_end_date_minutes").change();

        $("#start_date_time_section").parent().show();
        $("#end_date_time_section").parent().show();

        $("#planned_start_date_time_section").parent().show();
        $("#planned_end_date_time_section").parent().show();
      }
    });
    previousPlannedStartDate = $("#planned_start_date_date").val();
    previousPlannedStartHours = $("#planned_start_date_hours").val();
    previousPlannedStartMinutes = $("#planned_start_date_minutes").val();
    previousPlannedEndDate = $("#planned_end_date_date").val();
    previousPlannedEndHours = $("#planned_end_date_hours").val();
    previousPlannedEndMinutes = $("#planned_end_date_minutes").val();

    setInterval(function () {
      currentPlannedStartDate = $("#planned_start_date_date").val();
      currentPlannedStartHours = $("#planned_start_date_hours").val();
      currentPlannedStartMinutes = $("#planned_start_date_minutes").val();
      currentPlannedEndDate = $("#planned_end_date_date").val();
      currentPlannedEndHours = $("#planned_end_date_hours").val();
      currentPlannedEndMinutes = $("#planned_end_date_minutes").val();

      if (currentPlannedStartDate !== previousPlannedStartDate) {
        $("#start_date_date").val(currentPlannedStartDate).trigger("change");
        previousPlannedStartDate = currentPlannedStartDate;
      }

      if (currentPlannedStartHours !== previousPlannedStartHours) {
        $("#start_date_hours").val(currentPlannedStartHours).trigger("change");
        previousPlannedStartHours = currentPlannedStartHours;
      }

      if (currentPlannedStartMinutes !== previousPlannedStartMinutes) {
        $("#start_date_minutes")
          .val(currentPlannedStartMinutes)
          .trigger("change");
        previousPlannedStartMinutes = currentPlannedStartMinutes;
      }
      if (currentPlannedEndDate !== previousPlannedEndDate) {
        $("#end_date_date").val(currentPlannedEndDate).trigger("change");
        previousPlannedEndDate = currentPlannedEndDate;
      }

      if (currentPlannedEndHours !== previousPlannedEndHours) {
        $("#end_date_hours").val(currentPlannedEndHours).trigger("change");
        previousPlannedEndHours = currentPlannedEndHours;
      }

      if (currentPlannedEndMinutes !== previousPlannedEndMinutes) {
        $("#end_date_minutes").val(currentPlannedEndMinutes).trigger("change");
        previousPlannedEndMinutes = currentPlannedEndMinutes;
      }
    }, 500);

    // Set event listener for center popup
    $("#openCenterPopup").click(function () {
      openCenterPopup();
    });
    if ($("#place_booking").is(":checked")) {
      $("#openCenterPopup").show();
      updateLabelsBasedOnBookingType();
    } else {
      $("#openCenterPopup").hide();
    }

    // Set autofill mark beside field label
    setAutofill(["name"]);
    document
      .getElementById("place_booking")
      .addEventListener("change", function () {
        if (this.checked) {
          updateResourceFields();
          $("#openCenterPopup").show();
        } else {
          $("#openCenterPopup").hide();
          updateResourceFields();
        }
      });

    break;

  case "list":
  case "detail":
    break;
  default:
    break;
}

/* AUX FUNCTIONS */

// This function adds rows to the resources table at the bottom of the Editview
function insertResourceLine() {
  var isPlaceBooking = $("#place_booking").is(":checked");
  var fields = isPlaceBooking ? config_place_fields : config_resource_fields;

  if (!fields || !Array.isArray(fields)) {
    return;
  }

  ln = 0;
  // If there is any empty line, it won't add more lines
  for (var i = 0; i <= resourceMaxCount; i++) {
    if ($("#resource_id" + i).length && !$("#resource_id" + i).val()) {
      return ln;
    } else if (!$("#resource_id" + i).length) {
      ln = i;
      break;
    }
  }
  if ($("#resourceLine thead").length === 0) {
    var header = "<thead><tr>";
    fields.forEach(function (field) {
      header +=
        "<th class='resource_column " +
        field +
        "'>" +
        SUGAR.language.get(
          "stic_Bookings",
          "LBL_RESOURCES_" + field.toUpperCase()
        ) +
        "</th>";
    });
    header += "<th class='resource_column'></th></tr></thead>";
    $("#resourceLine").prepend(header);
  }

  var x = document.getElementById("resourceLine").insertRow(-1);
  x.id = "resourceLine" + ln;

  fields.forEach(function (field) {
    var cell = x.insertCell(-1);
    cell.className = "dataField " + field;

    if (field === "name") {
      cell.innerHTML =
        "<div class='resouce_data_group'> <input type='text' class='sqsEnabled yui-ac-input resouce_data_name' name='resource_name" +
        ln +
        "' id='resource_name" +
        ln +
        "' autocomplete='new-password' value='' title='' tabindex='3' >" +
        "<input type='hidden' name='resource_id[]' id='resource_id" +
        ln +
        "' value=''>" +
        "<span class='id-ff multiple'>" +
        "<button title='" +
        SUGAR.language.get("app_strings", "LBL_SELECT_BUTTON_TITLE") +
        "' type='button' class='button' name='btn_1' onclick='openResourceSelectPopup(" +
        ln +
        ")'>" +
        "<span class='suitepicon suitepicon-action-select'/></span></button>" +
        "<button type='button' name='btn_1' class='button lastChild' onclick='clearRow(this.form," +
        ln +
        ");'>" +
        "<span class='suitepicon suitepicon-action-clear'></span>" +
        "</span></div>";
    } else {
      var input = document.createElement("input");
      input.type = "text";
      input.className = "resource_data " + field;
      input.name = "resource_" + field + ln;
      input.id = "resource_" + field + ln;
      input.value = "";
      input.title = "";
      input.tabIndex = "3";
      input.readOnly = true;
      cell.appendChild(input);
    }
  });

  // Add remove button
  var removeCell = x.insertCell(-1);
  removeCell.innerHTML =
    "<input type='button' class='button' value='" +
    SUGAR.language.get("app_strings", "LBL_REMOVE") +
    "' tabindex='3' onclick='markResourceLineDeleted(" +
    ln +
    ")'>";

  // This is used to add the autofill functionality in the field. It searches records while writing the record name
  sqs_objects[getFormName() + "_resource_name" + ln] = {
    id: ln,
    form: getFormName(),
    method: "query",
    modules: ["stic_Resources"],
    group: "or",
    field_list: ["name", "id"].concat(
      fields.filter((field) => field !== "name")
    ),
    populate_list: ["resource_name" + ln, "resource_id" + ln].concat(
      fields
        .filter((field) => field !== "name")
        .map((field) => "resource_" + field + ln)
    ),
    conditions: [
      {
        name: "name",
        op: "like_custom",
        begin: "%",
        end: "%",
        value: "",
      },
    ],
    order: "name",
    limit: "30",
    post_onblur_function: "callbackResourceSelectQS(" + ln + ")",
    no_match_text: "No Match",
  };

  QSProcessedFieldsArray[getFormName() + "_resource_name" + ln] = false;
  enableQS(false);
  addToValidateCallback(
    getFormName(),
    "resource_name" + ln,
    "text",
    false,
    SUGAR.language.get(module, "LBL_RESOURCES_ERROR"),
    function (formName, resourceElement) {
      return isResourceAvailable(resourceElement.replace("name", "id"));
    }
  );
  resourceMaxCount++;
}
function resourceLineWithData(resourcesCount) {
  for (var i = 0; i <= resourceMaxCount; i++) {
    if ($("#resource_id" + i).length && $("#resource_id" + i).val()) {
      return true;
    }
  }
  return false;
}
function updateResourceFields() {
  var isPlaceBooking = $("#place_booking").is(":checked");
  var fields = isPlaceBooking ? config_place_fields : config_resource_fields;

  var header = "<tr>";
  fields.forEach(function (field) {
    var labelKey = isPlaceBooking ? 
      "LBL_PLACES_" + field.toUpperCase() : 
      "LBL_RESOURCES_" + field.toUpperCase();
    
    header +=
      "<th class='resource_column " +
      field +
      "'>" +
      SUGAR.language.get(
        "stic_Bookings",
        "LBL_RESOURCES_" + field.toUpperCase()
      ) +
      "</th>";
  });
  header += "<th class='resource_column'></th></tr>";
  $("#resourceLine thead").html(header);

  $("#resourceLine tbody").empty();
  resourceMaxCount = 0;

  insertResourceLine();
  if (!isPlaceBooking) {
    $(".filter-box").hide();
    $("#resourceSearchFields").hide();
    $("#resourcePlaceUserType").val("");
    $("#resourcePlaceType").val("");
    $("#resourceGender").val("");
    $("#resourceName").val("");
    $("#numberOfCenters").val("");
  }
  updateLabelsBasedOnBookingType();
}

function updateLabelsBasedOnBookingType() {
  var isPlaceBooking = $("#place_booking").is(":checked");
  
  if (isPlaceBooking) {
    // Change to PLACES labels
    $("#resourcesTitle").html(SUGAR.language.get('stic_Bookings', 'LBL_PLACES') + '  <button id="openCenterPopup" type="button" class="button">' + SUGAR.language.get('stic_Bookings', 'LBL_CENTERS_BUTTON') + '</button>');
    $("#resourcesAddLabel").text(SUGAR.language.get('stic_Bookings', 'LBL_PLACES_ADD'));
    $("#resourceNameLabel").text(SUGAR.language.get('stic_Bookings', 'LBL_PLACES_NAME'));
    $("#addResourceLine").val(SUGAR.language.get('stic_Bookings', 'LBL_RESOURCES_PLACES_ADD'));
    $("#loadCenterResourcesButton").text(SUGAR.language.get('stic_Bookings', 'LBL_RESOURCES_BUTTON'));
  } else {
    // Change to RESOURCES labels
    $("#resourcesTitle").html(SUGAR.language.get('stic_Bookings', 'LBL_RESOURCES') + '  <button id="openCenterPopup" type="button" class="button">' + SUGAR.language.get('stic_Bookings', 'LBL_CENTERS_BUTTON') + '</button>');
    $("#resourcesAddLabel").text(SUGAR.language.get('stic_Bookings', 'LBL_RESOURCES_ADD'));
    $("#resourceNameLabel").text(SUGAR.language.get('stic_Bookings', 'LBL_RESOURCES_NAME'));
    $("#addResourceLine").val(SUGAR.language.get('stic_Bookings', 'LBL_RESOURCES_ADD'));
    $("#loadCenterResourcesButton").text(SUGAR.language.get('stic_Bookings', 'LBL_RESOURCES_BUTTON'));
  }
  
  $("#openCenterPopup").off('click').on('click', function() {
    openCenterPopup();
  });
}

// Delete a resource row
function markResourceLineDeleted(ln) {
  $("#resourceLine" + ln).remove();

  if (!resourceLineWithData(resourceMaxCount)) {
    resourceLineCount = insertResourceLine();
  }
}

function openResourceSelectPopup(ln) {
  var isPlaceBooking = $("#place_booking").is(":checked");
  var fields = isPlaceBooking ? config_place_fields : config_resource_fields;

  var field_to_name_array = {
    id: "resource_id",
  };

  fields.forEach(function (field) {
    field_to_name_array[field] = "resource_" + field;
  });

  var popupRequestData = {
    call_back_function: "callbackResourceSelectPopup",
    passthru_data: { ln: ln },
    form_name: "EditView",
    field_to_name_array: field_to_name_array,
  };

  var resourceTypes =
    SUGAR.language.languages["app_list_strings"]["stic_resources_types_list"];
  var filteredTypes = Object.keys(resourceTypes).filter(function (type) {
    return isPlaceBooking
      ? type === "places"
      : type !== "places" && type !== "";
  });

  var typeQuery = filteredTypes
    .map(function (type) {
      return "&type_advanced[]=" + encodeURIComponent(type);
    })
    .join("");

  open_popup(
    "stic_Resources",
    600,
    400,
    typeQuery,
    true,
    false,
    popupRequestData
  );
}

function callbackResourceSelectQS(ln) {
  var isPlaceBooking = $("#place_booking").is(":checked");
  var fields = isPlaceBooking ? config_place_fields : config_resource_fields;

  if ($("#resource_id" + ln).val()) {
    fields.forEach(function (field) {
      if (field === "hourly_rate" || field === "daily_rate") {
        $("#resource_" + field + ln).val(
          myFormatNumber($("#resource_" + field + ln).val())
        );
      } else if (field === "color") {
        $("#resource_" + field + ln).colorPicker({ opacity: false });
      }
    });
  }
}

var fromPopupReturn = false;
// Callback function used after the Popup that select resources
function callbackResourceSelectPopup(popupReplyData) {
  fromPopupReturn = true;
  var nameToValueArray = popupReplyData.name_to_value_array;
  populateResourceLine(nameToValueArray, popupReplyData.passthru_data.ln);
}

// Fill a resource row with its data
function populateResourceLine(resource, ln) {
  Object.keys(resource).forEach(function (key, index) {
    $("#" + key + ln).val(resource[key]);
  }, resource);
  $("#resource_color" + ln).colorPicker();
}

// Function that asks the server if a resource is available within certain dates
function isResourceAvailable(resourceElement = null) {
  bookingId = $('[name="record"]').val()
    ? $('[name="record"]').val()
    : $(".listview-checkbox", $(".inlineEditActive").closest("tr")).val();
  if (
    $("#all_day", "form").is(":checked") ||
    getFieldValue("end_date").indexOf(" ") == -1
  ) {
    if (getFieldValue("end_date")) {
      var formatString = cal_date_format
        .replace(/%/g, "")
        .toLowerCase()
        .replace(/y/g, "yy")
        .replace(/m/g, "mm")
        .replace(/d/g, "dd");
      endDate = $.datepicker.parseDate(formatString, getFieldValue("end_date"));
      endDate.setDate(endDate.getDate() + 1);
      endDateValue = $.datepicker.formatDate(formatString, endDate);
    }
  } else {
    endDateValue = getFieldValue("end_date");
  }
  if (
    getFieldValue("start_date") &&
    typeof endDateValue !== "undefined" &&
    endDateValue != "" &&
    getFieldValue("status") != "cancelled"
  ) {
    $.ajax({
      url: "index.php?module=stic_Bookings&action=isResourceAvailable&sugar_body_only=true",
      dataType: "json",
      async: false,
      data: {
        startDate: dateToYMDHM(getDateObject(getFieldValue("start_date"))),
        endDate: dateToYMDHM(getDateObject(endDateValue)),
        resourceId: resourceElement ? $("#" + resourceElement).val() : null,
        bookingId: bookingId,
      },
      success: function (res) {
        if (res.success) {
          resourcesAllowed = res.resources_allowed;
        } else {
          alert("Error in the controller", res);
        }
      },
      error: function () {
        alert("Error send Request");
      },
    });
    if (resourcesAllowed) {
      return true;
    } else {
      return false;
    }
  }
  return true;
}

// Clean a resource row
function clearRow(form, ln) {
  SUGAR.clearRelateField(form, `resource_name${ln}`, `resource_id${ln}`);
  var isPlaceBooking = $("#place_booking").is(":checked");
  var fields = isPlaceBooking ? config_place_fields : config_resource_fields;

  fields.forEach(function (field) {
    $(`#resource_${field}${ln}`).val("");
    if (field === "color") {
      $(`#resource_${field}${ln}`).css("background-color", "");
    }
  });
}

function openCenterPopup() {
  var popupRequestData = {
    call_back_function: "callbackCenterSelectPopup",
    form_name: "EditView",
    field_to_name_array: {
      id: "center_id",
      name: "center_name",
    },
  };

  open_popup("stic_Centers", 600, 400, "", true, false, popupRequestData);
}

function callbackCenterSelectPopup(popupReplyData) {
  var centerId = popupReplyData.name_to_value_array.center_id;
  var centerName = popupReplyData.name_to_value_array.center_name;
  
  var centerAlreadySelected = selectedCenters.some(function(center) {
    return center.centerId === centerId;
  });
  
  if (centerAlreadySelected) {
    alert(SUGAR.language.get(module, "LBL_CENTER_ALREADY_SELECTED")+ ": " + centerName);
    return; 
  }
  
  selectedCenters.push({ centerId: centerId, centerName: centerName });
  updateSelectedCentersList();
  $("#selectedCenterName").text(centerName);
  $(".filter-box").show();
  $("#resourceSearchFields").show();
  if (selectedCenters.length === 1) {
    loadResourceTypes(centerId);
  }
  $("#loadCenterResourcesButton").off("click").on("click", loadResources);
}
$("#loadCenterResourcesButton").on("click", function () {
  loadResources();
});

function loadResources() {
  var startDate = getFieldValue("start_date");
  var endDate = getFieldValue("end_date");
  
  // Obtener valores correctamente de Selectize
  var resourcePlaceUserType = $("#resourcePlaceUserType")[0].selectize ? 
    $("#resourcePlaceUserType")[0].selectize.getValue() : 
    $("#resourcePlaceUserType").val();
  
  var resourcePlaceType = $("#resourcePlaceType")[0].selectize ? 
    $("#resourcePlaceType")[0].selectize.getValue() : 
    $("#resourcePlaceType").val();
  
  var resourceGender = $("#resourceGender")[0].selectize ? 
    $("#resourceGender")[0].selectize.getValue() : 
    $("#resourceGender").val();
  
  var resourceName = $("#resourceName").val();
  var numberOfCenters = $("#numberOfCenters").val();

  if (startDate === "" || endDate === "") {
    add_error_style(
      "EditView",
      "start_date",
      SUGAR.language.get("app_strings", "ERR_MISSING_REQUIRED_FIELDS")
    );
    add_error_style(
      "EditView",
      "end_date",
      SUGAR.language.get("app_strings", "ERR_MISSING_REQUIRED_FIELDS")
    );
    return;
  }

  loadCenterResources(
    resourcePlaceUserType,
    resourcePlaceType,
    resourceGender,
    resourceName,
    numberOfCenters
  );
}

$(document).ready(function() {
  $("#resourceSearchFields").hide();
  $(".filter-box").hide();
  $("#resourcePlaceUserType, #resourcePlaceType, #resourceGender").selectize({
      plugins: ['remove_button'],
      create: false,
      allowEmptyOption: true,
      multiple: true  
  });
});
function updateSelectedCentersList() {
  var list = $("#selectedCentersList");
  list.empty();
  selectedCenters.forEach(function (center, index) {
    list.append(
      "<div>" +
        center.centerName +
        " <input type='button' class='removeCenterButton' data-index='" +
        index +
        "' value='" +
        SUGAR.language.get("app_strings", "LBL_REMOVE") +
        "'>" +
        "</div>"
    );
  });
  var startDate = getFieldValue("start_date");
  var endDate = getFieldValue("end_date");
  $(".removeCenterButton")
    .off("click")
    .on("click", function () {
      var index = $(this).data("index");
      selectedCenters.splice(index, 1);
      updateSelectedCentersList();
      var resourcePlaceUserType = $("#resourcePlaceUserType").val();
      var resourcePlaceType = $("#resourcePlaceType").val();
      var resourceName = $("#resourceName").val();
      var resourceGender = $("#resourceGender").val();
      var numberOfCenters = $("#numberOfCenters").val();

      loadCenterResources(
        resourcePlaceUserType,
        resourcePlaceType,
        resourceGender,
        resourceName,
        numberOfCenters,
        getDateObject(startDate),
        getDateObject(endDate)
      );
    });
}
function loadResourceTypes(centerId) {
  $.ajax({
    url: "index.php?module=stic_Bookings&action=getResourceTypes&sugar_body_only=true",
    dataType: "json",
    data: { centerId: centerId },
    success: function (res) {
      if (res.success) {
        $(".filter-box").show();
        $("#resourceSearchFields").show();

        var userTypeSelectize = $("#resourcePlaceUserType")[0].selectize;
        var placeTypeSelectize = $("#resourcePlaceType")[0].selectize;
        var genderSelectize = $("#resourceGender")[0].selectize;
        
        if (userTypeSelectize) {
          userTypeSelectize.clearOptions();
          res.options.forEach(function(option) {
            userTypeSelectize.addOption({value: option.value, text: option.label});
          });
        }
        
        if (placeTypeSelectize) {
          placeTypeSelectize.clearOptions();
          res.options2.forEach(function(option) {
            placeTypeSelectize.addOption({value: option.value, text: option.label});
          });
        }
        
        if (genderSelectize) {
          genderSelectize.clearOptions();
          res.options3.forEach(function(option) {
            genderSelectize.addOption({value: option.value, text: option.label});
          });
        }
      } else {
        $(".filter-box").hide();
        $("#resourceSearchFields").hide();
        alert(
          SUGAR.language.get(module, "LBL_RESOURCES_EMPTY_RESOURCES_ERROR")
        );
      }
    },
    error: function () {
      $(".filter-box").hide();
      $("#resourceSearchFields").hide();
      alert(SUGAR.language.get(module, "LBL_RESOURCES_EMPTY_RESOURCES_ERROR"));
    },
  });
}
function loadCenterResources(
  resourcePlaceUserType = "",
  resourcePlaceType = "",
  resourceGender = "",
  resourceName = "",
  numberOfCenters = ""
) {
  var centerIds = selectedCenters.map((center) => center.centerId).join(",");
  var startDate = getDateObject(getFieldValue("start_date"));
  var endDate = getDateObject(getFieldValue("end_date"));

  $.ajax({
    url: "index.php?module=stic_Bookings&action=loadCenterResources&sugar_body_only=true",
    dataType: "json",
    data: {
      startDate: dateToYMDHM(startDate),
      endDate: dateToYMDHM(endDate),
      centerIds: centerIds,
      resourcePlaceUserType: resourcePlaceUserType,
      resourcePlaceType: resourcePlaceType,
      resourceGender: resourceGender,
      resourceName: resourceName,
      numberOfCenters: numberOfCenters,
    },
    success: function (res) {
      if (res.success) {
        updateResourceLines(res.resources);
        $("#resourceCount").text(
          SUGAR.language.get("stic_Bookings", "LBL_CENTERS_MESSAGE") + res.resources.length
        );
      } else {
        alert(
          SUGAR.language.get("stic_Bookings", "LBL_CENTER_RESOURCE_ERROR") + res.message
        );
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(SUGAR.language.get("stic_Bookings", "LBL_CENTER_RESOURCE_ERROR") + " " + textStatus);
    },
  });
}

function updateResourceLines(resources) {
  for (var i = 0; i < resourceMaxCount; i++) {
    $("#resourceLine" + i).remove();
  }
  resourceMaxCount = 0;

  resources.forEach(function (resource) {
    insertResourceLine();
    populateResourceLine(resource, resourceMaxCount - 1);
  });
}
function loadExistingResourcesData() {
  var bookingId = $('[name="record"]').val();
  
  if (!bookingId) {
      return;
  }
  
  $.ajax({
      url: "index.php?module=stic_Bookings&action=loadExistingResources&sugar_body_only=true",
      dataType: "json",
      data: {
          bookingId: bookingId
      },
      success: function (res) {
          if (res.success && res.resources.length > 0) {
              for (var i = 0; i < resourceMaxCount; i++) {
                  $("#resourceLine" + i).remove();
              }
              resourceMaxCount = 0;
              
              res.resources.forEach(function (resource) {
                  insertResourceLine();
                  populateResourceLine(resource, resourceMaxCount - 1);
              });
          }
      },
      error: function (jqXHR, textStatus, errorThrown) {
          console.log("Error loading existing resources: " + textStatus);
      }
  });
}
function closeResource(resourceId, bookingId) {
  $.ajax({
    url: "index.php?module=stic_Bookings&action=validateResourceDates&sugar_body_only=true",
    dataType: "json",
    async: false,
    data: {
      record_id: bookingId,
    },
    success: function (response) {
      if (!response.valid) {
        alert(
          SUGAR.language.get("stic_Bookings", "LBL_CLOSE_RESOURCE_BEFORE_START_ERROR")
        );
        return;
      }

      if (
        confirm(
          SUGAR.language.get("stic_Bookings", "LBL_CLOSE_RESOURCE_CONFIRM")
        )
      ) {
        $.ajax({
          url: "index.php?module=stic_Bookings&action=closeResource&sugar_body_only=true",
          dataType: "json",
          data: {
            record_id: bookingId,
            resource_id: resourceId,
          },
          success: function (res) {
            if (res.success) {
              window.location.reload();
            } else {
              alert(res.message);
            }
          },
        });
      }
    },
  });
}
