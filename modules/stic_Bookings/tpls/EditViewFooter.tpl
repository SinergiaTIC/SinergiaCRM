{*
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
 *}
<input type="hidden" id="periodic_action" name="periodic_action" value="createPeriodicBookingsRecords">
<input type="hidden" name="repeat_parent_id">

<div class="right-aligned-container">
  <div id="repeat_options" style="display: none;">
    <table class="BookingRepeatForm" width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr id="repeat_interval_row">
            <td valign="top" scope="row">{$MOD.LBL_REPEAT_INTERVAL}:</td>
            <td valign="top">
                <select name="repeat_interval">{html_options options=$repeat_intervals selected="1"}</select>
                <span id="repeat-interval-text"></span>
            </td>
        </tr>
        <tr id="repeat_end_row">
            <td width="12.5%" valign="top" scope="row">{$MOD.LBL_REPEAT_END}:</td>
            <td width="37.5%" valign="top">
                <div>
                    <input type="radio" name="repeat_end_type" value="count" id="repeat_count_radio" checked
                        onclick="toggle_repeat_end();" style="position: relative; top: -5px;">
                    {$MOD.LBL_REPEAT_END_AFTER}
                    <input type="number" size="3" name="repeat_count" value="1"> {$MOD.LBL_REPEAT_OCCURRENCES}
                </div>

                <div>
                    <input type="radio" name="repeat_end_type" id="repeat_until_radio" value="until"
                        onclick="toggle_repeat_end();" style="position: relative; top: -5px;">
                    {$MOD.LBL_REPEAT_END_BY}
                    <input type="text" class="date_input" size="11" maxlength="10" id="repeat_until_input"
                        name="repeat_until" value="" disabled>
                    <img border="0" src="index.php?entryPoint=getImage&imageName=jscalendar.gif"
                        alt="{$APP.LBL_ENTER_DATE}" id="repeat_until_trigger" align="absmiddle"
                        style="display: none;">

                    <script type="text/javascript">
                        {literal}
                            Calendar.setup({
                                inputField: "repeat_until_input",
                                ifFormat: "%d/%m/%Y",
                                daFormat: "%d/%m/%Y",
                                button: "repeat_until_trigger",
                                singleClick: true,
                                dateStr: "",
                                step: 1,
                                startWeekday: {/literal}{$CALENDAR_FDOW|default:'1'}{literal},
                                weekNumbers: false
                            });
                        {/literal}
                    </script>
                </div>
            </td>
        </tr>
        <tr id="repeat_dow_row">
            <td width="12.5%" valign="top" scope="row">{$MOD.LBL_REPEAT_DOW}:</td>
            <td width="37.5%" valign="top">
                {foreach name=dow from=$dow key=i item=d}
                    {$d.label} <input type="checkbox" name="repeat_dow_{$d.index}" id="repeat_dow_{$d.index}"
                        style="margin-right: 10px;">
                {/foreach}
            </td>
        </tr>
    </table>
  </div>
</div>

<h2 id="resourcesTitle">{$MOD.LBL_RESOURCES}  <button id="openCenterPopup" type="button" class="button">{$MOD.LBL_CENTERS_BUTTON}</button></h2>
<div class="filter-box">
    <div id="resourceSearchFields" class="filter-content">
        <div id="selectedCentersContainer">
            <div id="selectedCentersList"></div>
        </div>
        
        <div class="filter-row">
            <div class="filter-item">
                <label for="resourcePlaceUserType">{$MOD.LBL_RESOURCES_USER_TYPE}</label>
                <select id="resourcePlaceUserType" name="resourcePlaceUserType" multiple></select>
            </div>
            
            <div class="filter-item">
                <label for="resourcePlaceType">{$MOD.LBL_RESOURCES_PLACE_TYPE}</label>
                <select id="resourcePlaceType" name="resourcePlaceType" multiple></select>
            </div>
        </div>
        
        <div class="filter-row">
            <div class="filter-item">
                <label for="resourceGender">{$MOD.LBL_RESOURCES_GENDER}</label>
                <select id="resourceGender" name="resourceGender" multiple></select>
            </div>
            
            <div class="filter-item">
                <label for="resourceName">{$MOD.LBL_RESOURCES_NAME}</label>
                <input type="text" id="resourceName" name="resourceName">
            </div>
        </div>
        
        <div class="filter-row">
            <div class="filter-item">
                <label for="numberOfPlaces">{$MOD.LBL_NUMBER_OF_PLACES}</label>
                <input type="number" id="numberOfPlaces" name="numberOfPlaces">
            </div>
        </div>
        <div class="filter-actions grouped-buttons">
            <button id="loadCenterResourcesButton" type="button" class="button">
                {$MOD.LBL_ADD_BUTTON}
            </button>
            <button id="resetResourcesButton" type="button" class="button">
                {$MOD.LBL_UNDO_BUTTON}
            </button>
            <button id="deleteResourcesButton" type="button" class="button">
                {$APP.LBL_DELETE_BUTTON}
            </button>
            <span id="resourceCount"></span>
        </div>
    </div>
