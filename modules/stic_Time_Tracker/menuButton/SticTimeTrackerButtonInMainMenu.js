/*
    Javascript functionality related to the time registration button displayed in the top menu of the CRM.
    This button is defined in the file custom/themes/SuiteP/tpls/_headerModuleList.tpl 
*/

// Checks whether to show the time tracker button based on whether the time tracker module is being used
// If used, updates the button color based on whether or not there is an active time record for today
$(document).ready(function() 
{
    checkTimeTrackerButtonStatus();
});


// Check time tracker button status
function checkTimeTrackerButtonStatus() 
{
    const url = 'index.php?module=stic_Time_Tracker&action=getTimeTrackerRegisterButtonStatus';
    fetch(url)
        .then(response => response.json())
        .then(data => {
            localStorage.setItem('todayRegistrationStarted', data.todayRegistrationStarted);   
            var buttonRow = document.getElementById('time_tracker_button_row');                                    
            if (data.timeTrackerModuleActive == 1 && data.timeTrackerActiveInEmployee == 1){
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
            console.error('Failed to get time tracker button status. Could not find out if there is a time record started for today and for the logged in employee:', error);
        });
}

// Show the popup confirmation box 
function showTimeTrackerConfimrBox() 
{
    const url = 'index.php?module=stic_Time_Tracker&action=getLastTodayTimeTrackerRecordForEmployee';
    fetch(url)
        .then(response => response.json())
        .then(data => {
            drawTimeTrackerConfimrBox(data);
        })
        .catch(error => {
            console.error('Error when obtaining the last today time tracker record for employee:', error);
        });
}

// Draw dinamically the content of the confirmation box
function drawTimeTrackerConfimrBox(data) 
{
    var content = '<div class="time-tracker-dialog-info">';
    var userName = document.querySelector(".globallabel-user").innerText;

    if (localStorage.todayRegistrationStarted == '0') {
        content += `
            <p>${SUGAR.language.get('app_strings', 'LBL_CONFIRMATION_POPUP_BOX_CREATE')}</p>
            <br />
            <ul class='time-tracker-dialog-row'>
                <li>${SUGAR.language.get('app_strings', 'LBL_CONFIRMATION_POPUP_BOX_START_DATE')} ${SUGAR.language.get('app_strings', 'LBL_CONFIRMATION_POPUP_BOX_NOW')}</li>
                <li>${SUGAR.language.get('app_strings', 'LBL_CONFIRMATION_POPUP_BOX_EMPLOYEE')} ${userName}</li>
            </ul>`;
    } else {
        content += `
        <p>${SUGAR.language.get('app_strings', 'LBL_CONFIRMATION_POPUP_BOX_UPDATE_1', 'hola')}</p>
        <br />
        <ul class='time-tracker-dialog-row'>
            <li>${SUGAR.language.get('app_strings', 'LBL_CONFIRMATION_POPUP_BOX_NAME')} ${data.name}</li>
        </ul>
        <br />     
        <p>${SUGAR.language.get('app_strings', 'LBL_CONFIRMATION_POPUP_BOX_UPDATE_2')}</p>
        <br />
        <ul class='dialogRow'>
            <li>${SUGAR.language.get('app_strings', 'LBL_CONFIRMATION_POPUP_BOX_END_DATE')} ${SUGAR.language.get('app_strings', 'LBL_CONFIRMATION_POPUP_BOX_NOW')}</li>
        </ul>`;
    }

    content += `
        <br />
        <p>${SUGAR.language.get('app_strings', 'LBL_CONFIRMATION_POPUP_BOX_QUESTION')}</p>
        <br />
        <textarea id="time-tracker-dialog-description" rows="2" cols="20"></textarea>
        <br /><br />
        <div id="time-tracker-dialog-buttons">
            <button id="time-tracker-dialog-button-confirm" onclick="timeTrackerDialogConfirm(document.getElementById('time-tracker-dialog-description').value)">${SUGAR.language.get('app_strings', 'LBL_CONFIRMATION_POPUP_BOX_ACCEPT')}</button>
            <button id="timeTrackerButtonCancel" onclick="timeTrackerDialogCancel()">${SUGAR.language.get('app_strings', 'LBL_CONFIRMATION_POPUP_BOX_CANCEL')}</button>                                
        </div>
    </div>`;

    mydialog = document.getElementById('time-tracker-dialog-box');
    mydialog.innerHTML = content;
    mydialog.style.display = 'block';
}

// Create or Update a time tracker record
function timeTrackerDialogConfirm(description) 
{
    // Call to the action that create or update the correspondient time tracker record

    const url = 'index.php?module=stic_Time_Tracker&action=createOrUpdateTodayRegister';
    var data = {
        'description': description
    };

    var options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    };  

    fetch(url, options)
        .then(response => {location.reload();})
        .catch(error => {
            console.error('Error creating or updating a today time tracker for the employee:', error);
        });
      
    // Hide the dialog box
    document.getElementById('time-tracker-dialog-box').style.display = 'none';
}

// Hide the dialog box
function timeTrackerDialogCancel() 
{
    document.getElementById('time-tracker-dialog-box').style.display = 'none';
}