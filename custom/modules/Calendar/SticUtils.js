// It shows or hide the Cross button that remove the filters
if ($('#applied_filters').val()) {
    $('#cross_filters').show();
} else {
    $('#cross_filters').hide();
}

// Removes all filters and submit changes
function handleCrossRemoveFilters() {
    for (var key in relatedFilters) {
        clearRow(document.getElementById('form_filters'), relatedFilters[key].elementId);
    }
    fieldFilters.map((key) => {
        document.getElementById(key).innerHTML = "";
        document.getElementById(key).value = "";
    });
    $('#form_filters').submit();
}

// Clears a single filter, name and id fields
function clearRow(form, field) {
    SUGAR.clearRelateField(form, field + '_name', field + '_id');
}

fieldFilters = [
    'stic_sessions_color',
    'stic_sessions_activity_type',
    'stic_sessions_stic_events_type',
    'stic_followups_color',
    'stic_followups_type',
];

// Filters array
relatedFilters = {
    'stic_Sessions_stic_Events': {
        elementId: 'stic_sessions_stic_events',
        module: 'stic_Events',
    },
    'stic_Sessions_responsible': {
        elementId: 'stic_sessions_responsible',
        module: 'Contacts',
    },
    'stic_Sessions_Contacts': {
        elementId: 'stic_sessions_contacts',
        module: 'Contacts',
    },
    'stic_Sessions_Project': {
        elementId: 'stic_sessions_projects',
        module: 'Project',
    },
    'stic_FollowUps_Contacts': {
        elementId: 'stic_followups_contacts',
        module: 'Contacts',
    },
    'stic_FollowUps_Project': {
        elementId: 'stic_followups_projects',
        module: 'Project',
    },
};

// The SQS functions add the autocompletion functionality for the related input records
if(typeof sqs_objects == 'undefined'){
    var sqs_objects = new Array;
}

for (var key in relatedFilters) {
    sqs_objects["form_filters_" + relatedFilters[key].elementId + "_name"] = {
        id: relatedFilters[key].elementId,
        form: "form_filters",
        method: "query",
        modules: [relatedFilters[key].module],
        group: "or",
        field_list: ["name", "id"],
        populate_list: [relatedFilters[key].elementId + "_name", relatedFilters[key].elementId + "_id"],
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
        // post_onblur_function: "callbackSticEventSelectQS()",
        no_match_text: "No Match",
    };
    QSProcessedFieldsArray["form_filters_" + relatedFilters[key].elementId + "_name"] = false;
}

SUGAR.util.doWhen(
    "typeof(sqs_objects) != 'undefined'",
    enableQS
);

// Open the modal-popup that contains the filters
function toggle_filters() {
    $(".modal-calendar-filters").modal("toggle");
};

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

// selectizing filters
emptyString = "[" + SUGAR.language.get("app_strings", "LBL_STIC_EMPTY") + "]";
$("select").each(function () {
    if (this.id != 'stic_sessions_color' && this.id != 'stic_followups_color') { 
        var selectizeOptions = {
            plugins: ["remove_button"],
            allowEmptyOption: true
        }
        if ($(this).is("[multiple]")) {
            // Set text in empty strings
            $('option[value=""]', $(this)).text(emptyString);

        }
        $(this).selectize(selectizeOptions || {});
    }
});

// Adding color dots to "color" enum field
$(document).ready(function() {
    $('#stic_sessions_color').selectize({
        render: {
            option: function(item, escape) {
              return '<div class="option" style="display: flex; align-items: center; padding: 5px;">' +
                       '<div style="background-color: #' + escape(item.value) + '; width: 20px; height: 20px; border-radius: 50%; margin-right: 10px; margin-left: 10px;"></div>' +
                       escape(item.text) +
                     '</div>';
            },
            item: function(item, escape) {
              return '<div class="item" style="display: inline-flex; align-items: left; padding: 5px;" >' +
                '<div style="background-color: #' + escape(item.value) + '; width: 20px; height: 20px; border-radius: 50%; margin-right: 10px; margin-left: 5px;"></div>' +
                escape(item.text) +
              '</div>';
            }
        },
        plugins: ["remove_button"],
        allowEmptyOption: true
    });
    $('#stic_followups_color').selectize({
        render: {
            option: function(item, escape) {
              return '<div class="option" style="display: flex; align-items: center; padding: 5px;">' +
                       '<div style="background-color: #' + escape(item.value) + '; width: 20px; height: 20px; border-radius: 50%; margin-right: 10px; margin-left: 10px;"></div>' +
                       escape(item.text) +
                     '</div>';
            },
            item: function(item, escape) {
              return '<div class="item" style="display: inline-flex; align-items: left; padding: 5px;" >' +
                '<div style="background-color: #' + escape(item.value) + '; width: 20px; height: 20px; border-radius: 50%; margin-right: 10px; margin-left: 5px;"></div>' +
                escape(item.text) +
              '</div>';
            }
        },
        plugins: ["remove_button"],
        allowEmptyOption: true
    });
});