</div>
<br>
<table id="resourceLine" class="resource-table">
    <tr>
        {foreach from=$config_resource_fields key=field item=label}
            <th class="resource_column {if $field eq 'name'}resource_name{/if}
                {if $field eq 'hourly_rate' || $field eq 'daily_rate'}hidden-xs hidden-sm{/if}">
                {$label}
            </th>
        {/foreach}
        <th class="resource_column"></th>
    </tr>
</table>
<div style="padding-top: 2px">
    <input type="button" class="button" value="{$MOD.LBL_RESOURCES_ADD}" id="addResourceLine" />
</div>
<br>
{literal}
<script>
$(document).ready(function() {
    // Initialize Selectize on all multiple select elements
    $('#stic_resources_places_gender_list, #stic_resources_places_type_list, #stic_resources_places_users_list').selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        persist: false,
        allowEmptyOption: false,
        create: false
    });
});
</script>
<style>
        .BookingRepeatForm {
            width: 60% !important;
            table-layout: fixed;
        }

        .BookingRepeatForm td:first-child {
            width: 26% !important;
            white-space: nowrap;
            padding-right: 15px;
            text-align: left;
        }

        .BookingRepeatForm td:not(:first-child) {
            width: 75% !important;
        }

        .right-aligned-container {
            margin-left: 35px;
            width: 100%;
        }

        .resource-table .resouce_data_group>input {
            width: calc(100% - 85px);
        }

        .resource-table {
            width: 100%;
        }

        #resourceLine th {
            white-space: initial;
        }

        #resourceLine input.resource_color,
        #resourceLine input.resource_status,
        #resourceLine input.resource_hourly_rate,
        #resourceLine input.resource_daily_rate {
            max-width: 90px !important;
        }

        .resource-table th.resource_name {
            width: calc(20% + 85px);
            min-width: 250px;
        }

        .resource_data {
            color: grey;
            width: 95%;
            border-color: grey !important;
        }

        .filter-box {
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .filter-content {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 10px;
        }
        
        .filter-item {
            flex: 1;
            min-width: 200px;
        }
        
        .filter-item label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .filter-item select,
        .filter-item input {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .filter-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 5px;
        }
        
        #resourceCount {
            font-weight: bold;
        }
        #numberOfPlaces {
            width: 100px;
        }
        #resourceName {
            height: 32px; 
            box-sizing: border-box;
            padding: 8px 10px;
            border: 1px solid #d0d0d0;
            border-radius: 3px;
            width: 90%;
            font-size: 13px;
        }

        #selectedCentersContainer {
            margin-bottom: 10px;
        }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Make sure all the form elements exist before trying to access them
  var repeatType = document.getElementById('repeat_type');
  var repeatOptions = document.getElementById('repeat_options');
  var form = document.getElementById('EditView');
// Define tipos de eventos de todo el día
var allDayTypes = ['vacation', 'holiday', 'personal', 'sick', 'leave'];
var type = document.getElementById("type");
// Storage for previous values
var previousType = type ? type.value : '';
var previousStartDateHours = "09";
var previousStartDateMinutes = "00";
var previousEndDateHours = "18";
var previousEndDateMinutes = "00";

window.toggle_repeat_type = function() {
var repeatVal = document.getElementById('repeat_type').value;

if (typeof validate != "undefined" && typeof validate['EditView'] != "undefined") {
  validate['EditView'] = undefined;
}

// Show/hide interval and end rows based on repeat type
if (repeatVal == "") {
  document.getElementById("repeat_options").style.display = "none";
} else {
  document.getElementById("repeat_options").style.display = "";
  toggle_repeat_end();
}

// Show/hide DOW row for Weekly repeat type
var repeat_dow_row = document.getElementById("repeat_dow_row");
if (repeatVal == "Weekly") {
  repeat_dow_row.style.display = "";
} else {
  repeat_dow_row.style.display = "none";
}

// Set interval text
var intervalTextElm = document.getElementById('repeat-interval-text');
if (intervalTextElm && typeof SUGAR.language.languages.app_list_strings['repeat_intervals'] != 'undefined') {
  intervalTextElm.innerHTML = SUGAR.language.languages.app_list_strings['repeat_intervals'][repeatVal];
}
};

