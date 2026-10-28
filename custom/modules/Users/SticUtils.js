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

// Set module name
var module = "Users";

// Ensure editACL is defined (default to false when missing)
if (typeof editACL === 'undefined') {
    editACL = false;
}
/* VIEWS CUSTOM CODE */
switch (viewTypeUsers()) {
    case "edit":
    case "quickcreate":
    case "popup":
        // Disable fields if the user is not admin
        if (isAdminCurrentUser == '0') {
            adminOnlyFields = {
                "": {
                    enabled: [],
                    disabled: ["stic_work_calendar_c", "stic_clock_c", "sda_allowed_c", "stic_m182_issuing_organization_c"]
                },
                default: {
                    enabled: ["stic_work_calendar_c", "stic_clock_c", "sda_allowed_c", "stic_m182_issuing_organization_c"],
                    disabled: []
                }
            };
            setCustomStatus(adminOnlyFields, "");
        }
        break;
    
    case "detail":
        bootstrapUserLockoutUi();

        if (editACL){    
            // Define button content
            var buttons = {
                showWorkCalendarAssistant: {
                    id: "show_work_calendar_assistant",
                    title: SUGAR.language.get(module, "LBL_PERIODIC_WORK_CALENDAR_BUTTON"),
                    onclick: "location.href='" + STIC.siteUrl + "/index.php?module=stic_Work_Calendar&action=showWorkCalendarAssistant&employeeId=" + STIC.record.id + "'",
                },
            };
            createDetailViewButton(buttons.showWorkCalendarAssistant);
        }
        // Define button content
        var buttons = {
            showImpersonateUser: {
                id: "show_impersonate_user",
                title: SUGAR.language.get(module, "LBL_IMPERSONATE_USER_BUTTON"),
                onclick: "location.href='" + STIC.siteUrl + "/index.php?module=Users&action=startImpersonation&userId=" + STIC.record.id + "'",
            },
        };
        createDetailViewButton(buttons.showImpersonateUser);

        break;

    case "list":
        button = {
            id: "bt_work_calendar_periodic_creation_listview",
            title: SUGAR.language.get(module, "LBL_PERIODIC_WORK_CALENDAR_BUTTON"),
            text: SUGAR.language.get(module, "LBL_PERIODIC_WORK_CALENDAR_BUTTON"),
            onclick: "onClickWorkCalendarPeriodicCreationButton()",
        };
        createListViewButton(button);
        break;

    default:
        break;
}    


/**
 * Used as a callback for the periodic creation of Work Calendar Records
 */
function onClickWorkCalendarPeriodicCreationButton() {
    sugarListView.get_checks();
    if(sugarListView.get_checks_count() < 1) {
        alert(SUGAR.language.get('app_strings', 'LBL_LISTVIEW_NO_SELECTED'));
        return false;
    }
    document.MassUpdate.action.value='showWorkCalendarAssistant';
    document.MassUpdate.module.value='stic_Work_Calendar';
    document.MassUpdate.submit();
  }

/**
 * Initializes lockout countdown timer and unlock button
 */
function bootstrapUserLockoutUi() {
    initUserLockoutUi();
}

/**
 * Gets translation text with fallback
 */
function getUsersLang(labelKey, fallback) {
    var text = SUGAR.language.get(module, labelKey);
    if (!text || text === labelKey) {
        return fallback || labelKey;
    }
    return text;
}

/**
 * Replaces template placeholders with countdown values
 */
function formatLockoutText(template, values) {
    return template
        .replace('{minutes}', String(values.minutes))
        .replace('{seconds}', String(values.seconds));
}

/**
 * Stops lockout countdown timer if running
 */
function stopUserLockoutCountdown() {
    if (window.sticUserLockoutCountdownTimer) {
        window.clearInterval(window.sticUserLockoutCountdownTimer);
        window.sticUserLockoutCountdownTimer = null;
    }
}

/**
 * Renders countdown timer and unlock button
 */
