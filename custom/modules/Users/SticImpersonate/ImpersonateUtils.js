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

/**
 * Impersonation Utils
 */
document.addEventListener("DOMContentLoaded", function() {
    /**
     * Show impersonation indicator and message if impersonationData is defined
     */
    if (impersonationData === undefined || impersonationData === null) {
        return;
    }
    showImpersonationIndicator();
    showImpersonationMessage();
});

/**
 * Shows a message at the top of the page content indicating that the user is impersonating another user. 
 */
function showImpersonationMessage() {
    document.querySelectorAll('#stic-impersonation-message').forEach(el => el.remove());

    var ref = document.getElementById('pagecontent');
    if (ref.length === 0) {
        console.warn('Did not find #pagecontent, cannot insert impersonation message');
        return;
    }
    var message = document.createElement('div');
    message.id = 'stic-impersonation-message';
    message.className = 'msg-fatal-lock';   
    message.innerHTML = `
        ${modImpersonate.LBL_IMPERSONATE_MESSAGE_TITLE} 
        ${modImpersonate.LBL_IMPERSONATE_MESSAGE_DESCRIPTION}
        <strong>${impersonationData.target_user_name}</strong>
        ${modImpersonate.LBL_IMPERSONATE_MESSAGE_STOP_DESCRIPTION}
    `;
    // Inserting as the first child of the reference node
    ref.insertBefore(message, ref.firstChild);
}

/**
 * Shows an impersonation indicator in the top navigation bar.
 */
function showImpersonationIndicator() {

    // We hide the clear all alerts button to the user that emulates.
    const observer = new MutationObserver(() => {
        document.querySelectorAll('.clear-all-alerts-container').forEach(el => {
            el.style.display = 'none';
        });
    });
    observer.observe(document.body, { childList: true, subtree: true });



    // Removing previous indicators
    document.querySelectorAll('#stic-impersonation-indicator').forEach(el => el.remove());

    // Searching all #globalLinks to insert after each one desktop/mobile/tablet
    var refs = document.querySelectorAll('#globalLinks');
    if (refs.length === 0) {
        console.warn('Did not find #globalLinks, cannot insert impersonation message');
        return;
    }

    refs.forEach(ref => {
        var indicator = document.createElement(ref.tagName.toLowerCase());
        indicator.id = 'globalLinks';
        indicator.className = 'dropdown nav navbar-nav stic-impersonation-indicator-row';

        indicator.innerHTML = `
            <button class="btn dropdown-toggle suitepicon suitepicon-action-view stic-impersonation-indicator"  data-toggle="dropdown" aria-expanded="false">
            </button>
            <ul class="dropdown-menu stic-impersonation-dropdown" role="menu">
                <li class="stic-impersonation-dropdown-text">
                    <div class="stic-impersonation-dropdown-text-label">${modImpersonate.LBL_IMPERSONATE_MESSAGE_TITLE}</div>
                </li>
                <li class="stic-impersonation-dropdown-text">
                    <div class="stic-impersonation-dropdown-text-label">${modImpersonate.LBL_IMPERSONATE_TARGET_USER}:</div>${impersonationData.target_user_full_name}
                </li>
                <li class="stic-impersonation-dropdown-text">
                    <div class="stic-impersonation-dropdown-text-label">${modImpersonate.LBL_IMPERSONATE_ORIGINAL_USER}:</div>${impersonationData.original_user_full_name}
                </li>
                <li >
                    <a id="stic-impersonation-stop-btn" href="index.php?module=Users&action=stopImpersonation">
                        ✖ ${modImpersonate.LBL_IMPERSONATE_STOP_BUTTON}
                    </a>
                </li>
            </ul>
        `;

        // Inserting after the reference node
        ref.parentNode.insertBefore(indicator, ref.nextSibling);
    });
}