/* HEADER */
// Set module name
var module = 'stic_Attendances';

/* INCLUDES */

/* VALIDATION DEPENDENCIES */
validationDependencies = {
  duration:'status',
  status:'duration',
};

/* VALIDATION CALLBACKS */
addToValidateMoreThan(getFormName(), 'duration', 'decimal', false, SUGAR.language.languages.app_strings.ERR_INVALID_VALUE, 0.25);

addToValidateCallback(getFormName(), 'duration', 'decimal', false, SUGAR.language.get(module, 'LBL_ERROR_STATUS_DURATION'), function() {
	var status = getFieldValue('status','stic_attendances_status_list');
	var duration = getFieldValue('duration');
	if (duration == '' && ['yes', 'partial'].indexOf(status) != -1) {
		return false;
	} else {
		return true;
	}
});

addToValidateCallback(getFormName(), 'status', 'enum', false, SUGAR.language.get(module, 'LBL_ERROR_STATUS_DURATION'), function() {
	var status = getFieldValue('status','stic_attendances_status_list');
	var duration = getFieldValue('duration');
	if (duration == '' && ['yes', 'partial'].indexOf(status) != -1) {
		return false;
	} else {
		return true;
	}
});

/* VIEWS CUSTOM CODE */
switch (viewType()) {
	case 'edit':
	case 'quickcreate':
	case "popup":
		setAutofill(["name", "start_date"]);
		break;
	case 'detail':
		break;

	case 'list':
		break;

	default:
		break;
}

/* AUX FUNCTIONS */


var $status = $('select#status');
showHideDurationRequiredMark($status);
$status.on('change', function() {
  showHideDurationRequiredMark($status);
});

function showHideDurationRequiredMark($status) {
	if (['yes', 'partial'].indexOf($status.val()) != -1) {
		addRequiredMark('duration');
	} else {
		removeRequiredMark('duration');
	}
}