window.toggle_repeat_end = function() {
var repeatCountRadio = document.getElementById("repeat_count_radio");
var repeatUntilRadio = document.getElementById("repeat_until_radio");
var repeatUntilInput = document.getElementById("repeat_until_input");
var repeatCountInput = document.querySelector("input[name='repeat_count']");
var repeatUntilTrigger = document.getElementById("repeat_until_trigger");

if (!repeatCountRadio || !repeatUntilRadio || !repeatUntilInput || !repeatCountInput || !repeatUntilTrigger) {
  return; // Exit if elements don't exist
}

if (repeatCountRadio.checked) {
  // Enable count, disable until
  repeatUntilInput.disabled = true;
  repeatCountInput.disabled = false;
  repeatUntilTrigger.style.display = "none";

  if (typeof validate != "undefined" && typeof validate['EditView'] != "undefined") {
    removeFromValidate('EditView', 'repeat_until');
  }
  if (typeof addToValidateMoreThan === 'function') {
    addToValidateMoreThan('EditView', 'repeat_count', 'int', true, '{/literal}{$MOD.LBL_REPEAT_COUNT}{literal}', 1);
  }
} else if (repeatUntilRadio.checked) {
  // Enable until, disable count
  repeatCountInput.disabled = true;
  repeatUntilInput.disabled = false;
  repeatUntilTrigger.style.display = "";

  if (typeof validate != "undefined" && typeof validate['EditView'] != "undefined") {
    removeFromValidate('EditView', 'repeat_count');
  }
  if (typeof addToValidate === 'function') {
    addToValidate('EditView', 'repeat_until', 'date', true, '{/literal}{$MOD.LBL_REPEAT_UNTIL}{literal}');
  }
}

// Prevent an issue when a calendar date picker is hidden under a dialog
var editContainer = document.getElementById('cal-edit_c');
if (editContainer) {
  var pickerContainer = document.getElementById('container_repeat_until_trigger_c');
  if (pickerContainer) {
    pickerContainer.style.zIndex = editContainer.style.zIndex + 1;
  }
}
};
function setHoursInfo() {
try {
var startDayInput = document.getElementById('start_date_date');
var finalDayInput = document.getElementById('end_date_date');
var startHour = document.querySelector('[name=start_date_hours]');
var startMinute = document.querySelector('[name=start_date_minutes]');
var finalHour = document.querySelector('[name=end_date_hours]');
var finalMinute = document.querySelector('[name=end_date_minutes]');
if (!startDayInput || !finalDayInput || !startHour || !startMinute || !finalHour || !finalMinute) {
return;
}
// Parse date parts
var start = startDayInput.value + '/' + startHour.value + '/' + startMinute.value;
var final = finalDayInput.value + '/' + finalHour.value + '/' + finalMinute.value;
start = start.split('/');
final = final.split('/');
// Create date objects - adapted to DD/MM/YYYY format
var startDate = new Date(
parseInt(start[2]), // year
parseInt(start[1]) - 1, // month (0-indexed)
parseInt(start[0]), // day
parseInt(start[3]), // hour
parseInt(start[4])  // minute
);
var finalDate = new Date(
parseInt(final[2]), // year
parseInt(final[1]) - 1, // month (0-indexed)
parseInt(final[0]), // day
parseInt(final[3]), // hour
parseInt(final[4])  // minute
);

var minutes = Math.round(difference / 60000);
var hours = Math.floor((parseInt(minutes) / 60));
minutes = (parseInt(minutes) % 60);
hours = parseInt(hours) < 10 ? '0' + hours : hours;
minutes = parseInt(minutes) < 10 ? '0' + minutes : minutes;
} catch (e) {
console.error("Error calculating hours info:", e);
}
}

