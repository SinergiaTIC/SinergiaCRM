/*
    Javascript functionality related to the time registration button displayed in the top menu of the CRM.
    This button is defined in the file custom/themes/SuiteP/tpls/_headerModuleList.tpl 
*/

// Check time tracker button status
function checkTimeTrackerButtonStatus() 
{
    const url = 'index.php?module=stic_Time_Tracker&action=getTimeTrackerMenuButtonStatus';
    fetch(url)
        .then(response => response.json())
        .then(data => {
            localStorage.setItem('todayRegistrationStarted', data.todayRegistrationStarted);
            let buttonRow = document.querySelectorAll('.time_tracker_button_row');                             
            if (data.timeTrackerModuleActive == 1 && data.timeTrackerActiveInEmployee == 1){
                buttonRow.forEach(function(element) {
                    element.classList.remove('no-show-time-tracker-button');
                });
                let button = document.querySelectorAll('.time_tracker_button'); 
                button.forEach(function(element) {
                    if (data.todayRegistrationStarted == 0) {
                        element.classList.add('time-tracker-start');
                        element.classList.remove('time-tracker-stop');
                    } else {
                        element.classList.remove('time-tracker-start');
                        element.classList.add('time-tracker-stop');                                                
                    }
                });        
            } else {
                buttonRow.forEach(function(element) {
                    element.classList.add('no-show-time-tracker-button');
                }); 
            }
        })
        .catch(error => {
            console.error('Failed to get time tracker button status. Could not find out if there is a time record started for today and for the logged in employee:', error);
        });
}

// Show the popup confirmation box 
function showTimeTrackerConfirmBox() 
{
    const url = 'index.php?module=stic_Time_Tracker&action=getLastTodayTimeTrackerRecordForEmployee';
    fetch(url)
        .then(response => response.json())
        .then(data => {
            localStorage.setItem('date', data.date);
            drawTimeTrackerConfimrBox(data);
        })
        .catch(error => {
            console.error('Error when obtaining the last today time tracker record for employee:', error);
        });
}

// Format a float indicating hours and minutes in text format
function formatHoursMinutes(time) 
{
    const hours = Math.floor(time);
    const minutes = Math.round((time - hours) * 60);

    if (hours == 0 && minutes == 0) {
        return '-';
    } else if (hours == 0) {
        return `${minutes} ${SUGAR.language.get('app_strings', 'LBL_TIMETRACKER_POPUP_BOX_MINUTES')}`;
    } else if (hours == 0) {
        return `${hours} ${SUGAR.language.get('app_strings', 'LBL_TIMETRACKER_POPUP_BOX_HOURS')}`;
    } else {
        return `${hours} ${SUGAR.language.get('app_strings', 'LBL_TIMETRACKER_POPUP_BOX_HOURS')} ${minutes} ${SUGAR.language.get('app_strings', 'LBL_TIMETRACKER_POPUP_BOX_MINUTES')}`;
    }  
}

