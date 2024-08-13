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
var module = "stic_Bookings_Places_Calendar";
var updatedView = false;
loadScript("include/javascript/moment.min.js");

// // Load the calendar once everything else is loaded
// calendar = runCheckInterval();

// // Inicializar el calendario después de que el DOM esté completamente cargado
// document.addEventListener('DOMContentLoaded', function() {
//     initializeCalendar();
// });

function initializeCalendar() {
    var calendarEl = document.getElementById("calendar");
    if (!calendarEl) {
        console.error("Calendar element not found");
        return;
    }

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: function(fetchInfo, successCallback, failureCallback) {
            // Formatear las fechas al formato YYYY-MM-DD
            var start = moment(fetchInfo.start).format('YYYY-MM-DD');
            var end = moment(fetchInfo.end).format('YYYY-MM-DD');

console.log("veo start" + start);
            // var sticPlacesUser = $('#stic_resources_places_users_list').val();
            // var sticPlacesType = $('#stic_resources_places_type_list').val();
            // var sticPlacesBookingsType = $('#stic_resources_places_booking_type_list').val();
            $.ajax({
                url: 'index.php?module=stic_Bookings_Places_Calendar&action=get_places_availability_data&sugar_body_only=true',
                method: 'POST',
                data: {
                    start: start,
                    end: end,
                },
                success: function(response) {
                    try {
                        var data = JSON.parse(response);
                        var events = [];
                        for (var date in data) {
                            events.push({
                                title: `Disponibles: ${data[date].available}, Ocupados: ${data[date].occupied}`,
                                start: date,
                                allDay: true
                            });
                        }
                        successCallback(events);
                    } catch (e) {
                        console.error('Error parsing availability data:', e);
                        failureCallback(e);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching availability data:', error);
                    failureCallback(error);
                }
            });
        },

        selectable: true,
        selectMirror: true,
        // This is used for the click in an empty space of the calendar
        select: function(arg) {
            window.location.assign(
                "index.php?module=stic_Bookings&action=EditView&return_action=index&return_module=stic_Bookings_Calendar&start=" +
                    arg.startStr +
                    "&end=" +
                    arg.endStr +
                    "&allDay=" +
                    arg.allDay
            );
        },
    });

    calendar.render();
    return calendar;
}

$("#openCenterPopup").click(function() {
    openCenterPopup();
});
var globalCalendar; // Declarar una variable global para el calendario

function runCheckInterval() {
    var checkIfSearchPaneIsLoaded = setInterval(function() {
        if (SUGAR_callsInProgress === 0) {
            globalCalendar = initializeCalendar();
            clearInterval(checkIfSearchPaneIsLoaded);
            loadSavedFilters();
        }
    }, 200);
}



// Check device screen width
function mobileCheck() {
    if (window.innerWidth >= 768) {
        return false;
    } else {
        return true;
    }
}

// callback function used in the Popup that select events
function openSelectPopup(module, field) {
    var popupRequestData = {
        call_back_function: "callbackSelectPopup",
        form_name: "form_filters",
        field_to_name_array: {
            id: field + "_id",
            name: field + "_name",
        },
    };
    open_popup(module, 600, 400, "", true, false, popupRequestData);
}

var fromPopupReturn = false;
// callback function used after the Popup that select events
function callbackSelectPopup(popupReplyData) {
    fromPopupReturn = true;
    var nameToValueArray = popupReplyData.name_to_value_array;
    // It fills the data of the events
    Object.keys(nameToValueArray).forEach(function(key, index) {
        $('#'+key).val(nameToValueArray[key]);
    }, nameToValueArray);
}



// Clears a single filter, name and id fields
function clearRow(form, field) {
    SUGAR.clearRelateField(form, field + '_name', field + '_id');
}
$(document).ready(function() {
    globalCalendar = initializeCalendar();
    loadSavedFilters();
    setTimeout(updateCrossVisibility, 500);

    $('#btn-save-filters').click(function(e) {
        e.preventDefault();
        saveFilters();
    });
});


function saveFilters() {
    var filters = {
        stic_resources_places_users_list: $('#stic_center_id').val() || [],

        stic_resources_places_users_list: $('#stic_resources_places_users_list').val() || [],
        stic_resources_places_type_list: $('#stic_resources_places_type_list').val() || [],
        stic_resources_places_booking_type_list: $('#stic_resources_places_booking_type_list').val() || []
    };

    $.ajax({
        url: 'index.php?module=stic_Bookings_Places_Calendar&action=SaveFilters&sugar_body_only=true',
        method: 'POST',
        data: filters,
        success: function(response) {
            if (globalCalendar) {
                globalCalendar.refetchEvents();
            }
            updateCrossVisibility();
        },
        error: function(xhr, status, error) {
            console.error('Error saving filters:', error);
        }
    });
}


function loadSavedFilters() {
    $.ajax({
        url: 'index.php?module=stic_Bookings_Places_Calendar&action=get_places_availability_data&sugar_body_only=true',
        method: 'POST',
        data: {
            start: moment().startOf('month').format('YYYY-MM-DD'),
            end: moment().endOf('month').format('YYYY-MM-DD')
        },
        success: function(response) {
            var data = JSON.parse(response);
            if (data.savedFilters) {
                $('#stic_center_name').val(data.savedFilters.stic_resources_centers_name || []);
                $('#stic_resources_places_users_list').val(data.savedFilters.stic_resources_places_users_list || []);
                $('#stic_resources_places_type_list').val(data.savedFilters.stic_resources_places_type_list || []);
                $('#stic_resources_places_booking_type_list').val(data.savedFilters.stic_resources_places_booking_type_list || []);
            }
            if (globalCalendar) {
                globalCalendar.refetchEvents();
            } else {
                console.warn('Calendar not initialized yet');
            }
            updateCrossVisibility();
        },
        error: function(xhr, status, error) {
            console.error('Error loading saved filters:', error);
        }
    });
}

function hasAppliedFilters() {
    return $('#stic_resources_places_users_list').val().length > 0 ||
           $('#stic_resources_places_type_list').val().length > 0 ||
           $('#stic_resources_places_booking_type_list').val().length > 0;
}

function handleCrossRemoveFilters() {
    // Limpiar todos los selectores
    $('#stic_resources_places_users_list').val([]);
    $('#stic_resources_places_type_list').val([]);
    $('#stic_resources_places_booking_type_list').val([]);

    // Guardar los filtros (que ahora están vacíos)
    saveFilters();

    // Actualizar la visibilidad de la cruz
    updateCrossVisibility();

    // Recargar los eventos del calendario
    if (globalCalendar) {
        globalCalendar.refetchEvents();
    }
}
function updateCrossVisibility() {
    if (hasAppliedFilters()) {
        $('#cross_filters').show();
    } else {
        $('#cross_filters').hide();
    }
}

