/*
    Javascript functionality related to the time registration button displayed in the top menu of the CRM.
    This button is defined in the file custom/themes/SuiteP/tpls/_headerModuleList.tpl 
*/

// Check time tracker button status
function checkTimeTrackerButtonStatus() 
{
    const url = 'index.php?module=stic_Time_Tracker&action=getTimeTrackerRegisterButtonStatus';
    fetch(url)
        .then(response => response.json())
        .then(data => {
            var buttonRow = document.getElementById('time_tracker_button_row');                                    
            if (data.timeTrackerModuleActive == 1){
                buttonRow.classList.remove('no-show-time-tracker-button');
                var button = document.getElementById('time_tracker_button');        
                if (data.todayRegistrationStarted == 0) {
                    button.classList.add('time-tracker-start');
                    button.classList.remove('time-tracker-stop');
                } else {
                    button.classList.remove('time-tracker-start');
                    button.classList.add('time-tracker-stop');                                                
                }
            } else {
                buttonRow.classList.add('no-show-time-tracker-button');
            }
        })
        .catch(error => {
            console.error('Error when obtaining if there is a time registration started, or not, on today:', error);
        });
}

// Check if there is an active time register for today or not and update the button color 
function toggleTimeTrackerRegisterButton() 
{
    var result = window.confirm(SUGAR.language.get('app_strings', 'LBL_CONFIRMATION_POPUP'));
    if (result) {
        const url = 'index.php?module=stic_Time_Tracker&action=createOrUpdateTodayRegister';
        fetch(url)
            .then()
            .catch(error => {
                console.error('Error when obtaining if there is a time registration started, or not, on today:', error);
            });
    }
    location.reload();
}