/**

Handler for changes to the event type
*/
if (type) {
type.addEventListener("change", function() {
var repeatStartHour = document.getElementById('repeat_start_hour');
var repeatStartMinute = document.getElementById('repeat_start_minute');
var repeatFinalHour = document.getElementById('repeat_final_hour');
var repeatFinalMinute = document.getElementById('repeat_final_minute');
if (!repeatStartHour || !repeatStartMinute || !repeatFinalHour || !repeatFinalMinute) {
return;
}
if (!allDayTypes.includes(type.value)) {
if (allDayTypes.includes(previousType)) {
// Recover previous values
repeatStartHour.value = previousStartDateHours;
repeatStartMinute.value = previousStartDateMinutes;
repeatFinalHour.value = previousEndDateHours;
repeatFinalMinute.value = previousEndDateMinutes;
 // Show fields necessary with timed type records
 repeatStartHour.style.display = 'inline-block';
 repeatStartMinute.style.display = 'inline-block';
 
 var repeatHoursText = document.getElementById('repeat-hours-text');
 var endDateRow = document.getElementById('endDateRow');
 
 if (repeatHoursText) repeatHoursText.style.display = 'inline-block';
 if (endDateRow) endDateRow.style.display = 'table-row';
}
} else {
if (!allDayTypes.includes(previousType)) {
// Store in previous values
previousStartDateHours = repeatStartHour.value;
previousStartDateMinutes = repeatStartMinute.value;
previousEndDateHours = repeatFinalHour.value;
previousEndDateMinutes = repeatFinalMinute.value;
 // Set all day values
 repeatStartHour.value = '0';
 repeatStartMinute.value = '0';
 repeatFinalHour.value = '0';
 repeatFinalMinute.value = '0';
 
 // Hide fields not necessary with all day type records
 repeatStartHour.style.display = 'none';
 repeatStartMinute.style.display = 'none';
 
 var repeatHoursText = document.getElementById('repeat-hours-text');
 var endDateRow = document.getElementById('endDateRow');
 
 if (repeatHoursText) repeatHoursText.style.display = 'none';
 if (endDateRow) endDateRow.style.display = 'none';
}
}
previousType = type.value;
// Update duration if needed
setHoursInfo();
});
}