function initUserLockoutUi() {
    var $stateNode = $('#stic-user-lockout-state');
    if ($stateNode.length !== 1) {
        sticHideUserLockoutUi();
        return;
    }

    // Create unlock button for admins
    var showUnlockButton = String($stateNode.attr('data-show-unlock-button')) === '1';
    if (showUnlockButton && $('#unlock_user_button').length === 0) {
        var unlockButton = {
            id: "unlock_user_button",
            title: SUGAR.language.get(module, "LBL_UNLOCK_USER"),
            onclick: "sticPerformUnlockUser();",
        };
        createDetailViewButton(unlockButton);
    }

    var $countdownNode = $('#stic-user-lockout-countdown');
    if ($countdownNode.length !== 1) {
        return;
    }

    var remainingSeconds = parseInt($stateNode.attr('data-remaining-seconds'), 10);
    if (isNaN(remainingSeconds) || remainingSeconds <= 0) {
        return;
    }

    if ($stateNode.attr('data-countdown-started') === '1') {
        return;
    }
    $stateNode.attr('data-countdown-started', '1');

    stopUserLockoutCountdown();

    var countdownTpl = getUsersLang('LBL_USER_LOCKOUT_COUNTDOWN', '⛔ User locked out. It will be automatically unlocked in {minutes} min {seconds} sec.');

    // Update countdown text every second
    function renderCountdown(seconds) {
        var minutes = Math.floor(seconds / 60);
        var secs = seconds % 60;
        var paddedSeconds = secs < 10 ? '0' + secs : secs;
        $countdownNode.text(formatLockoutText(countdownTpl, {
            minutes: minutes,
            seconds: paddedSeconds
        }));
    }

    renderCountdown(remainingSeconds);

    // Decrement countdown, reload page when done
    window.sticUserLockoutCountdownTimer = window.setInterval(function() {
        remainingSeconds -= 1;
        if (remainingSeconds <= 0) {
            stopUserLockoutCountdown();
            $countdownNode.text(getUsersLang('LBL_USER_LOCKOUT_FINISHED_REFRESHING', '✅ Lockout period finished. Refreshing...'));
            $stateNode.attr('data-remaining-seconds', '0');
            window.setTimeout(function() {
                window.location.reload();
            }, 1200);
            return;
        }

        renderCountdown(remainingSeconds);
        $stateNode.attr('data-remaining-seconds', String(remainingSeconds));
    }, 1000);
}

/**
 * Submits unlock request to server and reloads DetailView
 */
function sticPerformUnlockUser() {
    // Stop any running countdown timer
    stopUserLockoutCountdown();

    // Remove the unlock button immediately
    $('#unlock_user_button').remove();

    // Show a brief transitional message before the page navigates away
    var $countdownNode = $('#stic-user-lockout-countdown');
    if ($countdownNode.length) {
        $countdownNode.text(getUsersLang('LBL_USER_UNLOCKED_REFRESHING', '✅ User unlocked. Refreshing...'));
    }

    var $form = $('form[name="DetailView"]');
    var record = $form.find('[name="record"]').val() || STIC.record.id;
    var sugarToken = $form.find('[name="sugar_token"]').val() || '';

    var params = new URLSearchParams();
    params.append('module', 'Users');
    params.append('action', 'unlockuser');
    params.append('record', record);
    params.append('sugar_token', sugarToken);

    fetch('index.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params.toString()
    }).finally(function() {
        window.location.href = 'index.php?module=Users&action=DetailView&record=' + encodeURIComponent(record);
    });
}

/**
 * Removes lockout UI elements from page
 */
function sticHideUserLockoutUi() {
    stopUserLockoutCountdown();
    $('#stic-user-lockout-countdown').text('').hide();
    $('#stic-user-lockout-state').remove();
    $('#unlock_user_button').remove();
}

/**
 * This function is a helper to determine the current view type
 * It is a clone of viewType() in SticInclude/Utils.js but adapted to work in Users modules
 * where the DetailView form exists alongside the EditView form.
 * @returns 
 */
function viewTypeUsers() {
  if ($(".listViewBody").length == 1) {
    return "list";
  } else if ($(".sub-panel .quickcreate form").length == 1) {
    return "quickcreate";
  } else if ($(".detail-view").length == 1 || ($("form[name=DetailView]").length == 1 && $("form[name=EditView]").length != 1)) {
    return "detail";
  } else if ($("form[name=EditView]").length == 1) {
    return "edit";
  } else if ($("#popup_query_form").length == 1) {
    return "popup";
  }
}