// Draw dinamically the content of the confirmation box
function drawTimeTrackerConfimrBox(data) 
{
    var content = '<div class="time-tracker-dialog-info">';
    var userName = document.querySelector(".globallabel-user").innerText;

    if (localStorage.todayRegistrationStarted == '0') {
        content += `
            <span class='time-tracker-popup-header'>${SUGAR.language.get('app_strings', 'LBL_TIMETRACKER_POPUP_BOX_CREATE_1')}</span>
            <div class='time-tracker-info-div'>
                <span>${SUGAR.language.get('app_strings', 'LBL_TIMETRACKER_POPUP_BOX_CREATE_2')}:</span>
                <br /><br />
                <ul class='time-tracker-dialog-row'>
                    <li>${SUGAR.language.get('app_strings', 'LBL_TIMETRACKER_POPUP_BOX_START_DATE')}: <span style="font-weight: bold;">` + localStorage.date + `</span></li>
                    <li>${SUGAR.language.get('app_strings', 'LBL_TIMETRACKER_POPUP_BOX_EMPLOYEE')}: <span style="font-weight: bold;">${userName}</span></li>
                </ul>
                <br /><br />
            </div>`;            
    } else {
        content += `
            <span class='time-tracker-popup-header'>${SUGAR.language.get('app_strings', 'LBL_TIMETRACKER_POPUP_BOX_UPDATE_1')}:</span>
            <div class='time-tracker-info-div'>
                <span>${SUGAR.language.get('app_strings', 'LBL_TIMETRACKER_POPUP_BOX_UPDATE_2')}:</span>
                <br /><br />
                <ul class='time-tracker-dialog-row'>
                    <li>${SUGAR.language.get('app_strings', 'LBL_TIMETRACKER_POPUP_BOX_NAME')}: <span style="font-weight: bold;">${data.recordName}</span></li>
                </ul>
                <br /><br />
                <span>${SUGAR.language.get('app_strings', 'LBL_TIMETRACKER_POPUP_BOX_UPDATE_3')}:</span>
                <br /><br />
                <ul class='time-tracker-dialog-row'>
                    <li>${SUGAR.language.get('app_strings', 'LBL_TIMETRACKER_POPUP_BOX_END_DATE')}: <span style="font-weight: bold;">` + localStorage.date + `</span></li>
                </ul>
            </div>`;

        const url = 'index.php?module=stic_Time_Tracker&action=getTodayTimeTrackerRecords';
        fetch(url)
            .then(response => response.json())
            .then(data => 
            {
                const buttonsDiv = document.getElementById('time-tracker-dialog-buttons');
                buttonsDiv.insertAdjacentHTML('beforebegin', `
                    <span class='time-tracker-popup-header' style='padding-top:5%'>${SUGAR.language.get('app_strings', 'LBL_TIMETRACKER_POPUP_BOX_INFO_TODAY_RECORDS_1')}</span>
                    <ul id="time-tracker-dynamic-list" class="time-tracker-dialog-row"></ul>
                `);

                const list = document.getElementById('time-tracker-dynamic-list');
                if (data.length > 1) 
                {
                    let suma = 0;
                    data.forEach((record, index) => 
                    {
                        const li = document.createElement('li');

                        let name = record.name;
                        if (!record.end_date) 
                            name +=` - ${SUGAR.language.get('app_strings', 'LBL_TIMETRACKER_POPUP_BOX_INFO_RECORD_IN_PROGRESS')}`;

                        let duration = parseFloat(record.duration);
                        suma += duration;
                        
                        href= window.location.origin + window.location.pathname + `?module=stic_Time_Tracker&action=DetailView&record=${record.id}`;
                        li.innerHTML = `<a href='${href}' target='_blank'>${name}</a>`;
                        if (record.end_date) 
                            li.innerHTML += `&nbsp&nbsp&nbsp&nbsp&nbsp (${formatHoursMinutes(duration)})`;

                        list.appendChild(li);
                    });
                    list.insertAdjacentHTML('beforebegin', `<span style='padding-left:4px;padding-top:3%;padding-bottom:3%;'><span style='font-weight:bold'>${SUGAR.language.get('app_strings', 'LBL_TIMETRACKER_POPUP_BOX_INFO_TODAY_RECORDS_2')}: </span> ${formatHoursMinutes(suma)}</span>`);
                } else {
                    const li = document.createElement('li');
                    li.innerHTML = `${SUGAR.language.get('app_strings', 'LBL_TIMETRACKER_POPUP_BOX_INFO_TODAY_RECORDS_3')}`;
                    list.insertAdjacentElement('afterbegin', li);
                }
            })
            .catch(error => {
                console.error('Failed to get today time tracker record for logged employee:', error);
            }
        );
    }

    content += `
        <div class='time-tracker-info-div'style='padding-top:3%;'>
            <span>${SUGAR.language.get('app_strings', 'LBL_TIMETRACKER_POPUP_BOX_QUESTION')}:</span>
            <textarea id="time-tracker-dialog-description" rows="2" cols="20" style='margin-top:3%;'></textarea>
        </div>

        <div id="time-tracker-dialog-buttons">
            <button id="time-tracker-dialog-button-confirm" onclick="timeTrackerDialogConfirm(localStorage.date, document.getElementById('time-tracker-dialog-description').value)">${SUGAR.language.get('app_strings', 'LBL_TIMETRACKER_POPUP_BOX_OK')}</button>
            <button id="timeTrackerButtonCancel" onclick="timeTrackerDialogCancel()">${SUGAR.language.get('app_strings', 'LBL_TIMETRACKER_POPUP_BOX_CANCEL')}</button>                                
        </div>
    </div>`;

    mydialog = document.getElementById('time-tracker-dialog-box');
    mydialog.innerHTML = content;
    mydialog.style.display = 'block';
}

// Create or Update a time tracker record
function timeTrackerDialogConfirm(date, description) 
{
    // Call to the action that create or update the correspondient time tracker record

    const url = 'index.php?module=stic_Time_Tracker&action=createOrUpdateTodayRegister';
    var data = {
        'date': date,
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
    mydialog = document.getElementById('time-tracker-dialog-box');
    mydialog.style.display = 'none';
}

// Hide the dialog box
function timeTrackerDialogCancel() 
{
    // Hide the dialog box
    mydialog = document.getElementById('time-tracker-dialog-box');
    mydialog.style.display = 'none';
}