// Initialize repeat options when page loads
if (repeatType && repeatOptions) {
repeatType.addEventListener('change', toggle_repeat_type);
// Setup initial display state
function toggleRepeatOptions() {
  const value = repeatType.value.toLowerCase();
  if (['daily', 'weekly', 'monthly', 'yearly'].includes(value)) {
    repeatOptions.style.display = '';
  } else {
    repeatOptions.style.display = 'none';
  }
}

toggleRepeatOptions(); // Initial setup
repeatType.addEventListener('change', toggleRepeatOptions);

// Initialize repeat end options
var repeatUntilRadio = document.getElementById("repeat_until_radio");
var repeatCountRadio = document.getElementById("repeat_count_radio");

if (repeatUntilRadio) {
  repeatUntilRadio.addEventListener('change', toggle_repeat_end);
}

if (repeatCountRadio) {
  repeatCountRadio.addEventListener('change', toggle_repeat_end);
  toggle_repeat_end(); // Initial state setup
}
}
// Add event listeners for duration calculation inputs
var startHour = document.querySelector('[name=repeat_start_hour]');
var startMinute = document.querySelector('[name=repeat_start_minute]');
var finalHour = document.querySelector('[name=repeat_final_hour]');
var finalMinute = document.querySelector('[name=repeat_final_minute]');
var startDayInput = document.getElementById('repeat_start_day_input');
var finalDayInput = document.getElementById('repeat_final_day_input');
if (startHour) startHour.addEventListener('change', setHoursInfo);
if (startMinute) startMinute.addEventListener('change', setHoursInfo);
if (finalHour) finalHour.addEventListener('change', setHoursInfo);
if (finalMinute) finalMinute.addEventListener('change', setHoursInfo);
if (startDayInput) startDayInput.addEventListener('change', setHoursInfo);
if (finalDayInput) finalDayInput.addEventListener('change', setHoursInfo);
// Set default dates if needed
if (startDayInput && !startDayInput.value) {
startDayInput.value = new Date().toLocaleDateString();
}
if (finalDayInput && !finalDayInput.value) {
finalDayInput.value = new Date().toLocaleDateString();
}
// Initial duration calculation
if (startDayInput && finalDayInput) {
setHoursInfo();
}
// Handle calendar picker events
document.body.addEventListener('click', function(event) {
if (event.target.closest('#container_repeat_start_day_trigger a')) {
if (finalDayInput && startDayInput) {
finalDayInput.value = startDayInput.value;
setHoursInfo();
}
} else if (event.target.closest('#container_repeat_final_day_trigger a')) {
setHoursInfo();
}
});
// Form submission handling with resource collection
if (form) {
form.addEventListener('submit', function(e) {
e.preventDefault();
  // Check if this is a periodic booking
  var repeatType = document.getElementById('repeat_type');
  var isPeriodic = repeatType && repeatType.value && repeatType.value !== "" && repeatType.value.toLowerCase() !== "none";
  
  // Process resource lines
  var resourceLines = document.querySelectorAll('#resourceLine tr.resource_row');
  if (resourceLines.length > 0) {
    resourceLines.forEach(function(row, index) {
      var resourceId = row.getAttribute('data-id');
      var resourceName = row.querySelector('.resource_name input').value;
      
      // Add hidden inputs for each resource
      if (!document.getElementById('resource_id_' + index)) {
        var hiddenIdInput = document.createElement('input');
        hiddenIdInput.type = 'hidden';
        hiddenIdInput.name = 'resource_ids[]';
        hiddenIdInput.id = 'resource_id_' + index;
        hiddenIdInput.value = resourceId;
        form.appendChild(hiddenIdInput);
        
        var hiddenNameInput = document.createElement('input');
        hiddenNameInput.type = 'hidden';
        hiddenNameInput.name = 'resource_names[]';
        hiddenNameInput.id = 'resource_name_' + index;
        hiddenNameInput.value = resourceName;
        form.appendChild(hiddenNameInput);
      }
    });
  }
  
  // Handle periodic booking form fields
  if (isPeriodic) {
    console.log("Processing periodic booking fields");
    
    // Add proper form parameters for periodic booking
    $('<input>').attr({
      type: 'hidden',
      name: 'action',
      value: 'editview'
    }).appendTo(form);
    
    $('<input>').attr({
      type: 'hidden',
      name: 'module',
      value: 'stic_Bookings'
    }).appendTo(form);
    
    $('<input>').attr({
      type: 'hidden',
      name: 'periodic_action',
      value: 'createPeriodicBookingsRecords'
    }).appendTo(form);
    
    // Process repeat count
    if (document.getElementById('repeat_count_radio') && document.getElementById('repeat_count_radio').checked) {
      var repeatCount = document.querySelector('input[name="repeat_count"]');
      if (repeatCount && !document.querySelector('input[name="repeat_count_hidden"]')) {
        $('<input>').attr({
          type: 'hidden',
          name: 'repeat_count_hidden',
          value: repeatCount.value
        }).appendTo(form);
      }
    }
    
    // Process repeat until
    if (document.getElementById('repeat_until_radio') && document.getElementById('repeat_until_radio').checked) {
      var repeatUntil = document.querySelector('input[name="repeat_until"]');
      if (repeatUntil && !document.querySelector('input[name="repeat_until_hidden"]')) {
        $('<input>').attr({
          type: 'hidden',
          name: 'repeat_until_hidden',
          value: repeatUntil.value
        }).appendTo(form);
      }
    }
    
    // Process repeat interval
    var repeatIntervalSelect = document.querySelector('select[name="repeat_interval"]');
    if (repeatIntervalSelect && !document.querySelector('input[name="repeat_interval_hidden"]')) {
      $('<input>').attr({
        type: 'hidden',
        name: 'repeat_interval_hidden',
        value: repeatIntervalSelect.value
      }).appendTo(form);
    }
    
    // Process weekly day selections
    if (repeatType.value === 'Weekly') {
      var dowCheckboxes = document.querySelectorAll('input[id^="repeat_dow_"]');
      if (dowCheckboxes.length > 0) {
        dowCheckboxes.forEach(function(checkbox) {
          if (checkbox.checked) {
            var dowIndex = checkbox.id.replace('repeat_dow_', '');
            $('<input>').attr({
              type: 'hidden',
              name: 'repeat_dow_' + dowIndex + '_hidden',
              value: '1'
            }).appendTo(form);
          }
        });
      }
    }
    
    // Add repeat_type value as hidden input
    $('<input>').attr({
      type: 'hidden',
      name: 'repeat_type_hidden',
      value: repeatType.value
    }).appendTo(form);
  }
    
  form.submit();
});
}
});
</script>
{/literal}
{{include file='include/EditView/footer.tpl'}}