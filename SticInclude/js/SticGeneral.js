// Check if there is an active time register for today or not
function toggleTimeTrackerRegisterButton() 
{
    var result = window.confirm(SUGAR.language.get('app_strings', 'LBL_CONFIRMATION_POPUP'));
    if (result) {
        const url = siteURL + '/index.php?module=stic_Time_Tracker&action=createOrUpdateTodayRegister';
        fetch(url)
            .then(data => 
            {
                location.reload();
                var button = document.getElementById('time_tracker_register');
                if (data == 0) {
                    button.classList.add('time-tracker-start');
                    button.classList.remove('time-tracker-stop');
                } else {
                    button.classList.remove('time-tracker-start');
                    button.classList.add('time-tracker-stop');
                }
            })
            .catch(error => {
                console.error('Error when obtaining if there is a time registration started, or not, on today:', error);
            });
    }
}
